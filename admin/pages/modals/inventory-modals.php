<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="itemName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sku" class="form-label">SKU/Barcode</label>
                                <input type="text" class="form-control" id="sku" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="unit" class="form-label">Unit of Measure</label>
                                <select class="form-select" id="unit" required>
                                    <option value="piece">Piece</option>
                                    <option value="kg">Kilogram</option>
                                    <option value="meter">Meter</option>
                                    <option value="box">Box</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="costPrice" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="costPrice" step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label for="sellingPrice" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="sellingPrice" step="0.01" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supplier" class="form-label">Supplier</label>
                                <input type="text" class="form-control" id="supplier" required>
                            </div>
                            <div class="col-md-6">
                                <label for="initialStock" class="form-label">Initial Stock</label>
                                <input type="number" class="form-control" id="initialStock" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProduct">Save Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-labelledby="stockAdjustmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockAdjustmentModalLabel">Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="stockAdjustmentForm">
                        <input type="hidden" id="adjustmentProductId">
                        <div class="mb-3">
                            <label for="adjustmentType" class="form-label">Adjustment Type</label>
                            <select class="form-select" id="adjustmentType" required>
                                <option value="add">Add Stock</option>
                                <option value="subtract">Subtract Stock</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjustmentQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="adjustmentQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="adjustmentReason" class="form-label">Reason</label>
                            <textarea class="form-control" id="adjustmentReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAdjustment">Save Adjustment</button>
                </div>
            </div>
        </div>
    </div>
    