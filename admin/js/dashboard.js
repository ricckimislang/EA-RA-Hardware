document.addEventListener("DOMContentLoaded", function () {
  // Debug DOM structure first
  debugDashboardStructure();
  
  // Initialize all charts with a slight delay to ensure DOM is fully ready
  setTimeout(function() {
    initializeDashboard();
  }, 100);

  // Add event listener for time range changes
  const timeRangeSelect = document.getElementById("timeRange");
  if (timeRangeSelect) {
    timeRangeSelect.addEventListener("change", function () {
      updateDashboard(this.value);
    });
  } else {
    console.error("Time range select element not found");
  }
});

function debugDashboardStructure() {
  console.log("Debugging dashboard structure...");
  
  // Check chart containers and canvases
  const containers = document.querySelectorAll('.chart-container');
  console.log(`Found ${containers.length} chart containers`);
  
  containers.forEach((container, index) => {
    console.log(`Container ${index + 1}: `, container);
    const canvas = container.querySelector('canvas');
    if (canvas) {
      console.log(`  - Canvas found with id: "${canvas.id}"`);
      if (canvas.getContext) {
        const ctx = canvas.getContext('2d');
        if (ctx) {
          console.log(`  - Canvas context available`);
        } else {
          console.error(`  - Canvas context NOT available`);
        }
      } else {
        console.error(`  - Canvas getContext method NOT available`);
      }
    } else {
      console.error(`  - No canvas element found in container`);
    }
  });
  
  // Check for each specific canvas
  const canvasIds = ["salesTrendChart", "expensesTreemap", "employeeSalaryChart", "productBubbleChart", "inventoryChart"];
  canvasIds.forEach(id => {
    const element = document.getElementById(id);
    if (element) {
      console.log(`Found canvas #${id} - Size: ${element.offsetWidth}x${element.offsetHeight}`);
      
      // Check if element is visible
      const style = window.getComputedStyle(element);
      if (style.display === 'none' || style.visibility === 'hidden' || element.offsetParent === null) {
        console.error(`Canvas #${id} is not visible!`);
      }
    } else {
      console.error(`Canvas #${id} not found in DOM`);
    }
  });
}

function initializeDashboard() {
  // Initialize with default time range (month)
  updateDashboard("month");
}

