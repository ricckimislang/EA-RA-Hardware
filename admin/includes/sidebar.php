<div class="sidebar">
    <div class="sidebar-header">
        <img src="../../assets/images/ea-ra-logo.png" alt="Hardware System Logo">
        <h3>Hardware Admin</h3>
    </div>

    <ul class="sidebar-menu">
        <?php if ($_SESSION['usertype'] == '1') { ?>
            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            <li>
                <a href="http://localhost/ea-ra-hardware/super_admin/pages/payroll.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'payroll')) ? 'active' : ''; ?>">
                    <i class="fas fa-wallet"></i>
                    <span>Payroll</span>
                </a>
            </li>


            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/inventory.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'inventory')) ? 'active' : ''; ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/employees.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'employees')) ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Employees</span>
                </a>
            </li>

            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/expenses.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'expenses')) ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/attendance_reports.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'attendance_reports')) ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i>
                    <span>Attendance Reports</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/employee_qr.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'employee_qr')) ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i>
                    <span>Employee Qr Codes</span>
                </a>
            </li>
        <?php } else if ($_SESSION['usertype'] == '2') { ?>
            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/inventory.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'inventory')) ? 'active' : ''; ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/expenses.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'expenses')) ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/sales_history.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'sales_history')) ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i>
                    <span>Sales History</span>
                </a>
            </li>

            <li>
                <a href="http://localhost/ea-ra-hardware/admin/pages/attendance_reports.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'attendance_reports')) ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i>
                    <span>Attendance Reports</span>
                </a>
            </li>
        <?php } ?>
        <li>
            <a href="http://localhost/ea-ra-hardware/admin/pages/settings.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'settings')) ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>

        <li>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Sidebar Toggle Button for Mobile -->
<button class="sidebar-toggle d-block d-md-none">
    <i class="fas fa-bars"></i>
</button>

<script>
    // Toggle sidebar on mobile
    document.querySelector('.sidebar-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.querySelector('.sidebar-toggle');

        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });
</script>