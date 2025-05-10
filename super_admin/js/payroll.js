/**
 * Payroll Management JavaScript
 * Handles payroll processing and report viewing
 */

document.addEventListener('DOMContentLoaded', function() {
    // Process Payroll Form Submission
    const processForm = document.getElementById('process-payroll-form');
    if (processForm) {
        processForm.addEventListener('submit', function(e) {
            e.preventDefault();
            processPayroll();
        });
    }
    
    // View Report Button Click
    const viewReportBtn = document.getElementById('view-report-btn');
    if (viewReportBtn) {
        viewReportBtn.addEventListener('click', function() {
            getPayrollReport();
        });
    }
    
    // Print Report Button Click
    const printReportBtn = document.getElementById('print-report-btn');
    if (printReportBtn) {
        printReportBtn.addEventListener('click', function() {
            printPayrollReport();
        });
    }
    
    // Export to Excel Button Click
    const exportExcelBtn = document.getElementById('export-excel-btn');
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', function() {
            exportPayrollToExcel();
        });
    }
    
    // Close Payroll Button Click
    const closePayrollBtn = document.getElementById('close-payroll-btn');
    if (closePayrollBtn) {
        closePayrollBtn.addEventListener('click', function() {
            const closePayrollModal = new bootstrap.Modal(document.getElementById('closePayrollModal'));
            closePayrollModal.show();
        });
    }
    
    // Confirm Close Payroll Button Click
    const confirmCloseBtn = document.getElementById('confirm-close-payroll');
    if (confirmCloseBtn) {
        confirmCloseBtn.addEventListener('click', function() {
            closePayroll();
        });
    }
    
    // Show warning when hovering over close button
    const closeBtn = document.getElementById('close-payroll-btn');
    const closeWarning = document.getElementById('close-warning');
    
    if (closeBtn && closeWarning) {
        closeBtn.addEventListener('mouseenter', function() {
            closeWarning.style.display = 'block';
        });
        
        closeBtn.addEventListener('mouseleave', function() {
            closeWarning.style.display = 'none';
        });
    }
});

/**
 * Process payroll for the selected period
 */
function processPayroll() {
    const messageDiv = document.getElementById('process-message');
    const payPeriodDropdown = document.getElementById('pay_period_dropdown');
    const selectedPeriod = payPeriodDropdown.value;
    
    // Validate period selection
    if (!selectedPeriod) {
        showMessage(messageDiv, 'Please select a pay period', 'danger');
        return;
    }
    
    // Parse the selected period (format: "startDate,endDate")
    const [startDate, endDate] = selectedPeriod.split(',');
    
    // Show loading message
    showMessage(messageDiv, 'Processing payroll...', 'info');
    
    // Create form data with the selected period
    const formData = new FormData();
    formData.append('start_date', startDate);
    formData.append('end_date', endDate);
    
    // Send request to process payroll
    fetch('../pages/api/payroll/process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Format dates for display
            const startFormatted = new Date(startDate).toLocaleDateString();
            const endFormatted = new Date(endDate).toLocaleDateString();
            const successMsg = `Payroll processed successfully for period: ${startFormatted} to ${endFormatted}`;
            
            showMessage(messageDiv, successMsg, 'success');
            // Reload the page to update the pay periods dropdown
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showMessage(messageDiv, data.message, 'danger');
        }
    })
    .catch(error => {
        showMessage(messageDiv, 'Error processing payroll: ' + error, 'danger');
    });
}

/**
 * Get payroll report for the selected period
 */
