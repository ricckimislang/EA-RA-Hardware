<?php
declare(strict_types=1);
require_once __DIR__ . '/../../database/config.php';

// Default date range (past 30 days)
$endDate = date('Y-m-d');
$startDate = date('Y-m-d', strtotime('-30 days'));

// Process filter if submitted - just for initial page load
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $startDate = htmlspecialchars(trim($_GET['start_date']));
    $endDate = htmlspecialchars(trim($_GET['end_date']));
}

include_once '../includes/head.php'; 
?>
<body>
    <!-- Include Sidebar -->
    <?php include_once '../includes/sidebar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">Sales History</h1>
                <div class="header-actions">
                    <!-- Date range filter form -->
                    <form id="date-filter-form" class="d-flex gap-2">
                        <div class="form-group">
                            <label for="start_date">From:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="form-group">
                            <label for="end_date">To:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="form-group d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="button" id="reset-filter" class="btn btn-outline-secondary ms-2">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Filter Buttons -->
            <div class="quick-filters mt-3 mb-2">
                <div class="btn-group">
                    <button type="button" id="filter-today" class="btn btn-sm btn-outline-primary">Today</button>
                    <button type="button" id="filter-week" class="btn btn-sm btn-outline-primary">This Week</button>
                    <button type="button" id="filter-month" class="btn btn-sm btn-outline-primary">This Month</button>
                    <button type="button" id="filter-quarter" class="btn btn-sm btn-outline-primary">Last 3 Months</button>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="salesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Date & Time</th>
                                    <th>Cashier</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via JavaScript -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-end">Total Sales Amount:</th>
                                    <th id="totalSales">₱0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Summary Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Sales Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <h6>Total Transactions</h6>
                                    <h3 id="total-transactions">0</h3>
                                </div>
                                <div class="col-6 mb-3">
                                    <h6>Total Items Sold</h6>
                                    <h3 id="total-items">0</h3>
                                </div>
                                <div class="col-6">
                                    <h6>Average Sale</h6>
                                    <h3 id="average-sale">₱0.00</h3>
                                </div>
                                <div class="col-6">
                                    <h6>Highest Sale</h6>
                                    <h3 id="highest-sale">₱0.00</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Top Selling Products</h5>
                        </div>
                        <div class="card-body">
                            <ul id="top-products-list" class="list-group">
                                <!-- Top products will be loaded via JavaScript -->
                                <li class="list-group-item text-center">Loading data...</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="../js/sales_history.js"></script>
    
</body>
</html>
