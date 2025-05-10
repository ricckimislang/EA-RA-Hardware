<?php
require_once '../../../../database/config.php';

// Check if PHPExcel is available, otherwise use simple CSV
if (!isset($_GET['pay_period_id'])) {
    exit("Pay period ID is required");
}

$payPeriodId = intval($_GET['pay_period_id']);

// Get period details
$periodSql = "SELECT start_date, end_date FROM pay_periods WHERE id = ?";
$periodStmt = $conn->prepare($periodSql);
$periodStmt->bind_param('i', $payPeriodId);
$periodStmt->execute();
$periodResult = $periodStmt->get_result();

if ($periodResult->num_rows == 0) {
    exit("Pay period not found");
}

$periodData = $periodResult->fetch_assoc();
$startDate = date('M d, Y', strtotime($periodData['start_date']));
$endDate = date('M d, Y', strtotime($periodData['end_date']));
$filename = "Payroll_Report_{$startDate}_to_{$endDate}.csv";

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Create file pointer connected to PHP output stream
$output = fopen('php://output', 'w');

// Write CSV header row
fputcsv($output, [
    'Employee Name', 
    'Position', 
    'Total Hours', 
    'Gross Pay', 
    'Deductions', 
    'Net Pay', 
    'Payment Status'
]);

// Get payroll data
$sql = "SELECT 
            e.full_name,
            pos.title as position,
            p.total_hours,
            p.gross_pay,
            p.deductions,
            p.net_pay,
            p.payment_status
        FROM payroll p
        JOIN employees e ON p.employee_id = e.id
        JOIN positions pos ON e.position_id = pos.id
        WHERE p.pay_period_id = ?
        ORDER BY e.full_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $payPeriodId);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$totalGrossPay = 0;
$totalDeductions = 0;
$totalNetPay = 0;

// Write each row of data
while ($row = $result->fetch_assoc()) {
    // Format data
    $paymentStatus = ucfirst($row['payment_status']);
    $grossPay = $row['gross_pay'];
    $deductions = $row['deductions'];
    $netPay = $row['net_pay'];
    
    // Add to totals
    $totalGrossPay += $grossPay;
    $totalDeductions += $deductions;
    $totalNetPay += $netPay;
    
    // Write row to CSV
    fputcsv($output, [
        $row['full_name'],
        $row['position'],
        (int)$row['total_hours'],
        number_format($grossPay, 2, '.', ','),
        number_format($deductions, 2, '.', ','),
        number_format($netPay, 2, '.', ','),
        $paymentStatus
    ]);
}

// Add a totals row
fputcsv($output, [
    'TOTALS',
    '',
    '',
    number_format($totalGrossPay, 2, '.', ','),
    number_format($totalDeductions, 2, '.', ','),
    number_format($totalNetPay, 2, '.', ','),
    ''
]);

// Add period information
fputcsv($output, []);
fputcsv($output, ["Pay Period:", "$startDate to $endDate"]);
fputcsv($output, ["Generated:", date('M d, Y h:i A')]);

fclose($output);
exit(); 