// Sample data for demonstration
const sampleProducts = [
    {
        id: 1,
        sku: 'HW001',
        itemName: 'Hammer',
        category: 'Hand Tools',
        brand: 'Stanley',
        stockLevel: 50,
        unit: 'pcs',
        costPrice: 250.00,
        sellingPrice: 350.00,
        lowStockThreshold: 10,
        description: 'Steel claw hammer',
        supplier: 'Hardware Supplies Co.'
    },
    {
        id: 2,
        sku: 'HW002',
        itemName: 'Screwdriver Set',
        category: 'Hand Tools',
        brand: 'DeWalt',
        stockLevel: 5,
        unit: 'sets',
        costPrice: 450.00,
        sellingPrice: 650.00,
        lowStockThreshold: 8,
        description: '6-piece precision set',
        supplier: 'Tools Direct'
    },
    {
        id: 3,
        sku: 'HW003',
        itemName: 'Paint Brush',
        category: 'Painting',
        brand: 'Purdy',
        stockLevel: 0,
        unit: 'pcs',
        costPrice: 120.00,
        sellingPrice: 180.00,
        lowStockThreshold: 15,
        description: '4-inch synthetic brush',
        supplier: 'Paint Supplies Inc.'
    }
];

// Sample categories for demonstration
const sampleCategories = [
    { id: 1, name: 'Hand Tools' },
    { id: 2, name: 'Power Tools' },
    { id: 3, name: 'Painting' },
    { id: 4, name: 'Plumbing' },
    { id: 5, name: 'Electrical' }
];

// Initialize DataTable with buttons
const productsTable = $('#productsTable').DataTable({
    responsive: true,
    dom: 'Bfrtlip',
    buttons: [
        {
            extend: 'print',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(:last-child)' // Exclude actions column from print
            }
        },
        {
            extend: 'excel',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(:last-child)' // Exclude actions column from export
            }
        }
    ],
    data: sampleProducts,
    processing: true,
    language: {
        processing: "Loading..."
    },
    pageLength: 10,
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
    ],
    order: [[1, "asc"]],
    columns: [
        { data: 'sku' },
        { data: 'itemName' },
        { data: 'category' },
        { data: 'brand' },
        {
            data: 'stockLevel',
            render: function (data, type, row) {
                if (type === 'display') {
                    let levelClass = 'normal';
                    if (data <= 0) {
                        levelClass = 'out';
                    } else if (data <= row.lowStockThreshold) {
                        levelClass = 'low';
                    }
                    return `<span class="stock-level ${levelClass}">${data} ${row.unit}</span>`;
                }
                return data;
            }
        },
        { data: 'unit' },
        {
            data: 'costPrice',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    return '₱' + parseFloat(data).toFixed(2);
                }
                return data;
            }
        },
        {
            data: 'sellingPrice',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    return '₱' + parseFloat(data).toFixed(2);
                }
                return data;
            }
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                    <div class="action-buttons">
                        <button class="btn btn-info btn-sm" onclick="editProduct(${row.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="adjustStock(${row.id})" title="Adjust Stock">
                            <i class="fas fa-boxes"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${row.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            }
        }
    ]
});

// Load and populate categories
function loadCategories() {
    const categorySelect = $('#category');
    const categoryFilter = $('#categoryFilter');

    categorySelect.empty();
    categoryFilter.empty();

    categoryFilter.append('<option value="">All Categories</option>');

    sampleCategories.forEach(category => {
        categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
        categoryFilter.append(`<option value="${category.id}">${category.name}</option>`);
    });
}

// Handle product form submission
$('#saveProduct').click(function () {
    if (!$('#productForm')[0].checkValidity()) {
        $('#productForm')[0].reportValidity();
        return;
    }

    const productData = {
        id: sampleProducts.length + 1,
        itemName: $('#itemName').val(),
        sku: $('#sku').val(),
        category: $('#category option:selected').text(),
        brand: $('#brand').val(),
        description: $('#description').val(),
        unit: $('#unit').val(),
        costPrice: parseFloat($('#costPrice').val()),
        sellingPrice: parseFloat($('#sellingPrice').val()),
        supplier: $('#supplier').val(),
        stockLevel: parseInt($('#initialStock').val()),
        lowStockThreshold: 10
    };

    sampleProducts.push(productData);
    $('#addProductModal').modal('hide');
    productsTable.clear().rows.add(sampleProducts).draw();
    updateSummaryCards();
});

