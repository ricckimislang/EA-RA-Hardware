<?php
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

    // First check if a QR code already exists for this employee
    $checkSql = "SELECT id FROM employee_qr_codes WHERE employee_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $employeeId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        // Update existing QR code
        $updateSql = "UPDATE employee_qr_codes SET qr_code_hash = ?, updated_at = NOW() WHERE employee_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $newHash, $employeeId);
    } else {
        // Insert new QR code record
        $insertSql = "INSERT INTO employee_qr_codes (employee_id, qr_code_hash, is_active, created_at, updated_at) 
                      VALUES (?, ?, 1, NOW(), NOW())";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("is", $employeeId, $newHash);
    }

    if ($stmt->execute()) {
        header("Location: employee_qr.php?success=QR code generated successfully.");
        exit;
    } else {
        header("Location: employee_qr.php?error=Failed to generate QR code: " . $stmt->error);
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
<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<!-- Toastify CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, .05);
        padding: 15px 20px;
    }

    .card-title {
        margin-bottom: 0;
        color: #333;
        font-weight: 600;
    }

    .card-body {
        padding: 20px;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-top: none;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .01);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .03);
    }

    .badge {
        padding: 6px 10px;
        font-weight: 500;
        border-radius: 30px;
    }

    .qr-code {
        padding: 10px;
        background-color: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        display: inline-block;
        margin-bottom: 10px;
    }

    .btn-action {
        border-radius: 6px;
        font-weight: 500;
        text-transform: none;
        letter-spacing: normal;
        padding: 0.375rem 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-print {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }

    .btn-print:hover {
        background-color: #138496;
        border-color: #117a8b;
        color: white;
    }

    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 1.5rem;
        margin: 0;
        color: #333;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<body>
    <?php include_once '../includes/sidebar.php' ?>
    <div class="main-content">
        <div class="container mt-4">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="bi bi-qr-code me-2"></i>
                    Employee QR Codes
                </h1>
                <a href="../pages/employees.php" class="btn btn-outline-primary btn-action">
                    <i class="bi bi-people"></i>
                    Manage Employees
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?php echo $_GET['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php echo $_GET['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
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
                                            <td class="align-middle"><?php echo $row['full_name']; ?></td>
                                            <td class="align-middle"><?php echo $row['position']; ?></td>
                                            <td class="align-middle"><?php echo $row['contact_number']; ?></td>
                                            <td class="align-middle ">
                                                <?php if ($row['qr_code_hash']): ?>
                                                    <div class="qr-code" id="qr-<?php echo $row['id']; ?>" data-hash="<?php echo $row['qr_code_hash']; ?>"></div>

                                                <?php else: ?>
                                                    <span class="text-danger">
                                                        <i class="bi bi-x-circle me-1"></i>
                                                        No QR code
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if ($row['qr_code_hash']): ?>
                                                    <?php if ($row['is_active']): ?>
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            Active
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle me-1"></i>
                                                            Inactive
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-dash-circle me-1"></i>
                                                        N/A
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-print" onclick="printQR('qr-<?php echo $row['id']; ?>', '<?php echo $row['full_name']; ?>')">
                                                        <i class="bi bi-printer"></i>
                                                        Print
                                                    </button>
                                                    <?php if ($row['qr_code_hash']): ?>
                                                        <button type="button" class="btn btn-sm btn-warning btn-action regenerate-qr"
                                                                data-employee-id="<?php echo $row['id']; ?>"
                                                                data-employee-name="<?php echo htmlspecialchars($row['full_name']); ?>">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                            Regenerate
                                                        </button>

                                                        <button type="button" class="btn btn-sm <?php echo $row['is_active'] ? 'btn-danger' : 'btn-success'; ?> btn-action toggle-status"
                                                                data-employee-id="<?php echo $row['id']; ?>"
                                                                data-employee-name="<?php echo htmlspecialchars($row['full_name']); ?>"
                                                                data-new-status="<?php echo $row['is_active'] ? 0 : 1; ?>">
                                                            <?php if ($row['is_active']): ?>
                                                                <i class="bi bi-toggle-off"></i>
                                                                Deactivate
                                                            <?php else: ?>
                                                                <i class="bi bi-toggle-on"></i>
                                                                Activate
                                                            <?php endif; ?>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-primary btn-action regenerate-qr"
                                                                data-employee-id="<?php echo $row['id']; ?>"
                                                                data-employee-name="<?php echo htmlspecialchars($row['full_name']); ?>">
                                                            <i class="bi bi-qr-code"></i>
                                                            Generate QR
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class="bi bi-people empty-icon"></i>
                                            <p class="mb-1">No employees found</p>
                                            <small>Add employees first to generate QR codes</small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <!-- Toastify JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Custom Notification System -->
        <script src="../js/notifications.js"></script>
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
                        showNotification('QR code element not found', 'error');
                        return;
                    }

                    // Get the original QR code hash from the data attribute
                    const qrHash = qrElement.getAttribute('data-hash');
                    if (!qrHash) {
                        showNotification('QR code hash not found', 'error');
                        return;
                    }

                    // Show loading notification
                    const hideLoading = showLoadingNotification('Generating high resolution QR code...');

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
                            hideLoading();
                            showNotification('Failed to generate high-resolution QR code', 'error');
                            return;
                        }

                        // Create print window with improved styling
                        const printWindow = window.open('', '_blank');
                        if (!printWindow) {
                            hideLoading();
                            showNotification('Pop-up blocked. Please allow pop-ups for this site to print QR codes.', 'warning');
                            return;
                        }

                        hideLoading();
                        showNotification(`QR code for ${name} generated successfully`, 'success');

                        const currentDate = new Date().toLocaleDateString();

                        printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>QR Code - ${name}</title>
                            <style>
                                @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
                                
                                body {
                                    font-family: 'Poppins', Arial, sans-serif;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    min-height: 100vh;
                                    margin: 0;
                                    padding: 20px;
                                    background-color: #f8f9fa;
                                }
                                
                                .qr-card {
                                    background-color: white;
                                    border-radius: 12px;
                                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                                    padding: 30px;
                                    text-align: center;
                                    max-width: 400px;
                                    width: 100%;
                                }
                                
                                .company-header {
                                    margin-bottom: 20px;
                                    color: #2c3e50;
                                }
                                
                                .qr-container {
                                    background-color: white;
                                    padding: 15px;
                                    border-radius: 8px;
                                    display: inline-block;
                                    margin-bottom: 20px;
                                    border: 1px solid #eaeaea;
                                }
                                
                                .qr-container img {
                                    display: block;
                                    max-width: 100%;
                                    height: auto;
                                }
                                
                                .employee-name {
                                    font-size: 24px;
                                    font-weight: 600;
                                    color: #2c3e50;
                                    margin: 15px 0 5px;
                                }
                                
                                .info-text {
                                    color: #6c757d;
                                    margin: 5px 0;
                                    font-size: 14px;
                                }
                                
                                .date {
                                    margin-top: 15px;
                                    font-size: 12px;
                                    color: #95a5a6;
                                }
                                
                                .print-btn {
                                    background-color: #3498db;
                                    color: white;
                                    border: none;
                                    padding: 10px 20px;
                                    border-radius: 5px;
                                    font-size: 14px;
                                    cursor: pointer;
                                    margin-top: 20px;
                                    font-weight: bold;
                                    transition: background-color 0.2s;
                                }
                                
                                .print-btn:hover {
                                    background-color: #2980b9;
                                }
                                
                                @media print {
                                    body {
                                        background-color: white;
                                        padding: 0;
                                    }
                                    
                                    .qr-card {
                                        box-shadow: none;
                                        padding: 0;
                                        max-width: 100%;
                                    }
                                    
                                    .no-print {
                                        display: none;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="qr-card">
                                <div class="company-header">
                                    <h2>EA-RA Hardware</h2>
                                    <p class="info-text">Employee Attendance System</p>
                                </div>
                                
                                <div class="qr-container">
                                    <img src="${printQrImage.src}" alt="QR Code">
                                </div>
                                
                                <h3 class="employee-name">${name}</h3>
                                <p class="info-text">Scan this QR code for attendance</p>
                                <p class="date">Generated on: ${currentDate}</p>
                                
                                <button class="print-btn no-print" onclick="window.print()">Print QR Code</button>
                            </div>
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

<script>
    $(document).ready(function() {
        // Show notifications based on URL parameters
        <?php if (isset($_GET['success'])): ?>
            showNotification("<?php echo htmlspecialchars($_GET['success']); ?>", "success");
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            showNotification("<?php echo htmlspecialchars($_GET['error']); ?>", "error");
        <?php endif; ?>
        
        // Handle QR code regeneration
        $('.regenerate-qr').on('click', function(e) {
            e.preventDefault();
            const employeeId = $(this).data('employee-id');
            const employeeName = $(this).data('employee-name');
            
            showConfirmDialog(`Are you sure you want to regenerate the QR code for ${employeeName}? The old QR code will no longer work.`, function() {
                const form = $(`<form method="post" action="employee_qr.php">
                    <input type="hidden" name="regenerate" value="1">
                    <input type="hidden" name="employee_id" value="${employeeId}">
                </form>`);
                $('body').append(form);
                
                // Show loading notification
                const hideLoading = showLoadingNotification(`Regenerating QR code for ${employeeName}...`);
                
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
        
        // Handle QR code activation/deactivation
        $('.toggle-status').on('click', function(e) {
            e.preventDefault();
            const employeeId = $(this).data('employee-id');
            const employeeName = $(this).data('employee-name');
            const newStatus = $(this).data('new-status');
            const statusText = newStatus == 1 ? 'activate' : 'deactivate';
            
            showConfirmDialog(`Are you sure you want to ${statusText} the QR code for ${employeeName}?`, function() {
                const form = $(`<form method="post" action="employee_qr.php">
                    <input type="hidden" name="toggle_status" value="1">
                    <input type="hidden" name="employee_id" value="${employeeId}">
                    <input type="hidden" name="new_status" value="${newStatus}">
                </form>`);
                $('body').append(form);
                
                // Show loading notification
                const hideLoading = showLoadingNotification(`${statusText.charAt(0).toUpperCase() + statusText.slice(1)}ing QR code...`);
                
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
    });
</script>