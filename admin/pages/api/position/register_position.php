<?php

require_once '../../../../database/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['position_name'])) {

    $position_name = $_POST['position_name'];
    $position_salary = $_POST['position_salary'];
    $position_description = $_POST['position_description'];

    $sql = "SELECT title FROM positions WHERE title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $position_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Position already exists'
        ]);
        exit;
    }

    $sql = "INSERT INTO positions (title, base_salary, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $position_name, $position_salary, $position_description);
    $success = $stmt->execute();
    
    if ($success) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Position registered successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $stmt->error
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}
$conn->close();
$stmt->close();
