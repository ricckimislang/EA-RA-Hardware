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
function getPayslipData($payrollId)
{
    global $conn;

    // Get basic payroll data
    $sql = "SELECT 
                p.id as payroll_id,
                e.id as employee_id,
                e.full_name,
                e.date_hired,
                e.salary_rate_type,
                e.overtime_rate,
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

    // Get standard work hours
    $standardWorkHours = isset($settings['standard_hours']) ? (int)$settings['standard_hours'] : 8;

    // Calculate overtime hours for the pay period
    $overtimeSql = "SELECT 
                    SUM(CASE WHEN total_hours > ? THEN total_hours - ? ELSE 0 END) as overtime_hours
                FROM attendance_records 
                WHERE employee_id = ? 
                AND DATE(time_in) BETWEEN ? AND ?";

    $overtimeStmt = $conn->prepare($overtimeSql);
    $overtimeStmt->bind_param('iiiss', $standardWorkHours, $standardWorkHours, $payslip['employee_id'], $payslip['start_date'], $payslip['end_date']);
    $overtimeStmt->execute();
    $overtimeResult = $overtimeStmt->get_result();
    $overtimeData = $overtimeResult->fetch_assoc();

    // Add overtime data to payslip
    $payslip['overtime_hours'] = $overtimeData['overtime_hours'] ?? 0;
    $payslip['regular_hours'] = $payslip['total_hours'] - $payslip['overtime_hours'];

    // Calculate hourly and overtime rates
    $standardWorkDays = 22; // Standard working days per month
    $hourlyRate = $payslip['base_salary'] / ($standardWorkDays * $standardWorkHours);
    $payslip['hourly_rate'] = $hourlyRate;

    // Calculate regular and overtime pay
    $payslip['regular_pay'] = $hourlyRate * $payslip['regular_hours'];
    $payslip['overtime_pay'] = $payslip['overtime_rate'] * $payslip['overtime_hours'];

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
        $ids = $idsResult->fetch_assoc();
        $payslip['sss_number'] = $ids['sss_number'];
        $payslip['pagibig_number'] = $ids['pagibig_number'];
        $payslip['philhealth_number'] = $ids['philhealth_number'];
        $payslip['tin_number'] = $ids['tin_number'];
    }

    return $payslip;
}
