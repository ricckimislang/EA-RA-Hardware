// Global Variables
let cart = [];
let currentDiscount = 0;

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

// Event Listeners
itemSearch.addEventListener("input", debounce(searchItems, 300));
discountType.addEventListener("change", updateDiscount);
checkoutBtn.addEventListener("click", handleCheckout);
// Update Totals
// In the global variables section, add this initialization:
checkoutBtn.disabled = true;

// Debounce Function
// This function limits the rate at which a function can fire by waiting for a pause
// in the function calls before executing. This improves performance and reduces
// unnecessary processing, especially for search inputs and window resize events.
function debounce(func, wait) {
  let timeout; // Store the timeout ID
  return function executedFunction(...args) {
    const later = () => {
      // Function to execute after the delay
      clearTimeout(timeout); // Clear the timeout to prevent multiple executions
      func(...args); // Execute the original function
    };
    clearTimeout(timeout); // Clear any existing timeout
    timeout = setTimeout(later, wait); // Set new timeout
  };
}

// Search Items - Using sample data for UI/UX demo
function searchItems() {
  const query = itemSearch.value.trim().toLowerCase();
  if (query.length < 2) {
    itemsGrid.innerHTML =
      '<p class="text-center">Type at least 2 characters to search</p>';
    return;
  }

  // Sample data for demonstration
  const sampleItems = [
    { id: 1, name: "Hammer", sku: "HMR001", price: 299.99, stock: 50 },
    { id: 2, name: "Screwdriver Set", sku: "SD002", price: 499.99, stock: 30 },
    { id: 3, name: "Paint Brush", sku: "PB003", price: 99.99, stock: 100 },
    { id: 4, name: "Measuring Tape", sku: "MT004", price: 199.99, stock: 45 },
  ];

  // Filter items based on search query
  const filteredItems = sampleItems.filter(
    (item) =>
      item.name.toLowerCase().includes(query) ||
      item.sku.toLowerCase().includes(query)
  );

  if (filteredItems.length === 0) {
    itemsGrid.innerHTML = '<p class="text-center">No items found</p>';
    return;
  }

  displayItems(filteredItems);
}

// Display Items in Grid
function displayItems(items) {
  itemsGrid.innerHTML = items
    .map(
      (item) => `
        <div class="item-card" onclick="addToCart(${JSON.stringify(item)})">
            <h4>${item.name}</h4>
            <p>SKU: ${item.sku}</p>
            <p class="price">₱${parseFloat(item.price).toFixed(2)}</p>
            <small>Stock: ${item.stock}</small>
        </div>
    `
    )
    .join("");
}

// Add Item to Cart
function addToCart(item) {
  const existingItem = cart.find((i) => i.id === item.id);

  if (existingItem) {
    if (existingItem.quantity < item.stock) {
      existingItem.quantity++;
    } else {
      alert("Maximum stock reached!");
      return;
    }
  } else {
    cart.push({ ...item, quantity: 1 });
  }

  updateCartDisplay();
  updateTotals();
}

// Update Cart Display
function updateCartDisplay() {
  cartItems.innerHTML = cart
    .map(
      (item) => `
        <div class="cart-item">
            <div>
                <h5>${item.name}</h5>
                <small>₱${item.price} × ${item.quantity}</small>
            </div>
            <div class="cart-item-controls">
                <button class="btn btn-sm btn-outline-primary" onclick="updateQuantity(${item.id}, -1)">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="mx-2">${item.quantity}</span>
                <button class="btn btn-sm btn-outline-primary" onclick="updateQuantity(${item.id}, 1)">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `
    )
    .join("");
}

// Update Item Quantity
function updateQuantity(itemId, change) {
  const item = cart.find((i) => i.id === itemId);
  if (!item) return;

  const newQuantity = item.quantity + change;
  if (newQuantity > 0 && newQuantity <= item.stock) {
    item.quantity = newQuantity;
  } else if (newQuantity <= 0) {
    removeItem(itemId);
    return;
  }

  updateCartDisplay();
  updateTotals();
}

// Remove Item from Cart
function removeItem(itemId) {
  cart = cart.filter((item) => item.id !== itemId);
  updateCartDisplay();
  updateTotals(); // This will now handle the button state
}