function getPayrollReport() {
    const payPeriodSelect = document.getElementById('pay_period_select');
    const payPeriodId = payPeriodSelect.value;
    const tableBody = document.getElementById('payroll-report-body');
    
    // Validate selection
    if (!payPeriodId) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Please select a pay period</td></tr>';
        return;
    }
    
    // Show loading message
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Loading report data...</td></tr>';
    
    // Send request to get payroll report
    fetch(`../pages/api/payroll/get_report.php?pay_period_id=${payPeriodId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.data.length > 0) {
            // Get period status from the first employee record
            const isPeriodClosed = data.data[0].period_status === 'processed';
            displayPayrollReport(data.data, isPeriodClosed);
        } else if (data.status === 'success' && data.data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No payroll data found for this period</td></tr>';
        } else {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">${data.message}</td></tr>`;
        }
    })
    .catch(error => {
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading report: ${error}</td></tr>`;
    });
}

/**
 * Format number as currency with peso sign and thousand separators
 */
function formatCurrency(amount) {
    // Ensure amount is a number
    const numberAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    
    // Format with peso symbol and thousand separators
    return '₱' + numberAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Display payroll report in the table
 */
function displayPayrollReport(reportData, isPeriodClosed = false) {
    const tableBody = document.getElementById('payroll-report-body');
    
    // Clear existing content
    tableBody.innerHTML = '';
    
    // Show the report action buttons when data is available
    document.querySelector('.report-actions').style.display = 'block';
    
    // Check if all employees are paid
    const allPaid = reportData.every(employee => employee.payment_status === 'paid');
    
    // Enable or disable close button based on payment status and period status
    const closeBtn = document.getElementById('close-payroll-btn');
    if (closeBtn) {
        if (isPeriodClosed) {
            closeBtn.disabled = true;
            closeBtn.title = "Payroll period is already closed";
        } else if (allPaid) {
            closeBtn.disabled = false;
            closeBtn.title = "Close this payroll period";
        } else {
            closeBtn.disabled = true;
            closeBtn.title = "All employees must be paid before closing";
        }
    }
    
    // Add each employee to the table
    reportData.forEach(employee => {
        const row = document.createElement('tr');
        
        // Determine payment status class
        const statusClass = employee.payment_status === 'paid' ? 'success' : 'warning';
        const statusText = employee.payment_status === 'paid' ? 'Paid' : 'Pending';
        const toggleText = employee.payment_status === 'paid' ? 'Mark Unpaid' : 'Mark Paid';
        const toggleClass = employee.payment_status === 'paid' ? 'danger' : 'success';
        
        // Format the row data - ensure hours is displayed as an integer
        let actionButtons = `
            <button class="btn btn-sm btn-info view-details" data-id="${employee.payroll_id}">
                Details
            </button>
            <button class="btn btn-sm btn-primary print-slip" data-id="${employee.payroll_id}">
                Print Slip
            </button>
        `;
        
        // Only show toggle button if period is not closed
        if (!isPeriodClosed) {
            actionButtons += `
                <button class="btn btn-sm btn-${toggleClass} toggle-status" data-id="${employee.payroll_id}" data-status="${employee.payment_status}">
                    ${toggleText}
                </button>
            `;
        }
        
        row.innerHTML = `
            <td>${employee.full_name}</td>
            <td>${employee.position}</td>
            <td>${parseInt(employee.total_hours)} hrs</td>
            <td>${formatCurrency(employee.gross_pay)}</td>
            <td>${formatCurrency(employee.deductions)}</td>
            <td>${formatCurrency(employee.net_pay)}</td>
            <td>
                <span class="text-black badge badge-${statusClass}">${statusText}</span>
            </td>
            <td class="action-buttons">
                ${actionButtons}
            </td>
        `;
        
        tableBody.appendChild(row);
    });
    
    // Add event listeners for details and print buttons
    addPayrollButtonListeners();
}

/**
 * Add event listeners to payroll action buttons
 */
function addPayrollButtonListeners() {
    // View details buttons
    const detailButtons = document.querySelectorAll('.view-details');
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const payrollId = this.getAttribute('data-id');
            viewPayslipDetails(payrollId);
        });
    });
    
    // Print payslip buttons
    const printButtons = document.querySelectorAll('.print-slip');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            const payrollId = this.getAttribute('data-id');
            printPayslip(payrollId);
        });
    });
    
    // Toggle payment status buttons
    const toggleButtons = document.querySelectorAll('.toggle-status');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const payrollId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');
            const newStatus = currentStatus === 'paid' ? 'pending' : 'paid';
            
            // Confirm before changing status
            if (confirm(`Are you sure you want to mark this payment as ${newStatus.toUpperCase()}?`)) {
                updatePaymentStatus(payrollId, newStatus, this);
            }
        });
    });
}

/**
 * Update payment status for an employee
 */
function updatePaymentStatus(payrollId, newStatus, buttonElement) {
    // Check if we should first verify that the period is still open
    fetch(`../pages/api/payroll/check_period_status.php?payroll_id=${payrollId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Check if period is closed
            if (data.data.period_status === 'processed') {
                alert('Cannot update payment status: This payroll period is already closed.');
                return;
            }
            
            // If it's open, proceed with the status update
            proceedWithStatusUpdate(payrollId, newStatus, buttonElement);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error checking period status: ' + error);
    });
}

