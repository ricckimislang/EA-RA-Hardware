<?php
// Database connection
require_once '../../../../database/config.php';

// Set up error handling and response
header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => '');

// Add more detailed debugging info
$debug = [];
$debug['request_method'] = $_SERVER['REQUEST_METHOD'];
$debug['content_type'] = $_SERVER['CONTENT_TYPE'] ?? 'No content type';
$debug['content_length'] = $_SERVER['CONTENT_LENGTH'] ?? 'No content length';
$debug['php_upload_max_filesize'] = ini_get('upload_max_filesize');
$debug['php_post_max_size'] = ini_get('post_max_size');

// Check if exceeded POST limit (this happens before POST data is populated)
if (empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
    $response['status'] = 'error';
    $response['message'] = 'Upload exceeded the maximum allowed size. Check post_max_size and upload_max_filesize in php.ini.';
    $response['debug'] = $debug;
    echo json_encode($response);
    exit;
}

// Create upload directory if it doesn't exist
$upload_dir = '../../../../uploads/employee_documents/';
if (!file_exists($upload_dir)) {
    $mkdir_result = mkdir($upload_dir, 0777, true);
    $debug['mkdir_result'] = $mkdir_result;
    if (!$mkdir_result) {
        $response['status'] = 'error';
        $response['message'] = 'Failed to create upload directory';
        $response['debug'] = $debug;
        echo json_encode($response);
        exit;
    }
}

// Function to handle file upload
function handleFileUpload($file_data, $employee_id, $document_type, $employee_name = '')
{
    global $upload_dir, $response, $debug;

    // Check if file data is valid
    if (!isset($file_data) || !is_array($file_data) || empty($file_data['name'])) {
        return null;
    }

    // Check for upload errors
    if ($file_data['error'] !== UPLOAD_ERR_OK) {
        $debug['upload_error_' . $document_type] = $file_data['error'];
        switch ($file_data['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $response['message'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $response['message'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
                break;
            case UPLOAD_ERR_PARTIAL:
                $response['message'] = 'The uploaded file was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_FILE:
                return null; // Not an error, just no file
            case UPLOAD_ERR_NO_TMP_DIR:
                $response['message'] = 'Missing a temporary folder';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $response['message'] = 'Failed to write file to disk';
                break;
            case UPLOAD_ERR_EXTENSION:
                $response['message'] = 'A PHP extension stopped the file upload';
                break;
            default:
                $response['message'] = 'Unknown upload error';
                break;
        }
        return null;
    }

    $file_ext = pathinfo($file_data['name'], PATHINFO_EXTENSION);

    // Only accept Word and PDF files
    $allowed_extensions = ['doc', 'docx', 'pdf'];
    if (!in_array(strtolower($file_ext), $allowed_extensions)) {
        $response['message'] = 'Only Word (DOC/DOCX) and PDF files are allowed for ' . $document_type;
        return null;
    }

    $sanitized_name = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $employee_name));
    $new_filename = $sanitized_name . '_' . $document_type . '_' . time() . '.' . $file_ext;
    $target_path = $upload_dir . $new_filename;

    // Check if temporary file exists and is readable
    if (!file_exists($file_data['tmp_name']) || !is_readable($file_data['tmp_name'])) {
        $debug['tmp_file_exists_' . $document_type] = file_exists($file_data['tmp_name']);
        $debug['tmp_file_readable_' . $document_type] = is_readable($file_data['tmp_name']);
        $response['message'] = 'Temporary file does not exist or is not readable: ' . $document_type;
        return null;
    }

    // Check if target directory is writable
    if (!is_writable(dirname($target_path))) {
        $debug['target_dir_writable_' . $document_type] = false;
        $response['message'] = 'Upload directory is not writable';
        return null;
    }

    $move_result = move_uploaded_file($file_data['tmp_name'], $target_path);
    $debug['move_result_' . $document_type] = $move_result;

    if ($move_result) {
        return 'uploads/employee_documents/' . $new_filename;
    } else {
        $debug['upload_error_last_' . $document_type] = error_get_last();
        $response['message'] = 'Error uploading file: ' . $document_type;
        return null;
    }
}

// Function to get file upload error message
function getFileUploadErrorMessage($error_code)
{
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload';
        default:
            return 'Unknown upload error';
    }
}

