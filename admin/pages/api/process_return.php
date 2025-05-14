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

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Get the logged-in user ID (assuming it's stored in session)
session_start();
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated';
    echo json_encode($response);
    exit;
}
$processedBy = (int)$_SESSION['user_id'];

try {
    // Validate required fields
    $requiredFields = ['transaction_id', 'sku', 'return_type', 'return_quantity', 'return_reason'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Required field missing: $field");
        }
    }

    // Extract and sanitize POST data
    $transactionId = htmlspecialchars(trim($_POST['transaction_id']));
    $sku = htmlspecialchars(trim($_POST['sku'])); 
    $returnType = htmlspecialchars(trim($_POST['return_type'])); // refund, exchange, store_credit
    $returnQuantity = (int)$_POST['return_quantity'];
    $returnReason = htmlspecialchars(trim($_POST['return_reason']));
    $notes = isset($_POST['notes']) ? htmlspecialchars(trim($_POST['notes'])) : '';
    
    // Additional fields based on return type
    $customerName = isset($_POST['customer_name']) ? htmlspecialchars(trim($_POST['customer_name'])) : null;
    $contactNumber = isset($_POST['contact_number']) ? htmlspecialchars(trim($_POST['contact_number'])) : null;
    $refundMethod = isset($_POST['refund_method']) ? htmlspecialchars(trim($_POST['refund_method'])) : null;
    $exchangeSku = isset($_POST['exchange_sku']) ? htmlspecialchars(trim($_POST['exchange_sku'])) : null;
    $exchangeQuantity = isset($_POST['exchange_quantity']) ? (int)$_POST['exchange_quantity'] : 0;
    
    // Get original sale information
    $stmt = $conn->prepare("
        SELECT 
            ps.sale_id, 
            ps.product_id, 
            ps.quantity_sold, 
            ps.discount_applied, 
            ps.sale_price,
            p.name AS product_name,
            p.cost_price,
            p.stock_level
        FROM product_sales ps
        JOIN products p ON ps.product_id = p.product_id
        WHERE ps.transaction_id = ? AND p.sku = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $transactionId, $sku);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("No matching sale found for this transaction ID and SKU");
    }
    
    $sale = $result->fetch_assoc();
    $stmt->close();
    
    // Validate return quantity
    if ($returnQuantity <= 0 || $returnQuantity > $sale['quantity_sold']) {
        throw new Exception("Invalid return quantity");
    }
    
    // Calculate return amount (consider discount)
    $unitPriceAfterDiscount = $sale['sale_price'] * (1 - $sale['discount_applied'] / 100);
    $returnAmount = $unitPriceAfterDiscount * $returnQuantity;
    
    // Start transaction
    $conn->begin_transaction();
    
    // 1. Create return transaction record
    $stmt = $conn->prepare("
        INSERT INTO return_transactions (
            transaction_id, 
            return_date, 
            return_type, 
            total_amount, 
            processed_by, 
            customer_name, 
            contact_number, 
            notes
        ) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param(
        "ssdisss", 
        $transactionId, 
        $returnType, 
        $returnAmount, 
        $processedBy, 
        $customerName, 
        $contactNumber, 
        $notes
    );
    
    $stmt->execute();
    $returnId = $conn->insert_id;
    $stmt->close();
    
    // 2. Create return item record
    $itemCondition = htmlspecialchars(trim($_POST['product_condition']));
    
    $stmt = $conn->prepare("
        INSERT INTO return_items (
            return_id, 
            product_id, 
            quantity, 
            unit_price, 
            subtotal, 
            `condition`, 
            reason_code, 
            other_reason
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param(
        "iiiddsss", 
        $returnId, 
        $sale['product_id'], 
        $returnQuantity, 
        $unitPriceAfterDiscount, 
        $returnAmount, 
        $itemCondition, 
        $returnReason, 
        $notes
    );
    
    $stmt->execute();
    $stmt->close();
    
    // 3. Handle inventory adjustments for good condition items
    if ($itemCondition === 'good') {
        // Add back to inventory
        $newStockLevel = $sale['stock_level'] + $returnQuantity;
        
        $stmt = $conn->prepare("
            UPDATE products 
            SET stock_level = ? 
            WHERE product_id = ?
        ");
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("ii", $newStockLevel, $sale['product_id']);
        $stmt->execute();
        $stmt->close();
        
        // Record stock transaction
        $stmt = $conn->prepare("
            INSERT INTO stock_transactions (
                product_id, 
                transaction_type, 
                quantity, 
                unit_price, 
                total_amount,
                reference_no, 
                notes
            ) VALUES (?, 'return', ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $referenceNo = "RTN" . str_pad((string)$returnId, 6, "0", STR_PAD_LEFT);
        $totalCost = $sale['cost_price'] * $returnQuantity;
        $returnNotes = "Return from transaction {$transactionId}";
        
        $stmt->bind_param(
            "iiddss", 
            $sale['product_id'], 
            $returnQuantity, 
            $sale['cost_price'], 
            $totalCost, 
            $referenceNo, 
            $returnNotes
        );
        
        $stmt->execute();
        $stmt->close();
    }
    
    // 4. Handle specific return types
    switch($returnType) {
        case 'store_credit':
            // Generate credit code and expiry date (1 year from now)
            $creditCode = 'SC-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $expiryDate = date('Y-m-d H:i:s', strtotime('+1 year'));
            
            $stmt = $conn->prepare("
                INSERT INTO store_credits (
                    return_id, 
                    credit_amount, 
                    credit_code, 
                    issue_date, 
                    expiry_date
                ) VALUES (?, ?, ?, NOW(), ?)
            ");
            
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param(
                "idss", 
                $returnId, 
                $returnAmount, 
                $creditCode, 
                $expiryDate
            );
            
            $stmt->execute();
            $stmt->close();
            
            // Add credit info to response for receipt printing
            $response['data'] = [
                'credit_code' => $creditCode,
                'credit_amount' => number_format($returnAmount, 2),
                'expiry_date' => $expiryDate
            ];
            break;
            
        case 'exchange':
            // Validate exchange SKU and quantity
            if (empty($exchangeSku) || $exchangeQuantity <= 0) {
                throw new Exception("Exchange details missing or invalid");
            }
            
            // Check if exchange product exists and has enough stock
            $stmt = $conn->prepare("
                SELECT product_id, stock_level, selling_price 
                FROM products 
                WHERE sku = ?
            ");
            
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param("s", $exchangeSku);
            $stmt->execute();
            $exchangeProduct = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if (!$exchangeProduct) {
                throw new Exception("Exchange product not found");
            }
            
            if ($exchangeProduct['stock_level'] < $exchangeQuantity) {
                throw new Exception("Not enough stock for exchange product");
            }
            
            // Reduce exchange product stock
            $newExchangeStock = $exchangeProduct['stock_level'] - $exchangeQuantity;
            
            $stmt = $conn->prepare("
                UPDATE products 
                SET stock_level = ? 
                WHERE product_id = ?
            ");
            
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param("ii", $newExchangeStock, $exchangeProduct['product_id']);
            $stmt->execute();
            $stmt->close();
            
            // Record exchange details in notes
            $exchangeNotes = "Exchanged for SKU: {$exchangeSku}, Quantity: {$exchangeQuantity}";
            
            $stmt = $conn->prepare("
                UPDATE return_transactions 
                SET notes = CONCAT(notes, ' ', ?) 
                WHERE return_id = ?
            ");
            
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param("si", $exchangeNotes, $returnId);
            $stmt->execute();
            $stmt->close();
            break;
            
        case 'refund':
            // Record refund method in notes
            if ($refundMethod) {
                $refundNotes = "Refund method: {$refundMethod}";
                
                $stmt = $conn->prepare("
                    UPDATE return_transactions 
                    SET notes = CONCAT(notes, ' ', ?) 
                    WHERE return_id = ?
                ");
                
                if (!$stmt) {
                    throw new Exception("Database error: " . $conn->error);
                }
                
                $stmt->bind_param("si", $refundNotes, $returnId);
                $stmt->execute();
                $stmt->close();
            }
            break;
    }
    
    // 5. Update original sale if partial return, or remove if full return
    if ($returnQuantity < $sale['quantity_sold']) {
        // Partial return - update original sale
        $newQuantity = $sale['quantity_sold'] - $returnQuantity;
        
        $stmt = $conn->prepare("
            UPDATE product_sales 
            SET quantity_sold = ? 
            WHERE sale_id = ?
        ");
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("ii", $newQuantity, $sale['sale_id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Full return - remove original sale
        $stmt = $conn->prepare("
            DELETE FROM product_sales 
            WHERE sale_id = ?
        ");
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $sale['sale_id']);
        $stmt->execute();
        $stmt->close();
    }
    
    // Commit transaction
    $conn->commit();
    
    // Build success response
    $response['success'] = true;
    $response['message'] = "Return processed successfully";
    if (!isset($response['data'])) {
        $response['data'] = [
            'return_id' => $returnId,
            'amount' => number_format($returnAmount, 2)
        ];
    }
    
} catch (Exception $e) {
    // Roll back transaction on error
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    
    $response['message'] = $e->getMessage();
} finally {
    // Close connection
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->close();
    }
    
    // Return JSON response
    echo json_encode($response);
} 