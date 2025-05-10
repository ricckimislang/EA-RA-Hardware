<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

try {
    // Build the query based on filter parameters
    $whereConditions = [];
    $params = [];
    $paramTypes = '';
    
    // Get the base query
    $sql = "SELECT ca.id, ca.employee_id, e.full_name as employee_name, ca.amount, 
                  ca.request_date, ca.approval_date, ca.status, ca.payment_method, 
                  ca.notes, ca.payroll_id, ca.approved_by
           FROM cash_advances ca
           JOIN employees e ON ca.employee_id = e.id";
    
    // Filter by status if provided
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $status = $conn->real_escape_string($_GET['status']);
        $whereConditions[] = "ca.status = ?";
        $params[] = $status;
        $paramTypes .= 's';
    }
    
    // Filter by employee if provided
    if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
        $employeeId = intval($_GET['employee_id']);
        $whereConditions[] = "ca.employee_id = ?";
        $params[] = $employeeId;
        $paramTypes .= 'i';
    }
    
    // Filter by date range if provided
    if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
        $dateFrom = $conn->real_escape_string($_GET['date_from']);
        $whereConditions[] = "ca.request_date >= ?";
        $params[] = $dateFrom;
        $paramTypes .= 's';
    }
    
    if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
        $dateTo = $conn->real_escape_string($_GET['date_to']);
        $whereConditions[] = "ca.request_date <= ?";
        $params[] = $dateTo;
        $paramTypes .= 's';
    }
    
    // Add the WHERE clause if there are conditions
    if (!empty($whereConditions)) {
        $sql .= " WHERE " . implode(" AND ", $whereConditions);
    }
    
    // Add sorting (newest first)
    $sql .= " ORDER BY ca.request_date DESC, ca.id DESC";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    
    // Bind parameters if there are any
    if (!empty($params)) {
        $stmt->bind_param($paramTypes, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all advances
    $advances = [];
    while ($row = $result->fetch_assoc()) {
        $advances[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'count' => count($advances),
        'data' => $advances
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 