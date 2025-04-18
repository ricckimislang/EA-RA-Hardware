<?php
require_once '../../../database/config.php';

header('Content-Type: application/json');

// Allow POST method for delete operations
$isDelete = ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($isDelete) {
    // Read JSON input for POST requests with application/json content type
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = isset($input['id']) ? intval($input['id']) : 0;

    // Validate ID
    if ($productId <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid product ID'
        ]);
        exit;
    }

    try {
        // Start transaction
        $conn->begin_transaction();

        // First, check if the product exists
        $checkStmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $checkStmt->bind_param('i', $productId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Product not found");
        }

        // Check if product has stock transactions
        $checkTransactionsStmt = $conn->prepare("SELECT COUNT(*) as count FROM stock_transactions WHERE product_id = ?");
        $checkTransactionsStmt->bind_param('i', $productId);
        $checkTransactionsStmt->execute();
        $transactionResult = $checkTransactionsStmt->get_result();
        $transactionData = $transactionResult->fetch_assoc();

        // Optional: Decide whether to allow deletion if there are transactions
        // Uncomment this block if you want to prevent deletion of products with transactions
        /*
        if ($transactionData['count'] > 0) {
            throw new Exception("Cannot delete product with existing stock transactions");
        }
        */

        // // Delete the stock transactions related to the product
        // $deleteTransactionsStmt = $conn->prepare("DELETE FROM stock_transactions WHERE product_id = ?");
        // $deleteTransactionsStmt->bind_param('i', $productId);
        // $deleteTransactionsStmt->execute();

        // Delete the product from the database
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param('i', $productId);
        $success = $stmt->execute();

        if (!$success) {
            throw new Exception("Database error: " . $conn->error);
        }

        // Check if any rows were affected
        if ($stmt->affected_rows === 0) {
            throw new Exception("No product deleted");
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();

        echo json_encode([
            'success' => false,
            'message' => 'Error deleting product: ' . $e->getMessage()
        ]);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
