<?php
require_once '../includes/head.php';
require_once '../includes/sidebar.php';
require_once '../../database/config.php';
?>

<link rel="stylesheet" href="../css/expenses.css">

<body>
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <h2 class="mb-4">Employee Management</h2>
                <!-- Add Employee Button -->
                <div class="btn-group mb-3 gap-1">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="fas fa-plus"></i> Register Employee
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerPositionModal">
                        <i class="fas fa-plus"></i> Register Position
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Employee Table -->
                    <div class="card">
                        <div class="card-body">
                            <table id="employeesTable" class="table table-hover dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Employment Type</th>
                                        <!-- <th>Salary Rate</th> -->
                                        <th>Date Hired</th>
                                        <th>Contact</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modals -->
        <?php include_once 'modals/employee-modal.php'; ?>
    </div>
    <script src="../js/employee.js"></script>
</body>

<?php
$conn->close();
?>