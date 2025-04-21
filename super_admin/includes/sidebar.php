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
            <a href="../pages/payroll.php" class="<?php echo (strpos($_SERVER['PHP_SELF'], 'payroll')) ? 'active' : ''; ?>">
                <i class="fas fa-wallet"></i>
                <span>Payroll</span>
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