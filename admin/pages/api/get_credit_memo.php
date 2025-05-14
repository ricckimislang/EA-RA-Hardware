<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../database/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

// Make sure we have a credit code
if (!isset($_GET['credit_code']) || empty($_GET['credit_code'])) {
    $response['message'] = 'Credit code is required';
    echo json_encode($response);
    exit;
}

$creditCode = htmlspecialchars(trim($_GET['credit_code']));

try {
    // Get store credit information
    $stmt = $conn->prepare("
        SELECT 
            sc.credit_id,
            sc.return_id,
            sc.credit_amount,
            sc.credit_code,
            sc.issue_date,
            sc.expiry_date,
            sc.used_amount,
            sc.is_active,
            rt.transaction_id,
            rt.customer_name,
            rt.contact_number,
            u.username as processed_by_username
        FROM store_credits sc
        JOIN return_transactions rt ON sc.return_id = rt.return_id
        JOIN users u ON rt.processed_by = u.id
        WHERE sc.credit_code = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("s", $creditCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Credit memo not found");
    }
    
    $credit = $result->fetch_assoc();
    $stmt->close();
    
    // Get returned items information
    $stmt = $conn->prepare("
        SELECT 
            ri.quantity,
            ri.unit_price,
            ri.subtotal,
            p.name as product_name,
            p.sku
        FROM return_items ri
        JOIN products p ON ri.product_id = p.product_id
        WHERE ri.return_id = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $credit['return_id']);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Calculate remaining credit
    $remainingCredit = $credit['credit_amount'] - $credit['used_amount'];
    
    // Prepare credit memo data
    $creditMemo = [
        'credit_id' => $credit['credit_id'],
        'credit_code' => $credit['credit_code'],
        'original_transaction_id' => $credit['transaction_id'],
        'customer_name' => $credit['customer_name'],
        'contact_number' => $credit['contact_number'],
        'issue_date' => $credit['issue_date'],
        'expiry_date' => $credit['expiry_date'],
        'credit_amount' => $credit['credit_amount'],
        'used_amount' => $credit['used_amount'],
        'remaining_amount' => $remainingCredit,
        'is_active' => (bool)$credit['is_active'],
        'processed_by' => $credit['processed_by_username'],
        'items' => $items,
        'store_name' => 'EA-RA Hardware',
        'store_address' => 'Address Line 1, City, State, ZIP',
        'store_contact' => '+1-123-456-7890',
        'memo_date' => date('Y-m-d H:i:s')
    ];
    
    // Build success response
    $response['success'] = true;
    $response['message'] = "Credit memo retrieved successfully";
    $response['data'] = $creditMemo;
    
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