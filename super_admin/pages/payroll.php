<?php
include '../includes/head.php';
require_once '../../database/config.php'; // Required for DB

$payPeriods = [];
$debug = false; // Set to true for debugging

// --- Define the function (copied from get_periods.php) ---
function getPayPeriods()
{
    global $conn;

    $sql = "SELECT 
                id,
                start_date, 
                end_date, 
                status,
                created_at
            FROM pay_periods
            ORDER BY start_date DESC";

    $result = $conn->query($sql);

    $periods = [];
    while ($row = $result->fetch_assoc()) {
        $periods[] = $row;
    }

    return $periods;
}

// --- Use the function directly ---
try {
    $payPeriods = getPayPeriods();
} catch (Exception $e) {
    if ($debug) {
        echo "Error fetching pay periods: " . $e->getMessage();
    }
}

// --- Optional debug output ---
if ($debug) {
    echo "<pre>";
    print_r($payPeriods);
    echo "</pre>";
}
?>



<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Payroll Management</h1>
            </div>

            <?php if ($debug): ?>
                <div class="alert alert-info">
                    <p>Debug Info:</p>
                    <p>API URL: <?= $periodsApiUrl ?></p>
                    <p>Response: <?= $periodsJson ?></p>
                    <p>Pay Periods Count: <?= count($payPeriods) ?></p>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Process Payroll Card -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Process Payroll</h6>
                        </div>
                        <div class="card-body">
                            <div id="process-message"></div>
                            <p class="mb-3">Select a pay period from the dropdown and click "Process Payroll".</p>
                            <form id="process-payroll-form">
                                <div class="form-group">
                                    <label for="pay_period_dropdown">Select Pay Period:</label>
                                    <select class="form-control" id="pay_period_dropdown" required>
                                        <option value="">Select a pay period</option>
                                        <?php
                                        // Generate pay periods for any month and year
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 1; // Allow selection from previous year
                                        $endYear = $currentYear + 1;   // Allow selection up to next year

                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                            for ($month = 1; $month <= 12; $month++) {
                                                $monthDate = new DateTime("$year-$month-01");
                                                $monthName = $monthDate->format('F Y');

                                                // First half of month (1-15)
                                                $firstStart = clone $monthDate;
                                                $firstEnd = clone $firstStart;
                                                $firstEnd->modify('+14 days');

                                                // Second half of month (16-end)
                                                $secondStart = clone $firstEnd;
                                                $secondStart->modify('+1 day');
                                                $secondEnd = clone $monthDate;
                                                $secondEnd->modify('last day of this month');

                                                // Format for display and value
                                                $firstStartStr = $firstStart->format('Y-m-d');
                                                $firstEndStr = $firstEnd->format('Y-m-d');
                                                $secondStartStr = $secondStart->format('Y-m-d');
                                                $secondEndStr = $secondEnd->format('Y-m-d');

                                                echo "<option value=\"$firstStartStr,$firstEndStr\">" . $monthName . " (1-15)</option>";
                                                echo "<option value=\"$secondStartStr,$secondEndStr\">" . $monthName . " (16-" . $secondEnd->format('d') . ")</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Process Payroll</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- View Payroll Reports Card -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">View Payroll Reports</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="pay_period_select">Select Pay Period:</label>
                                <select class="form-control" id="pay_period_select">
                                    <option value="">Select a pay period</option>
                                    <?php foreach ($payPeriods as $period): ?>
                                        <option value="<?= $period['id'] ?>">
                                            <?= date('M d, Y', strtotime($period['start_date'])) ?> to
                                            <?= date('M d, Y', strtotime($period['end_date'])) ?>
                                            (<?= $period['status'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button id="view-report-btn" class="btn btn-success">View Report</button>
                            <div id="close-warning" class="mt-2 small text-danger" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> Warning: Closing a payroll makes it permanent and it cannot be reopened or modified.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payroll Report Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Payroll Report</h6>
                            <div class="report-actions" style="display: none;">
                                <button id="print-report-btn" class="btn btn-sm btn-info mr-2">
                                    <i class="fas fa-print"></i> Print Report
                                </button>
                                <button id="export-excel-btn" class="btn btn-sm btn-success mr-2">
                                    <i class="fas fa-file-excel"></i> Export to Excel
                                </button>
                                <button id="close-payroll-btn" class="btn btn-sm btn-danger" disabled title="All employees must be paid before closing">
                                    <i class="fas fa-lock"></i> Close Payroll
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="payroll-report-table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Position</th>
                                            <th>Total Hours</th>
                                            <th>Gross Pay</th>
                                            <th>Deductions</th>
                                            <th>Net Pay</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payroll-report-body">
                                        <tr>
                                            <td colspan="8" class="text-center">Select a pay period to view the report</td>
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

    <!-- Modal for confirming payroll closure -->
    <div class="modal fade" id="closePayrollModal" tabindex="-1" aria-labelledby="closePayrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="closePayrollModalLabel">Confirm Payroll Closure</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Warning!</strong>
                    </div>
                    <p>You are about to permanently close this payroll period. This action:</p>
                    <ul>
                        <li>Cannot be undone</li>
                        <li>Will finalize all payments</li>
                        <li>Will prevent any future modifications</li>
                    </ul>
                    <p>Are you absolutely sure you want to continue?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-close-payroll">Yes, Close Payroll</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying detailed payslip calculations -->
    <div class="modal fade" id="payslipDetailsModal" tabindex="-1" aria-labelledby="payslipDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="payslipDetailsModalLabel">Payslip Calculation Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Employee Information Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Employee Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> <span id="employee-name"></span></p>
                                    <p><strong>Position:</strong> <span id="employee-position"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Date Hired:</strong> <span id="employee-hired"></span></p>
                                    <p><strong>Rate Type:</strong> <span id="employee-rate-type"></span></p>
                                    <p><strong>Base Salary:</strong> <span id="employee-base-salary"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hours and Earnings Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Hours & Earnings Calculation</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Hours</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Regular Hours</td>
                                            <td id="regular-hours"></td>
                                            <td id="regular-rate"></td>
                                            <td id="regular-amount"></td>
                                        </tr>
                                        <tr>
                                            <td>Overtime Hours</td>
                                            <td id="overtime-hours"></td>
                                            <td id="overtime-rate"></td>
                                            <td id="overtime-amount"></td>
                                        </tr>
                                        <tr class="table-light">
                                            <th colspan="3">Total Gross Pay</th>
                                            <th id="total-gross-pay"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Deductions Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Deduction Type</th>
                                            <th>Rate/Amount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="deductions-table-body">
                                        <!-- Deduction rows will be dynamically inserted here -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <th colspan="2">Total Deductions</th>
                                            <th id="total-deductions"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cash Advance Information Section -->
                    <div class="card mb-3" id="cash-advance-info-section" style="display: none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Cash Advance Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Available Cash Advance:</strong> <span id="available-advance"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Maximum Allowed:</strong> <span id="max-advance-amount"></span></p>
                                    <p class="text-muted small">Based on <span id="max-advance-percent"></span>% of monthly salary</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Pay Section -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Net Pay Calculation</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p><strong>Gross Pay:</strong> <span id="summary-gross-pay"></span></p>
                                    <p><strong>Total Deductions:</strong> <span id="summary-deductions"></span></p>
                                    <hr>
                                    <p class="h5"><strong>Net Pay:</strong> <span id="summary-net-pay"></span></p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="mt-3">
                                        <p><strong>Payment Status</strong></p>
                                        <span class="badge bg-success py-2 px-3 fs-6" id="payment-status">Paid</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="print-details-btn">Print Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Payroll Processing -->
    <script src="../js/payroll.js"></script>
</body>