<?php
include '../includes/head.php';
require_once '../../database/config.php';

// Get all employees for the dropdown
$employeesQuery = "SELECT e.id, e.full_name, p.title as position 
                  FROM employees e
                  JOIN positions p ON e.position_id = p.id
                  ORDER BY e.full_name";
$employeesResult = $conn->query($employeesQuery);

// Fetch cash advance limits from settings if available
$limitQuery = "SELECT setting_value FROM pay_settings WHERE setting_name = 'max_cash_advance_percent'";
$limitResult = $conn->query($limitQuery);
$maxAdvancePercent = ($limitResult && $limitResult->num_rows > 0) ? $limitResult->fetch_assoc()['setting_value'] : 30;
?>

<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Cash Advance Management</h1>
            </div>

            <div class="row">
                <!-- Cash Advance Request Card -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Request Cash Advance</h6>
                        </div>
                        <div class="card-body">
                            <div id="request-message"></div>
                            <p class="mb-3">Submit a new cash advance request for an employee.</p>
                            <form id="cash-advance-form">
                                <div class="form-group">
                                    <label for="employee_id">Select Employee:</label>
                                    <select class="form-control" id="employee_id" name="employee_id" required>
                                        <option value="">Select an employee</option>
                                        <?php while ($employee = $employeesResult->fetch_assoc()) { ?>
                                            <option value="<?= $employee['id'] ?>" data-position="<?= $employee['position'] ?>">
                                                <?= $employee['full_name'] ?> (<?= $employee['position'] ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount (₱):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" class="form-control" id="amount" name="amount" min="100" step="100" required>
                                    </div>
                                    <small class="form-text text-muted">Maximum advance is <?= $maxAdvancePercent ?>% of monthly salary.</small>
                                </div>
                                <div class="form-group">
                                    <label for="payment_method">Payment Method:</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="check">Check</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="notes">Reason/Notes:</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pending Requests</h6>
                        </div>
                        <div class="card-body">
                            <div id="pending-requests-message"></div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pending-requests-table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pending-requests-body">
                                        <tr>
                                            <td colspan="4" class="text-center">Loading pending requests...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cash Advance History Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Cash Advance History</h6>
                            <div class="filter-controls">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select id="status-filter" class="form-control form-control-sm">
                                            <option value="">All Statuses</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="paid">Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 d-flex">
                                        <input type="date" id="date-from" class="form-control form-control-sm mr-2">
                                        <input type="date" id="date-to" class="form-control form-control-sm mr-2">
                                        <button id="filter-btn" class="btn btn-sm btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="cash-advances-table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Amount</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                            <th>Payment Method</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cash-advances-body">
                                        <tr>
                                            <td colspan="7" class="text-center">Loading cash advance history...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvalModalLabel">Approve Cash Advance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="approval-form">
                        <input type="hidden" id="advance_id" name="advance_id">
                        <div class="mb-3">
                            <p>Are you sure you want to approve the cash advance request for <strong id="modal-employee-name"></strong>?</p>
                            <p>Amount: <strong id="modal-amount"></strong></p>
                        </div>
                        <div class="mb-3">
                            <label for="approval-notes" class="form-label">Notes (Optional):</label>
                            <textarea class="form-control" id="approval-notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="approve-btn">Approve</button>
                    <button type="button" class="btn btn-danger" id="reject-btn">Reject</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Cash Advance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Employee:</strong> <span id="detail-employee"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Amount:</strong> <span id="detail-amount"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Request Date:</strong> <span id="detail-request-date"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Approval Date:</strong> <span id="detail-approval-date"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> <span id="detail-status"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Payment Method:</strong> <span id="detail-payment-method"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Notes:</strong> <p id="detail-notes"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Payroll Deduction:</strong> <span id="detail-payroll"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Cash Advance Management -->
    <script src="../js/cash_advance.js"></script>
</body> 