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
        
        #togglePassword {
            cursor: pointer;
            background-color: var(--light-color);
            border-color: #ced4da;
            color: var(--primary-color);
        }
        
        #togglePassword:hover {
            background-color: #dfe6e9;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="assets/images/ea-ra-logo.png" alt="EA-RA Hardware Logo">
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
                    <button class="btn btn-outline-secondary input-group-text" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <div class="forgot-password">
                    <a href="register.php">Register</a>
                </div>
                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
            </form>
        </div>
    </div>
    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="otpModalLabel">
                        <i class="fas fa-shield-alt me-2"></i>Two-Factor Authentication
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <p class="lead">Please enter the 6-digit OTP sent to your email.</p>
                        <p class="text-muted small">Check your inbox and spam folder</p>
                    </div>
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1" style="width: 50px; height: 50px;" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1" style="width: 50px; height: 50px;" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1" style="width: 50px; height: 50px;" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1" style="width: 50px; height: 50px;" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1" style="width: 50px; height: 50px;" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1" style="width: 50px; height: 50px;" pattern="[0-9]" inputmode="numeric">
                    </div>
                    <div class="text-center">
                        <p id="otpTimer" class="text-muted mb-2">
                            <i class="fas fa-clock me-2"></i>OTP expires in: <span class="fw-bold">05:00</span>
                        </p>
                        <button type="button" class="btn btn-link text-primary" id="resendOtp" disabled>
                            <i class="fas fa-redo-alt me-1"></i>Resend OTP
                            <span class="resend-cooldown"></span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="verifyOtp">
                        <i class="fas fa-check me-1"></i>Verify OTP
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="admin/js/notifications.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Anti-brute force protection setup
            const MAX_FAILED_ATTEMPTS = 5;
            const COOLDOWN_TIME = 5 * 60 * 1000; // 5 minutes in milliseconds
            
            // Check if user is locked out
            function checkLockout() {
                const loginData = JSON.parse(localStorage.getItem('loginAttempts') || '{"attempts": 0, "lockedUntil": 0}');
                const currentTime = new Date().getTime();
                
                if (loginData.lockedUntil > currentTime) {
                    // User is locked out
                    const remainingTime = Math.ceil((loginData.lockedUntil - currentTime) / 1000 / 60);
                    showNotification(`Too many failed login attempts. Please try again in ${remainingTime} minute(s).`, 'error');
                    disableLoginForm(loginData.lockedUntil);
                    return true;
                }
                
                // Reset if cooldown has expired
                if (loginData.attempts >= MAX_FAILED_ATTEMPTS && loginData.lockedUntil < currentTime) {
                    loginData.attempts = 0;
                    localStorage.setItem('loginAttempts', JSON.stringify(loginData));
                }
                
                return false;
            }
            
            // Disable login form and display countdown
            function disableLoginForm(unlockTime) {
                const loginButton = document.querySelector('.btn-login');
                const usernameInput = document.getElementById('username');
                const passwordInput = document.getElementById('password');
                
                loginButton.disabled = true;
                usernameInput.disabled = true;
                passwordInput.disabled = true;
                
                // Start countdown timer
                const countdownInterval = setInterval(() => {
                    const currentTime = new Date().getTime();
                    if (unlockTime <= currentTime) {
                        clearInterval(countdownInterval);
                        loginButton.disabled = false;
                        usernameInput.disabled = false;
                        passwordInput.disabled = false;
                        loginButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
                        return;
                    }
                    
                    const remainingSeconds = Math.ceil((unlockTime - currentTime) / 1000);
                    const minutes = Math.floor(remainingSeconds / 60);
                    const seconds = remainingSeconds % 60;
                    loginButton.innerHTML = `<i class="fas fa-lock"></i> Locked (${minutes}:${seconds < 10 ? '0' : ''}${seconds})`;
                }, 1000);
            }
            
            // Record a failed login attempt
            function recordFailedAttempt() {
                const loginData = JSON.parse(localStorage.getItem('loginAttempts') || '{"attempts": 0, "lockedUntil": 0}');
                loginData.attempts++;
                
                if (loginData.attempts >= MAX_FAILED_ATTEMPTS) {
                    const lockUntil = new Date().getTime() + COOLDOWN_TIME;
                    loginData.lockedUntil = lockUntil;
                    showNotification(`Too many failed login attempts. Your account is locked for ${COOLDOWN_TIME/60000} minutes.`, 'error');
                    disableLoginForm(lockUntil);
                } else {
                    showNotification(`Login failed. ${MAX_FAILED_ATTEMPTS - loginData.attempts} attempts remaining before lockout.`, 'warning');
                }
                
                localStorage.setItem('loginAttempts', JSON.stringify(loginData));
            }
            
            // Reset login attempts on successful login
            function resetLoginAttempts() {
                localStorage.setItem('loginAttempts', JSON.stringify({"attempts": 0, "lockedUntil": 0}));
            }
            
            // Check for lockout on page load
            checkLockout();
            
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                // Toggle type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                // Check if user is locked out
                if (checkLockout()) {
                    return false;
                }
                
                const formElement = document.getElementById('loginForm');
                const formData = new FormData(formElement);

                fetch('api/login/login_process.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            resetLoginAttempts(); // Reset attempts on successful login
                            showNotification(data.message, 'success');
                            
                            // Show OTP modal
                            const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                            otpModal.show();
                            
                            // Start OTP timer
                            startOtpTimer();
                            
                            // Handle OTP verification
                            document.getElementById('verifyOtp').addEventListener('click', function() {
                                const otpInputs = document.querySelectorAll('.otp-input');
                                const otp = Array.from(otpInputs).map(input => input.value).join('');
                                
                                if (otp.length !== 6) {
                                    showNotification('Please enter a valid 6-digit OTP', 'error');
                                    return;
                                }
                                
                                // Verify OTP
                                fetch('api/login/verify_otp.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        user_id: data.user_id,
                                        otp: otp
                                    })
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        otpModal.hide();
                                        showNotification('OTP verified successfully', 'success');
                                        // Proceed with login
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
                                        showNotification(result.message || 'Invalid OTP. Please try again.', 'error');
                                    }
                                })
                                .catch(err => {
                                    console.error('OTP verification error:', err);
                                    showNotification('Error verifying OTP. Please try again.', 'error');
                                });
                            });
                            
                            // Handle OTP input auto-focus
                            const otpInputs = document.querySelectorAll('.otp-input');
                            otpInputs.forEach((input, index) => {
                                input.addEventListener('input', function() {
                                    if (this.value.length === 1) {
                                        if (index < otpInputs.length - 1) {
                                            otpInputs[index + 1].focus();
                                        }
                                    }
                                });
                                
                                input.addEventListener('keydown', function(e) {
                                    if (e.key === 'Backspace' && !this.value && index > 0) {
                                        otpInputs[index - 1].focus();
                                    }
                                });
                            });
                            
                            // Handle resend OTP
                            document.getElementById('resendOtp').addEventListener('click', function() {
                                fetch('api/login/resend_otp.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        user_id: data.user_id
                                    })
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        showNotification('New OTP sent successfully', 'success');
                                        startOtpTimer();
                                        // Clear OTP inputs
                                        otpInputs.forEach(input => input.value = '');
                                        otpInputs[0].focus();
                                    } else {
                                        showNotification(result.message || 'Failed to resend OTP', 'error');
                                    }
                                })
                                .catch(err => {
                                    console.error('Resend OTP error:', err);
                                    showNotification('Error resending OTP', 'error');
                                });
                            });
                            
                        } else {
                            // Check if it's an email error
                            if (data.error_type === 'email_error') {
                                showNotification(data.message, 'error');
                            } else {
                                recordFailedAttempt(); // Only record failed attempt if it's not an email error
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        showNotification('Something went wrong during login. Please try again.', 'error');
                    });
            });
            
            // OTP Timer function
            function startOtpTimer() {
                const timerElement = document.querySelector('#otpTimer span');
                let timeLeft = 5 * 60; // 5 minutes in seconds
                const resendButton = document.getElementById('resendOtp');
                
                const timer = setInterval(() => {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        document.getElementById('verifyOtp').disabled = true;
                        resendButton.disabled = false;
                        showNotification('OTP has expired. Please request a new one.', 'error');
                    }
                    timeLeft--;
                }, 1000);
                
                // Store timer ID to clear it when needed
                document.getElementById('otpModal').dataset.timerId = timer;
            }
        });
    </script>
</body>

</html>