/**
 * Proceed with updating payment status after verification
 */
function proceedWithStatusUpdate(payrollId, newStatus, buttonElement) {
    // Show loading state
    buttonElement.disabled = true;
    buttonElement.innerHTML = 'Updating...';
    
    // Create form data
    const formData = new FormData();
    formData.append('payroll_id', payrollId);
    formData.append('status', newStatus);
    
    // Send request to update status
    fetch('../pages/api/payroll/update_payment_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Refresh the report to show updated status
            getPayrollReport();
            
            // Check if all employees are now paid to update close button state
            checkAllEmployeesPaid();
        } else {
            alert('Error: ' + data.message);
            // Reset button state
            const oldStatus = newStatus === 'paid' ? 'pending' : 'paid';
            const toggleText = oldStatus === 'paid' ? 'Mark Unpaid' : 'Mark Paid';
            const toggleClass = oldStatus === 'paid' ? 'danger' : 'success';
            
            buttonElement.classList.remove('btn-success', 'btn-danger');
            buttonElement.classList.add(`btn-${toggleClass}`);
            buttonElement.innerHTML = toggleText;
            buttonElement.disabled = false;
        }
    })
    .catch(error => {
        alert('Error updating payment status: ' + error);
        buttonElement.disabled = false;
    });
}

/**
 * Check if all employees are paid and update close button accordingly
 */
function checkAllEmployeesPaid() {
    const tableRows = document.querySelectorAll('#payroll-report-body tr');
    let allPaid = true;
    
    tableRows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(7)');
        if (statusCell && !statusCell.textContent.includes('Paid')) {
            allPaid = false;
        }
    });
    
    const closeBtn = document.getElementById('close-payroll-btn');
    if (closeBtn) {
        closeBtn.disabled = !allPaid;
        closeBtn.title = allPaid ? "Close this payroll period" : "All employees must be paid before closing";
    }
}

/**
 * View payslip details
 */
