<?php
session_start();
include_once '../../database/config.php';

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get employees with their QR codes
$sql = "SELECT e.id, e.full_name, e.contact_number, p.title as position, qr.qr_code_hash, qr.is_active 
        FROM employees e
        LEFT JOIN employee_qr_codes qr ON e.id = qr.employee_id
        LEFT JOIN positions p ON e.position_id = p.id
        ORDER BY e.full_name";
$result = $conn->query($sql);

// Handle QR code regeneration if requested
if (isset($_POST['regenerate']) && isset($_POST['employee_id'])) {
    $employeeId = $_POST['employee_id'];
    // Generate a shorter QR code hash (25 chars)
    $newHash = substr(md5($employeeId . rand() . time()), 0, 25);

    $updateSql = "UPDATE employee_qr_codes SET qr_code_hash = ?, updated_at = NOW() WHERE employee_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $newHash, $employeeId);

    if ($stmt->execute()) {
        header("Location: employee_qr.php?success=QR code regenerated successfully.");
        exit;
    } else {
        header("Location: employee_qr.php?error=Failed to regenerate QR code.");
        exit;
    }
}

// Handle QR code activation/deactivation
if (isset($_POST['toggle_status']) && isset($_POST['employee_id'])) {
    $employeeId = $_POST['employee_id'];
    $newStatus = $_POST['new_status'] == 1 ? 1 : 0;

    $updateSql = "UPDATE employee_qr_codes SET is_active = ?, updated_at = NOW() WHERE employee_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $newStatus, $employeeId);

    if ($stmt->execute()) {
        $statusText = $newStatus ? "activated" : "deactivated";
        header("Location: employee_qr.php?success=QR code $statusText successfully.");
        exit;
    } else {
        header("Location: employee_qr.php?error=Failed to update QR code status.");
        exit;
    }
}
?>


<?php include_once '../includes/head.php' ?>



<body>
    <?php include_once '../includes/sidebar.php' ?>
    <div class="main-content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Employee QR Codes</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo $_GET['success']; ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Contact</th>
                            <th>QR Code</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td><?php echo $row['contact_number']; ?></td>
                                    <td>
                                        <?php if ($row['qr_code_hash']): ?>
                                            <div class="qr-code" id="qr-<?php echo $row['id']; ?>" data-hash="<?php echo $row['qr_code_hash']; ?>"></div>
                                            <button class="btn btn-sm btn-secondary mt-1" onclick="printQR('qr-<?php echo $row['id']; ?>', '<?php echo $row['full_name']; ?>')">Print</button>
                                        <?php else: ?>
                                            <span class="text-danger">No QR code</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['qr_code_hash']): ?>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['qr_code_hash']): ?>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="regenerate" value="1">
                                                <button type="submit" class="btn btn-sm btn-warning">Regenerate</button>
                                            </form>

                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="toggle_status" value="1">
                                                <input type="hidden" name="new_status" value="<?php echo $row['is_active'] ? 0 : 1; ?>">
                                                <button type="submit" class="btn btn-sm <?php echo $row['is_active'] ? 'btn-danger' : 'btn-success'; ?>">
                                                    <?php echo $row['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post">
                                                <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="regenerate" value="1">
                                                <button type="submit" class="btn btn-sm btn-primary">Generate QR</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No employees found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            // Wait for the QRCode library to load
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof QRCode === 'undefined') {
                    console.error('QRCode library failed to load');
                    return;
                }

                // Generate QR codes
                <?php
                if ($result->num_rows > 0) {
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        if ($row['qr_code_hash']) {
                            echo "const qrElement_{$row['id']} = document.getElementById('qr-{$row['id']}');\n";
                            echo "qrElement_{$row['id']}.setAttribute('data-hash', '{$row['qr_code_hash']}');\n";
                            echo "new QRCode(qrElement_{$row['id']}, {
                            text: '{$row['qr_code_hash']}',
                            width: 128,
                            height: 128,
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.H
                        });\n";
                        }
                    }
                }
                ?>

                // Print QR code
                window.printQR = function(qrId, name) {
                    const qrElement = document.getElementById(qrId);
                    if (!qrElement) {
                        alert('QR code element not found');
                        return;
                    }

                    // Get the original QR code hash from the data attribute
                    const qrHash = qrElement.getAttribute('data-hash');
                    if (!qrHash) {
                        alert('QR code hash not found');
                        return;
                    }

                    // Create a high-resolution QR code for printing
                    const printQrContainer = document.createElement('div');
                    new QRCode(printQrContainer, {
                        text: qrHash,
                        width: 512,
                        height: 512,
                        colorDark: '#000000',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    // Wait for QR code generation
                    setTimeout(() => {
                        const printQrImage = printQrContainer.querySelector('img');
                        if (!printQrImage) {
                            alert('Failed to generate high-resolution QR code');
                            return;
                        }

                        // Create print window with improved styling
                        const printWindow = window.open('', '_blank');
                        if (!printWindow) {
                            alert('Pop-up blocked. Please allow pop-ups for this site to print QR codes.');
                            return;
                        }

                        const currentDate = new Date().toLocaleDateString();

                        printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>QR Code - ${name}</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    min-height: 100vh;
                                    margin: 0;
                                    padding: 20px;
                                }
                                .qr-container {
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .qr-info {
                                    margin-top: 20px;
                                    text-align: center;
                                }
                                @media print {
                                    body {
                                        padding: 0;
                                    }
                                    .no-print {
                                        display: none;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="qr-container">
                                <img src="${printQrImage.src}" alt="QR Code" style="max-width: 100%;">
                            </div>
                            <div class="qr-info">
                                <h2>${name}</h2>
                                <p>Generated on: ${currentDate}</p>
                            </div>
                            <button class="no-print" onclick="window.print()">Print</button>
                        </body>
                        </html>
                    `);

                        printWindow.document.close();
                        printWindow.focus();
                    }, 200);
                };
            });
        </script>
    </div>
</body>

<?php $conn->close(); ?>