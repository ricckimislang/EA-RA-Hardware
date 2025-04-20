<?php
require_once '../../database/config.php';

header('Content-Type: application/json');

try {
    // Get the last transaction ID from product_sales table
    $sql = "SELECT transaction_id FROM product_sales ORDER BY sale_id DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = intval(substr($row['transaction_id'], -5));
        $nextId = $lastId + 1;
    } else {
        $nextId = 1;
    }

    // Format new transaction ID with leading zeros
    $newTransactionId = str_pad($nextId, 5, '0', STR_PAD_LEFT);

    echo json_encode([
        'success' => true,
        'transaction_id' => $newTransactionId
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error generating transaction ID: ' . $e->getMessage()
    ]);
}

$conn->close();