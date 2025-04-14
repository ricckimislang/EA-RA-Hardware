document.addEventListener("DOMContentLoaded", function () {
  // Initialize all charts
  initializeDashboard();

  // Add event listener for time range changes
  document.getElementById("timeRange").addEventListener("change", function () {
    updateDashboard(this.value);
  });
});

function initializeDashboard() {
  // Initialize with default time range (month)
  updateDashboard("month");
}

function updateDashboard(timeRange) {
  // TODO: Fetch real data from backend based on timeRange
  // For now using sample data
  updateSummaryCards(timeRange);
  initializeSalesTrendChart();
  initializeExpensesTreemap();
  initializeEmployeeSalaryChart();
  initializeProductBubbleChart();
  initializeInventoryChart();
}

function updateSummaryCards(timeRange) {
  // Sample data - replace with actual API calls
  document.getElementById("totalSales").textContent = "₱125,000.00";
  document.getElementById("totalExpenses").textContent = "₱45,000.00";
  document.getElementById("netProfit").textContent = "₱80,000.00";
  document.getElementById("totalOrders").textContent = "156";
}

// Update in all chart initialization functions:
function initializeSalesTrendChart() {
  const ctx = document.getElementById("salesTrendChart").getContext("2d");
  new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "Current Year",
          data: [65000, 59000, 80000, 81000, 56000, 95000],
          borderColor: "#4CAF50",
          tension: 0.4,
          fill: false,
        },
        {
          label: "Previous Year",
          data: [55000, 49000, 70000, 71000, 46000, 85000],
          borderColor: "#9E9E9E",
          borderDash: [5, 5],
          tension: 0.4,
          fill: false,
        },
        {
          label: "Moving Average",
          data: [62000, 69500, 73333, 73500, 72400, 77200],
          borderColor: "#2196F3",
          borderWidth: 2,
          pointRadius: 0,
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: {
          top: 20,
          right: 20,
          bottom: 20,
          left: 20,
        },
      },
      interaction: {
        mode: "index",
        intersect: false,
        axis: "xy",
      },
      plugins: {
        title: {
          display: true,
          text: "Sales Revenue Trend",
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              return `${
                context.dataset.label
              }: ₱${context.raw.toLocaleString()}`;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: (value) => "₱" + value.toLocaleString(),
          },
        },
      },
    },
  });
}

function initializeExpensesTreemap() {
  const ctx = document.getElementById("expensesTreemap").getContext("2d");
  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Utilities", "Salaries", "Maintenance", "Supplies", "Marketing"],
      datasets: [
        {
          data: [15000, 25000, 8000, 12000, 5000],
          backgroundColor: [
            "#FF9800",
            "#2196F3",
            "#4CAF50",
            "#9C27B0",
            "#F44336",
          ],
          borderWidth: 1,
          borderColor: "#fff",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: {
          top: 20,
          right: 20,
          bottom: 20,
          left: 20,
        },
      },
      interaction: {
        mode: "point",
        intersect: true,
      },
      plugins: {
        title: {
          display: true,
          text: "Expense Categories Distribution",
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              const label = context.label || "";
              const value = context.raw || 0;
              return `${label}: ₱${value.toLocaleString()}`;
            },
          },
        },
      },
    },
  });
}

function initializeEmployeeSalaryChart() {
  const ctx = document.getElementById("employeeSalaryChart").getContext("2d");
  new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "Individual Salaries",
          type: "line",
          data: [25000, 26000, 26000, 27000, 27000, 28000],
          borderColor: "#2196F3",
          backgroundColor: "rgba(33, 150, 243, 0.1)",
          fill: true,
          tension: 0.4,
        },
        {
          label: "Total Payroll",
          type: "bar",
          data: [125000, 130000, 130000, 135000, 135000, 140000],
          backgroundColor: "rgba(156, 39, 176, 0.2)",
          borderColor: "#9C27B0",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: "Employee Salary Trends",
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              return `${
                context.dataset.label
              }: ₱${context.raw.toLocaleString()}`;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: (value) => "₱" + value.toLocaleString(),
          },
        },
      },
    },
  });
}

function initializeProductBubbleChart() {
  const ctx = document.getElementById("productBubbleChart").getContext("2d");
  new Chart(ctx, {
    type: "bubble",
    data: {
      datasets: [
        {
          label: "Products",
          data: [
            { x: 100, y: 80, r: 15, name: "Product A" },
            { x: 65, y: 60, r: 10, name: "Product B" },
            { x: 40, y: 30, r: 8, name: "Product C" },
            { x: 20, y: 15, r: 5, name: "Product D" },
            { x: 10, y: 10, r: 4, name: "Product E" },
          ],
          backgroundColor: [
            "rgba(76, 175, 80, 0.6)",
            "rgba(33, 150, 243, 0.6)",
            "rgba(156, 39, 176, 0.6)",
            "rgba(255, 152, 0, 0.6)",
            "rgba(244, 67, 54, 0.6)",
          ],
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: "Product Sales Analysis",
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              return [
                `Product: ${context.raw.name}`,
                `Sales: ₱${context.raw.x.toLocaleString()}`,
                `Profit: ₱${context.raw.y.toLocaleString()}`,
                `Volume: ${context.raw.r * 100} units`,
              ];
            },
          },
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Sales (₱)",
          },
          ticks: {
            callback: (value) => "₱" + value.toLocaleString(),
          },
        },
        y: {
          title: {
            display: true,
            text: "Profit (₱)",
          },
          ticks: {
            callback: (value) => "₱" + value.toLocaleString(),
          },
        },
      },
    },
  });
}

function initializeInventoryChart() {
  const ctx = document.getElementById("inventoryChart").getContext("2d");
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["Tools", "Hardware", "Electrical", "Plumbing", "Paint"],
      datasets: [
        {
          label: "Normal Stock",
          data: [150, 200, 120, 180, 90],
          backgroundColor: "#4CAF50",
          borderWidth: 0,
        },
        {
          label: "Low Stock",
          data: [30, 25, 15, 20, 10],
          backgroundColor: "#FFC107",
          borderWidth: 0,
        },
        {
          label: "Out of Stock",
          data: [5, 8, 3, 4, 2],
          backgroundColor: "#f44336",
          borderWidth: 0,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: "nearest",
        intersect: false,
        axis: "xy",
      },
      plugins: {
        title: {
          display: true,
          text: "Inventory Stock Levels by Category",
          font: {
            size: 16,
          },
        },
        legend: {
          display: true,
          position: "top",
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              return `${context.dataset.label}: ${context.raw} items`;
            },
          },
        },
      },
      scales: {
        x: {
          stacked: true,
          title: {
            display: true,
            text: "Product Categories",
          },
        },
        y: {
          stacked: true,
          beginAtZero: true,
          title: {
            display: true,
            text: "Number of Items",
          },
        },
      },
    },
  });
}

function initializeTopSellingChart() {
  const ctx = document.getElementById("topSellingChart").getContext("2d");
  new Chart(ctx, {
    type: "horizontalBar",
    data: {
      labels: ["Product A", "Product B", "Product C", "Product D", "Product E"],
      datasets: [
        {
          data: [150, 120, 90, 85, 70],
          backgroundColor: "#2196F3",
          borderWidth: 0,
        },
      ],
    },
    options: {
      indexAxis: "y",
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: "nearest",
        intersect: false,
        axis: "xy",
      },
      plugins: {
        legend: {
          display: false,
        },
      },
      scales: {
        x: {
          beginAtZero: true,
        },
      },
    },
  });
}
