<?php include_once '../includes/head.php'; ?>
<body>
    <!-- Include Sidebar -->
    <?php include_once '../includes/sidebar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">Dashboard</h1>
                <div class="header-actions">
                    <!-- Placeholder for future header actions/buttons -->
                </div>
            </div>
        </div>
        
        <!-- Page Content Goes Here -->
        <div class="content-wrapper">
            <!-- Individual page content will be inserted here -->
            <?php if(isset($pageContent)) echo $pageContent; ?>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/main.js"></script>
</body>