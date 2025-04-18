<?php
require_once '../../../database/config.php';

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

// Check if there's a file upload
$receiptPath = null;
$updateReceiptPath = false;

if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
    // Process file upload
    $uploadResult = processReceiptUpload();
    $debug['file_upload'] = true;
    $debug['upload_result'] = $uploadResult;

    if (!$uploadResult['success']) {
        // If file upload failed, return error
        echo json_encode($uploadResult);
        exit;
    }

    // If successful, get the file path and mark for update
    $receiptPath = $uploadResult['file_path'];
    $updateReceiptPath = true;

    // Log the receipt path being set
    $debug['new_receipt_path'] = $receiptPath;
}

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

    // Check if receipt path is included in JSON (for explicit removal)
    if (isset($input['receiptPath'])) {
        $receiptPath = $input['receiptPath'];
        $updateReceiptPath = true;
        $debug['receiptPath_from_json'] = $receiptPath;
    }

    $debug['json_input'] = $input;
} else {
    // Handle form data
    $debug['post_data'] = $_POST;

    $input = [
        'expenseId' => $_POST['expenseId'] ?? '',
        'expenseName' => $_POST['expenseName'] ?? '',
        'expenseAmount' => $_POST['expenseAmount'] ?? '',
        'expenseCategory' => $_POST['expenseCategory'] ?? '',
        'expenseDate' => $_POST['expenseDate'] ?? '',
        'expenseNotes' => $_POST['expenseNotes'] ?? '',
    ];

    // If receiptPath was set from a previous upload, use it
    if (isset($_POST['receiptPath'])) {
        $receiptPath = $_POST['receiptPath'];
        $updateReceiptPath = true;
    }
}

// Ensure expenseId is an integer
if (isset($input['expenseId'])) {
    $input['expenseId'] = intval($input['expenseId']);
}

$debug['processed_input'] = $input;
$debug['receipt_path'] = $receiptPath;
$debug['update_receipt_path'] = $updateReceiptPath;

// Validate required fields
$requiredFields = ['expenseId', 'expenseName', 'expenseAmount', 'expenseCategory', 'expenseDate'];
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

