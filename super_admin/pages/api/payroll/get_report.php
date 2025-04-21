<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_GET['pay_period_id'])) {
        throw new Exception("Pay period ID is required");
    }
    
    $payPeriodId = $_GET['pay_period_id'];
    
    // Get payroll report for the period
    $reportData = getPayrollReport($payPeriodId);
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'data' => $reportData
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Function to get payroll report for a pay period
function getPayrollReport($payPeriodId) {
    global $conn;
    
    $sql = "SELECT 
                p.id as payroll_id,
                e.id as employee_id,
                e.full_name,
                pos.title as position,
                pp.start_date,
                pp.end_date,
                p.total_hours,
                p.gross_pay,
                p.deductions,
                p.net_pay,
                p.payment_status
            FROM payroll p
            JOIN employees e ON p.employee_id = e.id
            JOIN positions pos ON e.position_id = pos.id
            JOIN pay_periods pp ON p.pay_period_id = pp.id
            WHERE p.pay_period_id = ?
            ORDER BY e.full_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $payPeriodId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $payrollData = [];
    while ($row = $result->fetch_assoc()) {
        $payrollData[] = [
            'payroll_id' => $row['payroll_id'],
            'employee_id' => $row['employee_id'],
            'full_name' => $row['full_name'],
            'position' => $row['position'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'total_hours' => $row['total_hours'],
            'gross_pay' => $row['gross_pay'],
            'deductions' => $row['deductions'],
            'net_pay' => $row['net_pay'],
            'payment_status' => $row['payment_status']
        ];
    }
    
    return $payrollData;
}
?> 