function updateDashboard(timeRange) {
  console.log(`Updating dashboard for time range: ${timeRange}`);
  
  // Show loading state
  document.querySelectorAll(".chart-container").forEach((container) => {
    container.innerHTML =
      '<div class="text-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
  });

  // Fetch data from API
  fetch(`api/dashboard_data.php?timeRange=${timeRange}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Dashboard API response:", data);
      
      if (data.success) {
        try {
          // Update each component with error handling
          updateSummaryCards(data.summaryCards || {});
          
          // Clear any existing charts to prevent duplicates
          if (window.salesTrendChart && typeof window.salesTrendChart.destroy === 'function') {
            window.salesTrendChart.destroy();
            window.salesTrendChart = null;
          }
          
          if (window.expensesChart && typeof window.expensesChart.destroy === 'function') {
            window.expensesChart.destroy();
            window.expensesChart = null;
          }
          
          if (window.employeeSalaryChart && typeof window.employeeSalaryChart.destroy === 'function') {
            window.employeeSalaryChart.destroy();
            window.employeeSalaryChart = null;
          }
          
          if (window.productBubbleChart && typeof window.productBubbleChart.destroy === 'function') {
            window.productBubbleChart.destroy();
            window.productBubbleChart = null;
          }
          
          if (window.inventoryChart && typeof window.inventoryChart.destroy === 'function') {
            window.inventoryChart.destroy();
            window.inventoryChart = null;
          }
          
          // First try to rebuild chart containers if they were emptied
          rebuildChartContainers();
          
          // Initialize charts with appropriate container checks
          const salesTrendCanvas = document.getElementById("salesTrendChart") || 
                                   document.querySelector('.chart-container canvas#salesTrendChart');
          if (salesTrendCanvas) {
            console.log("Initializing sales trend chart", data.salesTrend);
            initializeSalesTrendChart(data.salesTrend || { labels: [], current: [], previous: [], movingAverage: [] });
          } else {
            console.error("Sales trend chart container not found");
          }
          
          const expensesTreemapCanvas = document.getElementById("expensesTreemap") ||
                                        document.querySelector('.chart-container canvas#expensesTreemap');
          if (expensesTreemapCanvas) {
            console.log("Initializing expenses chart", data.expenseCategories);
            initializeExpensesTreemap(data.expenseCategories || { labels: [], data: [] });
          } else {
            console.error("Expenses treemap container not found");
          }
          
          const employeeSalaryCanvas = document.getElementById("employeeSalaryChart") ||
                                       document.querySelector('.chart-container canvas#employeeSalaryChart');
          if (employeeSalaryCanvas) {
            console.log("Initializing employee salary chart", data.employeeSalaries);
            initializeEmployeeSalaryChart(data.employeeSalaries || { labels: [], individual: [], total: [] });
          } else {
            console.error("Employee salary chart container not found");
          }
          
          const productBubbleCanvas = document.getElementById("productBubbleChart") ||
                                      document.querySelector('.chart-container canvas#productBubbleChart');
          if (productBubbleCanvas) {
            console.log("Initializing product bubble chart", data.productSales);
            initializeProductBubbleChart(data.productSales || { data: [] });
          } else {
            console.error("Product bubble chart container not found");
          }
          
          const inventoryCanvas = document.getElementById("inventoryChart") ||
                                  document.querySelector('.chart-container canvas#inventoryChart');
          if (inventoryCanvas) {
            console.log("Initializing inventory chart", data.inventory);
            initializeInventoryChart(data.inventory || { labels: [], normal: [], low: [], out: [] });
          } else {
            console.error("Inventory chart container not found");
          }
        } catch (err) {
          console.error("Error initializing charts:", err);
          document.querySelectorAll(".chart-container").forEach((container) => {
            container.innerHTML =
              '<div class="alert alert-danger">Error initializing charts. Check console for details.</div>';
          });
        }
      } else {
        console.error("Error fetching dashboard data:", data.message);
        document.querySelectorAll(".chart-container").forEach((container) => {
          container.innerHTML =
            '<div class="alert alert-danger">Error loading data. Please try again later.</div>';
        });
      }
    })
    .catch((error) => {
      console.error("API request failed:", error);
      document.querySelectorAll(".chart-container").forEach((container) => {
        container.innerHTML =
          '<div class="alert alert-danger">Error loading data. Please try again later.</div>';
      });
    });
}

// Function to recreate chart canvases if they were removed
function rebuildChartContainers() {
  const chartContainers = document.querySelectorAll('.chart-container');
  const canvasIds = ["salesTrendChart", "expensesTreemap", "employeeSalaryChart", "productBubbleChart", "inventoryChart"];
  
  chartContainers.forEach((container) => {
    // Skip if container already has a canvas
    if (container.querySelector('canvas')) return;
    
    // Find which chart this container belongs to based on surrounding elements
    const cardTitle = container.closest('.card-body')?.querySelector('.card-title')?.textContent || '';
    let canvasId = '';
    
    if (cardTitle.includes('Sales Revenue')) canvasId = 'salesTrendChart';
    else if (cardTitle.includes('Expense Categories')) canvasId = 'expensesTreemap';
    else if (cardTitle.includes('Employee Salary')) canvasId = 'employeeSalaryChart';
    else if (cardTitle.includes('Product Sales')) canvasId = 'productBubbleChart';
    else if (cardTitle.includes('Inventory')) canvasId = 'inventoryChart';
    
    if (canvasId) {
      // Create a new canvas element
      const canvas = document.createElement('canvas');
      canvas.id = canvasId;
      container.innerHTML = ''; // Clear loading spinner or error message
      container.appendChild(canvas);
      console.log(`Rebuilt canvas #${canvasId} in container`);
    }
  });
}

function updateSummaryCards(data) {
  try {
    // Format numbers with the Philippine Peso symbol and commas
    const formatCurrency = (value) => {
      return (
        "₱" +
        parseFloat(value || 0).toLocaleString("en-PH", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })
      );
    };

    // Update the summary cards with real data
    const totalSalesElement = document.getElementById("totalSales");
    if (totalSalesElement) {
      totalSalesElement.textContent = formatCurrency(
        data.totalSales?.value || 0
      );
    }
    
    const totalExpensesElement = document.getElementById("totalExpenses");
    if (totalExpensesElement) {
      totalExpensesElement.textContent = formatCurrency(
        data.totalExpenses?.value || 0
      );
    }
    
    const netProfitElement = document.getElementById("netProfit");
    if (netProfitElement) {
      netProfitElement.textContent = formatCurrency(
        data.netProfit?.value || 0
      );
    }
    
    const totalOrdersElement = document.getElementById("totalOrders");
    if (totalOrdersElement) {
      totalOrdersElement.textContent = (data.totalOrders?.value || 0).toLocaleString("en-PH");
    }

    // Update trend indicators
    if (data.totalSales) updateTrendIndicator("totalSales", data.totalSales.change);
    if (data.totalExpenses) updateTrendIndicator("totalExpenses", data.totalExpenses.change);
    if (data.netProfit) updateTrendIndicator("netProfit", data.netProfit.change);
    if (data.totalOrders) updateTrendIndicator("totalOrders", data.totalOrders.change);
  } catch (error) {
    console.error("Error updating summary cards:", error);
  }
}

