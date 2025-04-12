<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEmployeeForm">
                            <div class="mb-3">
                                <label for="employeeName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="employeeName" required>
                            </div>
                            <div class="mb-3">
                                <label for="employeePosition" class="form-label">Position</label>
                                <input type="text" class="form-control" id="employeePosition" required>
                            </div>
                            <div class="mb-3">
                                <label for="baseSalary" class="form-label">Base Salary</label>
                                <input type="number" class="form-control" id="baseSalary" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="overtimeRate" class="form-label">Overtime Rate (per hour)</label>
                                <input type="number" class="form-control" id="overtimeRate" step="0.01" value="150.00" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Save Employee</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Employee Modal -->
        <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editEmployeeForm">
                            <input type="hidden" id="editEmployeeId">
                            <div class="mb-3">
                                <label for="editEmployeeName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="editEmployeeName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmployeePosition" class="form-label">Position</label>
                                <select class="form-control" name="edit_position" id="edit_position" required>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Inventory_clerk">Inventory Clerk</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editBaseSalary" class="form-label">Base Salary</label>
                                <input type="number" class="form-control" id="editBaseSalary" step="0.01" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateEmployeeBtn">Update Employee</button>
                    </div>
                </div>
            </div>
        </div>