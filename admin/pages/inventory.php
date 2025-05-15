<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="../css/inventory.css">

<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1>Inventory Management</h1>
            <div class="header-buttons d-flex gap-1">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Add Product
                </button>
                <!-- <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#contactSuppliers">
                    <i class="fas fa-address-card"></i> Add Product
                </button> -->
            </div>

        </div>


        <!-- Inventory Summary Cards -->
        <div class="row summary-cards mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Products</h6>
                        <h2 id="totalProducts">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Low Stock Items</h6>
                        <h2 id="lowStockItems" class="text-warning">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Out of Stock</h6>
                        <h2 id="outOfStock" class="text-danger">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Inventory Value</h6>
                        <!-- <h2 id="totalValue">₱0</h2> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="categoryFilter" class="form-label">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="brandFilter" class="form-label">Brand</label>
                    <select class="form-select" id="brandFilter">
                        <option value="">All Brands</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="stockFilter" class="form-label">Stock Level</label>
                    <select class="form-select" id="stockFilter">
                        <option value="">All Stock Levels</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                        <option value="normal">Normal Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" id="applyFilters">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table id="productsTable" class="table table-hover display responsive nowrap" width="100%">
                <thead>
                    <tr>
                        <th>SKU/Barcode</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Stock Level</th>
                        <th>Unit</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- modals -->
    <?php include_once 'modals/inventory-modals.php'; ?>


    <script src="../js/inventory.js"></script>
    <script src="../js/CRUD_inventory.js"></script>
</body>