function createrUserAccount($employee_id, $usertype)
{
    global $conn;
    $employeeId = $employee_id;

    $stmt = $conn->prepare('SELECT full_name, contact_number, email_address FROM employees WHERE id = ?');
    $stmt->bind_param('i', $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $username = explode(' ', $row['full_name'])[0];
    $full_name = $row['full_name'];
    $contact_number = $row['contact_number'];
    $email_address = $row['email_address'];
    
    $password = ($usertype == '2') ? 'admin' : 'cashier';
    $password = md5($password);

    $addUser = $conn->query("INSERT INTO users (employee_id, username, fullname, email, contact_no, password, usertype) VALUES ('$employeeId', '$username', '$full_name', '$email_address', '$contact_number', '$password', '$usertype')");
    if ($addUser) {
        return true;
    } else {
        return false;
    }
}

// get position name
function getPositionName($position_id){
    global $conn;
    $stmt = $conn->prepare('SELECT title FROM positions WHERE id = ?');
    $stmt->bind_param('i', $position_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['title'];
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Start transaction
        $conn->begin_transaction();

        // Debug received data
        $debug = [];
        $debug['post_data'] = $_POST;
        $debug['files_data'] = $_FILES;

        // Basic employee information - use null coalescing operator to handle missing values
        $full_name = isset($_POST['full_name']) ? $conn->real_escape_string($_POST['full_name']) : '';
        $position_id = isset($_POST['position_id']) ? intval($_POST['position_id']) : 0;
        $employment_type = isset($_POST['employment_type']) ? $conn->real_escape_string($_POST['employment_type']) : 'full-time';
        $salary_rate_type = isset($_POST['salary_rate_type']) ? $conn->real_escape_string($_POST['salary_rate_type']) : 'monthly';
        $date_hired = isset($_POST['date_hired']) ? $conn->real_escape_string($_POST['date_hired']) : date('Y-m-d');
        $overtime_rate = isset($_POST['overtime_rate']) ? floatval($_POST['overtime_rate']) : 0;
        $contact_number = isset($_POST['contact_number']) ? $conn->real_escape_string($_POST['contact_number']) : '';
        $email_address = !empty($_POST['email_address']) ? $conn->real_escape_string($_POST['email_address']) : null;

        // Validate required fields
        if (empty($full_name) || $position_id <= 0 || empty($date_hired) || empty($contact_number)) {
            throw new Exception("Missing required fields. Please fill in all required fields.");
        }

        // Government ID numbers
        $sss_number = !empty($_POST['sss_number']) ? $conn->real_escape_string($_POST['sss_number']) : null;
        $pagibig_number = !empty($_POST['pagibig_number']) ? $conn->real_escape_string($_POST['pagibig_number']) : null;
        $philhealth_number = !empty($_POST['philhealth_number']) ? $conn->real_escape_string($_POST['philhealth_number']) : null;
        $tin_number = !empty($_POST['tin_number']) ? $conn->real_escape_string($_POST['tin_number']) : null;

        // Insert employee data
        $query = "INSERT INTO employees 
                 (full_name, position_id, employment_type, salary_rate_type, date_hired, overtime_rate, contact_number, email_address) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('sisssdss', $full_name, $position_id, $employment_type, $salary_rate_type, $date_hired, $overtime_rate, $contact_number, $email_address);

        if ($stmt->execute()) {
            $employee_id = $conn->insert_id;

            $position_name = getPositionName($position_id);
            if($position_name == 'MANAGER'){
                $usertype = '2';
            }else if($position_name == 'CASHIER'){
                $usertype = '3';
            }else{
                $usertype = '4';
            }

            $createUserAccount = createrUserAccount($employee_id, $usertype);

            // Handle file uploads
            $sss_file_path = isset($_FILES['sss_file']) ? handleFileUpload($_FILES['sss_file'], $employee_id, 'sss') : null;
            $pagibig_file_path = isset($_FILES['pagibig_file']) ? handleFileUpload($_FILES['pagibig_file'], $employee_id, 'pagibig') : null;
            $philhealth_file_path = isset($_FILES['philhealth_file']) ? handleFileUpload($_FILES['philhealth_file'], $employee_id, 'philhealth') : null;
            $tin_file_path = isset($_FILES['tin_file']) ? handleFileUpload($_FILES['tin_file'], $employee_id, 'tin') : null;

            // Insert government IDs
            $gov_query = "INSERT INTO employee_government_ids 
                         (employee_id, sss_number, sss_file_path, pagibig_number, pagibig_file_path, 
                         philhealth_number, philhealth_file_path, tin_number, tin_file_path) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $gov_stmt = $conn->prepare($gov_query);
            $gov_stmt->bind_param(
                'issssssss',
                $employee_id,
                $sss_number,
                $sss_file_path,
                $pagibig_number,
                $pagibig_file_path,
                $philhealth_number,
                $philhealth_file_path,
                $tin_number,
                $tin_file_path
            );

            if ($gov_stmt->execute()) {
                // Commit the transaction
                $conn->commit();

                $response = array(
                    'status' => 'success',
                    'message' => 'Employee registered successfully',
                    'employee_id' => $employee_id,
                    'debug' => $debug
                );
            } else {
                throw new Exception("Error saving government IDs: " . $gov_stmt->error);
            }

            $gov_stmt->close();
        } else {
            throw new Exception("Error saving employee: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
        $response['debug'] = $debug ?? [];
    }
}

// Send response
echo json_encode($response);
$conn->close();
