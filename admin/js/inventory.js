// Initialize DataTable with buttons
// Modify the DataTable initialization to add better error handling
const productsTable = $("#productsTable").DataTable({
  // Replace ajax with serverSide processing and custom data loading
  serverSide: true,
  processing: true,
  ajax: function(data, callback, settings) {
    fetch("api/get_inventory.php")
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(json => {
        if (!json || !json.data) {
          console.error("Invalid API response structure", json);
          callback({ data: [] });
          return;
        }
        updateSummaryCards(json.data.summary);
        loadCategories(json.data.categories);
        loadBrands(json.data.brands);
        callback({ data: json.data.products });
      })
      .catch(error => {
        console.error("Fetch error:", error);
        callback({ data: [] });
      });
  },
  responsive: true,
  dom: "Bfrtlip",
  buttons: [
    {
      extend: "print",
      className: "btn btn-primary",
      exportOptions: {
        columns: ":not(:last-child)",
      },
    },
    {
      extend: "excel",
      className: "btn btn-primary",
      exportOptions: {
        columns: ":not(:last-child)",
      },
    },
  ],
  processing: true,
  language: {
    processing: "Loading...",
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
        if (type === "display") {
          let levelClass = "normal";
          if (data <= 0) {
            levelClass = "out";
          } else if (data <= row.lowStockThreshold) {
            levelClass = "low";
          }
          return `<span class="stock-level ${levelClass}">${data} ${row.unit}</span>`;
        }
        return data;
      },
    },
    { data: "unit" },
    {
      data: "costPrice",
      render: function (data, type, row) {
        if (type === "display" || type === "filter") {
          return "₱" + parseFloat(data).toFixed(2);
        }
        return data;
      },
    },
    {
      data: "sellingPrice",
      render: function (data, type, row) {
        if (type === "display" || type === "filter") {
          return "₱" + parseFloat(data).toFixed(2);
        }
        return data;
      },
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
      },
    },
  ],
});

// Load and populate categories
function loadCategories(categories) {
  const categorySelect = $("#category");
  const categoryFilter = $("#categoryFilter");

  categorySelect.empty();
  categoryFilter.empty();

  categoryFilter.append('<option value="">All Categories</option>');

  categories.forEach((category) => {
    categorySelect.append(
      `<option value="${category.category_id}">${category.name}</option>`
    );
    categoryFilter.append(
      `<option value="${category.category_id}">${category.name}</option>`
    );
  });
}

// Handle product form submission
$("#saveProduct").click(function () {
  if (!$("#productForm")[0].checkValidity()) {
    $("#productForm")[0].reportValidity();
    return;
  }

  const productData = {
    itemName: $("#itemName").val(),
    sku: $("#sku").val(),
    category: $("#category option:selected").text(),
    brand: $("#brand").val(),
    description: $("#description").val(),
    unit: $("#unit").val(),
    costPrice: parseFloat($("#costPrice").val()),
    sellingPrice: parseFloat($("#sellingPrice").val()),
    supplier: $("#supplier").val(),
    stockLevel: parseInt($("#initialStock").val()),
  };

  // Send data to server and refresh table
  fetch("api/add_product.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(productData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        $("#addProductModal").modal("hide");
        productsTable.ajax.reload();
        updateSummaryCards();
      } else {
        alert("Error adding product: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error adding product");
    });
});

// Handle stock adjustment form submission
$("#saveAdjustment").click(function () {
  if (!$("#stockAdjustmentForm")[0].checkValidity()) {
    $("#stockAdjustmentForm")[0].reportValidity();
    return;
  }

  const adjustmentData = {
    productId: parseInt($("#adjustmentProductId").val()),
    adjustmentType: $("#adjustmentType").val(),
    quantity: parseInt($("#adjustmentQuantity").val()),
  };

  fetch("api/adjust_stock.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(adjustmentData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        $("#stockAdjustmentModal").modal("hide");
        productsTable.ajax.reload();
        updateSummaryCards();
      } else {
        alert("Error adjusting stock: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error adjusting stock");
    });
});

// Filter handling
$("#applyFilters").click(function () {
  const categoryFilter = $("#categoryFilter").val();
  const brandFilter = $("#brandFilter").val();
  const stockFilter = $("#stockFilter").val();

  // Use client-side filtering
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const product = productsTable.row(dataIndex).data();

    if (!product) return false;

    const categoryMatch =
      !categoryFilter ||
      product.category === $("#categoryFilter option:selected").text();

    const brandMatch =
      !brandFilter ||
      product.brand.toLowerCase().includes(brandFilter.toLowerCase());

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

fetch("api/filter_products.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify({
    category: categoryFilter,
    brand: brandFilter,
    stock: stockFilter,
  }),
})
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      productsTable.clear().rows.add(data.products).draw();
    } else {
      console.error("Filter error:", data.message);
    }
  })
  .catch((error) => {
    console.error("Filter error:", error);
  });

// Delete product
function deleteProduct(productId) {
  if (confirm("Are you sure you want to delete this product?")) {
    fetch("api/delete_product.php", {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: productId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          productsTable.ajax.reload();
          updateSummaryCards();
        } else {
          alert("Error deleting product: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error deleting product");
      });
  }
}

// Edit product
function editProduct(productId) {
  fetch(`api/get_product.php?id=${productId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const product = data.product;
        $("#editProductId").val(product.id);
        $("#editItemName").val(product.itemName);
        $("#editSku").val(product.sku);
        $("#editCategory").val(product.categoryId);
        $("#editBrand").val(product.brand);
        $("#editDescription").val(product.description);
        $("#editUnit").val(product.unit);
        $("#editCostPrice").val(product.costPrice);
        $("#editSellingPrice").val(product.sellingPrice);
        $("#editStockLevel").val(product.stockLevel);
        $("#editProductModal").modal("show");
      } else {
        alert("Error loading product: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error loading product");
    });
}

// Adjust stock
function adjustStock(productId) {
  $("#adjustmentProductId").val(productId);
  $("#stockAdjustmentModal").modal("show");
}

// Initialize page
$(document).ready(function () {
  productsTable.ajax.reload();
});

// Add these functions before the DataTable initialization

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
