<?php
header('Content-Type: application/json');
include_once '../../../database/config.php';

// Get time range parameter
$timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : 'month';

// Set date range based on timeRange
$endDate = date('Y-m-d');
switch ($timeRange) {
    case 'today':
        $startDate = date('Y-m-d');
        $previousStartDate = date('Y-m-d', strtotime('-1 day'));
        $previousEndDate = date('Y-m-d', strtotime('-1 day'));
        $groupBy = 'HOUR(ps.sale_timestamp)';
        $dateFormat = '%H:00';
        $labels = array_map(function($hour) {
            return sprintf('%02d:00', $hour);
        }, range(0, 23));
        break;
    case 'week':
        $startDate = date('Y-m-d', strtotime('monday this week'));
        $previousStartDate = date('Y-m-d', strtotime('monday last week'));
        $previousEndDate = date('Y-m-d', strtotime('sunday last week'));
        $groupBy = 'DAYNAME(ps.sale_timestamp)';
        $dateFormat = '%W';
        $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        break;
    case 'year':
        $startDate = date('Y-01-01');
        $previousStartDate = date('Y-01-01', strtotime('-1 year'));
        $previousEndDate = date('Y-12-31', strtotime('-1 year'));
        $groupBy = 'MONTH(ps.sale_timestamp)';
        $dateFormat = '%b';
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        break;
    case 'month':
    default:
        $startDate = date('Y-m-01');
        $previousStartDate = date('Y-m-01', strtotime('-1 month'));
        $previousEndDate = date('Y-m-t', strtotime('-1 month'));
        $groupBy = 'DAY(ps.sale_timestamp)';
        $dateFormat = '%d';
        $daysInMonth = date('t');
        $labels = array_map(function($day) {
            return (string)$day;
        }, range(1, $daysInMonth));
        break;
}

// Initialize response array
$response = [
    'success' => true,
    'timeRange' => $timeRange,
    'summaryCards' => [],
    'salesTrend' => [
        'labels' => $labels,
        'current' => [],
        'previous' => [],
        'movingAverage' => []
    ],
    'expenseCategories' => [
        'labels' => [],
        'data' => []
    ],
    'employeeSalaries' => [
        'labels' => $labels,
        'individual' => [],
        'total' => []
    ],
    'productSales' => [
        'data' => []
    ],
    'inventory' => [
        'labels' => [],
        'normal' => [],
        'low' => [],
        'out' => []
    ]
];

