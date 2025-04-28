<?php
require_once 'database/config.php';
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = trim(htmlspecialchars($_POST['username']));
    $full_name = trim(htmlspecialchars($_POST['full_name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = trim(htmlspecialchars($_POST['phone']));
    $password = $_POST['password'];
    
    // Server-side validation
    if (empty($username) || empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit;
    }
    
    // Validate password length (matching client-side validation)
    if (strlen($password) < 8) {
        $response['message'] = 'Password must be at least 8 characters long';
        echo json_encode($response);
        exit;
    }
    
    // Check if username exists
    $check_username = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check_username->bind_param('s', $username);
    $check_username->execute();
    $check_username->store_result();
    
    if ($check_username->num_rows > 0) {
        $response['message'] = 'Username already exists';
        echo json_encode($response);
        $check_username->close();
        $conn->close();
        exit;
    }
    $check_username->close();
    
    // Check if email exists
    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email->bind_param('s', $email);
    $check_email->execute();
    $check_email->store_result();
    
    if ($check_email->num_rows > 0) {
        $response['message'] = 'Email already exists';
        echo json_encode($response);
        $check_email->close();
        $conn->close();
        exit;
    }
    $check_email->close();
    
    // Use md5 for password hashing (not recommended, but keeping as requested)
    // Consider using password_hash() in production for better security
    $hashed_password = md5($password);
    
    // Insert user
    $insert = $conn->prepare("INSERT INTO users (username, fullname, email, contact_no, password, usertype) VALUES (?, ?, ?, ?, ?, '1')");
    $insert->bind_param('sssss', $username, $full_name, $email, $phone, $hashed_password);
    
    if ($insert->execute()) {
        $response['success'] = true;
        $response['message'] = 'Registration successful';
    } else {
        $response['message'] = 'Registration failed: ' . $conn->error;
    }
    
    $insert->close();
    $conn->close();
    echo json_encode($response);
}

