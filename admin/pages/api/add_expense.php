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

// Get and validate JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON input'
    ]);
    exit;
}

// Validate required fields
$requiredFields = ['expenseName', 'expenseAmount', 'expenseCategory', 'expenseDate', 'expenseNotes'];
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

    // Insert new expense
    $stmt = $conn->prepare("INSERT INTO expense_transactions (category_id, expense_name, amount, transaction_date, receipt_path, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param(
        'isisss',
        $categoryId,
        $input['expenseName'],
        $input['expenseAmount'],
        $input['expenseDate'],
        $input['expenseReceipt'],
        $input['expenseNotes'],
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
