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
                    <h2>₱<span id="monthlyTotal">0.00</span></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-calendar-day me-2"></i>Today's Expenses</h5>
                    <h2>₱<span id="todayTotal">0.00</span></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-receipt me-2"></i>Pending Receipts</h5>
                    <h2><span id="pendingReceipts">0</span></h2>
                </div>
            </div>
        </div>

        <!-- Expense Filter Section -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="Rent">Rent</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Salaries">Salaries</option>
                        <option value="Supplies">Supplies</option>
                        <option value="Purchases">Purchases</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" id="applyFilter">
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
                        <th>Date</th>
                        <th>Category</th>
                        <th>Payee</th>
                        <th>Amount</th>
                        <th>Receipt</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="expenseTableBody">
                    <!-- Expense rows will be dynamically added here -->
                    <tr>
                        <td>2024-01-15</td>
                        <td>Utilities</td>
                        <td>Electric Company</td>
                        <td>5,000</td>
                        <td><a href="#" class="view-receipt">View</a></td>
                        <td>Monthly electricity bill</td>
                        <td>
                            <button class="btn btn-sm btn-info edit-expense"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger delete-expense"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- modals -->
    <?php include_once 'modals/add-expense.php'; ?>

    <script src="../js/expenses.js"></script>
</body>