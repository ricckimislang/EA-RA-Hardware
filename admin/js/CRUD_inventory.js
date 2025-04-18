/**
 * CRUD_inventory.js
 * Handles all CRUD operations for inventory management
 */

// CREATE - Add new product to inventory
function addProduct() {
  // Validate the form
  if (!$("#productForm")[0].checkValidity()) {
    $("#productForm")[0].reportValidity();
    return;
  }

  // Get form data
  const productData = {
    itemName: $("#itemName").val().trim(),
    sku: $("#sku").val().trim(),
    barCode: $("#barCode").val().trim(),
    category: $("#category option:selected").text(),
    brand:
      $("#brand").val().trim().charAt(0).toUpperCase() +
      $("#brand").val().trim().slice(1),
    description: $("#description").val().trim(),
    unit: $("#unit").val().trim(),
    costPrice: parseFloat($("#costPrice").val()),
    sellingPrice: parseFloat($("#sellingPrice").val()),
    stockLevel: parseInt($("#initialStock").val()),
    lowStockThreshold: parseInt($("#lowStockThreshold").val() || 5),
  };

  // Show loading state
  $("#saveProduct")
    .prop("disabled", true)
    .html('<i class="fas fa-spinner fa-spin"></i> Saving...');

  // Send data to server
  fetch("api/add_product.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(productData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // Show success message
        showNotification("Product added successfully", "success");

        // Reset form
        $("#productForm")[0].reset();

        // Close modal
        $("#addProductModal").modal("hide");

        // Refresh table and summary
        if (typeof productsTable !== "undefined") {
          productsTable.ajax.reload();
        }
        updateSummaryCards();
      } else {
        showNotification("Error: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Error adding product. Please try again.", "error");
    })
    .finally(() => {
      // Reset button state
      $("#saveProduct").prop("disabled", false).html("Save Product");
    });
}

