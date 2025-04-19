<?php
// Database connection
require_once '../../../../database/config.php';

// Set up error handling and response
header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => '', 'data' => array());

try {
    // Fetch all positions
    $query = "SELECT id, title, base_salary FROM positions ORDER BY title ASC";
    $result = $conn->query($query);
    
    if ($result) {
        $positions = array();
        while ($row = $result->fetch_assoc()) {
            $positions[] = $row;
        }
        
        $response = array(
            'status' => 'success',
            'message' => count($positions) . ' positions found',
            'data' => $positions
        );
        
        $result->free();
    } else {
        throw new Exception("Error fetching positions: " . $conn->error);
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Send response
echo json_encode($response);
$conn->close(); 