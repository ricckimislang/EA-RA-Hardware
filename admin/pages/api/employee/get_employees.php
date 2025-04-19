<?php
// Database connection
require_once '../../../../database/config.php';

// Set up error handling and response
header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => '', 'data' => array());

try {
    // Fetch all employees with position information
    $query = "SELECT e.id, e.full_name, e.employment_type, e.salary_rate_type, 
                    DATE_FORMAT(e.date_hired, '%Y-%m-%d') as date_hired, 
                    e.contact_number, e.email_address, p.title AS position_title 
             FROM employees e
             JOIN positions p ON e.position_id = p.id
             ORDER BY e.id DESC";

    $result = $conn->query($query);

    if ($result) {
        $employees = array();
        while ($row = $result->fetch_assoc()) {
            // Process data for better display
            $row['employment_type'] = ucfirst($row['employment_type']);
            $row['salary_rate_type'] = ucfirst($row['salary_rate_type']);
            $row['date_hired'] = date('M d, Y', strtotime($row['date_hired']));

            $employees[] = $row;
        }

        $response = array(
            'status' => 'success',
            'message' => count($employees) . ' employees found',
            'data' => $employees
        );

        $result->free();
    } else {
        throw new Exception("Error fetching employees: " . $conn->error);
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Send response
echo json_encode($response);
$conn->close();
