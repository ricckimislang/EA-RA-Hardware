<?php
require_once '../../database/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->begin_transaction();

        // Parse JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Extract transaction details
        $transactionId = $data['transactionId'];
        $cashierName = $data['cashierName'];
        $items = $data['items'];
        $subtotal = $data['subtotal'];
        $discount = $data['discount'];
        $total = $data['total'];

        // Insert each product's details
        foreach ($items as $item) {
            $productId = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $stmt = $conn->prepare("INSERT INTO product_sales (transaction_id, cashier_name, product_id, quantity_sold, discount_applied, sale_price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssiidd', $transactionId, $cashierName, $item['id'], $item['quantity'], $discount, $total);
            $stmt->execute();

            // Update stock level
            $decreaseStock = $conn->prepare("UPDATE products SET stock_level = stock_level - ? WHERE product_id = ?");
            $decreaseStock->bind_param('ii', $quantity, $productId);
            $decreaseStock->execute();
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Transaction added successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}
$conn->close();
