<?php
session_start();
require '../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']); // Hash the password

    $stmt = $conn->prepare("SELECT id, username, usertype, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];
            
            $response = [
                'success' => true, 
                'message' => 'Login successful!', 
                'usertype' => (int)$user['usertype']
            ];
        } else {
            $response = ['success' => false, 'message' => 'Invalid password'];
        }
    } else {
        $response = ['success' => false, 'message' => 'User not found'];
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
