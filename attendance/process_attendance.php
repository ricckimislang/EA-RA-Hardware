<?php
session_start();
include_once '../database/config.php';

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Check if request is POST and contains necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_hash'])) {
    $qrHash = $_POST['qr_hash'];
    $response = [];
    
    // Get the current datetime from server
    $currentDatetime = date('Y-m-d H:i:s');
    $currentDate = date('Y-m-d');
    
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
        
        // Check if employee already has an open attendance record for today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        
        $checkQuery = "SELECT id, time_in FROM attendance_records 
                      WHERE employee_id = ? AND time_in BETWEEN ? AND ? 
                      AND time_out IS NULL";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("iss", $employeeId, $todayStart, $todayEnd);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            // Employee has timed in but not timed out - proceed with time-out logic
            $record = $checkResult->fetch_assoc();
            $recordId = $record['id'];
            $timeIn = new DateTime($record['time_in']);
            $now = new DateTime($currentDatetime);
            
            // Check if at least 1 hour has passed since time-in
            $hourDiff = $now->diff($timeIn)->h + ($now->diff($timeIn)->days * 24);
            
            if ($hourDiff < 1) {
                $response = [
                    'status' => 'error',
                    'message' => 'You must wait at least 1 hour after time-in before you can time-out.'
                ];
            } else {
                // Check if it's still the same day
                $timeInDate = date('Y-m-d', strtotime($record['time_in']));
                
                if ($timeInDate != $currentDate) {
                    $response = [
                        'status' => 'error',
                        'message' => 'You can only time-out on the same day as your time-in.'
                    ];
                } else {
                    // Calculate hours worked
                    $interval = $timeIn->diff($now);
                    $totalHours = ($interval->days * 24) + $interval->h;
                    $minutes = $interval->i;
                    $totalHoursWithMinutes = $totalHours + ($minutes / 60);
                    
                    // Check if work period spans lunch time (12:00 PM - 1:00 PM)
                    $lunchStart = new DateTime($timeInDate . ' 12:00:00');
                    $lunchEnd = new DateTime($timeInDate . ' 13:00:00');
                    
                    // Deduct lunch hour if work period includes lunch time
                    if ($timeIn <= $lunchEnd && $now >= $lunchStart) {
                        // Calculate lunch overlap
                        $lunchDeduction = 1.0; // Default full hour
                        
                        // If employee arrived during lunch
                        if ($timeIn > $lunchStart) {
                            $lunchOverlap = ($lunchEnd->getTimestamp() - $timeIn->getTimestamp()) / 3600;
                            $lunchDeduction = min($lunchDeduction, $lunchOverlap);
                        }
                        
                        // If employee left during lunch
                        if ($now < $lunchEnd) {
                            $lunchOverlap = ($now->getTimestamp() - $lunchStart->getTimestamp()) / 3600;
                            $lunchDeduction = min($lunchDeduction, $lunchOverlap);
                        }
                        
                        // Apply lunch deduction
                        $lunchDeductionHours = floor($lunchDeduction);
                        $lunchDeductionMinutes = round(($lunchDeduction - $lunchDeductionHours) * 60);
                        
                        $totalHours -= $lunchDeductionHours;
                        $minutes -= $lunchDeductionMinutes;
                        
                        // Handle negative minutes
                        if ($minutes < 0) {
                            $totalHours--;
                            $minutes += 60;
                        }
                        
                        $totalHoursWithMinutes -= $lunchDeduction;
                    }
                    
                    // Round to 2 decimal places
                    $totalHoursWithMinutes = round($totalHoursWithMinutes, 2);
                    
                    // Update attendance record with time out and hours worked
                    $updateQuery = "UPDATE attendance_records SET time_out = ?, total_hours = ?, minutes = ? WHERE id = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("sidi", $currentDatetime, $totalHours, $minutes, $recordId);
                    
                    if ($updateStmt->execute()) {
                        $response = [
                            'status' => 'success',
                            'message' => "Time out recorded for $employeeName at " . date('h:i A', strtotime($currentDatetime)) . 
                                         ". Total hours: " . $totalHours . " hours, " . $minutes . " minutes"
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to record time out. Please try again.'
                        ];
                    }
                }
            }
        } else {
            // No open attendance record - proceed with time-in logic
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