// READ - Edit product (fetch product details)
function editProduct(productId) {
  fetch(`api/get_product.php?id=${productId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const product = data.product;
        $("#editProductId").val(product.id);
        $("#editItemName").val(product.itemName);
        $("#editSku").val(product.sku);
        $("#editBarCode").val(product.barcode);
        $("#editCategory").val(product.categoryId);
        $("#editBrand").val(product.brand);
        $("#editDescription").val(product.description);
        $("#editUnit").val(product.unit);
        $("#editCostPrice").val(product.costPrice);
        $("#editSellingPrice").val(product.sellingPrice);
        $("#editStockLevel").val(product.stockLevel);
        $("#editLowStockThreshold").val(product.lowStockThreshold || 5);
        $("#editProductModal").modal("show");
      } else {
        showNotification("Error loading product: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Error loading product", "error");
    });
}

// UPDATE - Save edited product
function updateProduct() {
  if (!$("#editProductForm")[0].checkValidity()) {
    $("#editProductForm")[0].reportValidity();
    return;
  }

  const productData = {
    id: $("#editProductId").val(),
    itemName: $("#editItemName").val().trim(),
    sku: $("#editSku").val().trim(),
    categoryId: $("#editCategory").val(),
    brand: $("#editBrand").val().trim(),
    description: $("#editDescription").val().trim(),
    unit: $("#editUnit").val().trim(),
    costPrice: parseFloat($("#editCostPrice").val()),
    sellingPrice: parseFloat($("#editSellingPrice").val()),
    stockLevel: parseInt($("#editStockLevel").val()),
    lowStockThreshold: parseInt($("#editLowStockThreshold").val() || 5),
  };

  $("#saveEditProduct")
    .prop("disabled", true)
    .html('<i class="fas fa-spinner fa-spin"></i> Updating...');

  fetch("api/update_product.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(productData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification("Product updated successfully", "success");
        $("#editProductModal").modal("hide");
        productsTable.ajax.reload();
        updateSummaryCards();
      } else {
        showNotification("Error updating product: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Error updating product", "error");
    })
    .finally(() => {
      $("#saveEditProduct").prop("disabled", false).html("Save Changes");
    });
}

// DELETE - Delete product
function deleteProduct(productId) {
  if (confirm("Are you sure you want to delete this product?")) {
    fetch("api/delete_product.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: productId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification("Product deleted successfully", "success");
          productsTable.ajax.reload();
          updateSummaryCards();
        } else {
          showNotification("Error deleting product: " + data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Error deleting product", "error");
      });
  }
}

// Adjust stock
function adjustStock(productId) {
  $("#adjustmentProductId").val(productId);
  $("#stockAdjustmentModal").modal("show");
}

// Handle stock adjustment form submission
function saveStockAdjustment() {
  if (!$("#stockAdjustmentForm")[0].checkValidity()) {
    $("#stockAdjustmentForm")[0].reportValidity();
    return;
  }

  const adjustmentData = {
    productId: parseInt($("#adjustmentProductId").val()),
    adjustmentType: $("#adjustmentType").val(),
    quantity: parseInt($("#adjustmentQuantity").val()),
    notes: $("#adjustmentNotes").val().trim(),
  };

  $("#saveAdjustment")
    .prop("disabled", true)
    .html('<i class="fas fa-spinner fa-spin"></i> Saving...');

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
        showNotification("Stock adjusted successfully", "success");
        $("#stockAdjustmentModal").modal("hide");
        $("#stockAdjustmentForm")[0].reset();
        productsTable.ajax.reload();
        updateSummaryCards();
      } else {
        showNotification("Error adjusting stock: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Error adjusting stock", "error");
    })
    .finally(() => {
      $("#saveAdjustment").prop("disabled", false).html("Save Adjustment");
    });
}

// Helper function to show notifications
function showNotification(message, type = "info") {
  // You can replace this with your preferred notification library
  // For example: toastr, sweetalert2, etc.

  // Simple implementation using alert
  if (type === "error") {
    alert("Error: " + message);
  } else if (type === "success") {
    alert("Success: " + message);
  } else {
    alert(message);
  }

  // If you have a notification library, use it instead:
  /*
  toastr[type](message, type === "error" ? "Error" : "Success", {
    closeButton: true,
    timeOut: 5000,
    progressBar: true
  });
  */
}

// Attach event listeners when document is ready
$(document).ready(function () {
  // Add product form submission
  $("#saveProduct").click(function (e) {
    e.preventDefault();
    addProduct();
  });

  // Edit product form submission
  $("#saveEditProduct").click(function (e) {
    e.preventDefault();
    updateProduct();
  });

  // Stock adjustment form submission
  $("#saveAdjustment").click(function (e) {
    e.preventDefault();
    saveStockAdjustment();
  });

  // Generate SKU button
  $("#generateSku").click(function () {
    const prefix = $("#category option:selected")
      .text()
      .substring(0, 3)
      .toUpperCase();
    const timestamp = new Date().getTime().toString().substring(9, 13);
    const random = Math.floor(Math.random() * 1000)
      .toString()
      .padStart(3, "0");
    $("#sku").val(`${prefix}-${timestamp}-${random}`);
  });

  // Auto-calculate selling price based on cost price and margin
  $("#costPrice, #margin").on("input", function () {
    const costPrice = parseFloat($("#costPrice").val()) || 0;
    const margin = parseFloat($("#margin").val()) || 0;

    if (costPrice > 0 && margin > 0) {
      const sellingPrice = costPrice * (1 + margin / 100);
      $("#sellingPrice").val(sellingPrice.toFixed(2));
    }
  });

  // Auto-calculate margin based on cost price and selling price
  $("#sellingPrice").on("input", function () {
    const costPrice = parseFloat($("#costPrice").val()) || 0;
    const sellingPrice = parseFloat($("#sellingPrice").val()) || 0;

    if (costPrice > 0 && sellingPrice > 0) {
      const margin = ((sellingPrice - costPrice) / costPrice) * 100;
      $("#margin").val(margin.toFixed(2));
    }
  });

  // Same for edit form
  $("#editCostPrice, #editMargin").on("input", function () {
    const costPrice = parseFloat($("#editCostPrice").val()) || 0;
    const margin = parseFloat($("#editMargin").val()) || 0;

    if (costPrice > 0 && margin > 0) {
      const sellingPrice = costPrice * (1 + margin / 100);
      $("#editSellingPrice").val(sellingPrice.toFixed(2));
    }
  });

  $("#editSellingPrice").on("input", function () {
    const costPrice = parseFloat($("#editCostPrice").val()) || 0;
    const sellingPrice = parseFloat($("#editSellingPrice").val()) || 0;

    if (costPrice > 0 && sellingPrice > 0) {
      const margin = ((sellingPrice - costPrice) / costPrice) * 100;
      $("#editMargin").val(margin.toFixed(2));
    }
  });
});
