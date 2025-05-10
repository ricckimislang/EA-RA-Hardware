<?php
include '../includes/head.php';
require_once '../../database/config.php';

// Validate employee ID parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to employees list if no ID specified
    header('Location: employees.php');
    exit;
}

$employeeId = intval($_GET['id']);

// Get employee details
$employeeSql = "SELECT e.id, e.full_name, e.employee_id as employee_code, e.date_hired,
                   p.title as position, p.base_salary
                FROM employees e
                JOIN positions p ON e.position_id = p.id
                WHERE e.id = ?";
$employeeStmt = $conn->prepare($employeeSql);
$employeeStmt->bind_param('i', $employeeId);
$employeeStmt->execute();
$employeeResult = $employeeStmt->get_result();

// Check if employee exists
if ($employeeResult->num_rows === 0) {
    // Redirect to employees list if employee not found
    header('Location: employees.php');
    exit;
}

$employee = $employeeResult->fetch_assoc();

// Get the max advance percentage from settings
$settingsSql = "SELECT setting_value FROM pay_settings WHERE setting_name = 'max_cash_advance_percent'";
$settingsResult = $conn->query($settingsSql);
$maxAdvancePercent = ($settingsResult && $settingsResult->num_rows > 0) ? 
    $settingsResult->fetch_assoc()['setting_value'] : 30; // Default to 30%

// Calculate maximum allowed advance based on salary
$maxAdvance = $employee['base_salary'] * ($maxAdvancePercent / 100);

// Get existing approved but unpaid advances
$existingAdvanceSql = "SELECT SUM(amount) as total_unpaid 
                      FROM cash_advances 
                      WHERE employee_id = ? 
                      AND status IN ('approved') 
                      AND payroll_id IS NULL";
$existingAdvanceStmt = $conn->prepare($existingAdvanceSql);
$existingAdvanceStmt->bind_param('i', $employeeId);
$existingAdvanceStmt->execute();
$existingUnpaidTotal = $existingAdvanceStmt->get_result()->fetch_assoc()['total_unpaid'] ?? 0;

// Adjust max advance based on existing unpaid advances
$adjustedMaxAdvance = $maxAdvance - $existingUnpaidTotal;
if ($adjustedMaxAdvance < 0) {
    $adjustedMaxAdvance = 0;
}

// Get all cash advances for this employee
$advancesSql = "SELECT ca.id, ca.amount, ca.request_date, ca.approval_date, ca.status, 
                      ca.payment_method, ca.notes, ca.payroll_id, u.full_name as approved_by
               FROM cash_advances ca
               LEFT JOIN users u ON ca.approved_by = u.id
               WHERE ca.employee_id = ?
               ORDER BY ca.request_date DESC, ca.id DESC";
$advancesStmt = $conn->prepare($advancesSql);
$advancesStmt->bind_param('i', $employeeId);
$advancesStmt->execute();
$advancesResult = $advancesStmt->get_result();
?>

