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

// Check if there's a file upload
$receiptPath = null;
if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
    // Process file upload
    $uploadResult = processReceiptUpload();
    
    if (!$uploadResult['success']) {
        // If file upload failed, return error
        echo json_encode($uploadResult);
        exit;
    }
    
    // If successful, get the file path
    $receiptPath = $uploadResult['file_path'];
}

// Process form data
$input = [];
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    // Handle JSON input for non-file data
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON input'
        ]);
        exit;
    }
} else {
    // Handle form data
    $input = [
        'expenseName' => $_POST['expenseName'] ?? '',
        'expenseAmount' => $_POST['expenseAmount'] ?? '',
        'expenseCategory' => $_POST['expenseCategory'] ?? '',
        'expenseDate' => $_POST['expenseDate'] ?? '',
        'expenseNotes' => $_POST['expenseNotes'] ?? '',
    ];
    
    // If receiptPath was set from a previous upload, use it
    if (isset($_POST['receiptPath'])) {
        $receiptPath = $_POST['receiptPath'];
    }
}

// Validate required fields
$requiredFields = ['expenseName', 'expenseAmount', 'expenseCategory', 'expenseDate'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if category exists, if not create it
    $categoryId = null;
    $stmt = $conn->prepare("SELECT category_id FROM expense_categories WHERE category_id = ?");
    $stmt->bind_param('i', $input['expenseCategory']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categoryId = $row['category_id'];
    } 
    //else {
    //     // Create new category
    //     $stmt = $conn->prepare("INSERT INTO expense_categories (name) VALUES (?)");
    //     $stmt->bind_param('s', $input['category']);
    //     $stmt->execute();
    //     $categoryId = $conn->insert_id;
    // }

    // Handle empty notes field
    $notes = isset($input['expenseNotes']) ? $input['expenseNotes'] : '';
    
    // If receiptPath came from JSON input, use it (for the case where file was uploaded separately)
    if (isset($input['receiptPath']) && $receiptPath === null) {
        $receiptPath = $input['receiptPath'];
    }

    // Insert new expense
    $stmt = $conn->prepare("INSERT INTO expense_transactions (category_id, expense_name, amount, transaction_date, receipt_path, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param(
        'isisss',
        $categoryId,
        $input['expenseName'],
        $input['expenseAmount'],
        $input['expenseDate'],
        $receiptPath,
        $notes
    );

    $stmt->execute();
    $expenseId = $conn->insert_id;

    // Commit transaction
    $conn->commit();

    // Return success response with new expense ID
    echo json_encode([
        'success' => true,
        'message' => 'Expense added successfully',
        'expenseId' => $expenseId
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error adding expense: ' . $e->getMessage()
    ]);
}

/**
 * Process receipt file upload
 * 
 * @return array Success status and message
 */
function processReceiptUpload() {
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
