<?php
require_once '../includes/head.php';
require_once '../includes/sidebar.php';
// Database connection and employee data fetching would go here
?>

<link rel="stylesheet" href="../css/expenses.css">

<body>
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <h2 class="mb-4">Employee Management</h2>
                <!-- Add Employee Button -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-plus"></i> Add Employee
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Employee Table -->
                    <div class="card">
                        <div class="card-body">
                            <table id="employeesTable" class="table table-hover dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Base Salary</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample data - would be populated from database -->
                                    <tr>
                                        <td>1</td>
                                        <td>John Doe</td>
                                        <td>Cashier</td>
                                        <td>₱15,000.00</td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-btn" data-id="1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
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

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#employeesTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'print',
                        className: 'btn btn-primary',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude actions column from print
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-primary',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude actions column from Excel
                        }
                    }
                ],
            });

            // Edit button click handler
            $('.edit-btn').click(function() {
                const employeeId = $(this).data('id');
                // In a real app, you would fetch employee data here
                $('#editEmployeeModal').modal('show');
            });

            // Save new employee
            $('#saveEmployeeBtn').click(function() {
                // Save logic would go here
                alert('Employee saved successfully!');
                $('#addEmployeeModal').modal('hide');
            });

            // Update employee
            $('#updateEmployeeBtn').click(function() {
                // Update logic would go here
                alert('Employee updated successfully!');
                $('#editEmployeeModal').modal('hide');
            });
        });
    </script>
</body>