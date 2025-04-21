<?php

/**
 * API endpoint for closing a payroll period
 * 
 * This script updates the status of a pay period to 'processed'
 * only if all employees in that period have been paid
 */

// Include database connection
require_once '../../../../database/config.php';
header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Validate pay period ID
if (!isset($_POST['pay_period_id']) || empty($_POST['pay_period_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Pay period ID is required'
    ]);
    exit;
}

$payPeriodId = intval($_POST['pay_period_id']);

try {
    // Begin transaction to ensure data consistency
    $conn->begin_transaction();

    // First, check if all employees in this pay period have been paid
    $checkSql = "SELECT COUNT(*) as unpaid_count 
                FROM payroll 
                WHERE pay_period_id = ? AND payment_status != 'paid'";
    
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('i', $payPeriodId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    $checkStmt->close();

    // If there are unpaid employees, don't close the payroll
    if ($row['unpaid_count'] > 0) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => 'Cannot close payroll. There are ' . $row['unpaid_count'] . ' unpaid employees.'
        ]);
        exit;
    }

    // Update the pay_periods table to mark it as processed
    $updateSql = "UPDATE pay_periods 
                 SET status = 'processed' 
                 WHERE id = ? AND status = 'open'";
                 
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param('i', $payPeriodId);
    $updateStmt->execute();
    
    // Check if the update was successful
    if ($updateStmt->affected_rows === 0) {
        // No rows updated, either ID doesn't exist or already processed
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => 'Pay period not found or already processed'
        ]);
        exit;
    }
    $updateStmt->close();

    // Commit the transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Payroll period closed successfully'
    ]);
    
} catch (Exception $e) {
    // Roll back any changes if an error occurred
    $conn->rollback();
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