// Validate expense ID
if ($input['expenseId'] <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid expense ID: ' . $input['expenseId'],
        'debug' => $debug
    ]);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if expense exists
    $checkStmt = $conn->prepare("SELECT transaction_id FROM expense_transactions WHERE transaction_id = ?");
    $checkStmt->bind_param('i', $input['expenseId']);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        throw new Exception('Expense not found with ID: ' . $input['expenseId']);
    }

    // Check if category exists
    $categoryId = null;
    $stmt = $conn->prepare("SELECT category_id FROM expense_categories WHERE category_id = ?");
    $stmt->bind_param('i', $input['expenseCategory']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categoryId = $row['category_id'];
    } else {
        throw new Exception('Invalid category ID');
    }

    // Handle notes field
    $notes = isset($input['expenseNotes']) ? $input['expenseNotes'] : '';

    $debug['final_values'] = [
        'expenseId' => $input['expenseId'],
        'categoryId' => $categoryId,
        'expenseName' => $input['expenseName'],
        'amount' => $input['expenseAmount'],
        'date' => $input['expenseDate'],
        'receiptPath' => $receiptPath,
        'notes' => $notes,
        'updateReceiptPath' => $updateReceiptPath
    ];

    if ($updateReceiptPath) {
        // Update all fields including receipt path
        $sql = "UPDATE expense_transactions 
                SET category_id = ?, expense_name = ?, amount = ?, transaction_date = ?, receipt_path = ?, notes = ? 
                WHERE transaction_id = ?";

        $debug['sql'] = $sql;
        $stmt = $conn->prepare($sql);

        // Debug: Log the actual values being bound to parameters
        $debug['bind_params'] = [
            'category_id' => $categoryId,
            'expense_name' => $input['expenseName'],
            'amount' => $input['expenseAmount'],
            'transaction_date' => $input['expenseDate'],
            'receipt_path' => $receiptPath, // Check if this value is correct
            'notes' => $notes,
            'transaction_id' => $input['expenseId']
        ];

        // Ensure receipt_path is a proper string, not null or 0
        if ($receiptPath === null && isset($input['receiptPath']) && $input['receiptPath'] === null) {
            // This is an explicit NULL for removing receipt
            $debug['receipt_path_handling'] = 'Setting to NULL';

            // Execute with NULL for receipt_path using direct query
            $execSql = sprintf(
                "UPDATE expense_transactions 
                SET category_id = %d, 
                    expense_name = '%s', 
                    amount = %f, 
                    transaction_date = '%s', 
                    receipt_path = NULL, 
                    notes = '%s' 
                WHERE transaction_id = %d",
                $categoryId,
                $conn->real_escape_string($input['expenseName']),
                floatval($input['expenseAmount']),
                $conn->real_escape_string($input['expenseDate']),
                $conn->real_escape_string($notes),
                $input['expenseId']
            );

            $debug['executed_sql'] = $execSql;
            $success = $conn->query($execSql);
        } else {
            // Normal path with a value for receipt_path
            if (empty($receiptPath)) {
                $debug['receipt_path_handling'] = 'Empty path detected, getting existing path';

                // Keep existing receipt path if the new one is empty
                $existingPathStmt = $conn->prepare("SELECT receipt_path FROM expense_transactions WHERE transaction_id = ?");
                $existingPathStmt->bind_param('i', $input['expenseId']);
                $existingPathStmt->execute();
                $existingPathResult = $existingPathStmt->get_result();
                $existingPathRow = $existingPathResult->fetch_assoc();

                if ($existingPathRow && !empty($existingPathRow['receipt_path'])) {
                    $receiptPath = $existingPathRow['receipt_path'];
                    $debug['existing_receipt_path'] = $receiptPath;
                }
            }

            $debug['receipt_path_handling'] = 'Using path: ' . $receiptPath;
            $receiptPathStr = (string)$receiptPath; // Convert to string
            $stmt->bind_param(
                'isisssi',
                $categoryId,             // i - category_id (integer)
                $input['expenseName'],   // s - expense_name (string)
                $input['expenseAmount'], // i - amount (treated as integer/numeric)
                $input['expenseDate'],   // s - transaction_date (string)
                $receiptPathStr,         // s - receipt_path (string)
                $notes,                  // s - notes (string)
                $input['expenseId']      // i - transaction_id (integer)
            );
            $success = $stmt->execute();
        }
    } else {
        // Update all fields except receipt path
        $sql = "UPDATE expense_transactions 
                SET category_id = ?, expense_name = ?, amount = ?, transaction_date = ?, notes = ? 
                WHERE transaction_id = ?";

        $debug['sql'] = $sql;
        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            'isissi',
            $categoryId,
            $input['expenseName'],
            $input['expenseAmount'],
            $input['expenseDate'],
            $notes,
            $input['expenseId']
        );

        $success = $stmt->execute();
    }

    $debug['execute_success'] = $success;

    if (!$success) {
        throw new Exception("Update query failed: " . $conn->error);
    }

    // Verify the update
    $verifyStmt = $conn->prepare("SELECT receipt_path FROM expense_transactions WHERE transaction_id = ?");
    $verifyStmt->bind_param('i', $input['expenseId']);
    $verifyStmt->execute();
    $verifyResult = $verifyStmt->get_result();
    $verifyRow = $verifyResult->fetch_assoc();
    $debug['verify_receipt_path'] = $verifyRow['receipt_path'];

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Expense updated successfully',
        'debug' => $debug
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error updating expense: ' . $e->getMessage(),
        'debug' => $debug
    ]);
}

/**
 * Process receipt file upload
 * 
 * @return array Success status and message
 */
function processReceiptUpload()
{
    // Define upload directory
    $uploadDir = '../../../assets/images/receipts/';

    // Ensure the directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get file information
    $file = $_FILES['receipt'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileError = $file['error'];

    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    // Validate file extension
    if (!in_array($fileExt, $allowedExtensions)) {
        return [
            'success' => false,
            'message' => 'Invalid file type. Only JPG, JPEG, and PNG files are allowed.'
        ];
    }

    // Validate file size (2MB max)
    if ($fileSize > 2 * 1024 * 1024) {
        return [
            'success' => false,
            'message' => 'File is too large. Maximum size is 2MB.'
        ];
    }

    // Generate unique filename to prevent overwriting
    $newFileName = uniqid('receipt_') . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;

    // Move uploaded file to destination
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        return [
            'success' => true,
            'message' => 'File uploaded successfully.',
            'file_path' => 'assets/images/receipts/' . $newFileName
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to upload file. Server error.'
        ];
    }
}
