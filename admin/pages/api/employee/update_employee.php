<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

// Verify database connection
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Add debugging information
$debug = [];
$debug['request_method'] = $_SERVER['REQUEST_METHOD'];
$debug['content_type'] = $_SERVER['CONTENT_TYPE'] ?? 'No content type';

// Process form data
$input = [];
if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Handle JSON input for non-file data
    $rawData = file_get_contents('php://input');
    $debug['raw_json'] = $rawData;

    $input = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON input: ' . json_last_error_msg()
        ]);
        exit;
    }
} else {
    // Handle form data
    $debug['post_data'] = $_POST;

    $input = [
        'employeeId' => $_POST['edit_employee_id'] ?? '',
        'fullName' => $_POST['edit_full_name'] ?? '',
        'positionId' => $_POST['edit_position_id'] ?? '',
        'employmentType' => $_POST['edit_employment_type'] ?? '',
        'dateHired' => $_POST['edit_date_hired'] ?? '',
        'overtimeRate' => $_POST['edit_overtime_rate'] ?? '',
        'contactNumber' => $_POST['edit_contact_number'] ?? '',
        'emailAddress' => $_POST['edit_email_address'] ?? '',
        'sssNumber' => $_POST['edit_sss_number'] ?? '',
        'pagibigNumber' => $_POST['edit_pagibig_number'] ?? '',
        'philhealthNumber' => $_POST['edit_philhealth_number'] ?? '',
        'tinNumber' => $_POST['edit_tin_number'] ?? ''
    ];
}

// Ensure employeeId is an integer
if (isset($input['employeeId'])) {
    $input['employeeId'] = intval($input['employeeId']);
}

// Validate and format dateHired
if (isset($input['dateHired']) && !empty($input['dateHired'])) {
    $date = DateTime::createFromFormat('Y-m-d', $input['dateHired']);
    if (!$date) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid date format for dateHired. Please use YYYY-MM-DD format.'
        ]);
        exit;
    }
    $input['dateHired'] = $date->format('Y-m-d');
}

$debug['processed_input'] = $input;

// Validate required fields
$requiredFields = ['employeeId', 'fullName', 'positionId', 'employmentType', 'dateHired', 'overtimeRate', 'contactNumber'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field",
            'debug' => $debug
        ]);
        exit;
    }
}

