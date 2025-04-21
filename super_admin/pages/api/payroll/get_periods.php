<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Get all pay periods
    $periods = getPayPeriods();
    
    // Return success response with periods
    echo json_encode([
        'status' => 'success',
        'data' => $periods
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Function to get all pay periods
function getPayPeriods() {
    global $conn;
    
    $sql = "SELECT 
                id,
                start_date, 
                end_date, 
                status,
                created_at
            FROM pay_periods
            ORDER BY start_date DESC";
    
    $result = $conn->query($sql);
    
    $periods = [];
    while ($row = $result->fetch_assoc()) {
        $periods[] = $row;
    }
    
    return $periods;
}
?> 