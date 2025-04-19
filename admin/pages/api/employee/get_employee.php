<?php
// Database connection
require_once '../../../../database/config.php';

// Set up error handling and response
header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => '', 'data' => null);

// Check if employee ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $employee_id = intval($_GET['id']);
    
    try {
        // Fetch employee data with position info
        $query = "SELECT e.*, p.title as position_title, p.base_salary
                  FROM employees e
                  JOIN positions p ON e.position_id = p.id
                  WHERE e.id = ?";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $employee = $result->fetch_assoc();
            
            // Fetch government IDs
            $gov_query = "SELECT * FROM employee_government_ids WHERE employee_id = ?";
            $gov_stmt = $conn->prepare($gov_query);
            $gov_stmt->bind_param('i', $employee_id);
            $gov_stmt->execute();
            $gov_result = $gov_stmt->get_result();
            
            if ($gov_result && $gov_result->num_rows > 0) {
                $employee['government_ids'] = $gov_result->fetch_assoc();
            } else {
                $employee['government_ids'] = null;
            }
            
            $response = array(
                'status' => 'success',
                'message' => 'Employee found',
                'data' => $employee
            );
            
            $gov_stmt->close();
        } else {
            $response['message'] = 'Employee not found';
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid employee ID';
}

// Send response
echo json_encode($response);
$conn->close(); 