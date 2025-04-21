<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate database connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . ($conn->connect_error ?? "Connection not established"));
    }
    
    // Validate input
    if (!isset($_POST['payroll_id']) || !isset($_POST['status'])) {
        throw new Exception("Payroll ID and status are required");
    }
    
    $payrollId = intval($_POST['payroll_id']);
    $status = $_POST['status'];
    
    // Validate status value
    if (!in_array($status, ['pending', 'paid'])) {
        throw new Exception("Invalid status value. Must be 'pending' or 'paid'");
    }
    
    // Update the payment status
    $sql = "UPDATE payroll SET payment_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param('si', $status, $payrollId);
    
    if (!$stmt->execute()) {
        throw new Exception("Error updating payment status: " . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception("No payroll record found with ID: $payrollId");
    }
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'message' => "Payment status updated to '$status' successfully"
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 