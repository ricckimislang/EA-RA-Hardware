<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_GET['id'])) {
        throw new Exception("Cash advance ID is required");
    }
    
    $advanceId = intval($_GET['id']);
    
    // Get the cash advance details
    $sql = "SELECT ca.id, ca.employee_id, e.full_name as employee_name, ca.amount, 
                  ca.request_date, ca.approval_date, ca.status, ca.payment_method, 
                  ca.notes, ca.payroll_id, ca.approved_by, u.fullname as approver_name,
                  p.title as position
           FROM cash_advances ca
           JOIN employees e ON ca.employee_id = e.id
           LEFT JOIN users u ON ca.approved_by = u.id
           JOIN positions p ON e.position_id = p.id
           WHERE ca.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $advanceId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Cash advance not found");
    }
    
    $advance = $result->fetch_assoc();
    
    // Get payroll details if linked
    if ($advance['payroll_id']) {
        $payrollSql = "SELECT p.id, pp.start_date, pp.end_date 
                      FROM payroll p
                      JOIN pay_periods pp ON p.pay_period_id = pp.id
                      WHERE p.id = ?";
        $payrollStmt = $conn->prepare($payrollSql);
        $payrollStmt->bind_param('i', $advance['payroll_id']);
        $payrollStmt->execute();
        $payrollResult = $payrollStmt->get_result();
        
        if ($payrollResult->num_rows > 0) {
            $payroll = $payrollResult->fetch_assoc();
            $advance['payroll_period'] = $payroll['start_date'] . ' to ' . $payroll['end_date'];
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $advance
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 