// Update Discount
function updateDiscount() {
  const type = discountType.value;
  currentDiscount = type === "none" ? 0 : 0.2; // 20% for both senior and PWD
  updateTotals();
}

// Update the updateTotals function:
function updateTotals() {
  const subtotal = cart.reduce(
    (sum, item) => sum + item.price * item.quantity,
    0
  );
  const discountAmount = subtotal * currentDiscount;
  const total = subtotal - discountAmount;

  subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
  discountElement.textContent = `₱${discountAmount.toFixed(2)}`;
  totalElement.textContent = `₱${total.toFixed(2)}`;

  checkoutBtn.disabled = cart.length === 0;
}

// Handle Checkout
async function handleCheckout() {
  if (cart.length === 0) {
    checkoutBtn.disabled = true;
    alert("Cart is empty!");
    return;
  }

  const sale = {
    items: cart,
    discount_type: discountType.value,
    discount_amount: parseFloat(
      (
        cart.reduce((sum, item) => sum + item.price * item.quantity, 0) *
        currentDiscount
      ).toFixed(2)
    ),
    total: parseFloat(
      (
        cart.reduce((sum, item) => sum + item.price * item.quantity, 0) *
        (1 - currentDiscount)
      ).toFixed(2)
    ),
  };

  try {
    const response = await fetch("../api/process_sale.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(sale),
    });

    const result = await response.json();
    if (result.success) {
      generateReceipt(sale, result.sale_id);
      cart = [];
      updateCartDisplay();
      updateTotals();
      discountType.value = "none";
      currentDiscount = 0;
      new bootstrap.Modal(document.getElementById("receipt-modal")).show();
    } else {
      alert("Error processing sale: " + result.message);
    }
  } catch (error) {
    console.error("Error processing sale:", error);
    alert("Error processing sale. Please try again.");
  }
}

// Generate Receipt
function generateReceipt(sale, saleId) {
  const date = new Date().toLocaleString();
  const receiptContent = `
        <div class="text-center mb-3">
            <h4>Hardware Store</h4>
            <p>123 Main Street<br>City, State</p>
            <p>Tel: (123) 456-7890</p>
        </div>
        <div class="mb-3">
            <p>Sale #: ${saleId}<br>
            Date: ${date}<br>
            Cashier: ${
              document.getElementById("cashier-name").value || "Admin"
            }</p>
        </div>
        <div class="mb-3">
            ${sale.items
              .map(
                (item) => `
                ${item.name}<br>
                ${item.quantity} × ₱${item.price.toFixed(2)} = ₱${(
                  item.quantity * item.price
                ).toFixed(2)}
            `
              )
              .join("<br>")}
        </div>
        <div class="mb-3">
            <hr>
            Subtotal: ₱${(sale.total / (1 - currentDiscount)).toFixed(2)}<br>
            ${
              sale.discount_type !== "none"
                ? `Discount (${
                    sale.discount_type
                  }): ₱${sale.discount_amount.toFixed(2)}<br>`
                : ""
            }
            <strong>Total: ₱${sale.total.toFixed(2)}</strong>
        </div>
        <div class="text-center mt-4">
            <p>Thank you for your purchase!</p>
        </div>
    `;

  receiptPreview.innerHTML = receiptContent;
}

// Print Receipt
document.getElementById("print-receipt").addEventListener("click", () => {
  const printWindow = window.open("", "", "width=600,height=600");
  const doc = printWindow.document;

  const html = doc.createElement("html");
  const head = doc.createElement("head");
  const title = doc.createElement("title");
  title.textContent = "Print Receipt";
  head.appendChild(title);

  const link = doc.createElement("link");
  link.rel = "stylesheet";
  link.href = "../css/pos.css";
  head.appendChild(link);

  const body = doc.createElement("body");
  const container = doc.createElement("div");
  container.innerHTML = receiptPreview.innerHTML;
  body.appendChild(container);

  html.appendChild(head);
  html.appendChild(body);
  doc.appendChild(html);

  printWindow.focus();
  printWindow.print();
  printWindow.close();
});

// Update date and time in header
function updateDateTime() {
  const now = new Date();
  document.getElementById("current-date").textContent =
    now.toLocaleDateString();
  document.getElementById("current-time").textContent =
    now.toLocaleTimeString();
}

// Update immediately and then every second
updateDateTime();
setInterval(updateDateTime, 1000);
