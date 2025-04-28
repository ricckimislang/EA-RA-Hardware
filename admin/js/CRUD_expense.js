function addExpense() {
  if (!$("#expenseForm")[0].checkValidity()) {
    $("#expenseForm")[0].reportValidity();
    return;
  }

  // Show loading state
  $("#saveExpense")
    .prop("disabled", true)
    .html('<i class="fas fa-spinner fa-spin"></i> Saving...');

  // Check if a file was selected
  const fileInput = document.getElementById("expenseReceipt");
  if (fileInput.files.length > 0) {
    // If a file is selected, use FormData to handle file upload and expense data together
    const formData = new FormData();
    formData.append("receipt", fileInput.files[0]);
    formData.append("expenseName", $("#expenseName").val().trim());
    formData.append("expenseAmount", $("#expenseAmount").val());
    formData.append("expenseCategory", $("#expenseCategory").val());
    formData.append("expenseDate", $("#expenseDate").val());
    formData.append("expenseNotes", $("#expenseNotes").val());

    // Send data to server using FormData
    fetch("api/add_expense.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        showNotification("Expense added successfully", "success");
        return response.json();
      })
      .then((data) => handleExpenseResponse(data))
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Error adding expense. Please try again.", "error");
      })
      .finally(() => {
        // Reset button state
        $("#saveExpense").prop("disabled", false).html("Save Expense");
      });
  } else {
    // If no file was selected, use JSON for expense data only
    const formData = {
      expenseName: $("#expenseName").val().trim(),
      expenseAmount: $("#expenseAmount").val(),
      expenseCategory: $("#expenseCategory").val(),
      expenseDate: $("#expenseDate").val(),
      expenseNotes: $("#expenseNotes").val(),
    };

    // Send data to server using JSON
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
        showNotification("Expense added successfully", "success");
        return response.json();
      })
      .then((data) => handleExpenseResponse(data))
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Error adding expense. Please try again.", "error");
      })
      .finally(() => {
        // Reset button state
        $("#saveExpense").prop("disabled", false).html("Save Expense");
      });
  }
}

// Helper function to handle expense response
function handleExpenseResponse(data) {
  if (data.success) {
    // Show success message
    showNotification("Expense added successfully", "success");

    // Reset form
    $("#expenseForm")[0].reset();

    // Close modal
    $("#addExpenseModal").modal("hide");

    // Refresh table and summary
    if (typeof expenseTable !== "undefined") {
      expenseTable.ajax.reload();
    }
    updateSummaryCards();
  } else {
    showNotification("Error: " + data.message, "error");
  }
}


