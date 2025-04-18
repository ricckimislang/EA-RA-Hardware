let expenseTable = null;

function initExpensesTable() {
  if ($.fn.DataTable.isDataTable("#expenseTable")) {
    $("#expenseTable").DataTable().destroy();
    $("#expenseTable tbody").empty();
  }

  expenseTable = $("#expenseTable").DataTable({
    processing: true,
    ajax: {
      url: "api/get_expenses.php",
      dataSrc: function (json) {
        console.log("Received JSON:", json);

        if (!json || !json.data) {
          console.error("Invalid API response", json);
          return [];
        }

        try {
          updateSummaryCards(json.data.summary);
          loadCategories(json.data.expenseCategories);
        } catch (e) {
          console.error("Error during post-processing:", e);
        }

        return json.data.expenses;
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
        exportOptions: {
          columns: ":not(:last-child)", // Exclude actions column
        },
      },
      {
        extend: "excel",
        className: "btn btn-primary",
        exportOptions: {
          columns: ":not(:last-child)", // Exclude actions column
        },
      },
    ],
    processing: true,
    language: {
      processing: "Loading...",
    },
    // order: [[0, "desc"]], // Sort by date column descending by default
    columns: [
      { data: "expenseName" },
      { data: "categoryName" },
      {
        data: "amount",
        render: function (data, type) {
          const price = parseFloat(data);
          if ((type === "display" || type === "filter") && !isNaN(price)) {
            return "₱" + price.toFixed(2);
          }
          return data;
        },
      },
      {
        data: "receiptPath",
        render: function (data, type, row) {
          if (type === "display") {
            if (data) {
              return `<a href="../../${data}" target="_blank" class="btn btn-sm btn-primary">View</a>`;
            } else {
              return '<span class="badge bg-warning text-dark">No Receipt</span>';
            }
          }
          return data;
        },
      },
      { data: "notes" },
      { data: "transactionDate" },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          if (!row?.transaction_id) return "";
          return `
            <div class="action-buttons">
              <button class="btn btn-info btn-sm" onclick="editExpense(${row.transaction_id})" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger btn-sm" onclick="deleteProduct(${row.transaction_idid})" title="Delete">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          `;
        },
      },
    ],
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
  });
}

function updateSummaryCards(summary) {
  if (!summary) {
    return;
  }

  const todayTotal = parseFloat(summary.todayTotal || 0);
  const monthlyTotal = parseFloat(summary.monthlyTotal || 0);
  const totalExpense = parseFloat(summary.total_expenses || 0);
  const pendingReceipts = parseInt(summary.pendingReceipts || 0);

  $("#todayTotal").text("₱" + todayTotal.toFixed(2));
  $("#monthlyTotal").text("₱" + monthlyTotal.toFixed(2));
  $("#totalExpense").text("₱" + totalExpense.toFixed(2));
  $("#pendingReceipts").text(pendingReceipts);

  // Update card titles with current month
  const currentMonth = new Date().toLocaleString("default", { month: "long" });
  $("#monthlyTotal")
    .closest(".card")
    .find("h5")
    .text(`Total Expenses (${currentMonth})`);
  $("#todayTotal").closest(".card").find("h5").text("Today's Expenses");
}

function loadCategories(expenseCategories) {
  const category = $("#expenseCategory");
  const categoryFilter = $("#categoryFilter");
  const editCategory = $("#editExpenseCategory");

  categoryFilter.empty();
  category.empty();
  editCategory.empty();

  categoryFilter.append('<option value="">All Categories</option>');
  editCategory.append('<option value="">Select Category</option>');

  expenseCategories.forEach((categories) => {
    categoryFilter.append(
      `<option value="${categories.category_id}">${categories.name}</option>`
    );
    category.append(
      `<option value="${categories.category_id}">${categories.name}</option>`
    );
    editCategory.append(
      `<option value="${categories.category_id}">${categories.name}</option>`
    );
  });
}

// Filter handling
$("#applyFilter").click(function () {
  const categoryFilter = $("#categoryFilter").val();
  const startDate = $("#startDate").val();
  const endDate = $("#endDate").val();

  // Use client-side filtering
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const expense = expenseTable.row(dataIndex).data();

    if (!expense) return false;

    // Category matching - check if no filter or if category ID matches
    const categoryMatch =
      !categoryFilter || expense.category_id == categoryFilter;

    // Date range matching
    const transactionDate = new Date(expense.transactionDate);
    const start = startDate ? new Date(startDate + "T00:00:00") : null;
    const end = endDate ? new Date(endDate + "T23:59:59") : null;

    const dateMatch =
      (!start || transactionDate >= start) && (!end || transactionDate <= end);

    return categoryMatch && dateMatch;
  });

  expenseTable.draw();

  // Calculate and display total for filtered results
  const filteredData = expenseTable.rows({ filter: "applied" }).data();
  let total = 0;
  filteredData.each(function (row) {
    total += parseFloat(row.amount) || 0;
  });

  // Remove existing total if any
  $("#filteredTotal").remove();

  // Only show total if filter is applied
  if (startDate || endDate || categoryFilter) {
    $("#totalFilteredExpense").text("₱" + total.toFixed(2));
    $("#totalFilteredExpense").closest(".card").show();
  }

  // Clear custom filter
  $.fn.dataTable.ext.search.pop();
});

$(function () {
  initExpensesTable();
});
