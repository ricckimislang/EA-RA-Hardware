/**
 * Dashboard JavaScript for Hardware System
 * All charts are standardized to use the same height (350px) via CSS 
 * and are configured to avoid cursor positioning glitches
 */

document.addEventListener("DOMContentLoaded", function () {
  // Debug DOM structure first
  debugDashboardStructure();

  // Initialize all charts with a slight delay to ensure DOM is fully ready
  setTimeout(function () {
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

  // Add event listener for employee salary month filter
  const salaryMonthFilter = document.getElementById("employeeSalaryMonth");
  if (salaryMonthFilter) {
    salaryMonthFilter.addEventListener("change", function() {
      const chartContainer = document.querySelector('#employeeSalaryChart').closest('.chart-container');
      chartContainer.innerHTML = '<div class="text-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

      // Fetch data for the selected month
      fetch(`api/dashboard_data.php?timeRange=month&salaryMonth=${this.value}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Rebuild the chart container
            chartContainer.innerHTML = '<canvas id="employeeSalaryChart"></canvas>';
            
            // Initialize the chart with new data
            initializeEmployeeSalaryChart(data.employeeSalaries);
          } else {
            chartContainer.innerHTML = '<div class="alert alert-danger">Error loading salary data. Please try again.</div>';
          }
        })
        .catch(error => {
          console.error("Error fetching salary data:", error);
          chartContainer.innerHTML = '<div class="alert alert-danger">Error loading salary data. Please try again.</div>';
        });
    });
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

          if (window.topProductsChart && typeof window.topProductsChart.destroy === 'function') {
            window.topProductsChart.destroy();
            window.topProductsChart = null;
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
            initializeEmployeeSalaryChart(data.employeeSalaries || { labels: [], datasets: [] });
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

          const topProductsCanvas = document.getElementById("topProductsChart") ||
            document.querySelector('.chart-container canvas#topProductsChart');
          if (topProductsCanvas) {
            console.log("Initializing top products chart", data.topProducts);
            initializeTopProductsChart(data.topProducts || { labels: [], values: [], revenue: [], profit: [], stock: [] });
          } else {
            console.error("Top products chart container not found");
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
  const canvasIds = ["salesTrendChart", "expensesTreemap", "employeeSalaryChart", "productBubbleChart", "inventoryChart", "topProductsChart"];

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
    else if (cardTitle.includes('Sales Trend')) canvasId = 'topProductsChart';

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
  trendElement.innerHTML = `<i class="fas fa-arrow-${isPositive ? "up" : "down"
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

    // Create gradient for current period
    const currentGradient = ctx.createLinearGradient(0, 0, 0, 400);
    currentGradient.addColorStop(0, 'rgba(76, 175, 80, 0.8)');
    currentGradient.addColorStop(1, 'rgba(76, 175, 80, 0.1)');

    // Create gradient for moving average
    const avgGradient = ctx.createLinearGradient(0, 0, 0, 400);
    avgGradient.addColorStop(0, 'rgba(33, 150, 243, 0.7)');
    avgGradient.addColorStop(1, 'rgba(33, 150, 243, 0.1)');

    window.salesTrendChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Current Period",
            data: chartData.current,
            borderColor: "#4CAF50",
            backgroundColor: currentGradient,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: "#4CAF50",
            pointBorderColor: "#FFF",
            pointHoverRadius: 6,
            pointHoverBackgroundColor: "#FFF",
            pointHoverBorderColor: "#4CAF50",
            pointHoverBorderWidth: 3
          },
          {
            label: "Previous Period",
            data: chartData.previous,
            borderColor: "#9E9E9E",
            backgroundColor: "rgba(158, 158, 158, 0.1)",
            borderDash: [5, 5],
            tension: 0.4,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: "#9E9E9E",
            pointBorderColor: "#FFF",
          },
          {
            label: "Moving Average",
            data: chartData.movingAverage,
            borderColor: "#2196F3",
            backgroundColor: avgGradient,
            borderWidth: 2,
            pointRadius: 0,
            fill: false,
            tension: 0.4,
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
        animation: {
          duration: 1000,
          easing: 'easeOutQuart'
        },
        plugins: {
          title: {
            display: true,
            text: "Sales Revenue Overview",
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: { weight: 'bold' },
            bodyFont: { size: 13 },
            callbacks: {
              label: (context) => {
                return `${context.dataset.label}: ₱${(context.raw || 0).toLocaleString()}`;
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0,0,0,0.05)'
            },
            ticks: {
              callback: (value) => "₱" + value.toLocaleString(),
              font: { size: 11 }
            },
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: { size: 11 }
            }
          }
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

    // Generate gradient colors for each expense category
    const gradientColors = [];
    const baseColors = [
      { start: '#FF9800', end: '#F57C00' },
      { start: '#2196F3', end: '#1976D2' },
      { start: '#4CAF50', end: '#388E3C' },
      { start: '#9C27B0', end: '#7B1FA2' },
      { start: '#F44336', end: '#D32F2F' },
      { start: '#607D8B', end: '#455A64' },
      { start: '#009688', end: '#00796B' },
      { start: '#E91E63', end: '#C2185B' },
      { start: '#FFEB3B', end: '#FBC02D' },
      { start: '#795548', end: '#5D4037' }
    ];

    // Sort data by expense values (descending)
    const sortedIndices = chartData.data
      .map((value, index) => ({ value, index }))
      .sort((a, b) => b.value - a.value)
      .map(item => item.index);

    const sortedLabels = sortedIndices.map(i => chartData.labels[i]);
    const sortedData = sortedIndices.map(i => chartData.data[i]);

    // Create gradients for each bar
    sortedIndices.forEach((_, index) => {
      const colorPair = baseColors[index % baseColors.length];
      const gradient = ctx.createLinearGradient(0, 0, 400, 0);
      gradient.addColorStop(0, colorPair.start);
      gradient.addColorStop(1, colorPair.end);
      gradientColors.push(gradient);
    });

    // Calculate total for percentage calculation
    const total = sortedData.reduce((sum, value) => sum + value, 0);

    // Format currency for display
    const formatCurrency = (value) => {
      return "₱" + parseFloat(value).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    };

    window.expensesChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: sortedLabels,
        datasets: [
          {
            data: sortedData,
            backgroundColor: gradientColors,
            borderWidth: 0,
            borderRadius: 6,
            barPercentage: 0.8,
          },
        ],
      },
      options: {
        indexAxis: 'y', // Horizontal bar chart
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
        animation: {
          duration: 1200,
          easing: 'easeInOutQuart'
        },
        plugins: {
          title: {
            display: true,
            text: "Expense Categories Distribution",
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          legend: {
            display: false, // Hide legend since colors differentiate categories
          },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: { weight: 'bold' },
            bodyFont: { size: 13 },
            callbacks: {
              label: (context) => {
                const label = context.label || "";
                const value = context.raw || 0;
                const percentage = ((value / total) * 100).toFixed(1);
                return [
                  `Amount: ${formatCurrency(value)}`,
                  `Percentage: ${percentage}% of total expenses`
                ];
              },
            },
          },
        },
        scales: {
          x: {
            grid: {
              color: 'rgba(0,0,0,0.03)'
            },
            ticks: {
              font: { size: 11 }
            }
          },
          y: {
            grid: {
              display: false
            },
            ticks: {
              font: { size: 11, weight: 'bold' }
            }
          }
        }
      },
    });
  } catch (error) {
    console.error("Error initializing Expenses Chart:", error);
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

    // Create 15-day period data from the original data
    const bimonthlyData = {
      labels: ['1st-15th', '16th-End'], // Use 15-day periods instead of daily labels
      datasets: []
    };

    // If we have data from the API
    if (data && data.datasets && data.datasets.length > 0) {
      // Process each employee's data
      data.datasets.forEach(employeeData => {
        // Create a new dataset for this employee
        const newEmployeeData = {
          label: employeeData.label,
          data: [0, 0] // Initialize with two periods (1st-15th and 16th-end)
        };

        // If the original data has data points, aggregate them into 15-day periods
        if (employeeData.data && employeeData.data.length > 0) {
          // Calculate first half (days 1-15) average
          let firstHalfSum = 0;
          let firstHalfCount = 0;

          // Calculate second half (days 16+) average
          let secondHalfSum = 0;
          let secondHalfCount = 0;

          // Process each day's data
          employeeData.data.forEach((value, index) => {
            const day = index + 1; // Assuming index 0 is day 1

            if (day <= 15) {
              firstHalfSum += parseFloat(value || 0);
              firstHalfCount++;
            } else {
              secondHalfSum += parseFloat(value || 0);
              secondHalfCount++;
            }
          });

          // Assign the calculated averages to the periods
          newEmployeeData.data[0] = firstHalfCount > 0 ? firstHalfSum / firstHalfCount : 0;
          newEmployeeData.data[1] = secondHalfCount > 0 ? secondHalfSum / secondHalfCount : 0;
        }

        // Add this employee's processed data to our new dataset
        bimonthlyData.datasets.push(newEmployeeData);
      });
    } else {
      // Get the selected month from the filter if available
      const salaryMonthFilter = document.getElementById("employeeSalaryMonth");
      const selectedMonth = salaryMonthFilter ? salaryMonthFilter.value : "";
      
      // Generate sample data with some randomness based on month
      // Base amounts increase by month from May 2024 to March 2025
      const getMonthMultiplier = (monthStr) => {
        const months = {
          "2024-05": 1.00, "2024-06": 1.01, "2024-07": 1.02, "2024-08": 1.03, 
          "2024-09": 1.04, "2024-10": 1.05, "2024-11": 1.06, "2024-12": 1.07,
          "2025-01": 1.08, "2025-02": 1.09, "2025-03": 1.10
        };
        return months[monthStr] || 1.05; // Default multiplier if month not found
      };
      
      const monthMult = getMonthMultiplier(selectedMonth);
      
      // If no data from API, create sample data for all employees
      bimonthlyData.datasets = [
        {
          label: "Juan Dela Cruz (Manager)",
          data: [38000 * monthMult, 39500 * monthMult]
        },
        {
          label: "Maria Santos (Supervisor)",
          data: [32500 * monthMult, 33000 * monthMult]
        },
        {
          label: "Pedro Reyes (Cashier)",
          data: [24000 * monthMult, 24500 * monthMult]
        },
        {
          label: "Ana Martinez (Cashier)",
          data: [23800 * monthMult, 24300 * monthMult]
        },
        {
          label: "Luis Garcia (Inventory Clerk)",
          data: [26000 * monthMult, 26500 * monthMult]
        },
        {
          label: "Carlos Lopez (Sales Associate)",
          data: [22000 * monthMult, 22500 * monthMult]
        },
        {
          label: "Miguel Ramos (Staff)",
          data: [18000 * monthMult, 18500 * monthMult]
        },
        {
          label: "Rosa Mendoza (Staff)",
          data: [18200 * monthMult, 18700 * monthMult]
        },
        {
          label: "Jose Cruz (Staff)",
          data: [18500 * monthMult, 19000 * monthMult]
        },
        {
          label: "Sofia Rivera (Sales Associate)",
          data: [21800 * monthMult, 22300 * monthMult]
        },
        {
          label: "Antonio Gomez (Marketing)",
          data: [25500 * monthMult, 26000 * monthMult]
        },
        {
          label: "Isabel Ortega (Customer Service)",
          data: [22500 * monthMult, 23000 * monthMult]
        },
        {
          label: "Fernando Ruiz (Technician)",
          data: [24000 * monthMult, 24500 * monthMult]
        },
        {
          label: "Patricia Silva (Accounting)",
          data: [22000 * monthMult, 22500 * monthMult]
        },
        {
          label: "Roberto Chavez (Security)",
          data: [20500 * monthMult, 21000 * monthMult]
        },
        {
          label: "Teresa Vargas (Maintenance)",
          data: [19000 * monthMult, 19500 * monthMult]
        },
        {
          label: "Alberto Herrera (IT Support)",
          data: [28000 * monthMult, 28500 * monthMult]
        },
        {
          label: "Riccki Mislang (Cashier)",
          data: [24200 * monthMult, 24700 * monthMult]
        }
      ];
    }

    // Remove the toggle button logic if it exists
    let chartContainer = chartCanvas.closest('.card-body');
    let toggleContainer = chartContainer.querySelector('.chart-toggle');
    if (toggleContainer) {
      toggleContainer.remove();
    }

    // If there's an existing chart, destroy it first
    if (window.employeeSalaryChart && typeof window.employeeSalaryChart.destroy === 'function') {
      window.employeeSalaryChart.destroy();
    }

    // Format currency for display
    const formatCurrency = (value) => {
      return "₱" + parseFloat(value).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    };

    // Define gradient colors for employee bars
    const gradientPairs = [
      { start: 'rgba(33, 150, 243, 0.9)', end: 'rgba(33, 150, 243, 0.5)' },
      { start: 'rgba(156, 39, 176, 0.9)', end: 'rgba(156, 39, 176, 0.5)' },
      { start: 'rgba(244, 67, 54, 0.9)', end: 'rgba(244, 67, 54, 0.5)' },
      { start: 'rgba(76, 175, 80, 0.9)', end: 'rgba(76, 175, 80, 0.5)' },
      { start: 'rgba(255, 152, 0, 0.9)', end: 'rgba(255, 152, 0, 0.5)' },
      { start: 'rgba(0, 188, 212, 0.9)', end: 'rgba(0, 188, 212, 0.5)' },
      { start: 'rgba(233, 30, 99, 0.9)', end: 'rgba(233, 30, 99, 0.5)' },
      { start: 'rgba(96, 125, 139, 0.9)', end: 'rgba(96, 125, 139, 0.5)' }
    ];

    // Create gradients for datasets
    const gradientColors = [];
    bimonthlyData.datasets.forEach((_, index) => {
      const colorPair = gradientPairs[index % gradientPairs.length];
      const gradient = ctx.createLinearGradient(0, 0, 0, 400);
      gradient.addColorStop(0, colorPair.start);
      gradient.addColorStop(1, colorPair.end);
      gradientColors.push(gradient);
    });

    // Assign colors to datasets
    bimonthlyData.datasets.forEach((dataset, index) => {
      dataset.backgroundColor = gradientColors[index];
      dataset.borderColor = gradientPairs[index % gradientPairs.length].start.replace('0.9', '1');
      dataset.borderWidth = 1;
      dataset.borderRadius = 4;
    });

    // Initialize the chart as a bar chart
    window.employeeSalaryChart = new Chart(ctx, {
      type: "bar",
      data: bimonthlyData, // Use our 15-day period data
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index', // Show tooltips for all employees in that period
          intersect: false,
        },
        animation: {
          duration: 1000,
          delay: (context) => context.dataIndex * 100
        },
        plugins: {
          title: {
            display: true,
            text: "Employee Salary Distribution (Bi-Monthly)",
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          legend: {
            display: true,
            position: 'top',
            labels: {
              usePointStyle: true,
              boxWidth: 8,
              padding: 15,
              font: { size: 11 }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: { weight: 'bold' },
            bodyFont: { size: 13 },
            callbacks: {
              label: (context) => {
                const label = context.dataset.label || '';
                const value = context.raw || 0;
                return `${label}: ${formatCurrency(value)}`;
              },
            },
          },
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            title: {
              display: true,
              text: 'Bi-Monthly Pay Period',
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          },
          y: { // Single Y axis for salary
            type: 'linear',
            display: true,
            position: 'left',
            beginAtZero: true,
            title: {
              display: true,
              text: 'Net Salary (₱)',
              color: '#666',
              font: {
                size: 12,
                weight: 'bold'
              }
            },
            grid: {
              drawOnChartArea: true,
              color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
              callback: (value) => formatCurrency(value),
              color: '#666',
              font: { size: 11 }
            }
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
      console.error("Could not get 2D context for Product Chart");
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

    // Transform bubble data to line chart format
    const products = {};
    
    // Group products by name
    chartData.data.forEach(item => {
      if (!products[item.name]) {
        products[item.name] = {
          sales: [],
          profit: [],
          volume: []
        };
      }
      products[item.name].sales.push(item.x);
      products[item.name].profit.push(item.y);
      products[item.name].volume.push(item.r * 5); // Convert radius to volume
    });

    // Create datasets for the line chart
    const datasets = [];
    const colors = [
      {primary: 'rgba(76, 175, 80, 1)', secondary: 'rgba(76, 175, 80, 0.2)'},
      {primary: 'rgba(33, 150, 243, 1)', secondary: 'rgba(33, 150, 243, 0.2)'},
      {primary: 'rgba(156, 39, 176, 1)', secondary: 'rgba(156, 39, 176, 0.2)'},
      {primary: 'rgba(255, 152, 0, 1)', secondary: 'rgba(255, 152, 0, 0.2)'},
      {primary: 'rgba(244, 67, 54, 1)', secondary: 'rgba(244, 67, 54, 0.2)'},
      {primary: 'rgba(96, 125, 139, 1)', secondary: 'rgba(96, 125, 139, 0.2)'},
      {primary: 'rgba(0, 150, 136, 1)', secondary: 'rgba(0, 150, 136, 0.2)'},
      {primary: 'rgba(233, 30, 99, 1)', secondary: 'rgba(233, 30, 99, 0.2)'}
    ];
    
    // Create a time period for x-axis (last 7 days)
    const labels = [];
    const today = new Date();
    for (let i = 6; i >= 0; i--) {
      const date = new Date();
      date.setDate(today.getDate() - i);
      labels.push(date.toLocaleDateString('en-PH', {month: 'short', day: 'numeric'}));
    }
    
    // Create datasets for top 5 products
    let index = 0;
    Object.keys(products).slice(0, 5).forEach(name => {
      const color = colors[index % colors.length];
      
      // Get average sales value for this product or use a placeholder
      const avgSales = products[name].sales.length > 0 
        ? products[name].sales.reduce((a, b) => a + b, 0) / products[name].sales.length 
        : Math.random() * 5000 + 1000;

      // Generate random data points around the average if we don't have 7 days of data
      const salesData = Array(7).fill(0).map((_, i) => {
        return products[name].sales[i] !== undefined 
          ? products[name].sales[i] 
          : avgSales * (0.8 + Math.random() * 0.4); // Random value around the average
      });
      
      // Calculate max value for this dataset for highlighting
      const maxValue = Math.max(...salesData);
      const maxIndex = salesData.indexOf(maxValue);
      
      // Create fancy gradient fills with 3 color stops
      const gradientFill = ctx.createLinearGradient(0, 0, 0, chartCanvas.height);
      gradientFill.addColorStop(0, color.primary.replace('1)', '0.4)'));
      gradientFill.addColorStop(0.5, color.primary.replace('1)', '0.1)'));
      gradientFill.addColorStop(1, color.primary.replace('1)', '0.05)'));
      
      datasets.push({
        label: name,
        data: salesData,
        borderColor: color.primary,
        backgroundColor: gradientFill,
        borderWidth: 3,
        pointRadius: (ctx) => {
          // Highlight max point with larger radius
          const index = ctx.dataIndex;
          return index === maxIndex ? 6 : 0; // Only show max point by default
        },
        pointHoverRadius: 8,
        pointBackgroundColor: (ctx) => {
          const index = ctx.dataIndex;
          return index === maxIndex ? '#ffffff' : color.primary;
        },
        pointBorderColor: (ctx) => {
          const index = ctx.dataIndex;
          return index === maxIndex ? color.primary : '#ffffff';
        },
        pointBorderWidth: 2,
        pointHoverBackgroundColor: '#ffffff',
        pointHoverBorderColor: color.primary,
        pointHoverBorderWidth: 3,
        tension: 0.4,
        fill: true
      });
      
      index++;
    });

    // Add reference line for average sales
    if (datasets.length > 0) {
      // Calculate average of all product sales
      const allSalesData = datasets.flatMap(dataset => dataset.data);
      const overallAverage = allSalesData.reduce((a, b) => a + b, 0) / allSalesData.length;
      
      datasets.push({
        label: 'Average Sales',
        data: Array(7).fill(overallAverage),
        borderColor: 'rgba(180, 180, 180, 0.8)',
        borderWidth: 1,
        borderDash: [5, 5],
        pointRadius: 0,
        fill: false,
        tension: 0,
        order: 999 // Draw behind other lines
      });
    }

    window.productBubbleChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: datasets
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: 'linear',
          delay: (context) => context.datasetIndex * 150 // Staggered animation
        },
        elements: {
          line: {
            borderJoinStyle: 'round'
          }
        },
        layout: {
          padding: {
            top: 20,
            right: 25,
            bottom: 20,
            left: 15
          }
        },
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            align: 'center',
            labels: {
              usePointStyle: true,
              boxWidth: 8,
              boxHeight: 8,
              padding: 15,
              font: { size: 11 }
            }
          },
          title: {
            display: true,
            text: "Product Sales Trend",
            font: {
              size: 16,
              weight: 'bold'
            },
            padding: {
              top: 10,
              bottom: 15
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.75)',
            titleFont: { weight: 'bold', size: 12 },
            bodyFont: { size: 12 },
            padding: 12,
            usePointStyle: true,
            borderColor: 'rgba(255,255,255,0.2)',
            borderWidth: 1,
            callbacks: {
              label: (context) => {
                // Highlight max values
                const datasetIndex = context.datasetIndex;
                const dataIndex = context.dataIndex;
                const dataset = context.chart.data.datasets[datasetIndex];
                
                if (dataset.label === 'Average Sales') {
                  return `${dataset.label}: ₱${context.raw.toLocaleString()}`;
                }
                
                // Is this the highest value for this product?
                const isMax = Math.max(...dataset.data) === dataset.data[dataIndex];
                
                return `${dataset.label}: ₱${context.raw.toLocaleString()}${isMax ? ' (Highest)' : ''}`;
              },
              labelTextColor: (context) => {
                const datasetIndex = context.datasetIndex;
                const dataIndex = context.dataIndex;
                const dataset = context.chart.data.datasets[datasetIndex];
                
                if (dataset.label === 'Average Sales') {
                  return '#aaaaaa';
                }
                
                // Is this the highest value?
                const isMax = Math.max(...dataset.data) === dataset.data[dataIndex];
                
                return isMax ? '#ffffff' : '#dddddd';
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              color: 'rgba(0,0,0,0.03)',
              drawBorder: false
            },
            ticks: {
              font: { size: 11 },
              padding: 5
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0,0,0,0.05)',
              drawBorder: false
            },
            ticks: {
              callback: (value) => "₱" + value.toLocaleString(),
              font: { size: 11 },
              maxTicksLimit: 6,
              padding: 8
            },
            title: {
              display: true,
              text: "Sales (₱)",
              font: {
                size: 12,
                weight: 'bold'
              },
              padding: { top: 0, bottom: 10 }
            },
            suggestedMin: 0,
            suggestedMax: (context) => {
              // Set max a bit higher than the highest value for better visualization
              const values = context.chart.data.datasets
                .filter(d => d.label !== 'Average Sales')
                .flatMap(d => d.data);
              const max = Math.max(...values);
              return max * 1.15; // 15% higher than max value
            }
          }
        }
      }
    });
  } catch (error) {
    console.error("Error initializing Product Chart:", error);
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
    
    // Create gradients for inventory levels
    const normalGradient = ctx.createLinearGradient(0, 0, 0, 400);
    normalGradient.addColorStop(0, 'rgba(76, 175, 80, 0.9)');
    normalGradient.addColorStop(1, 'rgba(76, 175, 80, 0.6)');
    
    const lowGradient = ctx.createLinearGradient(0, 0, 0, 400);
    lowGradient.addColorStop(0, 'rgba(255, 193, 7, 0.9)');
    lowGradient.addColorStop(1, 'rgba(255, 193, 7, 0.6)');
    
    const outGradient = ctx.createLinearGradient(0, 0, 0, 400);
    outGradient.addColorStop(0, 'rgba(244, 67, 54, 0.9)');
    outGradient.addColorStop(1, 'rgba(244, 67, 54, 0.6)');

    window.inventoryChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Normal Stock",
            data: chartData.normal,
            backgroundColor: normalGradient,
            borderWidth: 1,
            borderColor: 'rgba(76, 175, 80, 1)',
            borderRadius: 4,
            barPercentage: 0.8,
            categoryPercentage: 0.7
          },
          {
            label: "Low Stock",
            data: chartData.low,
            backgroundColor: lowGradient,
            borderWidth: 1,
            borderColor: 'rgba(255, 193, 7, 1)',
            borderRadius: 4,
            barPercentage: 0.8,
            categoryPercentage: 0.7
          },
          {
            label: "Out of Stock",
            data: chartData.out,
            backgroundColor: outGradient,
            borderWidth: 1,
            borderColor: 'rgba(244, 67, 54, 1)',
            borderRadius: 4,
            barPercentage: 0.8,
            categoryPercentage: 0.7
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: "index",
          intersect: false,
        },
        animation: {
          duration: 1000,
          easing: 'easeOutCubic',
          delay: (context) => context.dataIndex * 50
        },
        layout: {
          padding: {
            top: 15,
            right: 15,
            bottom: 15,
            left: 15
          }
        },
        plugins: {
          title: {
            display: true,
            text: "Inventory Status by Category",
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: { weight: 'bold' },
            bodyFont: { size: 13 },
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.y !== null) {
                  label += context.parsed.y + ' products';
                }
                return label;
              }
            }
          },
          legend: {
            position: 'top',
            labels: {
              usePointStyle: true,
              boxWidth: 8,
              padding: 15,
              font: { size: 11 }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: { size: 11 }
            }
          },
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Products',
              font: {
                size: 12,
                weight: 'bold'
              }
            },
            grid: {
              color: 'rgba(0,0,0,0.05)'
            },
            ticks: {
              font: { size: 11 }
            }
          }
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

