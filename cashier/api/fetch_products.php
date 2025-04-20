<?php
header('Content-Type: application/json');

// Database connection
require_once '../../database/config.php';

try {
    // Get category filter if provided
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    // Build query based on category filter
    $query = "SELECT p.product_id, p.sku, p.barcode, p.name, p.description, 
              p.selling_price as price, p.stock_level as stock, p.unit, 
              c.name as category_name, b.name as brand_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.category_id 
              LEFT JOIN brands b ON p.brand_id = b.brand_id";
    
    // Add category filter if not 'all'
    if ($category !== 'all') {
        $query .= " WHERE c.name = ?";
    }
    
    $stmt = $conn->prepare($query);
    
    // Bind category parameter if needed
    if ($category !== 'all') {
        $stmt->bind_param('s', $category);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['product_id'],
            'sku' => $row['sku'],
            'barcode' => $row['barcode'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => (float)$row['price'],
            'stock' => (int)$row['stock'],
            'unit' => $row['unit'],
            'category' => $row['category_name'],
            'brand' => $row['brand_name'],
            'image' => '../assets/images/products/default-product.png' // Default image
        ];
    }
    
    echo json_encode(['success' => true, 'products' => $products]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}