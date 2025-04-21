<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_GET['payroll_id'])) {
        throw new Exception("Payroll ID is required");
    }
    
    $payrollId = $_GET['payroll_id'];
    
    // Get payslip data
    $payslipData = getPayslipData($payrollId);
    
    if (!$payslipData) {
        throw new Exception("Payroll record not found");
    }
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'data' => $payslipData
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Function to get payslip data for a payroll record
function getPayslipData($payrollId) {
    global $conn;
    
    // Get basic payroll data
    $sql = "SELECT 
                p.id as payroll_id,
                e.id as employee_id,
                e.full_name,
                e.date_hired,
                e.salary_rate_type,
                e.contact_number,
                e.email_address,
                pos.title as position,
                pos.base_salary,
                pp.start_date,
                pp.end_date,
                pp.status as pay_period_status,
                p.total_hours,
                p.gross_pay,
                p.deductions,
                p.net_pay
            FROM payroll p
            JOIN employees e ON p.employee_id = e.id
            JOIN positions pos ON e.position_id = pos.id
            JOIN pay_periods pp ON p.pay_period_id = pp.id
            WHERE p.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $payrollId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    $payslip = $result->fetch_assoc();
    
    // Calculate deduction breakdowns based on rates in pay_settings
    $settingsSql = "SELECT * FROM pay_settings";
    $settingsResult = $conn->query($settingsSql);
    $settings = [];
    
    while ($row = $settingsResult->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
    
    // Add deduction breakdowns
    $payslip['sss'] = $payslip['gross_pay'] * ($settings['sss_rate'] / 100);
    $payslip['philhealth'] = $payslip['gross_pay'] * ($settings['philhealth_rate'] / 100);
    $payslip['pagibig'] = $payslip['gross_pay'] * ($settings['pagibig_rate'] / 100);
    $payslip['tin'] = $settings['tin_fixed'];
    
    // Get government IDs if available
    $idsSql = "SELECT * FROM employee_government_ids WHERE employee_id = ?";
    $idsStmt = $conn->prepare($idsSql);
    $idsStmt->bind_param('i', $payslip['employee_id']);
    $idsStmt->execute();
    $idsResult = $idsStmt->get_result();
    
    if ($idsResult->num_rows > 0) {
        $payslip['government_ids'] = $idsResult->fetch_assoc();
    }
    
    return $payslip;
}
?> 