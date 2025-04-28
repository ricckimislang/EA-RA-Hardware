<?php
session_start();
require_once '../../../database/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get values from request
$current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

// Validate inputs
if (empty($current_password) || empty($new_password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Hash passwords
$current_password_hash = md5($current_password);
$new_password_hash = md5($new_password);

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Verify current password
$verify_query = "SELECT * FROM users WHERE id = ? AND password = ?";
$stmt = $conn->prepare($verify_query);
$stmt->bind_param("is", $user_id, $current_password_hash);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

// Update password
$update_query = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("si", $new_password_hash, $user_id);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?> 