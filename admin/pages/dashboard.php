<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="../css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                        <h2 id="totalSales">₱0.00</h2>
                        <p class="trend"><i class="fas fa-arrow-up"></i> 12% vs last period</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Expenses</h6>
                        <h2 id="totalExpenses">₱0.00</h2>
                        <p class="trend"><i class="fas fa-arrow-down"></i> 5% vs last period</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Net Profit</h6>
                        <h2 id="netProfit">₱0.00</h2>
                        <p class="trend"><i class="fas fa-arrow-up"></i> 8% vs last period</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Orders</h6>
                        <h2 id="totalOrders">0</h2>
                        <p class="trend"><i class="fas fa-arrow-up"></i> 15% vs last period</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <!-- Sales Trend Chart -->
            <div class="col-md-8">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Sales Revenue Trend</h5>
                        <div class="chart-container">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Expenses Treemap Chart -->
            <div class="col-md-4">
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

        <div class="row mb-4">
            <!-- Employee Salary Chart -->
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Employee Salary Trends</h5>
                        <div class="chart-container">
                        <canvas id="employeeSalaryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Sales Analysis Chart -->
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Product Sales Analysis</h5>
                        <canvas id="productBubbleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Summary Chart -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Inventory Summary</h5>
                        <div class="chart-container">
                        <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="../js/dashboard.js"></script>
</body>