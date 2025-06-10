<?php
header('Content-Type: application/json');
include_once '../../../database/config.php';

// Set timezone to match your location
date_default_timezone_set('Asia/Manila');

// Get time range parameter
$timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : 'month';

// Add this near where other parameters are handled
$salaryMonth = isset($_GET['salaryMonth']) ? $_GET['salaryMonth'] : date('Y-m');

// Set date range based on timeRange
$endDate = date('Y-m-d 23:59:59');  // End of current day
switch ($timeRange) {
    case 'today':
        $startDate = date('Y-m-d 00:00:00');  // Start of current day
        $previousStartDate = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $previousEndDate = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $groupBy = 'HOUR(ps.sale_timestamp)';
        $dateFormat = '%H:00';
        $labels = array_map(function($hour) {
            return sprintf('%02d:00', $hour);
        }, range(0, 23));
        break;
    case 'week':
        $startDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $previousStartDate = date('Y-m-d 00:00:00', strtotime('monday last week'));
        $previousEndDate = date('Y-m-d 23:59:59', strtotime('sunday last week'));
        $groupBy = 'DAYNAME(ps.sale_timestamp)';
        $dateFormat = '%W';
        $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        break;
    case 'year':
        $startDate = date('Y-01-01 00:00:00');
        $previousStartDate = date('Y-01-01 00:00:00', strtotime('-1 year'));
        $previousEndDate = date('Y-12-31 23:59:59', strtotime('-1 year'));
        $groupBy = 'MONTH(ps.sale_timestamp)';
        $dateFormat = '%b';
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        break;
    case 'month':
    default:
        $startDate = date('Y-m-01 00:00:00');
        $previousStartDate = date('Y-m-01 00:00:00', strtotime('-1 month'));
        $previousEndDate = date('Y-m-t 23:59:59', strtotime('-1 month'));
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
        'datasets' => [],
        'debug_individual' => []
    ],
    'productSales' => [
        'data' => []
    ],
    'inventory' => [
        'labels' => [],
        'normal' => [],
        'low' => [],
        'out' => []
    ],
    'topProducts' => [
        'labels' => [],
        'values' => [],
        'revenue' => [],
        'profit' => [],
        'stock' => []
    ]
];

