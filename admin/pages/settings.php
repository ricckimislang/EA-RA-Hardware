<?php include '../includes/head.php'; ?>


<link rel="stylesheet" href="../css/settings.css">

<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <h1>Settings</h1>
        </div>

        <div class="settings-container">
            <!-- Admin Information Section -->
            <div class="card admin-info">
                <div class="card-header">
                    <h2>Admin Information</h2>
                </div>
                <div class="card-body">
                    <?php
                    require_once '../../database/config.php';

                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT u.* FROM users u WHERE u.id = ?";

                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $admin = $result->fetch_assoc();
                    ?>
                        <div class="info-row">
                            <div class="info-label">Full Name:</div>
                            <div class="info-value"><?php echo htmlspecialchars($admin['fullname']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Username:</div>
                            <div class="info-value"><?php echo htmlspecialchars($admin['username']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?php echo htmlspecialchars($admin['email']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Contact Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($admin['contact_no']); ?></div>
                        </div>
                    <?php
                    } else {
                        echo '<div class="alert alert-warning">Admin information not found.</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="card password-change">
                <div class="card-header">
                    <h2>Change Password</h2>
                </div>
                <div class="card-body">
                    <div id="password-message"></div>
                    <form id="change-password-form">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle password change form submission
            $("#change-password-form").on('submit', function(e) {
                e.preventDefault();

                var current_password = $("#current_password").val();
                var new_password = $("#new_password").val();
                var confirm_password = $("#confirm_password").val();

                // Simple validation
                if (new_password !== confirm_password) {
                    $("#password-message").html('<div class="alert alert-danger">New passwords do not match!</div>');
                    showNotification("New passwords do not match!", "error");
                    return;
                }

                // Send AJAX request
                $.ajax({
                    url: 'api/update_password.php',
                    type: 'POST',
                    data: {
                        current_password: current_password,
                        new_password: new_password
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            $("#password-message").html('<div class="alert alert-success">' + data.message + '</div>');
                            showNotification(data.message, "success");
                            $("#change-password-form")[0].reset();
                        } else {
                            $("#password-message").html('<div class="alert alert-danger">' + data.message + '</div>');
                            showNotification(data.message, "error");
                        }
                    },
                    error: function() {
                        $("#password-message").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        showNotification("An error occurred. Please try again.", "error");
                    }
                });
            });
        });
    </script>
</body>