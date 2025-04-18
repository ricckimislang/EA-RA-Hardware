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

