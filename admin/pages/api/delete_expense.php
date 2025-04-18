<?php
require_once '../../../database/config.php';

header('Content-Type: application/json');

// Allow DELETE method or POST with _method=DELETE for servers that don't support DELETE
$isDelete = ($_SERVER['REQUEST_METHOD'] === 'DELETE') || 
           ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE');

if ($isDelete) {
    // Get expense ID from query parameter or post data
    $transaction_id = 0;
    
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $transaction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    } else { // POST
        $transaction_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    }
    
    // Validate ID
    if ($transaction_id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid expense ID'
        ]);
        exit;
    }
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // First, check if the expense exists and get receipt path if it exists
        $checkStmt = $conn->prepare("SELECT receipt_path FROM expense_transactions WHERE transaction_id = ?");
        $checkStmt->bind_param('i', $transaction_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Expense not found");
        }
        
        $row = $result->fetch_assoc();
        $receiptPath = $row['receipt_path'];
        
        // Delete the expense from the database
        $stmt = $conn->prepare("DELETE FROM expense_transactions WHERE transaction_id = ?");
        $stmt->bind_param('i', $transaction_id);
        $success = $stmt->execute();
        
        if (!$success) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        // Check if any rows were affected
        if ($stmt->affected_rows === 0) {
            throw new Exception("No expense deleted");
        }
        
        // Commit transaction
        $conn->commit();
        
        // If there was a receipt file, delete it from the server
        if (!empty($receiptPath)) {
            $fullPath = __DIR__ . '/../../../' . $receiptPath;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Expense deleted successfully'
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        echo json_encode([
            'success' => false,
            'message' => 'Error deleting expense: ' . $e->getMessage()
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