<?php
session_start();

// Check if password has been submitted
if (!isset($_SESSION['admin_access']) && (!isset($_POST['access_password']) || $_POST['access_password'] !== 'earahardware')) {
    // Show password form if not authenticated
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Registration Access</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="card-title text-center mb-4">Admin Access</h4>
                            <?php
                            if (isset($_POST['access_password'])) {
                                echo '<div class="alert alert-danger">Invalid password</div>';
                            }
                            ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="access_password" class="form-label">Enter Access Password</label>
                                    <input type="password" class="form-control" id="access_password" name="access_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
    exit();
} else {
    // Set session variable for future access
    $_SESSION['admin_access'] = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Super Admin Registration</h3>

                        <form id="registrationForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" id="submitButton" class="btn btn-primary">Register</button>
                                <a href="index.php" class="btn btn-secondary">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPassword = document.getElementById('confirm_password');
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Form validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true; // Disable the button at the start of form submission
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            let isValid = true;

            if (password !== confirmPassword) {
                toastr.error('Passwords do not match!');
                isValid = false;
            }
            if (password.length < 8) {
                toastr.error('Password must be at least 8 characters long!');
                isValid = false;
            }

            if (isValid) {
                const form = document.getElementById('registrationForm');
                const formData = new FormData(form);
                
                $.ajax({
                    url: 'register_process.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        try {
                            const data = typeof response === 'string' ? JSON.parse(response) : response;
                            if (data.success) {
                                toastr.success(data.message);
                                setTimeout(() => {
                                    window.location.href = 'index.php';
                                }, 2000);
                            } else {
                                toastr.error(data.message);
                                submitButton.disabled = false; // Re-enable the button if there's an error
                            }
                        } catch (e) {
                            toastr.error('Error processing response: ' + e.message);
                            submitButton.disabled = false; // Re-enable the button if there's an error
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error: ' + error);
                        submitButton.disabled = false; // Re-enable the button if there's an error
                    }
                });
            } else {
                submitButton.disabled = false; // Re-enable the button if validation fails
            }
        });
    </script>
</body>

</html>