/**
 * Cash Advance Management JavaScript
 * Handles cash advance request submission, approval, and history viewing
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the page
    loadPendingRequests();
    loadCashAdvanceHistory();
    
    // Set today's date as default for date filters
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setMonth(today.getMonth() - 1);
    
    document.getElementById('date-to').valueAsDate = today;
    document.getElementById('date-from').valueAsDate = lastMonth;
    
    // Set up event listeners
    setupEventListeners();
    
    // Get employee ID dropdown
    const employeeSelect = document.getElementById('employee_id');
    if (employeeSelect) {
        employeeSelect.addEventListener('change', function() {
            validateMaxAdvance();
        });
    }
    
    // Amount input validation
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('change', function() {
            validateMaxAdvance();
        });
    }
});

/**
 * Set up all event listeners for the page
 */
function setupEventListeners() {
    // Cash Advance Form Submission
    const cashAdvanceForm = document.getElementById('cash-advance-form');
    if (cashAdvanceForm) {
        cashAdvanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitCashAdvanceRequest();
        });
    }
    
    // Filter Button
    const filterBtn = document.getElementById('filter-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            loadCashAdvanceHistory();
        });
    }
    
    // Approval Modal Buttons
    const approveBtn = document.getElementById('approve-btn');
    if (approveBtn) {
        approveBtn.addEventListener('click', function() {
            updateCashAdvanceStatus('approved');
        });
    }
    
    const rejectBtn = document.getElementById('reject-btn');
    if (rejectBtn) {
        rejectBtn.addEventListener('click', function() {
            updateCashAdvanceStatus('rejected');
        });
    }
}

/**
 * Submit a new cash advance request
 */
function submitCashAdvanceRequest() {
    const form = document.getElementById('cash-advance-form');
    const messageDiv = document.getElementById('request-message');
    
    // Create form data from the form
    const formData = new FormData(form);
    
    // Show loading message
    showMessage(messageDiv, 'Submitting cash advance request...', 'info');
    
    // Submit the request
    fetch('../pages/api/cash_advance/request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage(messageDiv, data.message, 'success');
            form.reset();
            
            // Reload pending requests
            loadPendingRequests();
            loadCashAdvanceHistory();
        } else {
            showMessage(messageDiv, data.message, 'danger');
        }
    })
    .catch(error => {
        showMessage(messageDiv, 'Error submitting request: ' + error, 'danger');
    });
}

/**
 * Load pending cash advance requests
 */
