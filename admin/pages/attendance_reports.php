<?php
session_start();
include_once '../../database/config.php';

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get filter parameters
$employeeId = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); // Default to first day of current month
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t'); // Default to last day of current month
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Get all employees for filter dropdown
$employeesQuery = "SELECT id, full_name FROM employees ORDER BY full_name";
$employeesResult = $conn->query($employeesQuery);

// Prepare the attendance report query
$sql = "SELECT a.id, a.employee_id, e.full_name, p.title as position, 
               DATE(a.time_in) as date, 
               TIME(a.time_in) as time_in, 
               TIME(a.time_out) as time_out, 
               a.total_hours, a.status, a.notes
        FROM attendance_records a
        JOIN employees e ON a.employee_id = e.id
        JOIN positions p ON e.position_id = p.id
        WHERE DATE(a.time_in) BETWEEN ? AND ?";

$params = [$startDate, $endDate];
$types = "ss";

if (!empty($employeeId)) {
    $sql .= " AND a.employee_id = ?";
    $params[] = $employeeId;
    $types .= "i";
}

if (!empty($status)) {
    $sql .= " AND a.status = ?";
    $params[] = $status;
    $types .= "s";
}

$sql .= " ORDER BY a.time_in DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Export to CSV if requested
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_report_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    // Add CSV header
    fputcsv($output, ['Employee', 'Position', 'Date', 'Time In', 'Time Out', 'Hours', 'Status', 'Notes']);

    // Add data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['full_name'],
            $row['position'],
            $row['date'],
            $row['time_in'],
            $row['time_out'] ?? 'Not logged out',
            $row['total_hours'] ?? '-',
            $row['status'],
            $row['notes'] ?? ''
        ]);
    }

    fclose($output);
    exit;
}
?>


<?php include_once '../includes/head.php' ?>

<body>
    <?php include_once '../includes/sidebar.php' ?>
    <div class="main-content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Attendance Reports</h1>
            </div>

            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-header bg-light">Filter Options</div>
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">All Employees</option>
                                <?php while ($employee = $employeesResult->fetch_assoc()): ?>
                                    <option value="<?php echo $employee['id']; ?>" <?php echo $employeeId == $employee['id'] ? 'selected' : ''; ?>>
                                        <?php echo $employee['full_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="present" <?php echo $status == 'present' ? 'selected' : ''; ?>>Present</option>
                                <option value="late" <?php echo $status == 'late' ? 'selected' : ''; ?>>Late</option>
                                <option value="half-day" <?php echo $status == 'half-day' ? 'selected' : ''; ?>>Half Day</option>
                                <option value="absent" <?php echo $status == 'absent' ? 'selected' : ''; ?>>Absent</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="attendance_reports.php" class="btn btn-secondary">Reset Filters</a>
                            <button type="submit" name="export" value="csv" class="btn btn-success">Export to CSV</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="card">
                <div class="card-header bg-light">
                    <span>Attendance Records (<?php echo $result->num_rows; ?> results)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Position</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['position']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                            <td><?php echo date('h:i A', strtotime($row['time_in'])); ?></td>
                                            <td>
                                                <?php echo $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : '<span class="text-muted">Not logged out</span>'; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['total_hours'] ? number_format($row['total_hours'], 2) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($row['status'] == 'present') $badgeClass = 'bg-success';
                                                if ($row['status'] == 'late') $badgeClass = 'bg-warning text-dark';
                                                if ($row['status'] == 'half-day') $badgeClass = 'bg-info text-dark';
                                                if ($row['status'] == 'absent') $badgeClass = 'bg-danger';
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($row['status']); ?></span>
                                            </td>
                                            <td><?php echo $row['notes'] ?? ''; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-3">No attendance records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <?php if ($result->num_rows > 0): ?>
                <?php
                // Re-execute query for summary data
                $stmt->execute();
                $summaryResult = $stmt->get_result();

                $totalHours = 0;
                $presentCount = 0;
                $lateCount = 0;
                $halfDayCount = 0;
                $absentCount = 0;

                while ($row = $summaryResult->fetch_assoc()) {
                    $totalHours += $row['total_hours'] ?? 0;

                    if ($row['status'] == 'present') $presentCount++;
                    if ($row['status'] == 'late') $lateCount++;
                    if ($row['status'] == 'half-day') $halfDayCount++;
                    if ($row['status'] == 'absent') $absentCount++;
                }
                ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">Summary</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-success"><?php echo $presentCount; ?></div>
                                        <div>Present</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-warning"><?php echo $lateCount; ?></div>
                                        <div>Late</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-info"><?php echo $halfDayCount; ?></div>
                                        <div>Half Day</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-danger"><?php echo $absentCount; ?></div>
                                        <div>Absent</div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <div class="h4">Total Hours: <?php echo number_format($totalHours, 2); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>

<?php $conn->close(); ?>