// Handle stock adjustment form submission
$('#saveAdjustment').click(function () {
    if (!$('#stockAdjustmentForm')[0].checkValidity()) {
        $('#stockAdjustmentForm')[0].reportValidity();
        return;
    }

    const productId = parseInt($('#adjustmentProductId').val());
    const adjustmentType = $('#adjustmentType').val();
    const quantity = parseInt($('#adjustmentQuantity').val());

    const product = sampleProducts.find(p => p.id === productId);
    if (product) {
        if (adjustmentType === 'add') {
            product.stockLevel += quantity;
        } else {
            product.stockLevel = Math.max(0, product.stockLevel - quantity);
        }

        $('#stockAdjustmentModal').modal('hide');
        productsTable.clear().rows.add(sampleProducts).draw();
        updateSummaryCards();
    }
});

// Filter handling
$('#applyFilters').click(function () {
    const categoryFilter = $('#categoryFilter').val();
    const brandFilter = $('#brandFilter').val();
    const stockFilter = $('#stockFilter').val();

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            const product = productsTable.row(dataIndex).data();

            const categoryMatch = !categoryFilter ||
                product.category === $('#categoryFilter option:selected').text();

            const brandMatch = !brandFilter ||
                product.brand.toLowerCase().includes(brandFilter.toLowerCase());

            const stockMatch = !stockFilter ||
                (stockFilter === 'low' && product.stockLevel <= product.lowStockThreshold && product.stockLevel > 0) ||
                (stockFilter === 'out' && product.stockLevel <= 0) ||
                (stockFilter === 'normal' && product.stockLevel > product.lowStockThreshold);

            return categoryMatch && brandMatch && stockMatch;
        }
    );

    productsTable.draw();

    // Clear custom filter
    $.fn.dataTable.ext.search.pop();
});

// Update summary cards
function updateSummaryCards() {
    const totalProducts = sampleProducts.length;
    const lowStockItems = sampleProducts.filter(p => p.stockLevel <= p.lowStockThreshold && p.stockLevel > 0).length;
    const outOfStock = sampleProducts.filter(p => p.stockLevel <= 0).length;
    const totalValue = sampleProducts.reduce((sum, p) => sum + (p.costPrice * p.stockLevel), 0);

    $('#totalProducts').text(totalProducts);
    $('#lowStockItems').text(lowStockItems);
    $('#outOfStock').text(outOfStock);
    $('#totalValue').text('₱' + totalValue.toFixed(2));
}

// Delete product
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        const index = sampleProducts.findIndex(p => p.id === productId);
        if (index !== -1) {
            sampleProducts.splice(index, 1);
            productsTable.clear().rows.add(sampleProducts).draw();
            updateSummaryCards();
        }
    }
}

// Edit product
function editProduct(productId) {
    const product = sampleProducts.find(p => p.id === productId);
    if (product) {
        $('#itemName').val(product.itemName);
        $('#sku').val(product.sku);
        $('#category').val(sampleCategories.find(c => c.name === product.category)?.id);
        $('#brand').val(product.brand);
        $('#description').val(product.description);
        $('#unit').val(product.unit);
        $('#costPrice').val(product.costPrice);
        $('#sellingPrice').val(product.sellingPrice);
        $('#supplier').val(product.supplier);
        $('#initialStock').val(product.stockLevel);
        $('#addProductModal').modal('show');
    }
}

// Adjust stock
function adjustStock(productId) {
    $('#adjustmentProductId').val(productId);
    $('#stockAdjustmentModal').modal('show');
}

// Initialize page
$(document).ready(function () {
    loadCategories();
    updateSummaryCards();
});