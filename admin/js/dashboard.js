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
    else if (cardTitle.includes('Top Selling Products')) canvasId = 'topProductsChart';

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
                return `${context.dataset.label
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

    // Sort data by expense values (descending)
    const sortedIndices = chartData.data
      .map((value, index) => ({ value, index }))
      .sort((a, b) => b.value - a.value)
      .map(item => item.index);

    const sortedLabels = sortedIndices.map(i => chartData.labels[i]);
    const sortedData = sortedIndices.map(i => chartData.data[i]);
    const sortedColors = sortedIndices.map(i => backgroundColors[i % backgroundColors.length]);

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
            backgroundColor: sortedColors,
            borderWidth: 0,
            borderRadius: 4,
            barPercentage: 0.7,
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
            bodyFont: {
              size: 13
            },
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
          // Add data labels to show percentages
          datalabels: {
            display: true,
            color: '#fff',
            anchor: 'end',
            align: 'right',
            formatter: (value) => {
              return ((value / total) * 100).toFixed(1) + '%';
            },
            font: {
              weight: 'bold',
              size: 11
            },
            // Only display if bar is wide enough
            display: (context) => context.dataset.data[context.dataIndex] / total > 0.05
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            grid: {
              display: false
            },
            ticks: {
              callback: (value) => "₱" + value.toLocaleString()
            },
            title: {
              display: true,
              text: "Amount (₱)",
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          },
          y: {
            grid: {
              display: false
            },
            title: {
              display: true,
              text: "Expense Categories",
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          }
        }
      },
    });

    // Check if Chart.js DataLabels plugin is available
    if (window.expensesChart.options.plugins.datalabels && !Chart.plugins?.getAll().find(p => p.id === 'datalabels')) {
      // If plugin is not available, remove the configuration
      delete window.expensesChart.options.plugins.datalabels;
      console.warn("Chart.js DataLabels plugin not found, percentage labels won't be shown");
    }

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
      // If no data from API, create sample data
      bimonthlyData.datasets = [
        {
          label: "Sample Employee 1",
          data: [45000, 48000]
        },
        {
          label: "Sample Employee 2",
          data: [52000, 55000]
        },
        {
          label: "Sample Employee 3",
          data: [38000, 42000]
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

    // Define colors for employee bars
    const barColors = [
      'rgba(33, 150, 243, 0.7)', 'rgba(156, 39, 176, 0.7)', 'rgba(244, 67, 54, 0.7)',
      'rgba(76, 175, 80, 0.7)', 'rgba(255, 152, 0, 0.7)', 'rgba(0, 188, 212, 0.7)',
      'rgba(233, 30, 99, 0.7)', 'rgba(96, 125, 139, 0.7)'
    ];

    // Assign colors to datasets
    bimonthlyData.datasets.forEach((dataset, index) => {
      dataset.backgroundColor = barColors[index % barColors.length];
      dataset.borderColor = barColors[index % barColors.length].replace('0.7', '1'); // Solid border
      dataset.borderWidth = 1;
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
        plugins: {
          title: {
            display: true,
            text: "Employee Salary Performance (Bi-Monthly)",
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
            bodyFont: {
              size: 13
            },
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
              color: '#666'
            }
          },
          // Remove the unused yTotal scale definition if it exists
          // yTotal: undefined // No longer needed
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

function initializeTopProductsChart(data) {
  const chartCanvas = document.getElementById("topProductsChart");
  if (!chartCanvas) {
    console.error("Top Products Chart canvas not found");
    return;
  }

  try {
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

    // Add event listeners to toggle buttons
    newUnitButton.addEventListener('click', function () {
      if (!this.classList.contains('active')) {
        newRevenueButton.classList.remove('active');
        newRevenueButton.classList.add('btn-outline-primary');
        newRevenueButton.classList.remove('btn-primary');

        this.classList.add('active');
        this.classList.add('btn-primary');
        this.classList.remove('btn-outline-primary');

        currentMetric = 'units';
        updateChartData();
      }
    });

    newRevenueButton.addEventListener('click', function () {
      if (!this.classList.contains('active')) {
        newUnitButton.classList.remove('active');
        newUnitButton.classList.add('btn-outline-primary');
        newUnitButton.classList.remove('btn-primary');

        this.classList.add('active');
        this.classList.add('btn-primary');
        this.classList.remove('btn-outline-primary');

        currentMetric = 'revenue';
        updateChartData();
      }
    });

    // Create custom colors for bars (without gradient to avoid potential conflicts)
    const colors = [
      '#43a047', // Green
      '#1e88e5', // Blue
      '#f9a825', // Amber
      '#8e24aa', // Purple
      '#e53935'  // Red
    ];

    // Function to update chart data based on selected metric
    function updateChartData() {
      if (!window.topProductsChart) return;

      const dataset = window.topProductsChart.data.datasets[0];

      if (currentMetric === 'units') {
        dataset.data = chartData.values;
        window.topProductsChart.options.scales.x.title.text = 'Units Sold';
        dataset.label = 'Units Sold';
      } else {
        dataset.data = chartData.revenue;
        window.topProductsChart.options.scales.x.title.text = 'Revenue (₱)';
        dataset.label = 'Revenue';
      }

      window.topProductsChart.update('none'); // Use 'none' animation to prevent UI jank
    }

    // Format currency for display
    const formatCurrency = (value) => {
      return "₱" + parseFloat(value).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    };

    // Use Chart.defaults to override any conflicting Bootstrap styles
    Chart.defaults.font.family = "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#666';

    // Setup chart with optimized configuration
    window.topProductsChart = new Chart(chartCanvas, {
      type: "bar",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Units Sold",
            data: chartData.values,
            backgroundColor: chartData.labels.map((_, i) => colors[i % colors.length]),
            borderWidth: 0,
            borderRadius: 4,
            barPercentage: 0.7,
          },
        ],
      },
      options: {
        indexAxis: 'y',  // Horizontal bar chart
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 500 // Shorter animations for better performance
        },
        interaction: {
          mode: 'nearest',
          axis: 'y',
          intersect: false // This helps with cursor precision
        },
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            enabled: true,
            position: 'nearest', // Better tooltip positioning
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: {
              weight: 'bold'
            },
            bodyFont: {
              size: 13
            },
            padding: 10,
            displayColors: false, // Simplifies tooltip appearance
            callbacks: {
              label: function (context) {
                const index = context.dataIndex;
                const dataset = context.chart.data.datasets[0];

                if (dataset.label === 'Units Sold') {
                  return [
                    `Units Sold: ${chartData.values[index].toLocaleString()} units`,
                    `Revenue: ${formatCurrency(chartData.revenue[index])}`,
                    `Profit: ${formatCurrency(chartData.profit[index])}`,
                    `Current Stock: ${chartData.stock[index]} units`
                  ];
                } else {
                  return [
                    `Revenue: ${formatCurrency(chartData.revenue[index])}`,
                    `Units Sold: ${chartData.values[index].toLocaleString()} units`,
                    `Profit: ${formatCurrency(chartData.profit[index])}`,
                    `Current Stock: ${chartData.stock[index]} units`
                  ];
                }
              },
              title: function (context) {
                return context[0].label;
              }
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            grid: {
              display: false,
              drawBorder: true,
              drawOnChartArea: false
            },
            title: {
              display: true,
              text: 'Units Sold',
              font: {
                size: 14,
                weight: 'bold'
              },
              padding: {
                top: 10,
                bottom: 10
              }
            },
            ticks: {
              precision: 0,
              maxRotation: 0, // Prevent tick rotation
              autoSkip: true,
              callback: function (value) {
                if (currentMetric === 'revenue') {
                  if (value >= 1000) {
                    return '₱' + (value / 1000).toFixed(1) + 'K';
                  }
                  return '₱' + value;
                }
                return value;
              }
            }
          },
          y: {
            grid: {
              display: false,
              drawBorder: true,
              drawOnChartArea: false
            },
            title: {
              display: true,
              text: 'Products',
              font: {
                size: 14,
                weight: 'bold'
              },
              padding: {
                top: 0,
                bottom: 0
              }
            },
            ticks: {
              padding: 5, // Add some padding to prevent text clipping
              mirror: false
            }
          }
        },
        // Set a specific z-index to ensure chart elements are properly layered
        layout: {
          padding: {
            left: 10,
            right: 20,
            top: 0,
            bottom: 10
          }
        }
      },
    });

    // Initial update
    updateChartData();

  } catch (error) {
    console.error("Error initializing Top Products Chart:", error);
    const chartContainer = chartCanvas.closest('.chart-container');
    if (chartContainer) {
      chartContainer.innerHTML = '<div class="alert alert-danger">Error initializing chart. See console for details.</div>';
    }
  }
}
