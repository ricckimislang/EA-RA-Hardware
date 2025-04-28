<?php
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
                        <table id="attendance-table" class="table table-striped table-hover mb-0">
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
                                <!-- Data will be populated by JavaScript -->
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
                                        <div class="h1 text-success summary-present"><?php echo $presentCount; ?></div>
                                        <div>Present</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-warning summary-late"><?php echo $lateCount; ?></div>
                                        <div>Late</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-info summary-half-day"><?php echo $halfDayCount; ?></div>
                                        <div>Half Day</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="h1 text-danger summary-absent"><?php echo $absentCount; ?></div>
                                        <div>Absent</div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <div class="h4">Total Hours: <span class="summary-total-hours"><?php echo number_format($totalHours, 2); ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <script>
            $(document).ready(function() {
                // Function to get badge class based on status
                function getStatusBadgeClass(status) {
                    switch(status) {
                        case 'present': return 'bg-success';
                        case 'late': return 'bg-warning text-dark';
                        case 'half-day': return 'bg-info text-dark';
                        case 'absent': return 'bg-danger';
                        default: return 'bg-secondary';
                    }
                }

                // Function to format date
                function formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                }

                // Function to format time
                function formatTime(timeString) {
                    if (!timeString) return '<span class="text-muted">Not logged out</span>';
                    return new Date('1970-01-01T' + timeString).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }

                // Function to load attendance data
                function loadAttendanceData() {
                    const employeeId = $('#employee_id').val();
                    const startDate = $('#start_date').val();
                    const endDate = $('#end_date').val();
                    const status = $('#status').val();

                    $.ajax({
                        url: 'get_attendance_data.php',
                        method: 'GET',
                        data: {
                            employee_id: employeeId,
                            start_date: startDate,
                            end_date: endDate,
                            status: status
                        },
                        dataType: 'json',
                        success: function(response) {
                            const table = $('#attendance-table').DataTable();
                            table.clear();

                            response.data.forEach(function(row) {
                                table.row.add([
                                    row.full_name,
                                    row.position,
                                    formatDate(row.date),
                                    formatTime(row.time_in),
                                    formatTime(row.time_out),
                                    row.total_hours ? parseFloat(row.total_hours).toFixed(2) : '-',
                                    `<span class="badge ${getStatusBadgeClass(row.status)}">${row.status.charAt(0).toUpperCase() + row.status.slice(1)}</span>`,
                                    row.notes || ''
                                ]);
                            });

                            table.draw();
                            updateSummary(response.summary);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading attendance data:', error);
                        }
                    });
                }

                // Function to update summary
                function updateSummary(summary) {
                    if (summary) {
                        $('.summary-present').text(summary.presentCount);
                        $('.summary-late').text(summary.lateCount);
                        $('.summary-half-day').text(summary.halfDayCount);
                        $('.summary-absent').text(summary.absentCount);
                        $('.summary-total-hours').text(summary.totalHours.toFixed(2));
                    }
                }

                // Initialize DataTable
                const table = $('#attendance-table').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 10,
                    "order": [[2, 'desc']],
                    "language": {
                        "search": "Filter records...",
                        "lengthMenu": "Show _MENU_ entries",
                        "zeroRecords": "No matching records found",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total entries)",
                        "paginate": {
                            "first": "First",
                            "last": "Last",
                            "next": "Next",
                            "previous": "Previous"
                        }
                    },
                    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
                });

                // Load initial data
                loadAttendanceData();

                // Handle filter form submission
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    loadAttendanceData();
                });

                // Handle reset button click
                $('.btn-secondary').on('click', function(e) {
                    e.preventDefault();
                    $('form')[0].reset();
                    loadAttendanceData();
                });
            });
        </script>
    </div>
</body>

<?php $conn->close(); ?>