<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Cash Advances: <?php echo htmlspecialchars($employee['full_name']); ?></h1>
                <a href="employees.php" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Employees
                </a>
            </div>
            
            <!-- Employee Info Card -->
            <div class="row">
                <div class="col-xl-12 col-md-12 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row mb-2">
                                        <div class="col-md-3"><strong>Name:</strong></div>
                                        <div class="col-md-9"><?php echo htmlspecialchars($employee['full_name']); ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3"><strong>Position:</strong></div>
                                        <div class="col-md-9"><?php echo htmlspecialchars($employee['position']); ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3"><strong>Employee ID:</strong></div>
                                        <div class="col-md-9"><?php echo htmlspecialchars($employee['employee_code']); ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3"><strong>Date Hired:</strong></div>
                                        <div class="col-md-9"><?php echo date('F d, Y', strtotime($employee['date_hired'])); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold">Cash Advance Availability</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="col-7">Max Allowed:</div>
                                                <div class="col-5 text-right">
                                                    ₱<?php echo number_format($maxAdvance, 2, '.', ','); ?>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-7">Current Unpaid:</div>
                                                <div class="col-5 text-right">
                                                    ₱<?php echo number_format($existingUnpaidTotal, 2, '.', ','); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7"><strong>Available:</strong></div>
                                                <div class="col-5 text-right font-weight-bold">
                                                    ₱<?php echo number_format($adjustedMaxAdvance, 2, '.', ','); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Request Cash Advance Card -->
            <div class="row">
                <div class="col-xl-12 col-md-12 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Request Cash Advance</h6>
                        </div>
                        <div class="card-body">
                            <form id="request-advance-form">
                                <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employeeId; ?>">
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount">Amount (₱)</label>
                                            <input type="number" class="form-control" id="amount" name="amount" 
                                                min="100" max="<?php echo $adjustedMaxAdvance; ?>" step="100" 
                                                <?php echo $adjustedMaxAdvance <= 0 ? 'disabled' : ''; ?>>
                                            <small class="form-text text-muted">
                                                Maximum allowed: ₱<?php echo number_format($adjustedMaxAdvance, 2, '.', ','); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_method">Payment Method</label>
                                            <select class="form-control" id="payment_method" name="payment_method" 
                                                <?php echo $adjustedMaxAdvance <= 0 ? 'disabled' : ''; ?>>
                                                <option value="cash">Cash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="check">Check</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="notes">Reason/Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="1" 
                                                <?php echo $adjustedMaxAdvance <= 0 ? 'disabled' : ''; ?>></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div id="request-message"></div>
                                        <button type="submit" class="btn btn-primary" 
                                            <?php echo $adjustedMaxAdvance <= 0 ? 'disabled' : ''; ?>>
                                            Submit Request
                                        </button>
                                        <?php if ($adjustedMaxAdvance <= 0): ?>
                                            <div class="alert alert-warning mt-2">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                This employee has reached their maximum cash advance limit.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cash Advance History Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cash Advance History</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Request Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Approval Date</th>
                                            <th>Approved By</th>
                                            <th>Payroll ID</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($advancesResult->num_rows === 0): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No cash advance records found.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php while ($advance = $advancesResult->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($advance['request_date'])); ?></td>
                                                    <td>₱<?php echo number_format($advance['amount'], 2, '.', ','); ?></td>
                                                    <td>
                                                        <?php 
                                                            $statusClass = '';
                                                            switch ($advance['status']) {
                                                                case 'pending': $statusClass = 'text-warning'; break;
                                                                case 'approved': $statusClass = 'text-primary'; break;
                                                                case 'rejected': $statusClass = 'text-danger'; break;
                                                                case 'paid': $statusClass = 'text-success'; break;
                                                            }
                                                            echo '<span class="font-weight-bold ' . $statusClass . '">' . 
                                                                 strtoupper($advance['status']) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $advance['approval_date'] ? 
                                                            date('M d, Y', strtotime($advance['approval_date'])) : '-'; ?>
                                                    </td>
                                                    <td><?php echo $advance['approved_by'] ?? '-'; ?></td>
                                                    <td>
                                                        <?php if ($advance['payroll_id']): ?>
                                                            <a href="payroll.php?view_payslip=<?php echo $advance['payroll_id']; ?>">
                                                                <?php echo $advance['payroll_id']; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($advance['notes'] ?? '-'); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle cash advance request form submission
            const requestForm = document.getElementById('request-advance-form');
            const messageDiv = document.getElementById('request-message');
            
            requestForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(requestForm);
                
                // Validate amount
                const amount = formData.get('amount');
                if (!amount || amount < 100 || amount > <?php echo $adjustedMaxAdvance; ?>) {
                    showMessage(messageDiv, 'Please enter a valid amount between ₱100 and ₱<?php echo number_format($adjustedMaxAdvance, 2, '.', ','); ?>', 'danger');
                    return;
                }
                
                // Submit request
                fetch('../pages/api/cash_advance/request.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showMessage(messageDiv, 'Cash advance request submitted successfully.', 'success');
                        requestForm.reset();
                        
                        // Reload the page after a short delay
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showMessage(messageDiv, 'Error: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    showMessage(messageDiv, 'Error submitting request: ' + error, 'danger');
                });
            });
            
            // Utility function to show messages
            function showMessage(element, message, type) {
                element.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
                setTimeout(() => element.querySelector('.alert').classList.add('show'), 100);
            }
        });
    </script>
</body>
</html> 