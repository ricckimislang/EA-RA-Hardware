// Global Variables
let cart = [];
let currentDiscount = 0;
let allProducts = [];

// DOM Elements
const itemSearch = document.getElementById("item-search");
const itemsGrid = document.getElementById("items-grid");
const cartItems = document.getElementById("cart-items");
const discountType = document.getElementById("discount-type");
const subtotalElement = document.getElementById("subtotal");
const discountElement = document.getElementById("discount");
const totalElement = document.getElementById("total");
const checkoutBtn = document.getElementById("checkout-btn");
const receiptPreview = document.getElementById("receipt-preview");
const categoryFilter = document.getElementById("category-filter");
const cashierId = document.getElementById("cashier-id");
const cashierName = document.getElementById("cashier-name");
const transactionId = document.getElementById("transaction-id");
const currentDate = document.getElementById("current-date");
const currentTime = document.getElementById("current-time");

// Event Listeners
itemSearch.addEventListener("input", debounce(searchItems, 300));
categoryFilter.addEventListener("change", loadProductsByCategory);
discountType.addEventListener("change", updateDiscount);
checkoutBtn.addEventListener("click", handleCheckout);

// Initialize
checkoutBtn.disabled = true;

// Set current date and time
function updateDateTime() {
  const now = new Date();
  currentDate.textContent = now.toLocaleDateString();
  currentTime.textContent = now.toLocaleTimeString();
}

// Update time every second
setInterval(updateDateTime, 1000);
updateDateTime();

// Load products on page load
document.addEventListener("DOMContentLoaded", async function () {
  loadProductsByCategory();
  // Set transaction ID (would be generated from database in a real app)
  transactionId.textContent = await generateTransactionId();
  getCashierName(cashierId.value);
});

// Handle checkout process
async function handleCheckout() {
  if (cart.length === 0) return;

  // Generate receipt
  const receipt = generateReceipt();

  // Display receipt in modal
  receiptPreview.innerHTML = receipt;

  // Show receipt modal
  const receiptModal = new bootstrap.Modal(
    document.getElementById("receipt-modal")
  );
  receiptModal.show();

  // Add event listener to print button
  document
    .getElementById("print-receipt")
    ?.addEventListener("click", function () {
      const printWindow = window.open("", "_blank");

      if (!printWindow) {
        alert("Pop-up blocked. Please allow pop-ups for this website.");
        return;
      }

      const receiptHTML =
        typeof receipt !== "undefined"
          ? receipt
          : "<div class='receipt'><div>No receipt data</div></div>";

      const doc = printWindow.document;
      doc.open(); // start the new document

      doc.write(`
      <!DOCTYPE html>
      <html>
        <head>
          <title>Receipt</title>
          <style>
            body { font-family: monospace; line-height: 1.5; }
            .receipt { width: 300px; margin: 0 auto; }
            .receipt-header { text-align: center; margin-bottom: 10px; }
            .receipt-items { margin: 10px 0; }
            .receipt-item { display: flex; justify-content: space-between; }
            .receipt-totals { border-top: 1px dashed #000; margin-top: 10px; padding-top: 10px; }
            .receipt-total { display: flex; justify-content: space-between; font-weight: bold; }
            @media print { body { width: 300px; } }
          </style>
        </head>
        <body onload="window.print(); setTimeout(() => window.close(), 500);">
          ${receiptHTML}
        </body>
      </html>
    `);

      doc.close(); // finalize writing
    });

  // Reset cart after checkout
  cart = [];
  renderCart();
  updateTotals();
  checkoutBtn.disabled = true;

  // Generate new transaction ID
  transactionId.textContent = await generateTransactionId();
}

// Generate a simple transaction ID
async function generateTransactionId() {
  try {
    const response = await fetch("api/get_transaction_id.php");
    const data = await response.json();

    if (!data.success) {
      console.error("Error getting transaction ID:", data.message);
      return "00000";
    }

    return data.transaction_id;
  } catch (error) {
    console.error("Error fetching transaction ID:", error);
    return "00000";
  }
}

// Utility Functions
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Product Functions
function loadProductsByCategory() {
  const category = categoryFilter.value;
  itemsGrid.innerHTML =
    '<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading products...</p>';

  fetch(`api/fetch_products.php?category=${category}`)
    .then((response) => response.json())
    .then((data) => {
      if (!data.success) {
        itemsGrid.innerHTML = `<p class="text-center text-danger">${
          data.message || "Error loading products"
        }</p>`;
        return;
      }

      allProducts = data.products;

      if (allProducts.length === 0) {
        itemsGrid.innerHTML =
          '<p class="text-center">No products available in this category</p>';
        return;
      }

      displayItems(allProducts);
    })
    .catch((error) => {
      console.error("Error fetching products:", error);
      itemsGrid.innerHTML =
        '<p class="text-center text-danger">Failed to load products. Please try again.</p>';
    });
}

