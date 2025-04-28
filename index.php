<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA-RA Hardware - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
        }

        body {
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 400px;
            max-width: 90%;
        }

        .login-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .login-header img {
            width: 80px;
            margin-bottom: 10px;
        }

        .login-body {
            padding: 30px;
        }

        .form-control {
            height: 44px;
            border-radius: 0 5px 5px 0;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-left: none;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group .form-control:focus {
            border-color: var(--secondary-color);
        }

        .btn-login {
            background-color: var(--secondary-color);
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #2980b9;
        }

        .input-group-text {
            height: 44px;
            padding: 0 15px;
            background-color: var(--light-color);
            border-radius: 5px 0 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        .hardware-icon {
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="assets/images/ea-ra-logo.svg" alt="EA-RA Hardware Logo">
            <h2>EA-RA Hardware</h2>
            <p>Hardware Management System</p>
        </div>

        <div class="login-body">
            <div class="text-center hardware-icon">
                <i class="fas fa-tools"></i>
            </div>

            <form id="loginForm">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="admin/js/notifications.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const formElement = document.getElementById('loginForm');
                const formData = new FormData(formElement);

                fetch('api/login/login_process.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            setTimeout(() => {
                                switch (data.usertype) {
                                    case 1: //superadmin
                                        window.location.href = 'super_admin/index.php';
                                        break;
                                    case 2: //admin
                                        window.location.href = 'admin/index.php';
                                        break;
                                    case 3: //cashier
                                        window.location.href = 'cashier/index.php';
                                        break;
                                    default:
                                        showNotification('Invalid User Type. Please contact the administrator.', 'error');
                                }
                            }, 1000);
                        } else {
                            showNotification(data.message || 'Login failed. Please try again.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        showNotification('Something went wrong during login. Please try again.', 'error');
                    });
            });
        });
    </script>
</body>

</html>