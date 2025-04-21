<?php
/**
 * API endpoint for checking pay period status
 * 
 * This script returns the status of a pay period based on a payroll ID
 */

// Include database connection
require_once '../../../../database/config.php';
header('Content-Type: application/json');

// Check if payroll ID is provided
if (!isset($_GET['payroll_id']) || empty($_GET['payroll_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payroll ID is required'
    ]);
    exit;
}

$payrollId = intval($_GET['payroll_id']);

try {
    // Prepare and execute query to get period status
    $sql = "SELECT pp.status as period_status
            FROM payroll p
            JOIN pay_periods pp ON p.pay_period_id = pp.id
            WHERE p.id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $payrollId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'period_status' => $row['period_status']
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Payroll record not found'
        ]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} 