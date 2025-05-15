<?php
declare(strict_types=1);
require_once __DIR__ . '/../../database/config.php';

include_once '../includes/head.php'; 
?>

<body>
    <!-- Include Sidebar -->
    <?php include_once '../includes/sidebar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Store Credits Management</h1>
            <div class="d-flex justify-content-between">
                <div class="search-wrapper">
                    <input type="text" id="credit-search" class="form-control" placeholder="Search by code, customer...">
                </div>
                <div class="filter-wrapper">
                    <select id="status-filter" class="form-select">
                        <option value="all">All Credits</option>
                        <option value="1">Active</option>
                        <option value="0">Expired/Used</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="creditsTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Credit Code</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Remaining</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Credit Details Modal -->
    <div class="modal fade" id="creditDetailsModal" tabindex="-1" aria-labelledby="creditDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="creditDetailsModalLabel">Credit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Credit Information</h6>
                            <p><strong>Credit Code:</strong> <span id="detail-credit-code"></span></p>
                            <p><strong>Issue Date:</strong> <span id="detail-issue-date"></span></p>
                            <p><strong>Expiry Date:</strong> <span id="detail-expiry-date"></span></p>
                            <p><strong>Status:</strong> <span id="detail-status"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Customer Information</h6>
                            <p><strong>Name:</strong> <span id="detail-customer-name"></span></p>
                            <p><strong>Contact:</strong> <span id="detail-customer-contact"></span></p>
                            <p><strong>Transaction ID:</strong> <span id="detail-transaction-id"></span></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6>Financial Details</h6>
                            <div class="d-flex justify-content-between">
                                <p><strong>Original Amount:</strong> <span id="detail-credit-amount"></span></p>
                                <p><strong>Used Amount:</strong> <span id="detail-used-amount"></span></p>
                                <p><strong>Remaining Balance:</strong> <span id="detail-remaining-amount"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <h6>Returned Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="returned-items-list">
                                <!-- Items will be loaded via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="usage-history-section" class="mt-4">
                        <h6>Usage History</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Amount Used</th>
                                        <th>Remaining After</th>
                                    </tr>
                                </thead>
                                <tbody id="usage-history-list">
                                    <!-- History will be loaded via JavaScript -->
                                    <tr>
                                        <td colspan="4" class="text-center">No usage history found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="print-credit-link" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> Print Credit Memo
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Use Credit Modal -->
    <div class="modal fade" id="useCreditModal" tabindex="-1" aria-labelledby="useCreditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="useCreditModalLabel">Use Store Credit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="use-credit-form">
                        <input type="hidden" id="use-credit-code" name="credit_code">
                        
                        <div class="mb-3">
                            <label for="use-transaction-id" class="form-label">Transaction ID</label>
                            <input type="text" class="form-control" id="use-transaction-id" name="transaction_id" required>
                            <div class="form-text">Enter the ID of the transaction where this credit is being applied</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="use-credit-amount" class="form-label">Amount to Use</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="use-credit-amount" name="amount_used" step="0.01" min="0.01" required>
                            </div>
                            <div class="form-text">Maximum available: ₱<span id="use-max-amount">0.00</span></div>
                        </div>
                        
                        <div class="alert alert-info">
                            <small>Note: If the full amount is used, the credit will be automatically deactivated.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submit-use-credit">Apply Credit</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable
            const creditsTable = $('#creditsTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[4, 'desc']], // Sort by issue date by default
                columns: [
                    { data: 'credit_code' },
                    { data: 'customer_name' },
                    { data: 'amount' },
                    { data: 'remaining' },
                    { data: 'issue_date' },
                    { data: 'expiry_date' },
                    { data: 'status' },
                    { data: 'actions' }
                ]
            });
            
            // Load store credits data
            loadStoreCredits();
            
            // Search functionality
            $('#credit-search').on('keyup', function() {
                creditsTable.search(this.value).draw();
            });
            
            // Status filter
            $('#status-filter').on('change', function() {
                const value = $(this).val();
                if (value === 'all') {
                    creditsTable.column(6).search('').draw();
                } else {
                    creditsTable.column(6).search(value === '1' ? 'Active' : 'Inactive').draw();
                }
            });
            
            // Handle view details click
            $(document).on('click', '.btn-view-credit', function() {
                const creditCode = $(this).data('credit-code');
                loadCreditDetails(creditCode);
            });
            
            // Handle use credit click
            $(document).on('click', '.btn-use-credit', function() {
                const creditCode = $(this).data('credit-code');
                const remaining = parseFloat($(this).data('remaining'));
                
                $('#use-credit-code').val(creditCode);
                $('#use-max-amount').text(remaining.toFixed(2));
                $('#use-credit-amount').attr('max', remaining);
                $('#useCreditModal').modal('show');
            });
            
            // Handle use credit form submission
            $('#submit-use-credit').on('click', function() {
                const form = $('#use-credit-form')[0];
                
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                
                const formData = new FormData(form);
                
                // Validate amount
                const amountUsed = parseFloat(formData.get('amount_used'));
                const maxAmount = parseFloat($('#use-max-amount').text());
                
                if (amountUsed <= 0) {
                    alert('Amount must be greater than zero');
                    return;
                }
                
                if (amountUsed > maxAmount) {
                    alert('Amount exceeds available credit balance');
                    return;
                }
                
                // Submit form via AJAX
                fetch('api/use_store_credit.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message);
                        } else {
                            alert('Success: ' + data.message);
                        }
                        
                        // Close modal and reload credits
                        $('#useCreditModal').modal('hide');
                        loadStoreCredits();
                    } else {
                        // Show error message
                        if (typeof toastr !== 'undefined') {
                            toastr.error(data.message);
                        } else {
                            alert('Error: ' + data.message);
                        }
                    }
                })
                .catch(error => {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Error: ' + error.message);
                    } else {
                        alert('Error: ' + error.message);
                    }
                });
            });
            
            // Function to load store credits
            function loadStoreCredits() {
                fetch('api/get_store_credits.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCreditsTable(data.credits);
                        } else {
                            showError(data.message || 'Failed to load store credits');
                        }
                    })
                    .catch(error => {
                        showError('Error loading store credits: ' + error.message);
                    });
            }
            
            // Function to update the credits table
            function updateCreditsTable(credits) {
                creditsTable.clear();
                
                credits.forEach(credit => {
                    // Calculate remaining amount
                    const remaining = parseFloat(credit.credit_amount) - parseFloat(credit.used_amount);
                    const remainingFormatted = remaining.toFixed(2);
                    const isActive = parseInt(credit.is_active) === 1 && remaining > 0;
                    
                    creditsTable.row.add({
                        'credit_code': credit.credit_code,
                        'customer_name': credit.customer_name || 'N/A',
                        'amount': '₱' + parseFloat(credit.credit_amount).toFixed(2),
                        'remaining': '₱' + remainingFormatted,
                        'issue_date': formatDate(credit.issue_date),
                        'expiry_date': formatDate(credit.expiry_date),
                        'status': isActive ? 
                            '<span class="badge bg-success">Active</span>' : 
                            '<span class="badge bg-danger">Inactive</span>',
                        'actions': `
                            <button class="btn btn-sm btn-outline-primary btn-view-credit" data-credit-code="${credit.credit_code}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <a href="print_credit_memo.php?code=${credit.credit_code}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                            <button class="btn btn-sm btn-outline-success btn-use-credit ${!isActive ? 'disabled' : ''}" 
                                data-credit-code="${credit.credit_code}" 
                                data-remaining="${remaining}" 
                                ${!isActive ? 'disabled' : ''}>
                                <i class="fas fa-cash-register"></i> Use
                            </button>
                        `
                    });
                });
                
                creditsTable.draw();
            }
            
            // Function to load credit details
            function loadCreditDetails(creditCode) {
                fetch(`api/get_credit_memo.php?credit_code=${creditCode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayCreditDetails(data.data);
                            $('#creditDetailsModal').modal('show');
                        } else {
                            showError(data.message || 'Failed to load credit details');
                        }
                    })
                    .catch(error => {
                        showError('Error loading credit details: ' + error.message);
                    });
            }
            
            // Function to display credit details in the modal
            function displayCreditDetails(credit) {
                // Set credit information
                $('#detail-credit-code').text(credit.credit_code);
                $('#detail-issue-date').text(formatDate(credit.issue_date));
                $('#detail-expiry-date').text(formatDate(credit.expiry_date));
                $('#detail-status').html(credit.is_active ? 
                    '<span class="badge bg-success">Active</span>' : 
                    '<span class="badge bg-danger">Inactive</span>'
                );
                
                // Set customer information
                $('#detail-customer-name').text(credit.customer_name || 'N/A');
                $('#detail-customer-contact').text(credit.contact_number || 'N/A');
                $('#detail-transaction-id').text(credit.original_transaction_id);
                
                // Set financial details
                $('#detail-credit-amount').text('₱' + parseFloat(credit.credit_amount).toFixed(2));
                $('#detail-used-amount').text('₱' + parseFloat(credit.used_amount).toFixed(2));
                $('#detail-remaining-amount').text('₱' + parseFloat(credit.remaining_amount).toFixed(2));
                
                // Set returned items
                const itemsList = $('#returned-items-list');
                itemsList.empty();
                
                credit.items.forEach(item => {
                    itemsList.append(`
                        <tr>
                            <td>${item.sku}</td>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>₱${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td>₱${parseFloat(item.subtotal).toFixed(2)}</td>
                        </tr>
                    `);
                });
                
                // Set usage history
                const usageHistoryList = $('#usage-history-list');
                usageHistoryList.empty();
                
                if (credit.usage_history && credit.usage_history.length > 0) {
                    credit.usage_history.forEach(usage => {
                        usageHistoryList.append(`
                            <tr>
                                <td>${formatDateTime(usage.usage_date)}</td>
                                <td>${usage.transaction_id}</td>
                                <td>₱${parseFloat(usage.amount_used).toFixed(2)}</td>
                                <td>₱${parseFloat(usage.remaining_after).toFixed(2)}</td>
                            </tr>
                        `);
                    });
                } else {
                    usageHistoryList.append(`
                        <tr>
                            <td colspan="4" class="text-center">No usage history found</td>
                        </tr>
                    `);
                }
                
                // Update print link
                $('#print-credit-link').attr('href', `print_credit_memo.php?code=${credit.credit_code}`);
            }
            
            // Helper function to format dates
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
            
            // Helper function to format date and time
            function formatDateTime(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) + ' ' + date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            
            // Function to show error messages
            function showError(message) {
                if (typeof toastr !== 'undefined') {
                    toastr.error(message, 'Error');
                } else {
                    alert('Error: ' + message);
                }
            }
        });
    </script>
</body>
</html> 