<div class="sidebar">
    <div class="sidebar-header">
        <img src="../../assets/images/ea-ra-logo.svg" alt="Hardware System Logo">
        <h3>Hardware Admin</h3>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="../pages/dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="../pages/inventory.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'inventory')) ? 'active' : ''; ?>">
                <i class="fas fa-boxes"></i>
                <span>Inventory</span>
            </a>
        </li>

        <li>
            <a href="../pages/employees.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'employees')) ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Employees</span>
            </a>
        </li>
            <a href="../pages/expenses.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'expenses')) ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Expenses</span>
            </a>
        </li>

        <!-- <li>
            <a href="../suppliers/index.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'suppliers')) ? 'active' : ''; ?>">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
        </li> -->

        <li>
            <a href="../reports/index.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'reports')) ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </li>

        <li>
            <a href="../settings/index.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'settings')) ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
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