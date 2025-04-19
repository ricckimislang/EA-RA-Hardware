<?php
session_start();
include_once '../database/config.php';

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Get server time for initial display
$serverTime = date('H:i:s');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA-RA Hardware - Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        #qr-reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .attendance-card {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .success-message {
            display: none;
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .error-message {
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .clock {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">EA-RA Hardware Attendance System</h1>

        <div class="attendance-card bg-light">
            <div class="clock" id="current-time"><?php echo $serverTime; ?></div>
            <div class="text-center mb-4">
                <h3>Scan your QR code to log attendance</h3>
                <div class="btn-group mb-3" role="group">
                    <button type="button" class="btn btn-success me-2" id="time-in-btn">Time In</button>
                    <button type="button" class="btn btn-danger" id="time-out-btn">Time Out</button>
                </div>
            </div>

            <div id="qr-reader"></div>

            <div class="success-message mt-3" id="success-message"></div>
            <div class="error-message mt-3" id="error-message"></div>

            <p class="text-center mt-4">
                <a href="employee_qr.php" class="btn btn-primary">View Employee QR Codes</a>
                <a href="reports.php" class="btn btn-secondary">Attendance Reports</a>
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/html5-qrcode.min.js"></script>
    <script>
        // Update clock using server time
        function updateClockFromServer() {
            $.ajax({
                url: 'get_server_time.php',
                type: 'GET',
                success: function(response) {
                    document.getElementById('current-time').textContent = response;
                },
                error: function() {
                    // Fallback to client time if server request fails
                    const now = new Date();
                    let hours = now.getHours().toString().padStart(2, '0');
                    let minutes = now.getMinutes().toString().padStart(2, '0');
                    let seconds = now.getSeconds().toString().padStart(2, '0');
                    document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
                }
            });
        }
        
        // Update every second
        setInterval(updateClockFromServer, 1000);
        
        // Initial update
        updateClockFromServer();

        // Mode selection (time in or time out)
        let mode = 'time-in';

        document.getElementById('time-in-btn').addEventListener('click', function() {
            mode = 'time-in';
            this.classList.add('active');
            document.getElementById('time-out-btn').classList.remove('active');
        });

        document.getElementById('time-out-btn').addEventListener('click', function() {
            mode = 'time-out';
            this.classList.add('active');
            document.getElementById('time-in-btn').classList.remove('active');
        });

        // QR Code Scanner
        const html5QrCode = new Html5Qrcode("qr-reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            // Stop scanning
            html5QrCode.stop().then(() => {
                // Process the QR code result
                processQrCode(decodedText);
            });
        };

        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        };

        html5QrCode.start({
            facingMode: "environment"
        }, config, qrCodeSuccessCallback);

        // Process QR code and submit attendance
        function processQrCode(qrHash) {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            // AJAX call to process attendance
            $.ajax({
                url: 'process_attendance.php',
                type: 'POST',
                data: {
                    qr_hash: qrHash,
                    mode: mode
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        successMessage.textContent = data.message;
                        successMessage.style.display = 'block';
                        errorMessage.style.display = 'none';

                        // Reset scanner after 3 seconds
                        setTimeout(() => {
                            successMessage.style.display = 'none';
                            html5QrCode.start({
                                facingMode: "environment"
                            }, config, qrCodeSuccessCallback);
                        }, 3000);
                    } else {
                        errorMessage.textContent = data.message;
                        errorMessage.style.display = 'block';
                        successMessage.style.display = 'none';

                        // Reset scanner after 3 seconds
                        setTimeout(() => {
                            errorMessage.style.display = 'none';
                            html5QrCode.start({
                                facingMode: "environment"
                            }, config, qrCodeSuccessCallback);
                        }, 3000);
                    }
                },
                error: function() {
                    errorMessage.textContent = 'Server error. Please try again.';
                    errorMessage.style.display = 'block';
                    successMessage.style.display = 'none';

                    // Reset scanner after 3 seconds
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                        html5QrCode.start({
                            facingMode: "environment"
                        }, config, qrCodeSuccessCallback);
                    }, 3000);
                }
            });
        }
    </script>
</body>

</html>