function initializeTopProductsChart(data) {
  const chartCanvas = document.getElementById("topProductsChart");
  if (!chartCanvas) {
    console.error("Top Products Chart canvas not found");
    return;
  }

  try {
    const ctx = chartCanvas.getContext("2d");
    if (!ctx) {
      console.error("Could not get 2D context for Top Products Chart");
      return;
    }
    
    // Ensure we have valid data
    const chartData = {
      labels: data.labels || ["Product A", "Product B", "Product C", "Product D", "Product E"],
      values: data.values || [150, 120, 90, 85, 70],
      revenue: data.revenue || [15000, 12000, 9000, 8500, 7000],
      profit: data.profit || [5000, 4000, 3000, 2800, 2300],
      stock: data.stock || [25, 30, 15, 20, 10]
    };

    // If there's an existing chart, destroy it first
    if (window.topProductsChart && typeof window.topProductsChart.destroy === 'function') {
      window.topProductsChart.destroy();
    }

    // Add toggle button for units/revenue if it doesn't exist
    let chartContainer = chartCanvas.closest('.card-body');
    let toggleContainer = chartContainer.querySelector('.chart-toggle');

    if (!toggleContainer) {
      toggleContainer = document.createElement('div');
      toggleContainer.className = 'chart-toggle d-flex justify-content-end mb-3';
      toggleContainer.innerHTML = `
        <div class="btn-group btn-group-sm" role="group">
          <button type="button" class="btn btn-primary active" data-metric="units">Units Sold</button>
          <button type="button" class="btn btn-outline-primary" data-metric="revenue">Revenue</button>
        </div>
      `;

      // Insert after the chart title
      const titleElement = chartContainer.querySelector('.card-title');
      if (titleElement) {
        titleElement.parentNode.insertBefore(toggleContainer, titleElement.nextSibling);
      } else {
        chartContainer.insertBefore(toggleContainer, chartContainer.firstChild);
      }
    }

    // Current active metric
    let currentMetric = 'units';

    // Get the buttons
    const unitButton = toggleContainer.querySelector('[data-metric="units"]');
    const revenueButton = toggleContainer.querySelector('[data-metric="revenue"]');

    // Clear any existing event listeners by cloning and replacing elements
    const newUnitButton = unitButton.cloneNode(true);
    const newRevenueButton = revenueButton.cloneNode(true);
    unitButton.parentNode.replaceChild(newUnitButton, unitButton);
    revenueButton.parentNode.replaceChild(newRevenueButton, revenueButton);

    // Create gradients for bars
    const unitsGradient = ctx.createLinearGradient(0, 400, 0, 0);
    unitsGradient.addColorStop(0, 'rgba(33, 150, 243, 0.9)');
    unitsGradient.addColorStop(1, 'rgba(33, 150, 243, 0.5)');
    
    const revenueGradient = ctx.createLinearGradient(0, 400, 0, 0);
    revenueGradient.addColorStop(0, 'rgba(76, 175, 80, 0.9)');
    revenueGradient.addColorStop(1, 'rgba(76, 175, 80, 0.5)');
    
    const profitGradient = ctx.createLinearGradient(0, 400, 0, 0);
    profitGradient.addColorStop(0, 'rgba(255, 152, 0, 0.9)');
    profitGradient.addColorStop(1, 'rgba(255, 152, 0, 0.5)');
    
    // Add event listeners to toggle buttons
    newUnitButton.addEventListener('click', function () {
      if (currentMetric !== 'units') {
        currentMetric = 'units';
        newUnitButton.classList.add('active');
        newUnitButton.classList.remove('btn-outline-primary');
        newUnitButton.classList.add('btn-primary');
        newRevenueButton.classList.remove('active');
        newRevenueButton.classList.add('btn-outline-primary');
        newRevenueButton.classList.remove('btn-primary');
        updateChartData();
      }
    });

    newRevenueButton.addEventListener('click', function () {
      if (currentMetric !== 'revenue') {
        currentMetric = 'revenue';
        newRevenueButton.classList.add('active');
        newRevenueButton.classList.remove('btn-outline-primary');
        newRevenueButton.classList.add('btn-primary');
        newUnitButton.classList.remove('active');
        newUnitButton.classList.add('btn-outline-primary');
        newUnitButton.classList.remove('btn-primary');
        updateChartData();
      }
    });

    // Initialize the chart
    window.topProductsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: chartData.labels,
        datasets: [{
          label: 'Units Sold',
          data: chartData.values,
          backgroundColor: unitsGradient,
          borderColor: 'rgba(33, 150, 243, 1)',
          borderWidth: 1,
          borderRadius: 4,
          barPercentage: 0.7,
          order: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1200,
          easing: 'easeOutQuart',
          delay: (context) => context.dataIndex * 100
        },
        layout: {
          padding: {
            top: 15,
            right: 20,
            bottom: 15,
            left: 15
          }
        },
        indexAxis: 'y',
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              usePointStyle: true,
              boxWidth: 8,
              padding: 15,
              font: { size: 11 }
            }
          },
          title: {
            display: true,
            text: 'Top Selling Products',
            font: {
              size: 16,
              weight: 'bold'
            },
            padding: {
              top: 10,
              bottom: 20
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: { weight: 'bold' },
            bodyFont: { size: 13 },
            callbacks: {
              label: function(context) {
                const label = context.dataset.label || '';
                const value = context.raw;
                if (context.dataset.label === 'Units Sold') {
                  return `${label}: ${value} units`;
                } else if (context.dataset.label === 'Revenue') {
                  return `${label}: ${formatCurrency(value)}`;
                } else if (context.dataset.label === 'Profit Margin') {
                  return `${label}: ${formatCurrency(value)}`;
                }
                return `${label}: ${value}`;
              }
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0,0,0,0.05)'
            },
            ticks: {
              font: { size: 11 }
            }
          },
          y: {
            grid: {
              display: false
            },
            ticks: {
              font: { size: 11, weight: 'bold' }
            }
          }
        }
      }
    });

    function updateChartData() {
      // Clear existing datasets
      window.topProductsChart.data.datasets = [];
      
      if (currentMetric === 'units') {
        window.topProductsChart.data.datasets.push({
          label: 'Units Sold',
          data: chartData.values,
          backgroundColor: unitsGradient,
          borderColor: 'rgba(33, 150, 243, 1)',
          borderWidth: 1,
          borderRadius: 4,
          barPercentage: 0.7,
          order: 1
        });
        
        // Add stock level as line chart
        window.topProductsChart.data.datasets.push({
          label: 'Current Stock',
          data: chartData.stock,
          type: 'line',
          borderColor: 'rgba(156, 39, 176, 0.8)',
          backgroundColor: 'rgba(156, 39, 176, 0.1)',
          borderWidth: 2,
          pointRadius: 4,
          pointBackgroundColor: 'rgba(156, 39, 176, 1)',
          pointBorderColor: '#fff',
          pointHoverRadius: 6,
          tension: 0.4,
          fill: false,
          order: 0
        });
        
        // Update x-axis label
        window.topProductsChart.options.scales.x.title = {
          display: true,
          text: 'Units',
          font: {
            size: 12,
            weight: 'bold'
          }
        };
      } else {
        window.topProductsChart.data.datasets.push({
          label: 'Revenue',
          data: chartData.revenue,
          backgroundColor: revenueGradient,
          borderColor: 'rgba(76, 175, 80, 1)',
          borderWidth: 1,
          borderRadius: 4,
          barPercentage: 0.7,
          order: 1
        });
        
        window.topProductsChart.data.datasets.push({
          label: 'Profit Margin',
          data: chartData.profit,
          backgroundColor: profitGradient,
          borderColor: 'rgba(255, 152, 0, 1)',
          borderWidth: 1,
          borderRadius: 4,
          barPercentage: 0.7,
          order: 2
        });
        
        // Update x-axis label
        window.topProductsChart.options.scales.x.title = {
          display: true,
          text: 'Amount (₱)',
          font: {
            size: 12,
            weight: 'bold'
          }
        };
        
        // Update x-axis ticks for currency
        window.topProductsChart.options.scales.x.ticks = {
          callback: (value) => "₱" + value.toLocaleString(),
          font: { size: 11 }
        };
      }
      
      window.topProductsChart.update();
    }

    const formatCurrency = (value) => {
      return "₱" + parseFloat(value).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    };

    // Initialize with default view
    updateChartData();

  } catch (error) {
    console.error("Error initializing Top Products Chart:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
}