function loadPendingRequests() {
    const tableBody = document.getElementById('pending-requests-body');
    const messageDiv = document.getElementById('pending-requests-message');
    
    // Show loading message
    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading pending requests...</td></tr>';
    
    // Fetch pending requests
    fetch('../pages/api/cash_advance/get_advances.php?status=pending')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.data.length > 0) {
                    // Clear the table
                    tableBody.innerHTML = '';
                    
                    // Add each request to the table
                    data.data.forEach(advance => {
                        const row = document.createElement('tr');
                        
                        // Format the date
                        const requestDate = new Date(advance.request_date).toLocaleDateString();
                        
                        // Format the amount with peso sign and comma separators
                        const formattedAmount = formatCurrency(advance.amount);
                        
                        row.innerHTML = `
                            <td>${advance.employee_name}</td>
                            <td>${formattedAmount}</td>
                            <td>${requestDate}</td>
                            <td>
                                <button class="btn btn-sm btn-success approve-btn" data-id="${advance.id}" 
                                    data-employee="${advance.employee_name}" data-amount="${advance.amount}">
                                    Approve
                                </button>
                                <button class="btn btn-sm btn-danger reject-btn" data-id="${advance.id}"
                                    data-employee="${advance.employee_name}" data-amount="${advance.amount}">
                                    Reject
                                </button>
                                <button class="btn btn-sm btn-info view-btn" data-id="${advance.id}">
                                    View
                                </button>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to the buttons
                    addPendingRequestButtonListeners();
                } else {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No pending requests</td></tr>';
                }
            } else {
                showMessage(messageDiv, data.message, 'danger');
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Error loading requests</td></tr>';
            }
        })
        .catch(error => {
            showMessage(messageDiv, 'Error loading requests: ' + error, 'danger');
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Error loading requests</td></tr>';
        });
}

/**
 * Load cash advance history
 */
function loadCashAdvanceHistory() {
    const tableBody = document.getElementById('cash-advances-body');
    
    // Get filter values
    const status = document.getElementById('status-filter').value;
    const dateFrom = document.getElementById('date-from').value;
    const dateTo = document.getElementById('date-to').value;
    
    // Show loading message
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Loading cash advance history...</td></tr>';
    
    // Build the query string
    let queryString = '?';
    if (status) queryString += `status=${status}&`;
    if (dateFrom) queryString += `date_from=${dateFrom}&`;
    if (dateTo) queryString += `date_to=${dateTo}&`;
    
    // Fetch cash advance history
    fetch(`../pages/api/cash_advance/get_advances.php${queryString}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.data.length > 0) {
                    // Clear the table
                    tableBody.innerHTML = '';
                    
                    // Add each advance to the table
                    data.data.forEach(advance => {
                        const row = document.createElement('tr');
                        
                        // Format the date
                        const requestDate = new Date(advance.request_date).toLocaleDateString();
                        
                        // Format the amount
                        const formattedAmount = formatCurrency(advance.amount);
                        
                        // Determine status class
                        let statusClass = '';
                        switch (advance.status) {
                            case 'approved': statusClass = 'success'; break;
                            case 'rejected': statusClass = 'danger'; break;
                            case 'pending': statusClass = 'warning'; break;
                            case 'paid': statusClass = 'info'; break;
                        }
                        
                        // Truncate notes if too long
                        const notes = advance.notes ? (advance.notes.length > 30 ? 
                            advance.notes.substring(0, 30) + '...' : advance.notes) : '';
                        
                        // Build actions based on status
                        let actions = `<button class="btn btn-sm btn-info view-details-btn" data-id="${advance.id}">View</button>`;
                        
                        if (advance.status === 'pending') {
                            actions += `
                                <button class="btn btn-sm btn-success approve-btn" data-id="${advance.id}" 
                                    data-employee="${advance.employee_name}" data-amount="${advance.amount}">
                                    Approve
                                </button>
                                <button class="btn btn-sm btn-danger reject-btn" data-id="${advance.id}"
                                    data-employee="${advance.employee_name}" data-amount="${advance.amount}">
                                    Reject
                                </button>
                            `;
                        }
                        
                        row.innerHTML = `
                            <td>${advance.employee_name}</td>
                            <td>${formattedAmount}</td>
                            <td>${requestDate}</td>
                            <td><span class="badge badge-${statusClass}">${advance.status.toUpperCase()}</span></td>
                            <td>${advance.payment_method || '-'}</td>
                            <td>${notes}</td>
                            <td>${actions}</td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to the buttons
                    addHistoryButtonListeners();
                } else {
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No cash advance records found</td></tr>';
                }
            } else {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">${data.message}</td></tr>`;
            }
        })
        .catch(error => {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading history: ${error}</td></tr>`;
        });
}

/**
 * Add event listeners to pending request buttons
 */
function addPendingRequestButtonListeners() {
    // Approve buttons
    const approveButtons = document.querySelectorAll('#pending-requests-body .approve-btn');
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const employee = this.getAttribute('data-employee');
            const amount = this.getAttribute('data-amount');
            
            showApprovalModal(id, employee, amount, 'approve');
        });
    });
    
    // Reject buttons
    const rejectButtons = document.querySelectorAll('#pending-requests-body .reject-btn');
    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const employee = this.getAttribute('data-employee');
            const amount = this.getAttribute('data-amount');
            
            showApprovalModal(id, employee, amount, 'reject');
        });
    });
    
    // View buttons
    const viewButtons = document.querySelectorAll('#pending-requests-body .view-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            viewCashAdvanceDetails(id);
        });
    });
}

/**
 * Add event listeners to history table buttons
 */
function addHistoryButtonListeners() {
    // View details buttons
    const viewButtons = document.querySelectorAll('#cash-advances-body .view-details-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            viewCashAdvanceDetails(id);
        });
    });
    
    // Approve buttons
    const approveButtons = document.querySelectorAll('#cash-advances-body .approve-btn');
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const employee = this.getAttribute('data-employee');
            const amount = this.getAttribute('data-amount');
            
            showApprovalModal(id, employee, amount, 'approve');
        });
    });
    
    // Reject buttons
    const rejectButtons = document.querySelectorAll('#cash-advances-body .reject-btn');
    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const employee = this.getAttribute('data-employee');
            const amount = this.getAttribute('data-amount');
            
            showApprovalModal(id, employee, amount, 'reject');
        });
    });
}

/**
 * Show approval modal with advance details
 */
function showApprovalModal(id, employee, amount, action) {
    // Set the modal values
    document.getElementById('advance_id').value = id;
    document.getElementById('modal-employee-name').textContent = employee;
    document.getElementById('modal-amount').textContent = formatCurrency(amount);
    
    // Update modal title based on action
    const modalTitle = document.getElementById('approvalModalLabel');
    if (action === 'approve') {
        modalTitle.textContent = 'Approve Cash Advance';
    } else {
        modalTitle.textContent = 'Reject Cash Advance';
    }
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
}

/**
 * Update cash advance status (approve or reject)
 */
function updateCashAdvanceStatus(status) {
    const advanceId = document.getElementById('advance_id').value;
    const notes = document.getElementById('approval-notes').value;
    
    // Prepare form data
    const formData = new FormData();
    formData.append('id', advanceId);
    formData.append('status', status);
    formData.append('notes', notes);
    
    // Send request to update status
    fetch('../pages/api/cash_advance/update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('approvalModal'));
            modal.hide();
            
            // Show success message in an alert
            alert(data.message);
            
            // Reload data
            loadPendingRequests();
            loadCashAdvanceHistory();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error updating status: ' + error);
    });
}

/**
 * View cash advance details
 */
function viewCashAdvanceDetails(id) {
    // Fetch the cash advance details
    fetch(`../pages/api/cash_advance/get_advance_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const advance = data.data;
                
                // Populate the details modal
                document.getElementById('detail-employee').textContent = advance.employee_name;
                document.getElementById('detail-amount').textContent = formatCurrency(advance.amount);
                document.getElementById('detail-request-date').textContent = new Date(advance.request_date).toLocaleDateString();
                document.getElementById('detail-approval-date').textContent = advance.approval_date ? 
                    new Date(advance.approval_date).toLocaleDateString() : 'Not approved yet';
                document.getElementById('detail-status').textContent = advance.status.toUpperCase();
                document.getElementById('detail-payment-method').textContent = advance.payment_method || 'Not specified';
                document.getElementById('detail-notes').textContent = advance.notes || 'No notes';
                
                // Show payroll deduction info if available
                if (advance.payroll_id) {
                    document.getElementById('detail-payroll').textContent = `Deducted in Payroll #${advance.payroll_id}`;
                } else {
                    document.getElementById('detail-payroll').textContent = 'Not yet deducted';
                }
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                modal.show();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error loading details: ' + error);
        });
}

/**
 * Format currency with peso sign and thousand separators
 */
function formatCurrency(amount) {
    // Ensure amount is a number
    const numberAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    
    // Format with peso symbol and thousand separators
    return '₱' + numberAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Validate maximum cash advance amount
 */
function validateMaxAdvance() {
    const employeeSelect = document.getElementById('employee_id');
    const amountInput = document.getElementById('amount');
    const messageDiv = document.getElementById('request-message');
    
    if (!employeeSelect.value) {
        return; // No employee selected
    }
    
    // Get the employee ID
    const employeeId = employeeSelect.value;
    
    // Check if the employee has a salary and calculate max advance
    fetch(`../pages/api/cash_advance/get_employee_salary.php?id=${employeeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const maxAdvanceAmount = parseFloat(data.data.max_advance);
                const currentAmount = parseFloat(amountInput.value);
                
                if (currentAmount > maxAdvanceAmount) {
                    showMessage(messageDiv, `Maximum advance amount is ${formatCurrency(maxAdvanceAmount)}`, 'warning');
                    amountInput.value = maxAdvanceAmount;
                } else {
                    messageDiv.innerHTML = '';
                }
            } else {
                showMessage(messageDiv, data.message, 'danger');
            }
        })
        .catch(error => {
            showMessage(messageDiv, 'Error checking max advance: ' + error, 'danger');
        });
}

/**
 * Show a message in the specified element
 */
function showMessage(element, message, type) {
    element.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            element.innerHTML = '';
        }, 5000);
    }
} 