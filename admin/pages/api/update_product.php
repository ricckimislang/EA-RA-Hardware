<?php
require_once('../../../database/config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON input'
        ]);
        exit;
    }

    $productId = $input['id'];
    $itemName = $input['itemName'];
    $category = $input['categoryId'];
    $sku = $input['sku'];
    $brand = $input['brand'];
    $description = $input['description'];
    $unit = $input['unit'];
    $costPrice = $input['costPrice'];
    $sellingPrice = $input['sellingPrice'];
    $stockLevel = $input['stockLevel'];
    $lowStockThreshold = $input['lowStockThreshold'];

    // get the brand first
    $brandId = null;
    $brndStmt = $conn->prepare("SELECT brand_id FROM brands WHERE name = ?");
    $brndStmt->bind_param('s', $brand);
    $brndStmt->execute();
    $brndStmt->bind_result($brandId);
    $brndStmt->fetch();
    $brndStmt->close();

    $stmt = $conn->prepare("UPDATE products SET 
        name = ?, 
        sku = ?, 
        category_id = ?, 
        brand_id = ?, 
        description = ?, 
        unit = ?, 
        cost_price = ?, 
        selling_price = ?, 
        stock_level = ?, 
        reorder_point = ?, 
        updated_at = CURRENT_TIMESTAMP 
        WHERE product_id = ?");

    $stmt->bind_param('ssiisssdiii', 
        $itemName, 
        $sku, 
        $category, 
        $brandId, 
        $description, 
        $unit, 
        $costPrice, 
        $sellingPrice, 
        $stockLevel, 
        $lowStockThreshold, 
        $productId
    );
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>