function viewPayslipDetails(payrollId) {
    // Open the payslip details in a modal or redirect to a details page
    fetch(`../pages/api/payroll/get_payslip.php?payroll_id=${payrollId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // For now, we'll just show an alert with some details
                alert(`Payslip details for: ${data.data.full_name}\nPosition: ${data.data.position}\nNet Pay: ${formatCurrency(data.data.net_pay)}`);
                
                // In a real implementation, you would populate a modal with this data
                // or redirect to a detailed view
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error loading payslip details: ' + error);
        });
}

/**
 * Print payslip for an employee
 */
function printPayslip(payrollId) {
    // In a real implementation, you would:
    // 1. Fetch the payslip data 
    // 2. Format it into a printable layout
    // 3. Open a print dialog
    
    // For now, we'll just open a new window with basic info
    fetch(`../pages/api/payroll/get_payslip.php?payroll_id=${payrollId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Create print window content
                const printWindow = window.open('', '_blank');
                const employee = data.data;
                
                printWindow.document.write(`
                    <html>
                    <head>
                        <title>Payslip - ${employee.full_name}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            .payslip { border: 1px solid #ccc; padding: 20px; max-width: 800px; margin: 0 auto; }
                            .header { text-align: center; margin-bottom: 20px; }
                            .info-row { display: flex; margin-bottom: 10px; }
                            .info-row .label { font-weight: bold; width: 200px; }
                            .section { margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; }
                            .total { font-weight: bold; margin-top: 15px; }
                            .footer { margin-top: 30px; text-align: center; font-size: 0.8em; }
                            @media print { .no-print { display: none; } }
                        </style>
                    </head>
                    <body>
                        <div class="no-print" style="margin-bottom: 20px; text-align: center;">
                            <button onclick="window.print()">Print Payslip</button>
                            <button onclick="window.close()">Close</button>
                        </div>
                        
                        <div class="payslip">
                            <div class="header">
                                <h2>Employee Payslip</h2>
                                <p>Pay Period: ${new Date(employee.start_date).toLocaleDateString()} to ${new Date(employee.end_date).toLocaleDateString()}</p>
                            </div>
                            
                            <div class="section">
                                <h3>Employee Information</h3>
                                <div class="info-row">
                                    <div class="label">Name:</div>
                                    <div>${employee.full_name}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Position:</div>
                                    <div>${employee.position}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Employee ID:</div>
                                    <div>${employee.employee_id}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Date Hired:</div>
                                    <div>${new Date(employee.date_hired).toLocaleDateString()}</div>
                                </div>
                            </div>
                            
                            <div class="section">
                                <h3>Earnings</h3>
                                <div class="info-row">
                                    <div class="label">Base Salary (${employee.salary_rate_type}):</div>
                                    <div>${formatCurrency(employee.base_salary)}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Hourly Rate:</div>
                                    <div>${formatCurrency(employee.hourly_rate)}/hour</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Overtime Rate:</div>
                                    <div>${formatCurrency(employee.overtime_rate)}/hour</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Regular Hours:</div>
                                    <div>${parseInt(employee.regular_hours)} hours</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Overtime Hours:</div>
                                    <div>${parseInt(employee.overtime_hours)} hours</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Total Hours:</div>
                                    <div>${parseInt(employee.total_hours)} hours</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Regular Pay:</div>
                                    <div>${formatCurrency(employee.regular_pay)}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Overtime Pay:</div>
                                    <div>${formatCurrency(employee.overtime_pay)}</div>
                                </div>
                                <div class="info-row total">
                                    <div class="label">Gross Pay:</div>
                                    <div>${formatCurrency(employee.gross_pay)}</div>
                                </div>
                            </div>
                            
                            <div class="section">
                                <h3>Deductions</h3>
                                <div class="info-row">
                                    <div class="label">SSS (${employee.sss ? (employee.sss / employee.gross_pay * 100).toFixed(1) : 5}%):</div>
                                    <div>${formatCurrency(employee.sss || 0)}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">PhilHealth (${employee.philhealth ? (employee.philhealth / employee.gross_pay * 100).toFixed(1) : 2.5}%):</div>
                                    <div>${formatCurrency(employee.philhealth || 0)}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Pag-IBIG (${employee.pagibig ? (employee.pagibig / employee.gross_pay * 100).toFixed(1) : 2}%):</div>
                                    <div>${formatCurrency(employee.pagibig || 0)}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Tax (Fixed):</div>
                                    <div>${formatCurrency(employee.tin || 0)}</div>
                                </div>
                                <div class="info-row total">
                                    <div class="label">Total Deductions:</div>
                                    <div>${formatCurrency(employee.deductions)}</div>
                                </div>
                            </div>
                            
                            <div class="section">
                                <div class="info-row total">
                                    <div class="label">Net Pay:</div>
                                    <div>${formatCurrency(employee.net_pay)}</div>
                                </div>
                            </div>
                            
                            <div class="footer">
                                <p>This is a computer-generated document. No signature required.</p>
                                <p>Generated on: ${new Date().toLocaleString()}</p>
                            </div>
                        </div>
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error preparing payslip: ' + error);
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

/**
 * Print the payroll report table (without action buttons)
 */
function printPayrollReport() {
    const payPeriodSelect = document.getElementById('pay_period_select');
    const periodText = payPeriodSelect.options[payPeriodSelect.selectedIndex].text;
    
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the table data
    const table = document.getElementById('payroll-report-table');
    
    // Create print content
    printWindow.document.write(`
        <html>
        <head>
            <title>Payroll Report - ${periodText}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h2, h3 { text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .badge {
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-weight: bold;
                }
                .badge-success { background-color: #d4edda; color: #155724; }
                .badge-warning { background-color: #fff3cd; color: #856404; }
                @media print {
                    h2, h3 { margin-top: 0; }
                }
                .no-print { display: none; }
            </style>
        </head>
        <body>
            <div class="no-print" style="margin-bottom: 20px; text-align: center;">
                <button onclick="window.print()">Print Report</button>
                <button onclick="window.close()">Close</button>
            </div>
            
            <h2>Payroll Report</h2>
            <h3>${periodText}</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Total Hours</th>
                        <th>Gross Pay</th>
                        <th>Deductions</th>
                        <th>Net Pay</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    `);
    
    // Add table rows without action buttons
    const rows = table.querySelectorAll('tbody tr');
    let totalGrossPay = 0;
    let totalDeductions = 0;
    let totalNetPay = 0;
    
    rows.forEach(row => {
        // Skip empty rows or messages
        if (row.cells.length <= 1) return;
        
        const cells = row.cells;
        if (cells.length >= 7) {
            // Add cells excluding the action buttons cell
            printWindow.document.write('<tr>');
            for (let i = 0; i < 7; i++) {
                printWindow.document.write(cells[i].outerHTML);
            }
            printWindow.document.write('</tr>');
            
            // Calculate totals
            if (cells[3].textContent) {
                const grossPay = parseFloat(cells[3].textContent.replace('₱', '').replace(/,/g, '').trim());
                if (!isNaN(grossPay)) totalGrossPay += grossPay;
            }
            
            if (cells[4].textContent) {
                const deductions = parseFloat(cells[4].textContent.replace('₱', '').replace(/,/g, '').trim());
                if (!isNaN(deductions)) totalDeductions += deductions;
            }
            
            if (cells[5].textContent) {
                const netPay = parseFloat(cells[5].textContent.replace('₱', '').replace(/,/g, '').trim());
                if (!isNaN(netPay)) totalNetPay += netPay;
            }
        }
    });
    
    // Add totals row
    printWindow.document.write(`
                    <tr style="font-weight: bold; background-color: #f8f9fc;">
                        <td colspan="3" class="text-right">TOTALS:</td>
                        <td>${formatCurrency(totalGrossPay)}</td>
                        <td>${formatCurrency(totalDeductions)}</td>
                        <td>${formatCurrency(totalNetPay)}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            
            <div style="margin-top: 50px; display: flex; justify-content: space-between;">
                <div style="width: 30%;">
                    <p style="margin-bottom: 40px;">Prepared by:</p>
                    <div style="border-top: 1px solid black; padding-top: 5px;">
                        <p style="text-align: center;">HR Manager</p>
                    </div>
                </div>
                <div style="width: 30%;">
                    <p style="margin-bottom: 40px;">Verified by:</p>
                    <div style="border-top: 1px solid black; padding-top: 5px;">
                        <p style="text-align: center;">Finance Manager</p>
                    </div>
                </div>
                <div style="width: 30%;">
                    <p style="margin-bottom: 40px;">Approved by:</p>
                    <div style="border-top: 1px solid black; padding-top: 5px;">
                        <p style="text-align: center;">General Manager</p>
                    </div>
                </div>
            </div>
            
            <p style="text-align: center; margin-top: 30px; font-size: 0.8em;">
                Generated on: ${new Date().toLocaleString()}
            </p>
        </body>
        </html>
    `);
    
    printWindow.document.close();
}

/**
 * Export payroll report to Excel
 */
function exportPayrollToExcel() {
    const payPeriodSelect = document.getElementById('pay_period_select');
    const payPeriodId = payPeriodSelect.value;
    
    if (!payPeriodId) {
        alert('Please select a pay period first');
        return;
    }
    
    // Redirect to the export endpoint
    window.location.href = `../pages/api/payroll/export_excel.php?pay_period_id=${payPeriodId}`;
}

/**
 * Close the current payroll period
 */
function closePayroll() {
    const payPeriodSelect = document.getElementById('pay_period_select');
    const payPeriodId = payPeriodSelect.value;
    
    if (!payPeriodId) {
        alert('Please select a pay period to close');
        return;
    }
    
    // Create form data
    const formData = new FormData();
    formData.append('pay_period_id', payPeriodId);
    
    // Send request to close payroll
    fetch('../pages/api/payroll/close_payroll.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const closePayrollModal = bootstrap.Modal.getInstance(document.getElementById('closePayrollModal'));
            closePayrollModal.hide();
            alert('Payroll period has been successfully closed.');
            // Reload the page to update the pay periods dropdown
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error closing payroll: ' + error);
    });
} 