// READ - Edit expense (fetch expense details)
function editExpense(transaction_id) {
  if (!transaction_id || isNaN(parseInt(transaction_id))) {
    showNotification("Invalid expense ID", "error");
    return;
  }

  console.log("Fetching expense details for ID:", transaction_id);

  // Clear form before populating with new data
  $("#editExpenseForm")[0].reset();
  $("#currentReceiptContainer").addClass("d-none");

  fetch(`api/get_edit_expense.php?id=${transaction_id}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      console.log("Expense data received:", data);

      if (data.success) {
        const item = data.expense;

        // Store ID as a number to ensure proper comparison
        $("#editExpenseId").val(parseInt(item.transaction_id));

        // Set other form fields
        $("#editExpenseCategory").val(item.category_id);
        $("#editExpenseName").val(item.expense_name);
        $("#editExpenseAmount").val(item.amount);
        $("#editExpenseNotes").val(item.notes);
        $("#editExpenseDate").val(item.transaction_date);


        // Display current receipt if exists
        if (item.receipt_path) {
          $("#currentReceiptContainer").removeClass("d-none");
          const receiptUrl = "../../" + item.receipt_path;
          $("#currentReceiptImage").attr("src", receiptUrl);
          $("#viewReceiptLink").attr("href", receiptUrl);
        } else {
          $("#currentReceiptContainer").addClass("d-none");
        }

        // Show modal
        $("#editExpenseModal").modal("show");
      } else {
        showNotification("Error loading expense: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Error loading expense", "error");
    });
}

// UPDATE - Update expense details
function UpdateExpense() {
  if (!$("#editExpenseForm")[0].checkValidity()) {
    $("#editExpenseForm")[0].reportValidity();
    return;
  }

  // Get expense ID and validate
  const expenseId = $("#editExpenseId").val();
  if (!expenseId || isNaN(parseInt(expenseId))) {
    showNotification("Invalid expense ID", "error");
    return;
  }

  // Show loading state
  $("#updateExpense")
    .prop("disabled", true)
    .html('<i class="fas fa-spinner fa-spin"></i> Updating...');

  // Check if a file was selected
  const fileInput = document.getElementById("editExpenseReceipt");


  // Log the expense ID being updated
  console.log("Updating expense ID:", expenseId);

  if (fileInput.files.length > 0) {
    // If a file is selected, use FormData for the update with file
    const formData = new FormData();
    formData.append("receipt", fileInput.files[0]);
    formData.append("expenseId", expenseId);
    formData.append("expenseName", $("#editExpenseName").val().trim());
    formData.append("expenseAmount", $("#editExpenseAmount").val());
    formData.append("expenseCategory", $("#editExpenseCategory").val());
    formData.append("expenseDate", $("#editExpenseDate").val());
    formData.append("expenseNotes", $("#editExpenseNotes").val());

    // Log form data (can't directly log FormData content)
    console.log("Form data keys:", [...formData.keys()]);
    console.log("Form values:", {
      expenseId: expenseId,
      expenseName: $("#editExpenseName").val().trim(),
      expenseAmount: $("#editExpenseAmount").val(),
      expenseCategory: $("#editExpenseCategory").val(),
      expenseDate: $("#editExpenseDate").val(),
      expenseNotes: $("#editExpenseNotes").val(),
      hasFile: true
    });

    // Send form data to update_expense.php
    fetch("api/update_expense.php", {
      method: "POST",
      body: formData,
    })
      .then(response => {
        console.log("Response status:", response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        showNotification("Expense updated successfully", "success");
        return response.json(); // Parse response to JSON
      })
      .then(data => {
        console.log("Full response data:", data); // Log the complete response
        handleUpdateResult(data); // Process the response
      })
      .catch(handleUpdateError);
  } else {
    // If no new file, use JSON for the update
    const formData = {
      expenseId: parseInt(expenseId),
      expenseName: $("#editExpenseName").val().trim(),
      expenseAmount: parseFloat($("#editExpenseAmount").val()),
      expenseCategory: parseInt($("#editExpenseCategory").val()),
      expenseDate: $("#editExpenseDate").val(),
      expenseNotes: $("#editExpenseNotes").val(),
    };



    // Log the data being sent
    console.log("Sending JSON data:", formData);

    // Send JSON data to update_expense.php
    fetch("api/update_expense.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then(response => {
        console.log("Response status:", response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        showNotification("Expense updated successfully", "success");
        return response.json(); // Parse response to JSON
      })
      .then(data => {
        console.log("Full response data:", data); // Log the complete response
        handleUpdateResult(data); // Process the response
      })
      .catch(handleUpdateError);
  }
}

// Process update response result
function handleUpdateResult(data) {
  if (data.success) {
    // Show success message
    showNotification("Expense updated successfully", "success");

    // Close modal
    $("#editExpenseModal").modal("hide");

    // Refresh table and summary
    if (typeof expenseTable !== "undefined") {
      expenseTable.ajax.reload();
    }
    updateSummaryCards();
  } else {
    showNotification("Error: " + data.message, "error");
  }

  // Reset button state
  $("#updateExpense").prop("disabled", false).html("Update Expense");
}

function handleUpdateError(error) {
  console.error("Error:", error);
  showNotification("Error updating expense. Please try again.", "error");

  // Reset button state
  $("#updateExpense").prop("disabled", false).html("Update Expense");
}

// DELETE - Delete expense
function deleteExpense(transaction_id) {
  if (!transaction_id || isNaN(parseInt(transaction_id))) {
    showNotification("Invalid expense ID", "error");
    return;
  }

  // Confirm deletion with the user
  showConfirmDialog("Are you sure you want to delete this expense? This action cannot be undone.", function () {
    // Show loading state (can be implemented with a spinner or disabling the delete button)
    console.log("Deleting expense ID:", transaction_id);

    // Send delete request to server
    fetch(`api/delete_expense.php?id=${transaction_id}`, {
      method: "DELETE"
    })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        showNotification("Expense deleted successfully", "success");
        return response.json();
      })
      .then(data => { 
        console.log("Delete response:", data);

        if (data.success) {
          showNotification("Expense deleted successfully", "success");

          // Refresh table and summary
          if (typeof expenseTable !== "undefined") {
            expenseTable.ajax.reload();
          }
          updateSummaryCards();
        } else {
          showNotification("Error: " + data.message, "error");
        }
      })
      .catch(error => {
        console.error("Error:", error);
        showNotification("Error deleting expense. Please try again.", "error");
      });
  });
}

$(document).ready(function () {
  // add expense form
  $("#saveExpense").on("click", function (e) {
    e.preventDefault();
    addExpense();
  });

  // edit expense form
  $("#updateExpense").on("click", function (e) {
    e.preventDefault();
    UpdateExpense();
  });
});
