<?php
require_once '../../../database/config.php';

header('Content-Type: application/json');

// Get product ID from query parameter
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Prepare and execute query
    $query = "SELECT p.*, c.name as category_name, b.name as brand_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.category_id 
              LEFT JOIN brands b ON p.brand_id = b.brand_id 
              WHERE p.product_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Product not found");
    }

    $row = $result->fetch_assoc();

    // Format response
    $product = [
        'id' => $row['product_id'],
        'sku' => $row['sku'],
        'barcode' => $row['barcode'],
        'itemName' => $row['name'],
        'category' => $row['category_name'],
        'brand' => $row['brand_name'],
        'description' => $row['description'],
        'stockLevel' => (int)$row['stock_level'],
        'lowStockThreshold' => (int)$row['reorder_point'],
        'unit' => $row['unit'],
        'costPrice' => (float)$row['cost_price'],
        'sellingPrice' => (float)$row['selling_price'],
        'categoryId' => $row['category_id'],
        'brandId' => $row['brand_id']
    ];

    echo json_encode([
        'success' => true,
        'product' => $product
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving product: ' . $e->getMessage()
    ]);
}
