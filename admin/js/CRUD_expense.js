function addExpense() {
  if (!$("#expenseForm")[0].checkValidity()) {
    $("#expenseForm")[0].reportValidity();
    return;
  }

  const formData = {
    expenseDate: $("#expenseDate").val(),
    expenseName: $("#expenseName").val().trim(),
    expenseAmount: $("#expenseAmount").val(),
    expenseCategory: $("#expenseCategory").val(),
    expenseReceipt: $("#expenseReceipt").val(),
    expenseNotes: $("#expenseNotes").val(),
  };

  // Show loading state
  $("#saveExpense")
    .prop("disabled", true)
    .html('<i class="fas fa-spinner fa-spin"></i> Saving...');

  // Send data to server
  fetch("api/add_expense.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(formData),
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
        showNotification("Expense added successfully", "success");

        // Reset form
        $("#expenseForm")[0].reset();

        // Close modal
        $("#addExpenseModal").modal("hide");

        $("#expenseForm")[0].reset();
        $("#addExpenseModal").modal("hide");
        // Refresh table and summary
        if (typeof productsTable !== "undefined") {
            expenseTable.ajax.reload();
          }
        updateSummaryCards();
      } else {
        showNotification("Error: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Error adding expense. Please try again.", "error");
    })
    .finally(() => {
      // Reset button state
      $("#saveExpense").prop("disabled", false).html("Save Expense");
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

$(document).ready(function () {
    // add expense form
  $("#saveExpense").on("click", function (e) {
    e.preventDefault();
    addExpense();
  });


});

// $(document).ready(function () {
//   // Add Expense Form Submission
//   $("#expenseForm").on("submit", function (e) {
//     e.preventDefault();
//     if (this.checkValidity()) {
//       const formData = new FormData(this);
//       $.ajax({
//         url: "api/add_expenses.php",
//         type: "POST",
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function (response) {
//           $("#addExpenseModal").modal("hide");
//           refreshExpensesTable();
//         },
//       });
//     }
//   });

//   // Edit Expense Handler
//   $(document).on("click", ".edit-expense", function () {
//     const expenseId = $(this).data("id");
//     $.get("api/expenses.php?id=" + expenseId, function (data) {
//       // Populate form fields with data
//       $("#expenseForm").trigger("reset");
//       $("#expenseId").val(expenseId);
//       $("#editExpenseModal").modal("show");
//     });
//   });

//   // Delete Expense Handler
//   $(document).on("click", ".delete-expense", function () {
//     if (confirm("Are you sure you want to delete this expense?")) {
//       const expenseId = $(this).data("id");
//       $.ajax({
//         url: "api/expenses.php",
//         type: "DELETE",
//         data: { id: expenseId },
//         success: function (response) {
//           refreshExpensesTable();
//         },
//       });
//     }
//   });

//   // Refresh Expenses Table
//   function refreshExpensesTable() {
//     $.get("api/expenses.php", function (data) {
//       $("#expensesTable").html(data);
//     });
//   }
// });
