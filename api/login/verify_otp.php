<?php
session_start();
require_once '../../database/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['otp']) || !isset($data['user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required parameters'
        ]);
        exit;
    }

    $enteredOtp = $data['otp'];
    $userId = $data['user_id'];

    // Validate OTP format
    if (!preg_match('/^\d{6}$/', $enteredOtp)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid OTP format'
        ]);
        exit;
    }

    // Verify OTP against database
    $stmt = $conn->prepare("SELECT usertype, OTP FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Check if OTP exists
    if (empty($user['OTP'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No OTP found. Please request a new one.'
        ]);
        exit;
    }

    // Verify OTP
    if ($enteredOtp === $user['OTP']) {
        // OTP is valid, clear OTP from database
        $updateStmt = $conn->prepare("UPDATE users SET OTP = NULL WHERE id = ?");
        $updateStmt->bind_param("i", $userId);
        $updateStmt->execute();
        $updateStmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'OTP verified successfully',
            'usertype' => (int)$user['usertype']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid OTP. Please try again.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

$conn->close(); 