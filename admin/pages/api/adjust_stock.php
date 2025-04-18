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
$requiredFields = ['productId', 'adjustmentType', 'quantity'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
}

$productId = intval($input['productId']);
$adjustmentType = $input['adjustmentType'];
$quantity = intval($input['quantity']);
$notes = isset($input['notes']) ? trim($input['notes']) : '';

try {
    // Start transaction
    $conn->begin_transaction();

    // Update stock level based on adjustment type
    if ($adjustmentType === 'add') {
        $sql = "UPDATE products SET stock_level = stock_level + ? WHERE product_id = ?";
    } elseif ($adjustmentType === 'subtract') {
        $sql = "UPDATE products SET stock_level = stock_level - ? WHERE product_id = ?";
    } else {
        throw new Exception('Invalid adjustment type');
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $quantity, $productId);
    $stmt->execute();

    // Add stock transaction record
    $stmt = $conn->prepare("INSERT INTO stock_transactions (product_id, transaction_type, quantity, transaction_date, notes) VALUES (?, ?, ?, NOW(), ?)");
    $transactionType = ($adjustmentType === 'add') ? 'stock_in' : 'stock_out';
    $stmt->bind_param('isis', $productId, $transactionType, $quantity, $notes);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Stock adjusted successfully'
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error adjusting stock: ' . $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();

?>