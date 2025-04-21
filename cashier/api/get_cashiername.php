<?php
session_start();
require '../../database/config.php';

$user_id = $_GET['user_id'];
if($user_id){
try{
    $stmt = $conn->prepare('SELECT u.employee_id, e.full_name FROM users u LEFT JOIN employees e ON u.employee_id = e.id WHERE u.id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $cashier_name = $row['full_name'];
        echo json_encode(['success' => true, 'cashier_name' => $cashier_name]);
    }
    else{
        echo json_encode(['success' => false, 'message' => 'Cashier not found']);
    }
}
catch(mysqli_sql_exception $e){
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
}
else{
    echo json_encode(['success' => false, 'message' => 'User ID not provided']);
}
?>