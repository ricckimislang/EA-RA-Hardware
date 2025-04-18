<?php
// Disable error display in output


require_once '../../../database/config.php';

header('Content-Type: application/json');

// Verify database connection
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

try {
    // Get all products with their category and brand names
    $query = "SELECT p.*, c.name as category_name, b.name as brand_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.category_id 
             LEFT JOIN brands b ON p.brand_id = b.brand_id";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Convert database field names to camelCase for JavaScript
        $products[] = [
            'id' => $row['product_id'],
            'sku' => $row['sku'],
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
    }

    // Get summary statistics
    $summary = [
        'total_products' => 0,
        'low_stock' => 0,
        'out_of_stock' => 0,
        'total_value' => 0
    ];

    foreach ($products as $product) {
        $summary['total_products']++;
        $summary['total_value'] += $product['stockLevel'] * $product['costPrice'];

        if ($product['stockLevel'] <= 0) {
            $summary['out_of_stock']++;
        } elseif ($product['stockLevel'] <= $product['lowStockThreshold']) {
            $summary['low_stock']++;
        }
    }

    // Get all categories
    $stmt = $conn->prepare("SELECT category_id, name FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    // Get all brands
    $stmt = $conn->prepare("SELECT brand_id, name FROM brands");
    $stmt->execute();
    $result = $stmt->get_result();
    $brands = [];
    while ($row = $result->fetch_assoc()) {
        $brands[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'products' => $products,
            'summary' => $summary,
            'categories' => $categories,
            'brands' => $brands
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
