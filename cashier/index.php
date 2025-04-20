<?php
$page_title = "Point of Sale";
require_once 'includes/head.php';
?>
<link rel="stylesheet" href="css/pos.css">

<body>
    <header>
        <div class="pos-header">
            <div class="store-info">
                <img src="../assets/images/ea-ra-logo.svg" alt="Store Logo" class="store-logo">
                <h1>EA-RA Hardware</h1>
            </div>
            <div class="transaction-info">
                <div class="cashier-info">
                    <span>Cashier: <strong id="cashier-name">User</strong></span>
                </div>

                <div class="transaction-number">
                    <span>Transaction #<strong id="transaction-id">0001</strong></span>
                </div>

                <div class="datetime-info">
                    <span id="current-date"></span>
                    <span id="current-time"></span>
                </div>
                <a href="logout.php" class="btn btn-sm btn-primary">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <div class="pos-container">
        <!-- Main POS Section -->
        <div class="pos-main">
            <!-- Search Section -->
            <div class="search-section">
                <div class="search-bar">
                    <div class="category-filter">
                        <select id="category-filter" class="form-control">
                            <option value="all">All Categories</option>
                            <option value="tools">Tools</option>
                            <option value="electrical">Electrical</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="paint">Paint & Supplies</option>
                            <option value="hardware">Hardware</option>
                            <option value="safety">Safety Equipment</option>
                        </select>
                    </div>
                    <input type="text" id="item-search" placeholder="Scan barcode or search by SKU/name..." autofocus>
                </div>
            </div>

            <!-- Items Grid -->
            <div class="items-grid" id="items-grid">
                <!-- Items will be dynamically loaded here -->
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <div class="cart-header">
                <h2>Current Sale</h2>
            </div>

            <div class="cart-items" id="cart-items">
                <!-- Cart items will be added here -->
            </div>

            <!-- Discount Section -->
            <div class="discount-section">
                <div class="form-group">
                    <label>Discount Type:</label>
                    <select id="discount-type" class="form-control">
                        <option value="none">No Discount</option>
                        <option value="senior">Senior Citizen (20%)</option>
                        <option value="pwd">PWD (20%)</option>
                    </select>
                </div>
            </div>

            <!-- Cart Totals -->
            <div class="cart-totals">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span id="subtotal">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <span id="discount">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong id="total">₱0.00</strong>
                </div>
                <button class="checkout-btn" id="checkout-btn">
                    <i class="fas fa-cash-register"></i> Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receipt-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Receipt Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="receipt-preview" id="receipt-preview">
                        <!-- Receipt content will be generated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="print-receipt">Print Receipt</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/pos.js"></script>

</body>