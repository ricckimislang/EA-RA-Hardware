<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_GET['employee_id'])) {
        throw new Exception("Employee ID is required");
    }
    
    $employeeId = intval($_GET['employee_id']);
    
    // Get the employee's information
    $employeeSql = "SELECT e.id, e.full_name, p.base_salary
                    FROM employees e
                    JOIN positions p ON e.position_id = p.id
                    WHERE e.id = ?";
    $employeeStmt = $conn->prepare($employeeSql);
    $employeeStmt->bind_param('i', $employeeId);
    $employeeStmt->execute();
    $employeeResult = $employeeStmt->get_result();
    
    if ($employeeResult->num_rows === 0) {
        throw new Exception("Employee not found");
    }
    
    $employee = $employeeResult->fetch_assoc();
    
    // Get cash advance settings
    $settingsSql = "SELECT setting_value FROM pay_settings WHERE setting_name = 'max_cash_advance_percent'";
    $settingsResult = $conn->query($settingsSql);
    $maxAdvancePercent = ($settingsResult && $settingsResult->num_rows > 0) ? 
        $settingsResult->fetch_assoc()['setting_value'] : 30; // Default to 30%
    
    // Get approved but unpaid advances
    $pendingAdvancesSql = "SELECT SUM(amount) as total_pending 
                          FROM cash_advances 
                          WHERE employee_id = ? 
                          AND status = 'approved' 
                          AND payroll_id IS NULL";
    $pendingStmt = $conn->prepare($pendingAdvancesSql);
    $pendingStmt->bind_param('i', $employeeId);
    $pendingStmt->execute();
    $pendingAdvances = $pendingStmt->get_result()->fetch_assoc()['total_pending'] ?? 0;
    
    // Get all advances for this employee (including paid ones)
    $advanceHistorySql = "SELECT id, amount, request_date, approval_date, status, payroll_id, notes
                          FROM cash_advances
                          WHERE employee_id = ?
                          ORDER BY request_date DESC, id DESC
                          LIMIT 10"; // Limit to most recent 10
    $historyStmt = $conn->prepare($advanceHistorySql);
    $historyStmt->bind_param('i', $employeeId);
    $historyStmt->execute();
    $historyResult = $historyStmt->get_result();
    
    $advanceHistory = [];
    while ($row = $historyResult->fetch_assoc()) {
        $advanceHistory[] = $row;
    }
    
    // Get the total paid advances in the current month
    $thisMonth = date('Y-m-01');
    $nextMonth = date('Y-m-01', strtotime('+1 month'));
    
    $paidThisMonthSql = "SELECT SUM(amount) as total_paid_this_month
                         FROM cash_advances
                         WHERE employee_id = ?
                         AND status = 'paid'
                         AND approval_date BETWEEN ? AND ?";
    $paidThisMonthStmt = $conn->prepare($paidThisMonthSql);
    $paidThisMonthStmt->bind_param('iss', $employeeId, $thisMonth, $nextMonth);
    $paidThisMonthStmt->execute();
    $paidThisMonth = $paidThisMonthStmt->get_result()->fetch_assoc()['total_paid_this_month'] ?? 0;
    
    // Calculate maximum allowed advance
    $maxAllowedAdvance = $employee['base_salary'] * ($maxAdvancePercent / 100);
    
    // Calculate remaining available advance
    $remainingAvailable = $maxAllowedAdvance - $pendingAdvances;
    if ($remainingAvailable < 0) {
        $remainingAvailable = 0;
    }
    
    // Return the cash advance summary
    echo json_encode([
        'status' => 'success',
        'data' => [
            'employee_id' => $employee['id'],
            'full_name' => $employee['full_name'],
            'base_salary' => $employee['base_salary'],
            'max_advance_percent' => $maxAdvancePercent,
            'max_advance_amount' => $maxAllowedAdvance,
            'pending_advances' => floatval($pendingAdvances),
            'remaining_available' => floatval($remainingAvailable),
            'paid_this_month' => floatval($paidThisMonth),
            'history' => $advanceHistory
        ]
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 