function searchItems() {
  const query = itemSearch.value.trim().toLowerCase();

  if (query.length < 2 && allProducts.length > 0) {
    displayItems(allProducts);
    return;
  }

  if (query.length < 2) {
    itemsGrid.innerHTML =
      '<p class="text-center">Type at least 2 characters to search</p>';
    return;
  }

  const filteredItems = allProducts.filter(
    (item) =>
      item.name.toLowerCase().includes(query) ||
      item.sku.toLowerCase().includes(query) ||
      (item.barcode && item.barcode.toLowerCase().includes(query))
  );

  if (filteredItems.length === 0) {
    itemsGrid.innerHTML = '<p class="text-center">No items found</p>';
    return;
  }

  displayItems(filteredItems);
}

function displayItems(items) {
  itemsGrid.innerHTML = items
    .map(
      (item) => `
        <div class="item-card" onclick="addToCart(${item.id})">
            <div class="item-details">
                <h4 class="item-name">${item.name}</h4>
                <p>SKU: ${item.sku}</p>
                <p class="price">₱${parseFloat(item.price).toFixed(2)}</p>
                <small class="item-stock ${
                  item.stock < 10 ? "text-danger" : ""
                }">
                    Stock: ${item.stock} ${item.unit}
                </small>
            </div>
        </div>
    `
    )
    .join("");
}

// Cart Management Functions
function addToCart(productId) {
  const product = allProducts.find((p) => p.id === productId);
  if (!product) return;

  if (product.stock <= 0) {
    alert(`Sorry, ${product.name} is out of stock.`);
    return;
  }

  const existingItem = cart.find((item) => item.id === product.id);

  if (existingItem) {
    if (existingItem.quantity >= product.stock) {
      alert(`Sorry, only ${product.stock} ${product.unit} available in stock.`);
      return;
    }
    existingItem.quantity += 1;
  } else {
    cart.push({
      id: product.id,
      name: product.name,
      price: product.price,
      quantity: 1,
      unit: product.unit,
    });
  }

  renderCart();
  updateTotals();

  checkoutBtn.disabled = cart.length === 0;
}

function removeFromCart(index) {
  cart.splice(index, 1);
  renderCart();
  updateTotals();

  checkoutBtn.disabled = cart.length === 0;
}

function updateQuantity(index, change) {
  const item = cart[index];
  const product = allProducts.find((p) => p.id === item.id);

  const newQuantity = item.quantity + change;

  if (newQuantity <= 0) {
    removeFromCart(index);
    return;
  }

  if (newQuantity > product.stock) {
    alert(`Sorry, only ${product.stock} ${product.unit} available in stock.`);
    return;
  }

  item.quantity = newQuantity;
  renderCart();
  updateTotals();
}

function renderCart() {
  if (cart.length === 0) {
    cartItems.innerHTML = '<p class="text-center">No items in cart</p>';
    return;
  }

  cartItems.innerHTML = cart
    .map(
      (item, index) => `
    <div class="cart-item">
      <div class="cart-item-details">
        <h5>${item.name}</h5>
        <p>₱${item.price.toFixed(2)} × ${item.quantity} ${item.unit}</p>
      </div>
      <div class="cart-item-actions">
        <span>₱${(item.price * item.quantity).toFixed(2)}</span>
        <div class="quantity-controls">
          <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, -1)">
            <i class="fas fa-minus"></i>
          </button>
          <span class="quantity">${item.quantity}</span>
          <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, 1)">
            <i class="fas fa-plus"></i>
          </button>
          <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${index})">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
  `
    )
    .join("");
}

// Discount and Totals Functions
function updateDiscount() {
  const discountTypeValue = discountType.value;

  switch (discountTypeValue) {
    case "senior":
    case "pwd":
      currentDiscount = 0.2;
      break;
    default:
      currentDiscount = 0;
  }

  updateTotals();
}

function updateTotals() {
  const subtotal = cart.reduce(
    (total, item) => total + item.price * item.quantity,
    0
  );

  const discountAmount = subtotal * currentDiscount;

  const total = subtotal - discountAmount;

  subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
  discountElement.textContent = `₱${discountAmount.toFixed(2)}`;
  totalElement.textContent = `₱${total.toFixed(2)}`;
}

// Global Variables and other code remains unchanged...