function updateTrendIndicator(elementId, changePercentage) {
  const element = document.getElementById(elementId);
  if (!element) return;
  
  const trendElement = element.nextElementSibling;
  if (!trendElement) return;
  
  const isPositive = (changePercentage || 0) >= 0;
  const isExpense = elementId === "totalExpenses";

  // For expenses, positive change is bad and negative change is good
  const isGood = isExpense ? !isPositive : isPositive;

  // Update trend icon and class
  trendElement.innerHTML = `<i class="fas fa-arrow-${
    isPositive ? "up" : "down"
  }"></i> ${Math.abs(changePercentage || 0).toFixed(1)}% vs last period`;
  trendElement.classList.remove("trend-up", "trend-down");
  trendElement.classList.add(isGood ? "trend-up" : "trend-down");
}

function initializeSalesTrendChart(data) {
  const chartCanvas = document.getElementById("salesTrendChart");
  if (!chartCanvas) {
    console.error("Sales Trend Chart canvas not found");
    return;
  }
  
  try {
    const ctx = chartCanvas.getContext("2d");
    if (!ctx) {
      console.error("Could not get 2D context for Sales Trend Chart");
      return;
    }

    // Ensure we have valid data
    const chartData = {
      labels: data.labels || [],
      current: data.current || [],
      previous: data.previous || [],
      movingAverage: data.movingAverage || []
    };
    
    // If there's an existing chart, destroy it first
    if (window.salesTrendChart && typeof window.salesTrendChart.destroy === 'function') {
      window.salesTrendChart.destroy();
    }

    window.salesTrendChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Current Period",
            data: chartData.current,
            borderColor: "#4CAF50",
            tension: 0.4,
            fill: false,
          },
          {
            label: "Previous Period",
            data: chartData.previous,
            borderColor: "#9E9E9E",
            borderDash: [5, 5],
            tension: 0.4,
            fill: false,
          },
          {
            label: "Moving Average",
            data: chartData.movingAverage,
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
                }: ₱${(context.raw || 0).toLocaleString()}`;
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
  } catch (error) {
    console.error("Error initializing Sales Trend Chart:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
}

function initializeExpensesTreemap(data) {
  const chartCanvas = document.getElementById("expensesTreemap");
  if (!chartCanvas) {
    console.error("Expenses Treemap canvas not found");
    return;
  }
  
  try {
    const ctx = chartCanvas.getContext("2d");
    if (!ctx) {
      console.error("Could not get 2D context for Expenses Treemap");
      return;
    }

    // Ensure we have valid data
    const chartData = {
      labels: data.labels || [],
      data: data.data || []
    };
    
    // If there's an existing chart, destroy it first
    if (window.expensesChart && typeof window.expensesChart.destroy === 'function') {
      window.expensesChart.destroy();
    }

    // Generate colors for each expense category
    const backgroundColors = [
      "#FF9800", "#2196F3", "#4CAF50", "#9C27B0", "#F44336", 
      "#607D8B", "#009688", "#E91E63", "#FFEB3B", "#795548"
    ];
    
    window.expensesChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            data: chartData.data,
            backgroundColor: backgroundColors.slice(0, chartData.labels.length),
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
  } catch (error) {
    console.error("Error initializing Expenses Treemap:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
}

function initializeEmployeeSalaryChart(data) {
  const chartCanvas = document.getElementById("employeeSalaryChart");
  if (!chartCanvas) {
    console.error("Employee Salary Chart canvas not found");
    return;
  }
  
  try {
    const ctx = chartCanvas.getContext("2d");
    if (!ctx) {
      console.error("Could not get 2D context for Employee Salary Chart");
      return;
    }

    // Ensure we have valid data
    const chartData = {
      labels: data.labels || [],
      individual: data.individual || [],
      total: data.total || []
    };
    
    // If there's an existing chart, destroy it first
    if (window.employeeSalaryChart && typeof window.employeeSalaryChart.destroy === 'function') {
      window.employeeSalaryChart.destroy();
    }
    
    window.employeeSalaryChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Individual Salaries",
            type: "line",
            data: chartData.individual,
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.1)",
            fill: true,
            tension: 0.4,
          },
          {
            label: "Total Payroll",
            type: "bar",
            data: chartData.total,
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
                }: ₱${(context.raw || 0).toLocaleString()}`;
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
  } catch (error) {
    console.error("Error initializing Employee Salary Chart:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
}

function initializeProductBubbleChart(data) {
  const chartCanvas = document.getElementById("productBubbleChart");
  if (!chartCanvas) {
    console.error("Product Bubble Chart canvas not found");
    
    // Try to find it by alternative selectors
    const alternativeCanvas = document.querySelector('.chart-container canvas#productBubbleChart');
    if (alternativeCanvas) {
      console.log("Found product bubble chart using alternative selector");
      initializeProductBubbleChartWithCanvas(alternativeCanvas, data);
      return;
    }
    return;
  }
  
  initializeProductBubbleChartWithCanvas(chartCanvas, data);
}

function initializeProductBubbleChartWithCanvas(chartCanvas, data) {
  try {
    const ctx = chartCanvas.getContext("2d");
    if (!ctx) {
      console.error("Could not get 2D context for Product Bubble Chart");
      return;
    }

    // Ensure we have valid data
    const chartData = {
      data: data.data || []
    };
    
    // If there's an existing chart, destroy it first
    if (window.productBubbleChart && typeof window.productBubbleChart.destroy === 'function') {
      window.productBubbleChart.destroy();
    }

    // Generate colors for each product
    const backgroundColors = [
      "rgba(76, 175, 80, 0.6)",
      "rgba(33, 150, 243, 0.6)",
      "rgba(156, 39, 176, 0.6)",
      "rgba(255, 152, 0, 0.6)",
      "rgba(244, 67, 54, 0.6)",
      "rgba(96, 125, 139, 0.6)",
      "rgba(0, 150, 136, 0.6)",
      "rgba(233, 30, 99, 0.6)",
      "rgba(255, 235, 59, 0.6)",
      "rgba(121, 85, 72, 0.6)"
    ];
    
    // Add colors to each product
    const products = chartData.data.map((item, index) => {
      return {
        ...item,
        backgroundColor: backgroundColors[index % backgroundColors.length]
      };
    });
    
    window.productBubbleChart = new Chart(ctx, {
      type: "bubble",
      data: {
        datasets: [
          {
            label: "Products",
            data: products,
            backgroundColor: products.map((p) => p.backgroundColor)
          }
        ]
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
                if (!context.raw) return [];
                return [
                  `Product: ${context.raw.name || 'Unknown'}`,
                  `Sales: ₱${(context.raw.x || 0).toLocaleString()}`,
                  `Profit: ₱${(context.raw.y || 0).toLocaleString()}`,
                  `Volume: ${(context.raw.r || 0) * 5} units`,
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
  } catch (error) {
    console.error("Error initializing Product Bubble Chart:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
}

function initializeInventoryChart(data) {
  const chartCanvas = document.getElementById("inventoryChart");
  if (!chartCanvas) {
    console.error("Inventory Chart canvas not found");
    return;
  }
  
  try {
    const ctx = chartCanvas.getContext("2d");
    if (!ctx) {
      console.error("Could not get 2D context for Inventory Chart");
      return;
    }

    // Ensure we have valid data
    const chartData = {
      labels: data.labels || [],
      normal: data.normal || [],
      low: data.low || [],
      out: data.out || []
    };
    
    // If there's an existing chart, destroy it first
    if (window.inventoryChart && typeof window.inventoryChart.destroy === 'function') {
      window.inventoryChart.destroy();
    }
    
    window.inventoryChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Normal Stock",
            data: chartData.normal,
            backgroundColor: "#4CAF50",
            borderWidth: 0,
          },
          {
            label: "Low Stock",
            data: chartData.low,
            backgroundColor: "#FFC107",
            borderWidth: 0,
          },
          {
            label: "Out of Stock",
            data: chartData.out,
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
                return `${context.dataset.label}: ${context.raw || 0} items`;
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
  } catch (error) {
    console.error("Error initializing Inventory Chart:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
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
