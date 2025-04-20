<?php
session_start();
require '../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $password = md5($password); // Hash the password

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];

            echo json_encode(['success' => true, 'message' => 'Login successful', 'usertype' => (int)$user['usertype']]);
        } else {
            echo json_encode(['success' => false, 'message' => $password]);
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
