<?php

declare(strict_types=1);
require_once __DIR__ . '/../../../database/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'data' => [],
    'message' => '',
    'summary' => [],
    'topProducts' => []
];

try {
    // Establish database connection
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    // Get and sanitize input
    $startDate = isset($_GET['start_date']) ? htmlspecialchars(trim($_GET['start_date'])) : date('Y-m-d', strtotime('-30 days'));
    $endDate = isset($_GET['end_date']) ? htmlspecialchars(trim($_GET['end_date'])) : date('Y-m-d');
    $cashier = isset($_GET['cashier_name']) ? htmlspecialchars(trim($_GET['cashier_name'])) : '';
    $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

    // Build SQL with JOIN to get product details including SKU
    $sql = "SELECT ps.sale_id, ps.transaction_id, ps.cashier_name, ps.product_id, 
            ps.quantity_sold, ps.discount_applied, ps.sale_price, ps.sale_timestamp,
            p.sku, p.name as product_name 
            FROM product_sales ps
            JOIN products p ON ps.product_id = p.product_id 
            WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ?";
    $params = [$startDate, $endDate];
    $types = "ss";

    if ($cashier !== '') {
        $sql .= " AND ps.cashier_name = ?";
        $params[] = $cashier;
        $types .= "s";
    }
    if ($productId > 0) {
        $sql .= " AND ps.product_id = ?";
        $params[] = $productId;
        $types .= "i";
    }
    $sql .= " ORDER BY ps.sale_timestamp DESC";

    // Prepare and execute
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data
    $sales = [];
    $totalSales = 0;
    $totalItems = 0;
    $highestSale = 0;
    $transactionIds = [];
    $productSales = [];

    while ($row = $result->fetch_assoc()) {
        // Calculate the final price after discount
        $discountMultiplier = 1 - ((float)$row['discount_applied'] / 100);
        $finalPrice = (float)$row['sale_price'] * (int)$row['quantity_sold'] * $discountMultiplier;
        
        // Add to totals
        $totalSales += $finalPrice;
        $totalItems += (int)$row['quantity_sold'];
        $highestSale = max($highestSale, $finalPrice);
        $transactionIds[$row['transaction_id']] = true;
        
        // Track sales by product for top products
        if (!isset($productSales[$row['product_id']])) {
            $productSales[$row['product_id']] = [
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity' => 0,
                'sales' => 0
            ];
        }
        $productSales[$row['product_id']]['quantity'] += (int)$row['quantity_sold'];
        $productSales[$row['product_id']]['sales'] += $finalPrice;
        
        $sales[] = [
            'sale_id' => $row['sale_id'],
            'transaction_id' => $row['transaction_id'],
            'sku' => $row['sku'],
            'cashier_name' => $row['cashier_name'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'quantity_sold' => (int)$row['quantity_sold'],
            'discount_applied' => (float)$row['discount_applied'],
            'sale_price' => (float)$row['sale_price'],
            'sale_timestamp' => $row['sale_timestamp'],
        ];
    }
    $stmt->close();
    
    // Calculate summary data
    $totalTransactions = count($transactionIds);
    $averageSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
    
    $response['summary'] = [
        'total' => $totalSales,
        'transactions' => $totalTransactions,
        'items' => $totalItems,
        'average' => $averageSale,
        'highest' => $highestSale
    ];
    
    // Get cashiers for filter
    $cashiersQuery = "SELECT DISTINCT cashier_name FROM product_sales 
                     WHERE DATE(sale_timestamp) BETWEEN ? AND ? 
                     ORDER BY cashier_name";
    $stmt = $conn->prepare($cashiersQuery);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $cashiersResult = $stmt->get_result();
    
    $cashiers = [];
    while ($row = $cashiersResult->fetch_assoc()) {
        $cashiers[] = $row['cashier_name'];
    }
    $stmt->close();
    
    // Sort product sales by quantity sold (descending) and get the top 5
    usort($productSales, function($a, $b) {
        return $b['quantity'] - $a['quantity'];
    });
    $topProducts = array_slice(array_values($productSales), 0, 5);
    
    $conn->close();

    $response['success'] = true;
    $response['data'] = $sales;
    $response['cashiers'] = $cashiers;
    $response['topProducts'] = $topProducts;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
