<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../database/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Verify request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Get credit code from POST data
$creditCode = $_POST['credit_code'] ?? '';
$transactionId = $_POST['transaction_id'] ?? '';
$amountUsed = isset($_POST['amount_used']) ? (float)$_POST['amount_used'] : 0;

// Validate inputs
if (empty($creditCode)) {
    $response['message'] = 'Credit code is required';
    echo json_encode($response);
    exit;
}

if (empty($transactionId)) {
    $response['message'] = 'Transaction ID is required';
    echo json_encode($response);
    exit;
}

if ($amountUsed <= 0) {
    $response['message'] = 'Amount used must be greater than zero';
    echo json_encode($response);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if credit exists and is active
    $checkQuery = "
        SELECT 
            credit_id, 
            credit_amount, 
            used_amount, 
            is_active,
            expiry_date
        FROM store_credits 
        WHERE credit_code = ?
    ";
    
    $checkStmt = $conn->prepare($checkQuery);
    
    if (!$checkStmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $checkStmt->bind_param("s", $creditCode);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Credit not found");
    }
    
    $credit = $result->fetch_assoc();
    $checkStmt->close();
    
    // Check if credit is active
    if ($credit['is_active'] != 1) {
        throw new Exception("This credit is no longer active");
    }
    
    // Check if credit is expired
    $expiry = new DateTime($credit['expiry_date']);
    $now = new DateTime();
    
    if ($expiry < $now) {
        throw new Exception("This credit has expired");
    }
    
    // Calculate remaining amount
    $remainingAmount = $credit['credit_amount'] - $credit['used_amount'];
    
    if ($remainingAmount <= 0) {
        throw new Exception("This credit has no remaining balance");
    }
    
    // Check if requested amount exceeds remaining balance
    if ($amountUsed > $remainingAmount) {
        throw new Exception("Amount exceeds remaining credit balance");
    }
    
    // Update credit usage
    $newUsedAmount = $credit['used_amount'] + $amountUsed;
    $newRemainingAmount = $credit['credit_amount'] - $newUsedAmount;
    
    // Determine if credit should be deactivated (fully used)
    $isActive = ($newRemainingAmount > 0) ? 1 : 0;
    
    $updateQuery = "
        UPDATE store_credits 
        SET used_amount = ?, 
            is_active = ? 
        WHERE credit_id = ?
    ";
    
    $updateStmt = $conn->prepare($updateQuery);
    
    if (!$updateStmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $updateStmt->bind_param("dii", $newUsedAmount, $isActive, $credit['credit_id']);
    $updateStmt->execute();
    
    if ($updateStmt->affected_rows === 0) {
        throw new Exception("Failed to update credit");
    }
    
    $updateStmt->close();
    
    // Record credit usage history
    $historyQuery = "
        INSERT INTO credit_usage_history (
            credit_id, 
            transaction_id, 
            amount_used, 
            remaining_after,
            usage_date
        ) VALUES (?, ?, ?, ?, NOW())
    ";
    
    $historyStmt = $conn->prepare($historyQuery);
    
    if (!$historyStmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $historyStmt->bind_param("isdd", $credit['credit_id'], $transactionId, $amountUsed, $newRemainingAmount);
    $historyStmt->execute();
    
    if ($historyStmt->affected_rows === 0) {
        throw new Exception("Failed to record usage history");
    }
    
    $historyStmt->close();
    
    // Commit transaction
    $conn->commit();
    
    // Build success response
    $response['success'] = true;
    $response['message'] = "Credit used successfully";
    $response['remaining_amount'] = $newRemainingAmount;
    $response['is_active'] = $isActive == 1;
    
} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    
    $response['message'] = $e->getMessage();
} finally {
    // Close connection
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->close();
    }
    
    // Return JSON response
    echo json_encode($response);
} 