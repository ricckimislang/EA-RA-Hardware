<?php
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

// Get and validate JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON input'
    ]);
    exit;
}

// Validate required fields
$requiredFields = ['itemName', 'sku', 'barCode', 'category', 'unit', 'costPrice', 'sellingPrice', 'stockLevel'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if category exists, if not create it
    $categoryId = null;
    $stmt = $conn->prepare("SELECT category_id FROM categories WHERE name = ?");
    $stmt->bind_param('s', $input['category']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categoryId = $row['category_id'];
    } else {
        // Create new category
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param('s', $input['category']);
        $stmt->execute();
        $categoryId = $conn->insert_id;
    }

    // Check if brand exists, if not create it
    $brandId = null;
    if (!empty($input['brand'])) {
        $stmt = $conn->prepare("SELECT brand_id FROM brands WHERE name = ?");
        $stmt->bind_param('s', $input['brand']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $brandId = $row['brand_id'];
        } else {
            // Create new brand
            $stmt = $conn->prepare("INSERT INTO brands (name) VALUES (?)");
            $stmt->bind_param('s', $input['brand']);
            $stmt->execute();
            $brandId = $conn->insert_id;
        }
    }

    // Check if SKU already exists
    $stmt = $conn->prepare("SELECT product_id FROM products WHERE sku = ?");
    $stmt->bind_param('s', $input['sku']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception("Product with SKU '{$input['sku']}' already exists");
    }

    // Set default values for optional fields
    $description = $input['description'] ?? '';
    $lowStockThreshold = $input['lowStockThreshold'] ?? 5; // Default threshold


    // Insert new product
    $stmt = $conn->prepare("
        INSERT INTO products (
            name, sku, barcode, category_id, brand_id, description, 
            unit, cost_price, selling_price, stock_level, 
            reorder_point, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
        'sssiissiidd',
        $input['itemName'],
        $input['sku'],
        $input['barCode'],
        $categoryId,
        $brandId,
        $description,
        $input['unit'],
        $input['costPrice'],
        $input['sellingPrice'],
        $input['stockLevel'],
        $lowStockThreshold
    );

    $stmt->execute();
    $productId = $conn->insert_id;

    // If we have initial stock, add a stock transaction record
    if ($input['stockLevel'] > 0) {
        $stmt = $conn->prepare("
            INSERT INTO stock_transactions (
                product_id, transaction_type, quantity, reference_no,
                transaction_date, notes
            ) VALUES (?, ?, ?, ?, NOW(), 'Initial inventory')
        ");
        $transactionType = 'initial'; // Set transaction type to 'initial' for initial stock
        $referenceNo  = $input['sku'];
        
        $stmt->bind_param('isis', $productId, $transactionType, $input['stockLevel'], $referenceNo);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    // Return success response with new product ID
    echo json_encode([
        'success' => true,
        'message' => 'Product added successfully',
        'productId' => $productId
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error adding product: ' . $e->getMessage()
    ]);
}