// Handle checkout process
async function handleCheckout() {
  if (cart.length === 0) return;

  try {
    // First save the transaction to database
    await saveTransaction();

    // Generate receipt
    const receipt = generateReceipt();

    // Display receipt in modal
    receiptPreview.innerHTML = receipt;

    // Show receipt modal
    const receiptModal = new bootstrap.Modal(
      document.getElementById("receipt-modal")
    );
    receiptModal.show();

    // Add event listener to print button
    document
      .getElementById("print-receipt")
      ?.addEventListener("click", function () {
        const printWindow = window.open("", "_blank");

        if (!printWindow) {
          alert("Pop-up blocked. Please allow pop-ups for this website.");
          return;
        }

        const receiptHTML =
          typeof receipt !== "undefined"
            ? receipt
            : "<div class='receipt'><div>No receipt data</div></div>";

        const doc = printWindow.document;
        doc.open(); // start the new document

        doc.write(`
        <!DOCTYPE html>
        <html>
          <head>
            <title>Receipt</title>
            <style>
              body { font-family: monospace; line-height: 1.5; }
              .receipt { width: 300px; margin: 0 auto; }
              .receipt-header { text-align: center; margin-bottom: 10px; }
              .receipt-items { margin: 10px 0; }
              .receipt-item { display: flex; justify-content: space-between; }
              .receipt-totals { border-top: 1px dashed #000; margin-top: 10px; padding-top: 10px; }
              .receipt-total { display: flex; justify-content: space-between; font-weight: bold; }
              @media print { body { width: 300px; } }
            </style>
          </head>
          <body onload="window.print(); setTimeout(() => window.close(), 500);">
            ${receiptHTML}
          </body>
        </html>
      `);

        doc.close(); // finalize writing
      });

    // Reset cart after checkout
    cart = [];
    renderCart();
    updateTotals();
    loadProductsByCategory();
    checkoutBtn.disabled = true;

    // Generate new transaction ID
    transactionId.textContent = await generateTransactionId();
  } catch (error) {
    console.error("Checkout error:", error);
    alert("There was an error processing your checkout. Please try again.");
  }
}

// Save transaction to database
async function saveTransaction() {
  const subtotal = parseFloat(subtotalElement.textContent.replace("₱", ""));
  const discount = parseFloat(discountElement.textContent.replace("₱", ""));
  const total = parseFloat(totalElement.textContent.replace("₱", ""));

  const transactionData = {
    transactionId: transactionId.textContent,
    cashierName: cashierName.textContent,
    items: cart.map((item) => ({
      id: item.id, // Match with PHP expected field
      quantity: item.quantity,
      price: item.price,
    })),
    subtotal: subtotal,
    discount: discount,
    total: total,
  };

  try {
    const response = await fetch("api/add_transaction.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(transactionData),
    });

    const data = await response.json();

    if (!data.success) {
      console.error("Error saving transaction:", data.message);
      throw new Error(data.message || "Failed to save transaction");
    } else {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Transaction saved successfully.",
        timer: 1000,
        showConfirmButton: false,
      });

      return true;
    }
  } catch (error) {
    console.error("Error saving transaction:", error);
    throw error;
  }
}

function generateReceipt() {
  const subtotal = cart.reduce(
    (total, item) => total + item.price * item.quantity,
    0
  );
  const discountAmount = subtotal * currentDiscount;
  const total = subtotal - discountAmount;

  const discountTypeText =
    discountType.options[discountType.selectedIndex].text;

  return `
    <div class="receipt">
      <div class="receipt-header">
        <h4>EA-RA Hardware</h4>
        <p>Transaction #${transactionId.textContent}</p>
        <p>${new Date().toLocaleString()}</p>
        <p>Cashier: ${cashierName.textContent}</p>
      </div>
      
      <div class="receipt-items">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Item</th>
              <th>Qty</th>
              <th class="text-end">Price</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            ${cart
              .map(
                (item) => `
              <tr>
                <td>${item.name}</td>
                <td>${item.quantity} ${item.unit}</td>
                <td class="text-end">₱${item.price.toFixed(2)}</td>
                <td class="text-end">₱${(item.price * item.quantity).toFixed(
                  2
                )}</td>
              </tr>
            `
              )
              .join("")}
          </tbody>
        </table>
      </div>
      
      <div class="receipt-totals">
        <div class="d-flex justify-content-between">
          <span>Subtotal:</span>
          <span>₱${subtotal.toFixed(2)}</span>
        </div>
        
        <div class="d-flex justify-content-between">
          <span>Discount ${
            currentDiscount > 0 ? `(${discountTypeText})` : ""
          }:</span>
          <span>₱${discountAmount.toFixed(2)}</span>
        </div>
        
        <div class="d-flex justify-content-between fw-bold mt-2">
          <span>TOTAL:</span>
          <span>₱${total.toFixed(2)}</span>
        </div>
      </div>
      
      <div class="receipt-footer text-center mt-4">
        <p>Thank you for shopping at EA-RA Hardware!</p>
      </div>
    </div>
  `;
}

async function getCashierName(user_id) {
  const cashierId = user_id;

  try {
    const response = await fetch(
      `api/get_cashiername.php?user_id=${cashierId}`
    );
    const data = await response.json();

    if (data.success) {
      cashierName.textContent = data.cashier_name;
    } else {
      console.error("Error getting cashier name:", data.message);
    }
  } catch (error) {
    console.error("Error getting cashier name:", error);
  }
}
