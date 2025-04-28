<?php
include_once '../../database/config.php';

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Get filter parameters
$employeeId = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$status = isset($_GET['status']) ? $_GET['status'] : '';

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

// Prepare response data
$response = [
    'data' => [],
    'summary' => [
        'presentCount' => 0,
        'lateCount' => 0,
        'halfDayCount' => 0,
        'absentCount' => 0,
        'totalHours' => 0
    ]
];

// Process attendance records
while ($row = $result->fetch_assoc()) {
    $response['data'][] = $row;

    // Update summary counts
    switch ($row['status']) {
        case 'present':
            $response['summary']['presentCount']++;
            break;
        case 'late':
            $response['summary']['lateCount']++;
            break;
        case 'half-day':
            $response['summary']['halfDayCount']++;
            break;
        case 'absent':
            $response['summary']['absentCount']++;
            break;
    }

    // Add to total hours
    $response['summary']['totalHours'] += $row['total_hours'] ?? 0;
}

// Close database connection
$conn->close();

// Set header and return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 