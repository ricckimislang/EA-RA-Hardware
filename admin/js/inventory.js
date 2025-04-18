// Initialize with null to avoid undefined errors
let productsTable = null;

function initProductsTable() {
  if ($.fn.DataTable.isDataTable("#productsTable")) {
    $("#productsTable").DataTable().destroy();
    $("#productsTable tbody").empty();
  }

  productsTable = $("#productsTable").DataTable({
    processing: true,
    ajax: {
      url: "api/get_inventory.php",
      dataSrc: function (json) {
        console.log("Received JSON:", json);

        if (!json || !json.data) {
          console.error("Invalid API response", json);
          return [];
        }

        try {
          updateSummaryCards(json.data.summary);
          loadCategories(json.data.categories);
          loadBrands(json.data.brands);
        } catch (e) {
          console.error("Error during post-processing:", e);
        }

        if (!Array.isArray(json.data.products)) {
          console.error("Products data is not an array", json.data.products);
          return [];
        }

        return json.data.products;
      },
      error: function (xhr, error, thrown) {
        if (error === "abort" || thrown === "abort") return;

        if (xhr.status === 0) {
          console.error("Network error");
          alert("Unable to connect to server. Check your internet connection.");
        } else {
          console.error("AJAX Error", {
            status: xhr.status,
            responseText: xhr.responseText,
            error,
            thrown,
          });
          alert("Error loading data. Check the console.");
        }

        return [];
      },
    },
    responsive: true,
    dom: "Bfrtlip",
    buttons: [
      {
        extend: "print",
        className: "btn btn-primary",
        exportOptions: { columns: ":not(:last-child)" },
      },
      {
        extend: "excel",
        className: "btn btn-primary",
        exportOptions: { columns: ":not(:last-child)" },
      },
    ],
    language: {
      processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
      emptyTable: "No inventory data available",
      zeroRecords: "No matching products found",
      info: "Showing _START_ to _END_ of _TOTAL_ products",
      infoEmpty: "Showing 0 products",
      infoFiltered: "(filtered from _MAX_ total products)",
    },
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    order: [[1, "asc"]],
    columns: [
      { data: "sku" },
      { data: "itemName" },
      { data: "category" },
      { data: "brand" },
      {
        data: "stockLevel",
        render: function (data, type, row) {
          const stock = parseInt(data);
          const threshold = parseInt(row.lowStockThreshold);
          let levelClass = "normal";

          if (type === "display") {
            if (!isNaN(stock) && stock <= 0) levelClass = "out";
            else if (!isNaN(stock) && !isNaN(threshold) && stock <= threshold)
              levelClass = "low";

            const displayStock = isNaN(stock) ? "N/A" : stock;
            const displayUnit = isNaN(stock) ? "" : row.unit;
            return `<span class="stock-level ${levelClass}">${displayStock} ${displayUnit}</span>`;
          }

          return data;
        },
      },
      { data: "unit" },
      {
        data: "costPrice",
        render: function (data, type) {
          const price = parseFloat(data);
          if ((type === "display" || type === "filter") && !isNaN(price)) {
            return "₱" + price.toFixed(2);
          }
          return data;
        },
      },
      {
        data: "sellingPrice",
        render: function (data, type) {
          const price = parseFloat(data);
          if ((type === "display" || type === "filter") && !isNaN(price)) {
            return "₱" + price.toFixed(2);
          }
          return data;
        },
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          if (!row?.id) return "";
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
        },
      },
    ],
    drawCallback: function () {
      console.log("Table draw complete.");
    },
    initComplete: function (settings, json) {
      console.log("Table init complete", json);
      if (!json?.data?.products?.length) {
        console.warn("No products received on init.");
      }
    },
  });
}

// Load and populate categories
function loadCategories(categories) {
  const categorySelect = $("#category");
  const editCategory = $("#editCategory");
  const categoryFilter = $("#categoryFilter");

  categorySelect.empty();
  editCategory.empty();
  categoryFilter.empty();

  categoryFilter.append('<option value="">All Categories</option>');

  categories.forEach((category) => {
    categorySelect.append(
      `<option value="${category.category_id}">${category.name}</option>`
    );
    categoryFilter.append(
      `<option value="${category.category_id}">${category.name}</option>`
    );
    editCategory.append(
      `<option value="${category.category_id}">${category.name}</option>`
    );
  });
}

// Filter handling
$("#applyFilters").click(function () {
  const categoryFilter = $("#categoryFilter").val();
  const brandFilter = $("#brandFilter").val();
  const stockFilter = $("#stockFilter").val();

  // Use client-side filtering
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const product = productsTable.row(dataIndex).data();

    if (!product) return false;

    // Category matching - check if no filter or if category ID matches
    const categoryMatch =
      !categoryFilter || product.categoryId == categoryFilter; // Use == for type coercion

    // Brand matching - check if no filter or if brand ID matches
    const brandMatch = !brandFilter || product.brandId == brandFilter; // Use == for type coercion

    // Stock level matching - unchanged
    const stockMatch =
      !stockFilter ||
      (stockFilter === "low" &&
        product.stockLevel <= product.lowStockThreshold &&
        product.stockLevel > 0) ||
      (stockFilter === "out" && product.stockLevel <= 0) ||
      (stockFilter === "normal" &&
        product.stockLevel > product.lowStockThreshold);

    return categoryMatch && brandMatch && stockMatch;
  });

  productsTable.draw();

  // Clear custom filter
  $.fn.dataTable.ext.search.pop();
});

// Update summary cards with data from API
function updateSummaryCards(summary) {
  if (!summary) return;

  $("#totalProducts").text(summary.total_products || 0);
  $("#lowStockItems").text(summary.low_stock || 0);
  $("#outOfStock").text(summary.out_of_stock || 0);
  $("#totalValue").text(
    "₱" + (parseFloat(summary.total_value) || 0).toFixed(2)
  );
}

// Load and populate brands dropdown
function loadBrands(brands) {
  const brandSelect = $("#brand");
  const brandFilter = $("#brandFilter");

  brandSelect.empty();
  brandFilter.empty();

  brandFilter.append('<option value="">All Brands</option>');

  brands.forEach((brand) => {
    brandSelect.append(
      `<option value="${brand.brand_id}">${brand.name}</option>`
    );
    brandFilter.append(
      `<option value="${brand.brand_id}">${brand.name}</option>`
    );
  });
}

// Trigger once DOM is fully ready
$(function () {
  initProductsTable();
});
