<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Validate database connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . ($conn->connect_error ?? "Connection not established"));
    }
    
    // Validate input
    if (!isset($_POST['start_date']) || !isset($_POST['end_date'])) {
        throw new Exception("Start date and end date are required");
    }
    
    // Get the dates from the POST request
    $startDateStr = $_POST['start_date'];
    $endDateStr = $_POST['end_date'];
    
    // Create or get pay period
    $payPeriodId = createPayPeriod($startDateStr, $endDateStr);
    
    // Process payroll for the period
    $result = processPayroll($payPeriodId, $startDateStr, $endDateStr);
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'message' => "Payroll processed successfully for period: $startDateStr to $endDateStr",
        'pay_period_id' => $payPeriodId
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Function to create a pay period
function createPayPeriod($startDate, $endDate)
{
    global $conn;
    
    // Check if the pay period already exists
    $checkSql = "SELECT id FROM pay_periods WHERE start_date = ? AND end_date = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('ss', $startDate, $endDate);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    
    // Create a new pay period
    $sql = "INSERT INTO pay_periods (start_date, end_date, status) VALUES (?, ?, 'open')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $startDate, $endDate);
    
    if ($stmt->execute()) {
        $newId = $conn->insert_id;
        return $newId;
    } else {
        throw new Exception("Error creating pay period: " . $conn->error);
    }
}