try {
    // Get total sales
    $stmt = $conn->prepare("
        SELECT SUM(ps.sale_price * ps.quantity_sold) as current_sales
        FROM product_sales ps
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentSales = $result->fetch_assoc()['current_sales'] ?? 0;
    
    // Get previous period sales
    $stmt = $conn->prepare("
        SELECT SUM(ps.sale_price * ps.quantity_sold) as previous_sales
        FROM product_sales ps
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $previousStartDate, $previousEndDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $previousSales = $result->fetch_assoc()['previous_sales'] ?? 0;
    
    // Calculate percentage change
    $salesChange = $previousSales > 0 ? (($currentSales - $previousSales) / $previousSales) * 100 : 0;
    
    // Get total expenses
    $stmt = $conn->prepare("
        SELECT SUM(et.amount) as current_expenses
        FROM expense_transactions et
        WHERE et.transaction_date BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentExpenses = $result->fetch_assoc()['current_expenses'] ?? 0;
    
    // Get previous period expenses
    $stmt = $conn->prepare("
        SELECT SUM(et.amount) as previous_expenses
        FROM expense_transactions et
        WHERE et.transaction_date BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $previousStartDate, $previousEndDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $previousExpenses = $result->fetch_assoc()['previous_expenses'] ?? 0;
    
    // Calculate percentage change
    $expensesChange = $previousExpenses > 0 ? (($currentExpenses - $previousExpenses) / $previousExpenses) * 100 : 0;
    
    // Calculate net profit
    $currentProfit = $currentSales - $currentExpenses;
    $previousProfit = $previousSales - $previousExpenses;
    $profitChange = $previousProfit > 0 ? (($currentProfit - $previousProfit) / $previousProfit) * 100 : 0;
    
    // Get total orders (count distinct transaction_ids)
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT ps.transaction_id) as current_orders
        FROM product_sales ps
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentOrders = $result->fetch_assoc()['current_orders'] ?? 0;
    
    // Get previous period orders
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT ps.transaction_id) as previous_orders
        FROM product_sales ps
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $previousStartDate, $previousEndDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $previousOrders = $result->fetch_assoc()['previous_orders'] ?? 0;
    
    // Calculate percentage change
    $ordersChange = $previousOrders > 0 ? (($currentOrders - $previousOrders) / $previousOrders) * 100 : 0;
    
    // Update summary cards data
    $response['summaryCards'] = [
        'totalSales' => [
            'value' => $currentSales,
            'change' => $salesChange
        ],
        'totalExpenses' => [
            'value' => $currentExpenses,
            'change' => $expensesChange
        ],
        'netProfit' => [
            'value' => $currentProfit,
            'change' => $profitChange
        ],
        'totalOrders' => [
            'value' => $currentOrders,
            'change' => $ordersChange
        ]
    ];
    
    // Get sales trend data
    $stmt = $conn->prepare("
        SELECT $groupBy as period, SUM(ps.sale_price * ps.quantity_sold) as sales
        FROM product_sales ps
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ? 
        GROUP BY $groupBy 
        ORDER BY ps.sale_timestamp
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $salesData = array_fill(0, count($labels), 0);
    while ($row = $result->fetch_assoc()) {
        $period = $row['period'];
        $index = is_numeric($period) ? $period - 1 : array_search($period, $labels);
        if ($index !== false) {
            $salesData[$index] = (float)$row['sales'];
        }
    }
    $response['salesTrend']['current'] = $salesData;
    
    // Get previous period sales trend
    $stmt = $conn->prepare("
        SELECT $groupBy as period, SUM(ps.sale_price * ps.quantity_sold) as sales
        FROM product_sales ps
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ? 
        GROUP BY $groupBy 
        ORDER BY ps.sale_timestamp
    ");
    $stmt->bind_param("ss", $previousStartDate, $previousEndDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $previousSalesData = array_fill(0, count($labels), 0);
    while ($row = $result->fetch_assoc()) {
        $period = $row['period'];
        $index = is_numeric($period) ? $period - 1 : array_search($period, $labels);
        if ($index !== false) {
            $previousSalesData[$index] = (float)$row['sales'];
        }
    }
    $response['salesTrend']['previous'] = $previousSalesData;
    
    // Calculate moving average for sales trend
    $movingAverageData = [];
    $windowSize = 3;
    for ($i = 0; $i < count($salesData); $i++) {
        $sum = 0;
        $count = 0;
        for ($j = max(0, $i - $windowSize + 1); $j <= $i; $j++) {
            $sum += $salesData[$j];
            $count++;
        }
        $movingAverageData[$i] = $count > 0 ? $sum / $count : 0;
    }
    $response['salesTrend']['movingAverage'] = $movingAverageData;
    
    // Get expense categories data
    $stmt = $conn->prepare("
        SELECT ec.name, SUM(et.amount) as total
        FROM expense_transactions et
        JOIN expense_categories ec ON et.category_id = ec.category_id
        WHERE et.transaction_date BETWEEN ? AND ?
        GROUP BY et.category_id
        ORDER BY total DESC
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $expenseLabels = [];
    $expenseData = [];
    while ($row = $result->fetch_assoc()) {
        $expenseLabels[] = $row['name'];
        $expenseData[] = (float)$row['total'];
    }
    $response['expenseCategories']['labels'] = $expenseLabels;
    $response['expenseCategories']['data'] = $expenseData;
    
    // Get employee salary data
    $stmt = $conn->prepare("
        SELECT DATE_FORMAT(pp.end_date, ?) as period, 
               SUM(p.net_pay) as total_amount,
               AVG(p.net_pay) as avg_amount
        FROM payroll p
        JOIN pay_periods pp ON p.pay_period_id = pp.id
        WHERE pp.end_date BETWEEN ? AND ?
        GROUP BY period
        ORDER BY pp.end_date
    ");
    $stmt->bind_param("sss", $dateFormat, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $salaryTotalData = array_fill(0, count($labels), 0);
    $salaryAvgData = array_fill(0, count($labels), 0);
    while ($row = $result->fetch_assoc()) {
        $period = $row['period'];
        $index = is_numeric($period) ? $period - 1 : array_search($period, $labels);
        if ($index !== false) {
            $salaryTotalData[$index] = (float)$row['total_amount'];
            $salaryAvgData[$index] = (float)$row['avg_amount'];
        }
    }
    $response['employeeSalaries']['individual'] = $salaryAvgData;
    $response['employeeSalaries']['total'] = $salaryTotalData;
    
    // Get product sales data
    $stmt = $conn->prepare("
        SELECT p.name, 
               SUM(ps.quantity_sold) as sold_quantity,
               SUM(ps.sale_price * ps.quantity_sold) as sales_amount,
               SUM((ps.sale_price - p.cost_price) * ps.quantity_sold) as profit
        FROM product_sales ps
        JOIN products p ON ps.product_id = p.product_id
        WHERE DATE(ps.sale_timestamp) BETWEEN ? AND ?
        GROUP BY ps.product_id
        ORDER BY sales_amount DESC
        LIMIT 10
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productData = [];
    while ($row = $result->fetch_assoc()) {
        $productData[] = [
            'name' => $row['name'],
            'x' => (float)$row['sales_amount'],
            'y' => (float)$row['profit'],
            'r' => (int)min(20, max(5, $row['sold_quantity'] / 5))
        ];
    }
    $response['productSales']['data'] = $productData;
    
    // Get inventory data
    $stmt = $conn->prepare("
        SELECT c.name as category,
               SUM(CASE WHEN p.stock_level > p.reorder_point THEN 1 ELSE 0 END) as normal_stock,
               SUM(CASE WHEN p.stock_level <= p.reorder_point AND p.stock_level > 0 THEN 1 ELSE 0 END) as low_stock,
               SUM(CASE WHEN p.stock_level = 0 THEN 1 ELSE 0 END) as out_of_stock
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        GROUP BY p.category_id
        ORDER BY c.name
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $inventoryLabels = [];
    $normalStock = [];
    $lowStock = [];
    $outOfStock = [];
    while ($row = $result->fetch_assoc()) {
        $inventoryLabels[] = $row['category'];
        $normalStock[] = (int)$row['normal_stock'];
        $lowStock[] = (int)$row['low_stock'];
        $outOfStock[] = (int)$row['out_of_stock'];
    }
    $response['inventory']['labels'] = $inventoryLabels;
    $response['inventory']['normal'] = $normalStock;
    $response['inventory']['low'] = $lowStock;
    $response['inventory']['out'] = $outOfStock;
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
}

echo json_encode($response); 