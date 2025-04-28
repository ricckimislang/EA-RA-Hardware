<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="../css/charts-fix.css">

<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1>Dashboard</h1>
            <div class="date-filter">
                <select class="form-select" id="timeRange">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row summary-cards mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Sales</h6>
                        <h2 id="totalSales"></h2>
                        <p class="trend"><i class="fas fa-arrow-up"></i></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Expenses</h6>
                        <h2 id="totalExpenses"></h2>
                        <p class="trend"><i class="fas fa-arrow-down"></i></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Net Profit</h6>
                        <h2 id="netProfit"></h2>
                        <p class="trend"><i class="fas fa-arrow-up"></i></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Orders</h6>
                        <h2 id="totalOrders"></h2>
                        <p class="trend"><i class="fas fa-arrow-up"></i></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->


        <!-- Product Sales Analysis Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Product Sales Analysis</h5>
                        <div class="chart-container">
                            <canvas id="productBubbleChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products Chart -->
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Sales Trend</h5>
                        <p class="text-muted">Top Selling Products</p>
                        <div class="chart-container">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Summary Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Inventory Summary</h5>
                        <div class="chart-container">
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Treemap Chart -->
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Expense Categories</h5>
                        <div class="chart-container">
                            <canvas id="expensesTreemap"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Salary Chart -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Employee Salary Performance</h5>
                        <div class="chart-container">
                            <canvas id="employeeSalaryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Sales Trend Chart -->
            <div class="col-md-12">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Sales Revenue Overview</h5>
                        <div class="chart-container">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="../js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>