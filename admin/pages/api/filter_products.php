<?php
// Add strict error handling at the top
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/wamp64/logs/api_errors.log');

require_once '../../../database/config.php';

header('Content-Type: application/json');

// Verify database connection first
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get and validate JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Extract filter parameters with proper validation
$categoryFilter = isset($input['category']) && !empty($input['category']) ? $input['category'] : null;
$brandFilter = isset($input['brand']) && !empty($input['brand']) ? $input['brand'] : null;
$stockFilter = isset($input['stock']) && !empty($input['stock']) ? $input['stock'] : null;

try {
    // Start building the query
    $query = "SELECT p.*, c.name as category, b.name as brand 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.category_id 
              LEFT JOIN brands b ON p.brand_id = b.brand_id 
              WHERE 1=1";
    
    $params = [];
    
    // Add category filter if provided
    if (!empty($categoryFilter)) {
        $query .= " AND c.category_id = ?";
        $params[] = $categoryFilter;
    }
    
    // Add brand filter if provided
    if (!empty($brandFilter)) {
        $query .= " AND b.brand_id = ?";
        $params[] = $brandFilter;
    }
    
    // Add stock level filter if provided
    if (!empty($stockFilter)) {
        switch ($stockFilter) {
            case 'low':
                $query .= " AND p.stock_level > 0 AND p.stock_level <= p.low_stock_threshold";
                break;
            case 'out':
                $query .= " AND p.stock_level <= 0";
                break;
            case 'normal':
                $query .= " AND p.stock_level > p.low_stock_threshold";
                break;
        }
    }
    
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all products
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Convert database field names to camelCase for JavaScript
        $products[] = [
            'id' => $row['product_id'],
            'sku' => $row['sku'],
            'itemName' => $row['name'],
            'category' => $row['category'],
            'brand' => $row['brand'],
            'description' => $row['description'],
            'stockLevel' => (int)$row['stock_level'],
            'lowStockThreshold' => (int)$row['low_stock_threshold'],
            'unit' => $row['unit'],
            'costPrice' => (float)$row['cost_price'],
            'sellingPrice' => (float)$row['selling_price'],
            'supplier' => $row['supplier']
        ];
    }
    
    // Return success response with filtered products
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error filtering products: ' . $e->getMessage()
    ]);
}
?>