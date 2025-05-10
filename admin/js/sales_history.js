/**
 * Sales History JavaScript
 * Handles DataTables initialization and date filtering
 */

let salesTable;

document.addEventListener('DOMContentLoaded', function () {
    initSalesTable();
    setupEventListeners();

    // Initial load of data
    loadSalesData();
});

/**
 * Initialize the sales DataTable with configuration
 */
function initSalesTable() {
    // DataTable initialization with configuration
    salesTable = $('#salesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']], // Sort by date column (index 1) in descending order
        language: {
            search: "Search records:",
            lengthMenu: "Show _MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        // Add export functionality
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Export to Excel',
                className: 'btn btn-sm btn-success me-2',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Export all visible columns
                },
                title: 'Sales History Report'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-sm btn-info',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Print all visible columns
                },
                title: 'Sales History Report',
                customize: function (win) {
                    // Add custom styling to printed report
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');

                    // Add date range to the printed report
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    const dateStr = `<div style="text-align:center; margin-bottom: 15px;">
                        <p>Date Range: ${startDate} to ${endDate}</p>
                    </div>`;
                    $(win.document.body).find('h1').after(dateStr);
                }
            }
        ]
    });
}

/**
 * Set up event listeners for user interactions
 */
function setupEventListeners() {
    // Form submission - use AJAX instead of page reload
    $('#date-filter-form').on('submit', function (e) {
        e.preventDefault();
        loadSalesData();
    });

    // Reset button functionality
    $('#reset-filter').on('click', function () {
        // Reset to default dates (last 30 days)
        const today = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(today.getDate() - 30);

        // Format dates for input fields (YYYY-MM-DD)
        document.getElementById('end_date').value = formatDate(today);
        document.getElementById('start_date').value = formatDate(thirtyDaysAgo);

        // Reset cashier filter if it exists
        if (document.getElementById('cashier-filter')) {
            document.getElementById('cashier-filter').value = '';
        }

        // Load data with new date range
        loadSalesData();
    });

    // Quick filter buttons
    setupQuickFilters();

    // Cashier filter change
    $(document).on('change', '#cashier-filter', function () {
        loadSalesData();
    });
}

/**
 * Load sales data using the API endpoint
 * @param {Object} additionalFilters - Optional additional filter parameters
 */
function loadSalesData(additionalFilters = {}) {
    // Get filter parameters
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const cashierName = document.getElementById('cashier-filter') ?
        document.getElementById('cashier-filter').value : '';

    // Combine with any additional filters provided
    const filters = {
        start_date: startDate,
        end_date: endDate,
        cashier_name: cashierName,
        ...additionalFilters
    };

    // Build query string
    const queryString = Object.keys(filters)
        .filter(key => filters[key] !== null && filters[key] !== undefined && filters[key] !== '')
        .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`)
        .join('&');

    // Fetch data from API
    fetch(`api/sales_history.php?${queryString}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateSalesTable(data.data);
                updateSummarySection(data.summary);
                updateTopProducts(data.topProducts);

                // Debug the cashiers array
                console.log('Cashiers from API:', data.cashiers);

                // Update cashier filter
                if (data.cashiers && data.cashiers.length > 0) {
                    updateCashierFilter(data.cashiers);
                }
            } else {
                showError(data.message || 'Error loading sales data');
            }
        })
        .catch(error => {
            showError('Failed to load sales data: ' + error.message);
        });
}

/**
 * Create or update cashier filter dropdown
 * @param {Array} cashiers - List of cashier names
 */
function updateCashierFilter(cashiers) {
    // Check if filter container exists
    const filterContainer = document.querySelector('.quick-filters');
    if (!filterContainer) return;

    // Check if filter already exists
    let cashierFilter = document.getElementById('cashier-filter');
    const currentValue = cashierFilter ? cashierFilter.value : '';
    
    // If filter doesn't exist, create the container
    if (!cashierFilter) {
        const cashierFilterDiv = document.createElement('div');
        cashierFilterDiv.className = 'mt-2';
        cashierFilterDiv.id = 'cashier-filter-container';
        filterContainer.appendChild(cashierFilterDiv);
    }
    
    // Get or create the container
    const filterContainerDiv = document.getElementById('cashier-filter-container');
    
    // Create the select dropdown HTML with all cashiers
    const selectHtml = `
        <div class="input-group">
            <span class="input-group-text">Cashier:</span>
            <select id="cashier-filter" class="form-select">
                <option value="">All Cashiers</option>
                ${cashiers.map(cashier => `<option value="${escapeHtml(cashier)}" ${currentValue === cashier ? 'selected' : ''}>${escapeHtml(cashier)}</option>`).join('')}
            </select>
        </div>
    `;
    
    // Update the container content
    filterContainerDiv.innerHTML = selectHtml;
    
    // Re-attach event listener
    document.getElementById('cashier-filter').addEventListener('change', function() {
        loadSalesData();
    });
}

