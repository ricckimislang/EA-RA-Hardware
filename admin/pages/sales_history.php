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
<style>   /* Make transaction ID column more visible */
    #salesTable th:first-child,
    #salesTable td:first-child {
        background-color: #f0f8ff; /* Light blue background */
        font-weight: bold;
        min-width: 120px;
    }
    
    /* Ensure all table cells display correctly */
    #salesTable td, #salesTable th {
        display: table-cell !important;
    }</style>
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
                                    <th>Transaction ID</th>
                                    <th>SKU</th>
                                    <th>Date & Time</th>
                                    <th>Cashier</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via JavaScript -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="9" class="text-end">Total Sales Amount:</th>
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
    
    <!-- Return Processing Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Process Return</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="transaction-details mb-4">
                        <h6>Transaction Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Transaction ID:</strong> <span id="return-transaction-id"></span></p>
                                <p><strong>Product:</strong> <span id="return-product-name"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Quantity:</strong> <span id="return-quantity"></span></p>
                                <p><strong>Total Amount:</strong> <span id="return-total"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="return-form">
                        <input type="hidden" name="transaction_id" id="return-transaction-id-input">
                        <input type="hidden" name="sku" id="return-sku-input">
                        
                        <div class="mb-3">
                            <label for="return-type" class="form-label">Return Type</label>
                            <select class="form-select" id="return-type" name="return_type" required>
                                <option value="refund">Refund</option>
                                <option value="exchange">Exchange</option>
                                <option value="store_credit">Store Credit</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="return-quantity-input" class="form-label">Return Quantity</label>
                            <input type="number" class="form-control" id="return-quantity-input" name="return_quantity" min="1" required>
                            <div class="form-text">Enter the quantity being returned</div>
                        </div>

                        <div class="mb-3">
                            <label for="product-condition" class="form-label">Product Condition</label>
                            <select class="form-select" id="product-condition" name="product_condition" required>
                                <option value="">Select Product Condition</option>
                                <option value="good">Good Condition</option>
                                <option value="minor_damage">Minor Damage</option>
                                <option value="major_damage">Major Damage</option>
                                <option value="defective">Defective</option>
                            </select>
                            <div class="form-text">Describe the current condition of the product being returned</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="return-reason" class="form-label">Return Reason</label>
                            <select class="form-select" id="return-reason" name="return_reason" required>
                                <option value="">Select a reason</option>
                                <option value="damaged">Product Damaged</option>
                                <option value="defective">Product Defective</option>
                                <option value="other">Other (Please Specify)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="return-notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="return-notes" name="notes" rows="3"></textarea>
                        </div>
                        
                        <div id="customer-fields">
                            <div class="mb-3">
                                <label for="customer-name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer-name" name="customer_name">
                            </div>
                            
                            <div class="mb-3">
                                <label for="customer-contact" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="customer-contact" name="contact_number">
                            </div>
                        </div>
                        
                        <div id="refund-fields">
                            <div class="mb-3">
                                <label for="refund-method" class="form-label">Refund Method</label>
                                <select class="form-select" id="refund-method" name="refund_method">
                                    <option value="cash">Cash</option>
                                    
                                    <option value="store_credit">Store Credit</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="store-credit-fields" class="d-none">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> A store credit memo will be generated. The customer can use this credit for future purchases.
                            </div>
                        </div>
                        
                        <div id="exchange-fields" class="d-none">
                            <div class="mb-3">
                                <label for="exchange-sku" class="form-label">Exchange SKU</label>
                                <input type="text" class="form-control" id="exchange-sku" name="exchange_sku">
                            </div>
                            
                            <div class="mb-3">
                                <label for="exchange-qty" class="form-label">Exchange Quantity</label>
                                <input type="number" class="form-control" id="exchange-qty" name="exchange_quantity" min="1">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="return-form" class="btn btn-primary">Process Return</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="../js/sales_history.js"></script>
    
</body>
</html>
