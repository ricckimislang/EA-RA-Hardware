<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate required fields
    if (!isset($_POST['employee_id']) || empty($_POST['employee_id'])) {
        throw new Exception("Employee ID is required");
    }
    
    if (!isset($_POST['amount']) || empty($_POST['amount'])) {
        throw new Exception("Amount is required");
    }
    
    // Extract and sanitize inputs
    $employeeId = intval($_POST['employee_id']);
    $amount = floatval($_POST['amount']);
    $paymentMethod = isset($_POST['payment_method']) ? $conn->real_escape_string($_POST['payment_method']) : 'cash';
    $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
    $requestDate = date('Y-m-d');
    
    // Validate amount (minimum 100)
    if ($amount < 100) {
        throw new Exception("Amount must be at least ₱100");
    }
    
    // Check if employee exists
    $checkSql = "SELECT id FROM employees WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('i', $employeeId);
    $checkStmt->execute();
    
    if ($checkStmt->get_result()->num_rows === 0) {
        throw new Exception("Employee not found");
    }
    
    // Check if the employee has maximum allowed advances
    // Get the employee's salary information
    $salaryQuery = "SELECT p.base_salary 
                    FROM employees e
                    JOIN positions p ON e.position_id = p.id
                    WHERE e.id = ?";
                    
    $salaryStmt = $conn->prepare($salaryQuery);
    $salaryStmt->bind_param('i', $employeeId);
    $salaryStmt->execute();
    $salaryResult = $salaryStmt->get_result();
    
    if ($salaryResult->num_rows === 0) {
        throw new Exception("Employee salary information not found");
    }
    
    $baseSalary = $salaryResult->fetch_assoc()['base_salary'];
    
    // Get max advance percentage from settings
    $settingsSql = "SELECT setting_value FROM pay_settings WHERE setting_name = 'max_cash_advance_percent'";
    $settingsResult = $conn->query($settingsSql);
    $maxAdvancePercent = ($settingsResult && $settingsResult->num_rows > 0) ? 
        $settingsResult->fetch_assoc()['setting_value'] : 30; // Default to 30%
    
    // Calculate maximum allowed advance
    $maxAllowedAdvance = $baseSalary * ($maxAdvancePercent / 100);
    
    // Check existing unpaid advances
    $existingAdvanceSql = "SELECT SUM(amount) as total_unpaid 
                          FROM cash_advances 
                          WHERE employee_id = ? 
                          AND status IN ('pending', 'approved') 
                          AND payroll_id IS NULL";
                          
    $existingAdvanceStmt = $conn->prepare($existingAdvanceSql);
    $existingAdvanceStmt->bind_param('i', $employeeId);
    $existingAdvanceStmt->execute();
    $existingAdvanceResult = $existingAdvanceStmt->get_result();
    $existingUnpaidTotal = $existingAdvanceResult->fetch_assoc()['total_unpaid'] ?? 0;
    
    // Check if the new request would exceed the maximum
    if (($existingUnpaidTotal + $amount) > $maxAllowedAdvance) {
        throw new Exception("This request would exceed the maximum allowed advance amount of ₱" . 
                           number_format($maxAllowedAdvance, 2, '.', ',') . 
                           ". Current unpaid advances: ₱" . 
                           number_format($existingUnpaidTotal, 2, '.', ','));
    }
    
    // Insert the cash advance request
    $sql = "INSERT INTO cash_advances 
            (employee_id, amount, request_date, payment_method, notes, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('idsss', $employeeId, $amount, $requestDate, $paymentMethod, $notes);
    
    if ($stmt->execute()) {
        $advanceId = $conn->insert_id;
        echo json_encode([
            'status' => 'success',
            'message' => 'Cash advance request submitted successfully',
            'data' => [
                'id' => $advanceId,
                'employee_id' => $employeeId,
                'amount' => $amount,
                'request_date' => $requestDate
            ]
        ]);
    } else {
        throw new Exception("Error submitting cash advance request: " . $conn->error);
    }
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 