<?php
require_once '../includes/head.php';
?>
<link rel="stylesheet" href="../css/expenses.css">

<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1>Expense Management</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                <i class="fas fa-plus"></i> Add New Expense
            </button>
        </div>

        <!-- Expense Summary Cards -->
        <div class="summary-cards">
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-wallet me-2"></i>Total Expenses (This Month)</h5>
                    <h2><span id="monthlyTotal"></span></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-calendar-day me-2"></i>Today's Expenses</h5>
                    <h2><span id="todayTotal"></span></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-receipt me-2"></i>Pending Receipts</h5>
                    <h2><span id="pendingReceipts"></span></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-wallet me-2"></i>Total Expense</h5>
                    <h2><span id="totalExpense"></span></h2>
                </div>
            </div>
            <div class="card" style="display: none;">
                <div class="card-body">
                    <h5><i class="fas fa-money-bill-alt"></i> Total Filtered Expense</h5>
                    <h2><span id="totalFilteredExpense"></span></h2>
                </div>
            </div>
        </div>

        <!-- Expense Filter Section -->
        <div class="filter-section">
            <div class="row mb-2">
                <div class="col-md-3">
                    <input type="date" class="form-control" id="startDate" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="endDate" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="categoryFilter">
                        <option value="">All Categories</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="applyFilter">
                        <i class="fas fa-filter"></i> Apply Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Expense Table -->
        <h2>Log Table</h2>
        <div class="table-responsive">
            <table id="expenseTable" class="table table-hover display responsive nowrap" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Receipt</th>
                        <th>Notes</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Expense rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- modals -->
    <?php include_once 'modals/add-expense.php'; ?>

    <script src="../js/expenses.js"></script>
    <script src="../js/CRUD_expense.js"></script>
</body>