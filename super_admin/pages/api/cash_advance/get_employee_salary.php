<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_GET['id'])) {
        throw new Exception("Employee ID is required");
    }
    
    $employeeId = intval($_GET['id']);
    
    // Get the max advance percentage from settings
    $settingsSql = "SELECT setting_value FROM pay_settings WHERE setting_name = 'max_cash_advance_percent'";
    $settingsResult = $conn->query($settingsSql);
    $maxAdvancePercent = ($settingsResult && $settingsResult->num_rows > 0) ? 
        $settingsResult->fetch_assoc()['setting_value'] : 30; // Default to 30%
    
    // Get the employee's salary information
    $sql = "SELECT e.id, e.full_name, e.salary_rate_type, p.base_salary, p.title
            FROM employees e
            JOIN positions p ON e.position_id = p.id
            WHERE e.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Employee not found");
    }
    
    $employee = $result->fetch_assoc();
    
    // Calculate maximum allowed advance based on salary
    $monthlySalary = $employee['base_salary'];
    $maxAdvance = $monthlySalary * ($maxAdvancePercent / 100);
    
    // Check for existing unpaid advances
    $advanceSql = "SELECT SUM(amount) as total_unpaid 
                   FROM cash_advances 
                   WHERE employee_id = ? 
                   AND status IN ('pending', 'approved') 
                   AND payroll_id IS NULL";
    
    $advanceStmt = $conn->prepare($advanceSql);
    $advanceStmt->bind_param('i', $employeeId);
    $advanceStmt->execute();
    $advanceResult = $advanceStmt->get_result();
    $unpaidAdvances = $advanceResult->fetch_assoc()['total_unpaid'] ?? 0;
    
    // Adjust max advance based on existing unpaid advances
    $adjustedMaxAdvance = $maxAdvance - $unpaidAdvances;
    if ($adjustedMaxAdvance < 0) {
        $adjustedMaxAdvance = 0;
    }
    
    // Return the salary information
    echo json_encode([
        'status' => 'success',
        'data' => [
            'employee_id' => $employee['id'],
            'full_name' => $employee['full_name'],
            'position' => $employee['title'],
            'salary_type' => $employee['salary_rate_type'],
            'base_salary' => $employee['base_salary'],
            'max_advance_percent' => $maxAdvancePercent,
            'max_advance' => $adjustedMaxAdvance,
            'existing_advances' => $unpaidAdvances
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