<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Register New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="position_id" class="form-label">Position</label>
                            <select class="form-select" id="position_id" name="position_id" required>
                                <option value="" selected disabled>Select Position</option>
                                <!-- Will be populated from database -->
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Employment Type</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employment_type" id="fulltime" value="full-time" checked>
                                    <label class="form-check-label" for="fulltime">Full-time</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employment_type" id="parttime" value="part-time">
                                    <label class="form-check-label" for="parttime">Part-time</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Salary Rate Type</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="salary_rate_type" id="daily" value="daily">
                                    <label class="form-check-label" for="daily">Daily</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="salary_rate_type" id="monthly" value="monthly" checked>
                                    <label class="form-check-label" for="monthly">Monthly</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="salary_rate_type" id="hourly" value="hourly">
                                    <label class="form-check-label" for="hourly">Hourly</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="date_hired" class="form-label">Date Hired</label>
                            <input type="date" class="form-control" id="date_hired" name="date_hired" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="overtime_rate" class="form-label">Overtime Rate (per hour)</label>
                            <input type="number" class="form-control" id="overtime_rate" name="overtime_rate" step="0.01" value="150.00" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="email_address" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email_address" name="email_address">
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Government IDs</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sss_number" class="form-label">SSS Number</label>
                            <input type="text" class="form-control" id="sss_number" name="sss_number">
                        </div>
                        <div class="col-md-6">
                            <label for="sss_file" class="form-label">SSS Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="sss_file" name="sss_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pagibig_number" class="form-label">Pag-IBIG Number</label>
                            <input type="text" class="form-control" id="pagibig_number" name="pagibig_number">
                        </div>
                        <div class="col-md-6">
                            <label for="pagibig_file" class="form-label">Pag-IBIG Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="pagibig_file" name="pagibig_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="philhealth_number" class="form-label">PhilHealth Number</label>
                            <input type="text" class="form-control" id="philhealth_number" name="philhealth_number">
                        </div>
                        <div class="col-md-6">
                            <label for="philhealth_file" class="form-label">PhilHealth Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="philhealth_file" name="philhealth_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tin_number" class="form-label">TIN Number</label>
                            <input type="text" class="form-control" id="tin_number" name="tin_number">
                        </div>
                        <div class="col-md-6">
                            <label for="tin_file" class="form-label">TIN Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="tin_file" name="tin_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Register Employee</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_employee_id" name="edit_employee_id">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="edit_full_name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_position_id" class="form-label">Position</label>
                            <select class="form-select" id="edit_position_id" name="edit_position_id" required>
                                <option value="" selected disabled>Select Position</option>
                                <!-- Will be populated from database -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employment Type</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_employment_type" id="edit_fulltime" value="full-time">
                                    <label class="form-check-label" for="edit_fulltime">Full-time</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_employment_type" id="edit_parttime" value="part-time">
                                    <label class="form-check-label" for="edit_parttime">Part-time</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Salary Rate Type</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_salary_rate_type" id="edit_daily" value="daily">
                                    <label class="form-check-label" for="edit_daily">Daily</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_salary_rate_type" id="edit_monthly" value="monthly">
                                    <label class="form-check-label" for="edit_monthly">Monthly</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_salary_rate_type" id="edit_hourly" value="hourly">
                                    <label class="form-check-label" for="edit_hourly">Hourly</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_date_hired" class="form-label">Date Hired</label>
                            <input type="date" class="form-control" id="edit_date_hired" name="edit_date_hired" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_overtime_rate" class="form-label">Overtime Rate (per hour)</label>
                            <input type="number" class="form-control" id="edit_overtime_rate" name="edit_overtime_rate" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="edit_contact_number" name="edit_contact_number" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_email_address" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="edit_email_address" name="edit_email_address">
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Government IDs</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_sss_number" class="form-label">SSS Number</label>
                            <input type="text" class="form-control" id="edit_sss_number" name="edit_sss_number">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_sss_file" class="form-label">SSS Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="edit_sss_file" name="edit_sss_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_pagibig_number" class="form-label">Pag-IBIG Number</label>
                            <input type="text" class="form-control" id="edit_pagibig_number" name="edit_pagibig_number">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pagibig_file" class="form-label">Pag-IBIG Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="edit_pagibig_file" name="edit_pagibig_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_philhealth_number" class="form-label">PhilHealth Number</label>
                            <input type="text" class="form-control" id="edit_philhealth_number" name="edit_philhealth_number">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_philhealth_file" class="form-label">PhilHealth Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="edit_philhealth_file" name="edit_philhealth_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_tin_number" class="form-label">TIN Number</label>
                            <input type="text" class="form-control" id="edit_tin_number" name="edit_tin_number">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_tin_file" class="form-label">TIN Document (PDF or Word only)</label>
                            <input type="file" class="form-control" id="edit_tin_file" name="edit_tin_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateEmployeeBtn">Update Employee</button>
            </div>
        </div>
    </div>
</div>

<!-- View Employee Modal -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeeModalLabel">Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h4 id="view_full_name" class="fw-bold"></h4>
                            <span id="view_position" class="badge bg-primary"></span>
                            <span id="view_employment_type" class="badge bg-secondary ms-2"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Personal Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Date Hired</th>
                                            <td id="view_date_hired"></td>
                                        </tr>
                                        <tr>
                                            <th>Salary Rate</th>
                                            <td id="view_salary_rate"></td>
                                        </tr>
                                        <tr>
                                            <th>Overtime Rate</th>
                                            <td id="view_overtime_rate"></td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td id="view_contact_number"></td>
                                        </tr>
                                        <tr>
                                            <th>Email Address</th>
                                            <td id="view_email_address"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Government IDs</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">SSS Number</th>
                                            <td id="view_sss_number"></td>
                                        </tr>
                                        <tr>
                                            <th>Pag-IBIG Number</th>
                                            <td id="view_pagibig_number"></td>
                                        </tr>
                                        <tr>
                                            <th>PhilHealth Number</th>
                                            <td id="view_philhealth_number"></td>
                                        </tr>
                                        <tr>
                                            <th>TIN Number</th>
                                            <td id="view_tin_number"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Documents</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="document_links">
                                        <!-- Document links will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-from-view" id="editFromViewBtn">Edit Employee</button>
            </div>
        </div>
    </div>
</div>