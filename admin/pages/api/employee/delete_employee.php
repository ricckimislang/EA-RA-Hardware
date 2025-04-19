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

// Get employee ID
$employeeId = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Validate employee ID
if ($employeeId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid employee ID'
    ]);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if employee exists
    $checkStmt = $conn->prepare("SELECT id FROM employees WHERE id = ?");
    $checkStmt->bind_param('i', $employeeId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        throw new Exception('Employee not found with ID: ' . $employeeId);
    }

    // Get file paths before deleting
    $filePathsStmt = $conn->prepare("SELECT sss_file_path, pagibig_file_path, philhealth_file_path, tin_file_path 
                                    FROM employee_government_ids 
                                    WHERE employee_id = ?");
    $filePathsStmt->bind_param('i', $employeeId);
    $filePathsStmt->execute();
    $filePathsResult = $filePathsStmt->get_result();
    $filePaths = $filePathsResult->fetch_assoc();

    // Delete from employee_government_ids first (foreign key constraint)
    $deleteGovIdsStmt = $conn->prepare("DELETE FROM employee_government_ids WHERE employee_id = ?");
    $deleteGovIdsStmt->bind_param('i', $employeeId);
    $deleteGovIdsSuccess = $deleteGovIdsStmt->execute();

    if (!$deleteGovIdsSuccess) {
        throw new Exception("Failed to delete government IDs: " . $conn->error);
    }

    // Delete from employees table
    $deleteEmployeeStmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $deleteEmployeeStmt->bind_param('i', $employeeId);
    $deleteEmployeeSuccess = $deleteEmployeeStmt->execute();

    if (!$deleteEmployeeSuccess) {
        throw new Exception("Failed to delete employee: " . $conn->error);
    }

    // Commit transaction
    $conn->commit();
    
    // Delete files after successful database deletion
    if ($filePaths) {
        $baseDir = '../../../../';
        $filesToDelete = [
            $filePaths['sss_file_path'],
            $filePaths['pagibig_file_path'],
            $filePaths['philhealth_file_path'],
            $filePaths['tin_file_path']
        ];
        
        foreach ($filesToDelete as $filePath) {
            if ($filePath && file_exists($baseDir . $filePath)) {
                unlink($baseDir . $filePath);
            }
        }
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Employee deleted successfully'
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error deleting employee: ' . $e->getMessage()
    ]);
} 