<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../database/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'credits' => []
];

try {
    // Get all store credits with customer info
    $query = "
        SELECT 
            sc.credit_id,
            sc.credit_code,
            sc.credit_amount,
            sc.used_amount,
            sc.issue_date,
            sc.expiry_date,
            sc.is_active,
            rt.customer_name,
            rt.contact_number,
            rt.transaction_id
        FROM store_credits sc
        JOIN return_transactions rt ON sc.return_id = rt.return_id
        ORDER BY sc.issue_date DESC
    ";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    // Fetch all credits
    $credits = [];
    while ($row = $result->fetch_assoc()) {
        // Check if credit is expired
        $expiry = new DateTime($row['expiry_date']);
        $now = new DateTime();
        
        if ($expiry < $now) {
            // Credit is expired, mark as inactive if not already
            if ($row['is_active'] == 1) {
                $updateStmt = $conn->prepare("
                    UPDATE store_credits 
                    SET is_active = 0 
                    WHERE credit_id = ?
                ");
                
                if ($updateStmt) {
                    $updateStmt->bind_param("i", $row['credit_id']);
                    $updateStmt->execute();
                    $updateStmt->close();
                    
                    // Update the row data to reflect the change
                    $row['is_active'] = 0;
                }
            }
        }
        
        $credits[] = $row;
    }
    
    // Build success response
    $response['success'] = true;
    $response['message'] = "Store credits retrieved successfully";
    $response['credits'] = $credits;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    // Close connection
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->close();
    }
    
    // Return JSON response
    echo json_encode($response);
} 