// Validate employee ID
if ($input['employeeId'] <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid employee ID: ' . $input['employeeId'],
        'debug' => $debug
    ]);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if employee exists
    $checkStmt = $conn->prepare("SELECT id FROM employees WHERE id = ?");
    $checkStmt->bind_param('i', $input['employeeId']);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        throw new Exception('Employee not found with ID: ' . $input['employeeId']);
    }

    // Check if position exists
    $positionId = null;
    $stmt = $conn->prepare("SELECT id FROM positions WHERE id = ?");
    $stmt->bind_param('i', $input['positionId']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $positionId = $row['id'];
    } else {
        throw new Exception('Invalid position ID');
    }

    // Update employee information
    $sql = "UPDATE employees 
            SET full_name = ?, position_id = ?, employment_type = ?, date_hired = ?, overtime_rate = ?, contact_number = ?, email_address = ? 
            WHERE id = ?";

    $debug['sql'] = $sql;
    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        'sisssssi',
        $input['fullName'],
        $positionId,
        $input['employmentType'],
        $input['dateHired'],
        $input['overtimeRate'],
        $input['contactNumber'],
        $input['emailAddress'],
        $input['employeeId']
    );

    $success = $stmt->execute();
    $debug['execute_success'] = $success;

    if (!$success) {
        throw new Exception("Update query failed: " . $conn->error);
    }
    
    // Process file uploads
    $uploadDir = '../../../../uploads/employee_documents/';
    $debug['upload_dir'] = $uploadDir;
    
    // Make sure the upload directory exists with proper permissions
    if (!file_exists($uploadDir)) {
        $dirCreated = mkdir($uploadDir, 0755, true);
        $debug['dir_created'] = $dirCreated;
        if (!$dirCreated) {
            throw new Exception("Failed to create upload directory: $uploadDir");
        }
    } else {
        // Check if directory is writable
        if (!is_writable($uploadDir)) {
            $debug['dir_writable'] = false;
            throw new Exception("Upload directory is not writable: $uploadDir");
        } else {
            $debug['dir_writable'] = true;
        }
    }
    
    // Helper function to handle file uploads
    function processFileUpload($fileInput, $fileType, $employeeId, $uploadDir, &$debug) {
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
            $debug[$fileInput . '_status'] = isset($_FILES[$fileInput]) ? $_FILES[$fileInput]['error'] : 'not_set';
            return null;
        }
        
        // Log file details
        $debug[$fileInput . '_details'] = [
            'name' => $_FILES[$fileInput]['name'],
            'size' => $_FILES[$fileInput]['size'],
            'type' => $_FILES[$fileInput]['type'],
            'error' => $_FILES[$fileInput]['error']
        ];
        
        $fileExtension = strtolower(pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION));
        $newFileName = $fileType . '_' . $employeeId . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $newFileName;
        $relativePath = 'uploads/employee_documents/' . $newFileName;
        
        $debug[$fileInput . '_path'] = $filePath;
        
        $moveResult = move_uploaded_file($_FILES[$fileInput]['tmp_name'], $filePath);
        $debug[$fileInput . '_move_result'] = $moveResult;
        
        if (!$moveResult) {
            $debug[$fileInput . '_error'] = error_get_last();
            return null;
        }
        
        // Make sure file is readable
        if (!is_readable($filePath)) {
            $debug[$fileInput . '_readable'] = false;
            return null;
        }
        
        return $relativePath;
    }
    
    // Process SSS file upload
    $sssFilePath = processFileUpload('edit_sss_file', 'sss', $input['employeeId'], $uploadDir, $debug);
    
    // Process Pag-IBIG file upload
    $pagibigFilePath = processFileUpload('edit_pagibig_file', 'pagibig', $input['employeeId'], $uploadDir, $debug);
    
    // Process PhilHealth file upload
    $philhealthFilePath = processFileUpload('edit_philhealth_file', 'philhealth', $input['employeeId'], $uploadDir, $debug);
    
    // Process TIN file upload
    $tinFilePath = processFileUpload('edit_tin_file', 'tin', $input['employeeId'], $uploadDir, $debug);

    // Get current file paths to preserve them if no new file is uploaded
    $currentPathsQuery = "SELECT 
                          sss_file_path, 
                          pagibig_file_path, 
                          philhealth_file_path, 
                          tin_file_path 
                          FROM employee_government_ids 
                          WHERE employee_id = ?";
    $pathsStmt = $conn->prepare($currentPathsQuery);
    $pathsStmt->bind_param('i', $input['employeeId']);
    $pathsStmt->execute();
    $pathsResult = $pathsStmt->get_result();
    $currentPaths = $pathsResult->fetch_assoc();
    
    // Use existing paths if no new file is uploaded
    if (!$sssFilePath && isset($currentPaths['sss_file_path'])) {
        $sssFilePath = $currentPaths['sss_file_path'];
    }
    if (!$pagibigFilePath && isset($currentPaths['pagibig_file_path'])) {
        $pagibigFilePath = $currentPaths['pagibig_file_path'];
    }
    if (!$philhealthFilePath && isset($currentPaths['philhealth_file_path'])) {
        $philhealthFilePath = $currentPaths['philhealth_file_path'];
    }
    if (!$tinFilePath && isset($currentPaths['tin_file_path'])) {
        $tinFilePath = $currentPaths['tin_file_path'];
    }
    
    // Update government IDs including file paths
    $govIdSql = "UPDATE employee_government_ids 
                SET sss_number = ?, 
                    pagibig_number = ?, 
                    philhealth_number = ?, 
                    tin_number = ?";
    
    // Add file paths to the SQL if they exist
    $govIdParams = [$input['sssNumber'], $input['pagibigNumber'], $input['philhealthNumber'], $input['tinNumber']];
    $govIdTypes = 'ssss';
    
    if ($sssFilePath) {
        $govIdSql .= ", sss_file_path = ?";
        $govIdParams[] = $sssFilePath;
        $govIdTypes .= 's';
    }
    
    if ($pagibigFilePath) {
        $govIdSql .= ", pagibig_file_path = ?";
        $govIdParams[] = $pagibigFilePath;
        $govIdTypes .= 's';
    }
    
    if ($philhealthFilePath) {
        $govIdSql .= ", philhealth_file_path = ?";
        $govIdParams[] = $philhealthFilePath;
        $govIdTypes .= 's';
    }
    
    if ($tinFilePath) {
        $govIdSql .= ", tin_file_path = ?";
        $govIdParams[] = $tinFilePath;
        $govIdTypes .= 's';
    }
    
    $govIdSql .= " WHERE employee_id = ?";
    $govIdParams[] = $input['employeeId'];
    $govIdTypes .= 'i';
    
    $govIdStmt = $conn->prepare($govIdSql);
    $govIdStmt->bind_param($govIdTypes, ...$govIdParams);
    
    $govIdSuccess = $govIdStmt->execute();
    $debug['gov_id_update_success'] = $govIdSuccess;
    $debug['gov_id_sql'] = $govIdSql;

    if (!$govIdSuccess) {
        throw new Exception("Government ID update failed: " . $conn->error);
    }

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Employee updated successfully',
        'debug' => $debug
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error updating employee: ' . $e->getMessage(),
        'debug' => $debug
    ]);
}