// Process payroll for all employees in a pay period
function processPayroll($payPeriodId, $startDate, $endDate)
{
    global $conn;
    
    // Get the pay settings
    $settingsSql = "SELECT * FROM pay_settings";
    $settingsResult = $conn->query($settingsSql);
    
    if (!$settingsResult) {
        throw new Exception("Error fetching pay settings: " . $conn->error);
    }
    
    $settings = [];
    while ($row = $settingsResult->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
    
    // Verify we have all required settings
    $requiredSettings = ['sss_rate', 'philhealth_rate', 'pagibig_rate', 'tin_fixed', 'standard_hours', 'overtime_multiplier'];
    foreach ($requiredSettings as $setting) {
        if (!isset($settings[$setting])) {
            throw new Exception("Missing required pay setting: $setting");
        }
    }
    
    // Get all attendance records for the pay period
    $attendanceRecords = getAttendanceRecords($startDate, $endDate);
    
    if (!$attendanceRecords) {
        throw new Exception("Error fetching attendance records");
    }
    
    $recordCount = $attendanceRecords->num_rows;
    
    if ($recordCount == 0) {
        throw new Exception("No attendance records found for period $startDate to $endDate");
    }
    
    // First delete any existing payroll entries for this period to avoid duplicates
    $deleteSql = "DELETE FROM payroll WHERE pay_period_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param('i', $payPeriodId);
    $deleteStmt->execute();
    
    // Standard work values for hourly rate calculation
    $standardWorkDays = 22; // Standard working days in a month
    $standardWorkHours = (int)$settings['standard_hours']; // Standard working hours per day
    $overtimeMultiplier = (float)$settings['overtime_multiplier']; // Overtime rate multiplier
    
    // Process each employee's payroll
    $processedCount = 0;
    while ($employee = $attendanceRecords->fetch_assoc()) {
        // Get monthly salary from base_salary
        $monthlySalary = $employee['base_salary'];
        
        // Calculate hourly rate using the formula: Monthly Salary / (Work Days × Work Hours)
        $hourlyRate = $monthlySalary / ($standardWorkDays * $standardWorkHours);
        
        // Get employee's individual overtime rate
        $getOvertimeRateSql = "SELECT overtime_rate FROM employees WHERE id = ?";
        $overtimeStmt = $conn->prepare($getOvertimeRateSql);
        $overtimeStmt->bind_param('i', $employee['employee_id']);
        $overtimeStmt->execute();
        $overtimeResult = $overtimeStmt->get_result();
        $overtimeRow = $overtimeResult->fetch_assoc();
        $overtimeRate = $overtimeRow ? $overtimeRow['overtime_rate'] : ($hourlyRate * $overtimeMultiplier);
        
        // Get daily hours breakdown to calculate overtime
        $dailyHoursSql = "SELECT 
                            DATE(time_in) as work_date, 
                            total_hours
                        FROM attendance_records 
                        WHERE employee_id = ? AND DATE(time_in) BETWEEN ? AND ?";
        $dailyStmt = $conn->prepare($dailyHoursSql);
        $dailyStmt->bind_param('iss', $employee['employee_id'], $startDate, $endDate);
        $dailyStmt->execute();
        $dailyResult = $dailyStmt->get_result();
        
        $regularHours = 0;
        $overtimeHours = 0;
        
        while ($day = $dailyResult->fetch_assoc()) {
            $hoursToday = (int)$day['total_hours'];
            
            if ($hoursToday <= $standardWorkHours) {
                $regularHours += $hoursToday;
            } else {
                $regularHours += $standardWorkHours;
                $overtimeHours += ($hoursToday - $standardWorkHours);
            }
        }
        
        // Calculate regular pay and overtime pay
        $regularPay = $hourlyRate * $regularHours;
        $overtimePay = $overtimeRate * $overtimeHours;
        
        // Total gross pay (regular + overtime)
        $grossPay = $regularPay + $overtimePay;
        
        // Total hours (regular + overtime)
        $totalHours = $regularHours + $overtimeHours;
        
        // Calculate deductions
        $sss = $grossPay * ($settings['sss_rate'] / 100);
        $philhealth = $grossPay * ($settings['philhealth_rate'] / 100);
        $pagibig = $grossPay * ($settings['pagibig_rate'] / 100);
        $tin = $settings['tin_fixed']; // Fixed amount
        
        $totalDeductions = $sss + $philhealth + $pagibig + $tin;
        $netPay = $grossPay - $totalDeductions;
        
        // Insert into payroll
        $payrollSql = "INSERT INTO payroll 
                        (pay_period_id, employee_id, total_hours, gross_pay, deductions, net_pay)
                        VALUES (?, ?, ?, ?, ?, ?)";
        
        $payrollStmt = $conn->prepare($payrollSql);
        if (!$payrollStmt) {
            throw new Exception("Error preparing payroll statement: " . $conn->error);
        }
        
        $payrollStmt->bind_param(
            'iiiddd',
            $payPeriodId,
            $employee['employee_id'],
            $totalHours,
            $grossPay,
            $totalDeductions,
            $netPay
        );
        
        if (!$payrollStmt->execute()) {
            throw new Exception("Error executing payroll insert: " . $payrollStmt->error);
        }
        
        $processedCount++;
    }
    
    // Close the pay period
    $closeSql = "UPDATE pay_periods SET status = 'open' WHERE id = ?";
    $closeStmt = $conn->prepare($closeSql);
    $closeStmt->bind_param('i', $payPeriodId);
    
    if (!$closeStmt->execute()) {
        throw new Exception("Error closing pay period: " . $closeStmt->error);
    }
    
    return ["processed_count" => $processedCount];
}

// Get attendance records for the pay period
function getAttendanceRecords($startDate, $endDate)
{
    global $conn;
    
    $sql = "SELECT 
                ar.employee_id,
                e.full_name,
                e.position_id,
                p.title as position_title,
                p.base_salary,
                SUM(ar.total_hours) as total_hours,
                COUNT(CASE WHEN ar.status != 'absent' THEN 1 END) as days_worked,
                COUNT(CASE WHEN ar.status = 'present' THEN 1 END) as days_present,
                COUNT(CASE WHEN ar.status = 'late' THEN 1 END) as days_late,
                COUNT(CASE WHEN ar.status = 'half-day' THEN 1 END) as days_half,
                COUNT(CASE WHEN ar.status = 'absent' THEN 1 END) as days_absent
            FROM attendance_records ar
            JOIN employees e ON ar.employee_id = e.id
            JOIN positions p ON e.position_id = p.id
            WHERE DATE(ar.time_in) BETWEEN ? AND ?
            GROUP BY ar.employee_id";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing attendance query: " . $conn->error);
    }
    
    $stmt->bind_param('ss', $startDate, $endDate);
    
    if (!$stmt->execute()) {
        throw new Exception("Error executing attendance query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    return $result;
}