/**
 * Update the sales table with new data
 * @param {Array} salesData - Array of sales records
 */
function updateSalesTable(salesData) {
    // Clear existing table data
    salesTable.clear();

    // Add new data
    if (salesData.length > 0) {
        salesData.forEach(sale => {
            salesTable.row.add([
                sale.sku,
                new Date(sale.sale_timestamp).toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }),
                sale.cashier_name,
                sale.product_name,
                sale.quantity_sold,
                '₱' + formatNumber(sale.sale_price),
                sale.discount_applied + '%',
                '₱' + formatNumber(sale.sale_price * sale.quantity_sold * (1 - sale.discount_applied / 100))
            ]);
        });
    }

    // Redraw the table
    salesTable.draw();
}

/**
 * Update the summary section with new data
 * @param {Object} summary - Summary data object
 */
function updateSummarySection(summary) {
    // Update summary cards with thousand separators
    document.getElementById('total-transactions').textContent = formatNumber(summary.transactions || 0).split('.')[0];
    document.getElementById('total-items').textContent = formatNumber(summary.items || 0).split('.')[0];
    document.getElementById('average-sale').textContent = '₱' + formatNumber(summary.average || 0);
    document.getElementById('highest-sale').textContent = '₱' + formatNumber(summary.highest || 0);

    // Update total sales in the table footer
    document.getElementById('totalSales').textContent = '₱' + formatNumber(parseFloat(summary.total) || 0);
}

/**
 * Update the top products section with new data
 * @param {Array} topProducts - Array of top selling products
 */
function updateTopProducts(topProducts) {
    const topProductsList = document.getElementById('top-products-list');

    // Clear existing list
    topProductsList.innerHTML = '';

    // Add new items or show message if empty
    if (topProducts.length > 0) {
        topProducts.forEach(product => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `
                ${escapeHtml(product.product_name)}
                <span class="d-flex gap-3">
                    <span class="badge bg-primary rounded-pill">${formatNumber(product.quantity).split('.')[0]} units</span>
                    <span class="badge bg-success rounded-pill">₱${formatNumber(product.sales)}</span>
                    <button class="btn btn-sm btn-outline-primary filter-by-product" data-product-id="${product.product_id}">
                        <i class="fas fa-filter"></i>
                    </button>
                </span>
            `;
            topProductsList.appendChild(li);
        });
    } else {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.textContent = 'No data available for the selected period';
        topProductsList.appendChild(li);
    }
}

/**
 * Set up quick filter buttons for common date ranges
 */
function setupQuickFilters() {
    // Today
    $('#filter-today').on('click', function () {
        const today = new Date();
        const dateStr = formatDate(today);
        document.getElementById('start_date').value = dateStr;
        document.getElementById('end_date').value = dateStr;
        loadSalesData();
    });

    // This week
    $('#filter-week').on('click', function () {
        const today = new Date();
        const firstDayOfWeek = new Date(today);
        const day = today.getDay(); // 0 is Sunday, 1 is Monday, etc.
        const diff = today.getDate() - day + (day === 0 ? -6 : 1); // Adjust for Sunday
        firstDayOfWeek.setDate(diff);

        document.getElementById('start_date').value = formatDate(firstDayOfWeek);
        document.getElementById('end_date').value = formatDate(today);
        loadSalesData();
    });

    // This month
    $('#filter-month').on('click', function () {
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

        document.getElementById('start_date').value = formatDate(firstDayOfMonth);
        document.getElementById('end_date').value = formatDate(today);
        loadSalesData();
    });

    // Last 3 months
    $('#filter-quarter').on('click', function () {
        const today = new Date();
        const threeMonthsAgo = new Date(today);
        threeMonthsAgo.setMonth(today.getMonth() - 3);

        document.getElementById('start_date').value = formatDate(threeMonthsAgo);
        document.getElementById('end_date').value = formatDate(today);
        loadSalesData();
    });

    // Filter by product if clicked in top products list
    $(document).on('click', '.filter-by-product', function () {
        const productId = $(this).data('product-id');
        loadSalesData({ product_id: productId });
    });
}

/**
 * Show error message using toast notification
 * @param {string} message - Error message to display
 */
function showError(message) {
    if (typeof toastr !== 'undefined') {
        toastr.error(message, 'Error');
    } else {
        alert('Error: ' + message);
    }
}

/**
 * Format number with thousand separators and two decimal places
 * @param {number} num - Number to format
 * @return {string} Formatted number string
 */
function formatNumber(num) {
    return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

/**
 * Format date object to YYYY-MM-DD string for date inputs
 * @param {Date} date - Date object to format
 * @return {string} Formatted date string (YYYY-MM-DD)
 */
function formatDate(date) {
    const year = date.getFullYear();
    // Add leading zero if month/day is less than 10
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/**
 * Escape HTML to prevent XSS
 * @param {string} unsafe - Unsafe string that might contain HTML
 * @return {string} Escaped safe string
 */
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
