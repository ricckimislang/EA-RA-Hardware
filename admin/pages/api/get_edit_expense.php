<?php
require_once '../../../database/config.php';

header('Content-Type: application/json');

// Get product ID from query parameter
$transaction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Prepare and execute query
    $query = "SELECT et.*, ec.name as category_name
              FROM expense_transactions et
              LEFT JOIN expense_categories ec ON et.category_id = ec.category_id
              WHERE et.transaction_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("expense not found");
    }

    $row = $result->fetch_assoc();

    // Format response
    $expense = [
        'transaction_id' => $row['transaction_id'],
        'category_id' => $row['category_id'],
        'expense_name' => $row['expense_name'],
        'amount' => (float)$row['amount'],
        'transaction_date' => $row['transaction_date'],
        'receipt_path' => $row['receipt_path'],
        'notes' => $row['notes'],
    ];

    echo json_encode([
        'success' => true,
        'expense' => $expense
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving expense: ' . $e->getMessage()
    ]);
}
