<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="expenseForm" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="expenseDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="expenseDate" name="date" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d')  ?>" required>
                        <div class="invalid-feedback">Please select a date.</div>
                    </div>
                    <div class="mb-3">
                        <label for="expenseName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="expenseName" name="expenseName" required>
                        <div class="invalid-feedback">Please enter a name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="expenseAmount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="expenseAmount" name="amount" step="0.01" required>
                            <div class="invalid-feedback">Please enter an amount.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="expenseCategory" class="form-label">Category</label>
                        <select class="form-select" id="expenseCategory" name="category" required>
                            <option value="" selected disabled>Select Category</option>
                        </select>
                        <div class="invalid-feedback">Please select a category.</div>
                    </div>
                    <div class="mb-3">
                        <label for="expenseReceipt" class="form-label">Receipt</label>
                        <input type="file" class="form-control" id="expenseReceipt" name="receipt" accept="image/*">
                        <div class="form-text">Upload an image of the receipt (optional)</div>
                    </div>
                    <div class="mb-3">
                        <label for="expenseNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="expenseNotes" name="notes" rows="3" placeholder="Enter additional notes here..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="expenseForm" class="btn btn-primary" id="saveExpense">Save Expense</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm" class="needs-validation" novalidate>
                    <input type="hidden" id="editExpenseId" name="expenseId">
                    <div class="mb-3">
                        <label for="editExpenseDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="editExpenseDate" name="date" max="<?php echo date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">Please select a date.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editExpenseName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editExpenseName" name="expenseName" required>
                        <div class="invalid-feedback">Please enter a name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editExpenseAmount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="editExpenseAmount" name="amount" step="0.01" required>
                            <div class="invalid-feedback">Please enter an amount.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editExpenseCategory" class="form-label">Category</label>
                        <select class="form-select" id="editExpenseCategory" name="category" required>
                        </select>
                        <div class="invalid-feedback">Please select a category.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editExpenseReceipt" class="form-label">Receipt</label>
                        <input type="file" class="form-control" id="editExpenseReceipt" name="receipt" accept="image/*">
                        <div class="form-text">Upload a new receipt image (optional)</div>
                        <div id="currentReceiptContainer" class="mt-2 d-none">
                            <p class="mb-1">Current Receipt:</p>
                            <div class="d-flex align-items-center mb-2">
                                <img id="currentReceiptImage" src="" alt="Current Receipt" class="img-thumbnail me-2" style="max-height: 100px; max-width: 100px;">
                                <a id="viewReceiptLink" href="" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>View Full Size
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editExpenseNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="editExpenseNotes" name="notes" rows="3" placeholder="Enter additional notes here..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateExpense">Update Expense</button>
            </div>
        </div>
    </div>
</div>