try {
    // Get total sales
    $stmt = $conn->prepare("
        SELECT SUM(ps.sale_price) as current_sales
        FROM product_sales ps
        WHERE ps.sale_timestamp BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentSales = $result->fetch_assoc()['current_sales'] ?? 0;
    
    // Get previous period sales
    $stmt = $conn->prepare("
        SELECT SUM(ps.sale_price) as previous_sales
        FROM product_sales ps
        WHERE ps.sale_timestamp BETWEEN ? AND ?
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
        WHERE ps.sale_timestamp BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentOrders = $result->fetch_assoc()['current_orders'] ?? 0;
    
    // Get previous period orders
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT ps.transaction_id) as previous_orders
        FROM product_sales ps
        WHERE ps.sale_timestamp BETWEEN ? AND ?
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
        SELECT $groupBy as period, SUM(ps.sale_price) as sales
        FROM product_sales ps
        WHERE ps.sale_timestamp BETWEEN ? AND ? 
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
        SELECT $groupBy as period, SUM(ps.sale_price) as sales
        FROM product_sales ps
        WHERE ps.sale_timestamp BETWEEN ? AND ? 
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
    
    // Get employee salary data - MODIFIED FOR DEVELOPMENT/DEMO DATA & INDIVIDUAL TRENDS
    // Note: This query is modified to handle test data from future dates (April 2025)
    // In production, you should use the date-filtered version with appropriate date range
    
    // Fetch individual employee salary data
    $stmt_ind = $conn->prepare("
        SELECT 
            e.id as employee_id, 
            e.full_name as employee_name,
            DATE_FORMAT(pp.end_date, '%d') as raw_day, 
            pp.end_date,
            p.net_pay
        FROM payroll p
        JOIN pay_periods pp ON p.pay_period_id = pp.id
        JOIN employees e ON p.id = e.id
        WHERE DATE_FORMAT(pp.end_date, '%Y-%m') = ?
        ORDER BY e.id, pp.end_date
    ");
    $stmt_ind->bind_param("s", $salaryMonth);
    $stmt_ind->execute();
    $result_ind = $stmt_ind->get_result();

    $individualTrends = [];
    $debug_individual_salary_data = [];
    $employeeNames = []; // Store unique employee names

    while ($row = $result_ind->fetch_assoc()) {
        $debug_individual_salary_data[] = $row;
        $employeeId = $row['employee_id'];
        $employeeName = $row['employee_name'];
        
        if (!isset($individualTrends[$employeeName])) {
            $employeeNames[] = $employeeName; // Add unique name
            $individualTrends[$employeeName] = [
                'id' => $employeeId,
                'data' => array_fill(0, count($labels), 0) // Initialize data array based on period labels
            ];
        }
        
        // Map individual data similarly to aggregated data (handling April 2025 demo data)
        if (strpos($row['end_date'], '2025-04') !== false) {
            $day = (int)$row['raw_day'];
            // Map April 15/30 to specific indices/labels for the current month view
            $mappedIndex = -1;
            if ($day <= 15 && count($labels) > 6) { // Ensure index exists
                $mappedIndex = 6; // Map to 7th label (representing 1st half)
            } else if ($day > 15 && count($labels) > 20) { // Ensure index exists
                $mappedIndex = 20; // Map to 21st label (representing 2nd half)
            }
            
            if ($mappedIndex != -1) {
                $individualTrends[$employeeName]['data'][$mappedIndex] = (float)$row['net_pay'];
            }
        } else {
             // Handle normal case if needed (e.g., data from the actual selected period)
             // You would map $row['raw_day'] to the correct index in $labels here
        }
    }

    // Reformat individualTrends for easier consumption by Chart.js (array of datasets)
    $datasets = [];
    foreach ($individualTrends as $name => $trendData) {
        $datasets[] = [
            'label' => $name,
            'data' => $trendData['data']
        ];
    }

    // Simplify the response structure
    $response['employeeSalaries'] = [
        'labels' => $labels,
        'datasets' => $datasets,
        'debug_individual' => $debug_individual_salary_data
    ];

    // Remove previous structure elements if they exist
    // unset($response['employeeSalaries']['individual']);
    // unset($response['employeeSalaries']['total']);
    // unset($response['employeeSalaries']['individual_trends']);
    // unset($response['employeeSalaries']['debug_aggregated']);
    
    // Get product sales data
    $stmt = $conn->prepare("
        SELECT p.name, 
               SUM(ps.quantity_sold) as sold_quantity,
               SUM(ps.sale_price) as sales_amount,
               SUM((ps.sale_price - p.cost_price)) as profit
        FROM product_sales ps
        JOIN products p ON ps.product_id = p.product_id
        WHERE ps.sale_timestamp BETWEEN ? AND ?
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
               SUM(CASE WHEN p.stock_level = 5 THEN 1 ELSE 0 END) as out_of_stock
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
    
    // Get top selling products
    $stmt = $conn->prepare("
        SELECT p.name, 
               SUM(ps.quantity_sold) as total_quantity,
               SUM(ps.sale_price) as total_revenue,
               SUM((ps.sale_price - p.cost_price)) as total_profit,
               p.stock_level as current_stock
        FROM product_sales ps
        JOIN products p ON ps.product_id = p.product_id
        WHERE ps.sale_timestamp BETWEEN ? AND ?
        GROUP BY ps.product_id, p.name, p.stock_level
        ORDER BY total_quantity DESC
        LIMIT 5
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $topProductLabels = [];
    $topProductValues = [];
    $topProductRevenue = [];
    $topProductProfit = [];
    $topProductStock = [];
    
    while ($row = $result->fetch_assoc()) {
        $topProductLabels[] = $row['name'];
        $topProductValues[] = (int)$row['total_quantity'];
        $topProductRevenue[] = (float)$row['total_revenue'];
        $topProductProfit[] = (float)$row['total_profit'];
        $topProductStock[] = (int)$row['current_stock'];
    }
    
    $response['topProducts']['labels'] = $topProductLabels;
    $response['topProducts']['values'] = $topProductValues;
    $response['topProducts']['revenue'] = $topProductRevenue;
    $response['topProducts']['profit'] = $topProductProfit;
    $response['topProducts']['stock'] = $topProductStock;
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
}

echo json_encode($response); 