<?php
session_start();
include_once '../database/config.php';

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Check if request is POST and contains necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_hash']) && isset($_POST['mode'])) {
    $qrHash = $_POST['qr_hash'];
    $mode = $_POST['mode'];
    $response = [];
    
    // Get the current datetime from server
    $currentDatetime = date('Y-m-d H:i:s');
    
    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        $response = [
            'status' => 'error',
            'message' => 'Database connection failed: ' . mysqli_connect_error()
        ];
        echo json_encode($response);
        exit;
    }
    
    // Ensure database timezone matches PHP timezone
    $timeZoneQuery = "SET time_zone = '" . date('P') . "'";
    $conn->query($timeZoneQuery);
    
    // Find employee by QR code hash
    $stmt = $conn->prepare("SELECT e.id, e.full_name FROM employee_qr_codes qr 
                           JOIN employees e ON qr.employee_id = e.id 
                           WHERE qr.qr_code_hash = ? AND qr.is_active = 1");
    $stmt->bind_param("s", $qrHash);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $response = [
            'status' => 'error',
            'message' => 'Invalid QR code or employee not found.'
        ];
    } else {
        $employee = $result->fetch_assoc();
        $employeeId = $employee['id'];
        $employeeName = $employee['full_name'];
        
        // Get attendance settings
        $settingsQuery = "SELECT setting_name, setting_value FROM attendance_settings";
        $settingsResult = $conn->query($settingsQuery);
        $settings = [];
        
        while ($row = $settingsResult->fetch_assoc()) {
            $settings[$row['setting_name']] = $row['setting_value'];
        }
        
        // Process based on mode (time-in or time-out)
        if ($mode === 'time-in') {
            // Check if employee already has an open attendance record for today
            $todayStart = date('Y-m-d 00:00:00');
            $todayEnd = date('Y-m-d 23:59:59');
            
            $checkQuery = "SELECT id FROM attendance_records 
                          WHERE employee_id = ? AND time_in BETWEEN ? AND ? 
                          AND time_out IS NULL";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("iss", $employeeId, $todayStart, $todayEnd);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'You have already timed in today and not yet timed out.'
                ];
            } else {
                // Determine status (on time or late)
                $status = 'present';
                $workStartTime = $settings['work_start_time'] ?? '08:00:00';
                $lateThreshold = $settings['late_threshold_minutes'] ?? 15;
                
                $currentTime = date('H:i:s');
                $lateTime = date('H:i:s', strtotime($workStartTime . ' + ' . $lateThreshold . ' minutes'));
                
                if ($currentTime > $lateTime) {
                    $status = 'late';
                }
                
                // Record time-in
                $insertQuery = "INSERT INTO attendance_records (employee_id, time_in, status) VALUES (?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("iss", $employeeId, $currentDatetime, $status);
                
                if ($insertStmt->execute()) {
                    $response = [
                        'status' => 'success',
                        'message' => "Time in recorded for $employeeName at " . date('h:i A', strtotime($currentDatetime))
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to record time in. Please try again.'
                    ];
                }
            }
        } else if ($mode === 'time-out') {
            // Find the most recent open attendance record for the employee
            $findQuery = "SELECT id, time_in FROM attendance_records 
                         WHERE employee_id = ? AND time_out IS NULL 
                         ORDER BY time_in DESC LIMIT 1";
            $findStmt = $conn->prepare($findQuery);
            $findStmt->bind_param("i", $employeeId);
            $findStmt->execute();
            $findResult = $findStmt->get_result();
            
            if ($findResult->num_rows === 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'No open attendance record found. Please time in first.'
                ];
            } else {
                $record = $findResult->fetch_assoc();
                $recordId = $record['id'];
                $timeIn = new DateTime($record['time_in']);
                $timeOut = new DateTime($currentDatetime);
                
                // Calculate hours worked
                $interval = $timeIn->diff($timeOut);
                $hoursWorked = $interval->h + ($interval->i / 60);
                
                // Update attendance record with time out and hours worked
                $updateQuery = "UPDATE attendance_records SET time_out = ?, total_hours = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("sdi", $currentDatetime, $hoursWorked, $recordId);
                
                if ($updateStmt->execute()) {
                    $response = [
                        'status' => 'success',
                        'message' => "Time out recorded for $employeeName at " . date('h:i A', strtotime($currentDatetime)) . 
                                     ". Total hours: " . number_format($hoursWorked, 2)
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to record time out. Please try again.'
                    ];
                }
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid mode. Please select time in or time out.'
            ];
        }
    }
    
    // Close connection
    $stmt->close();
    $conn->close();
    
    // Return response as JSON
    echo json_encode($response);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}
?> 