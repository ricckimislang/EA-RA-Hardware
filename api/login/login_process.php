<?php
session_start();
require '../../database/config.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']); // Hash the password

    $stmt = $conn->prepare("SELECT id, username, usertype, password, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            // Generate 6-digit OTP
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in database
            $updateStmt = $conn->prepare("UPDATE users SET OTP = ? WHERE id = ?");
            $updateStmt->bind_param("si", $otp, $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];
            
            // Send OTP via email
            try {
                $mail = new PHPMailer(true);
                
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'cashperapadala@gmail.com'; // Replace with your email
                $mail->Password = 'orsj eblo clkp hxhz'; // Replace with your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom('earahardware-noreply@gmail.com', 'EA-RA Hardware System');
                $mail->addAddress($user['email'], $user['username']);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Login Verification Code';
                
                // Email template
                $emailTemplate = '
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #333;
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                        }
                        .header {
                            background-color: #2c3e50;
                            color: white;
                            padding: 20px;
                            text-align: center;
                            border-radius: 5px 5px 0 0;
                        }
                        .content {
                            background-color: #f9f9f9;
                            padding: 20px;
                            border: 1px solid #ddd;
                            border-radius: 0 0 5px 5px;
                        }
                        .otp-code {
                            font-size: 32px;
                            font-weight: bold;
                            color: #2c3e50;
                            text-align: center;
                            letter-spacing: 5px;
                            margin: 20px 0;
                            padding: 15px;
                            background-color: #ecf0f1;
                            border-radius: 5px;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 20px;
                            font-size: 12px;
                            color: #666;
                        }
                        .note {
                            background-color: #fff3cd;
                            border: 1px solid #ffeeba;
                            color: #856404;
                            padding: 10px;
                            border-radius: 5px;
                            margin: 15px 0;
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>EA-RA Hardware System</h2>
                        <p>Login Verification Code</p>
                    </div>
                    <div class="content">
                        <p>Hello ' . htmlspecialchars($user['username']) . ',</p>
                        <p>Your login verification code is:</p>
                        <div class="otp-code">' . $otp . '</div>
                        <div class="note">
                            <strong>Important:</strong>
                            <ul>
                                <li>This code will expire in 5 minutes</li>
                                <li>Do not share this code with anyone</li>
                                <li>If you did not request this code, please ignore this email</li>
                            </ul>
                        </div>
                        <p>Please enter this code to complete your login process.</p>
                        <p>If you did not attempt to log in, please contact the system administrator immediately.</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message, please do not reply to this email.</p>
                        <p>&copy; ' . date('Y') . ' EA-RA Hardware System. All rights reserved.</p>
                    </div>
                </body>
                </html>';
                
                $mail->Body = $emailTemplate;
                $mail->AltBody = "Your login verification code is: $otp\n\nThis code will expire in 5 minutes.\n\nIf you did not request this code, please ignore this email.";
                
                $mail->send();
                
                $response = [
                    'success' => true, 
                    'message' => 'Login successful. Please check your email for the verification code.', 
                    'usertype' => (int)$user['usertype'],
                    'user_id' => $user['id']
                ];
            } catch (Exception $e) {
                // Clear OTP from database if email fails
                $clearStmt = $conn->prepare("UPDATE users SET OTP = NULL WHERE id = ?");
                $clearStmt->bind_param("i", $user['id']);
                $clearStmt->execute();
                $clearStmt->close();
                
                // Clear session data
                unset($_SESSION['user_id']);
                unset($_SESSION['username']);
                unset($_SESSION['usertype']);
                
                $response = [
                    'success' => false, 
                    'message' => 'Unable to send verification code. Please check your email address and internet connection.',
                    'error_type' => 'email_error'
                ];
                error_log("Email Error: " . $mail->ErrorInfo);
            }
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
