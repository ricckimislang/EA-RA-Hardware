$(document).ready(function () {
  // Initialize DataTable with AJAX loading
  initEmployeesTable();

  // Load positions for dropdown
  loadPositions();

  // Form submission
  $("#saveEmployeeBtn").on("click", function () {
    registerEmployee();
  });

  // Set today's date as default for date hired
  const today = new Date().toISOString().split("T")[0];
  $("#date_hired").val(today);

  // View employee
  $(document).on("click", ".view-employee", function () {
    const employeeId = $(this).data("id");
    viewEmployee(employeeId);
  });

  // Edit from view
  $("#editFromViewBtn").on("click", function () {
    $("#viewEmployeeModal").modal("hide");
    // Get the employee ID from a data attribute or a hidden field
    const employeeId = $(this).data("employee-id");
    editEmployee(employeeId);
  });

  // Update employee
  $("#updateEmployeeBtn").on("click", function () {
    updateEmployee();
  });

  // Delete employee
  $(document).on("click", ".delete-employee", function () {
    const employeeId = $(this).data("id");
    
    if (confirm("Are you sure you want to delete this employee? This action cannot be undone.")) {
      $.ajax({
        url: "api/employee/delete_employee.php",
        type: "POST",
        data: { id: employeeId },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            showToast("success", "Employee deleted successfully");
            reloadEmployeesTable();
          } else {
            showToast("error", "Error deleting employee: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          showToast("error", "Failed to delete employee. Please try again.");
        }
      });
    }
  });
});

// Function to initialize employees DataTable
function initEmployeesTable() {
  if ($.fn.DataTable.isDataTable("#employeesTable")) {
    $("#employeesTable").DataTable().destroy();
    $("#employeesTable tbody").empty();
  }

  employeesTable = $("#employeesTable").DataTable({
    processing: true,
    serverSide: false,
    ajax: {
      url: "api/employee/get_employees.php",
      dataSrc: function (json) {
        // console.log("Received JSON:", json);

        if (!json || !json.data) {
          console.error("Invalid API response", json);
          return [];
        }

        return json.data;
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
    language: {
      processing: "Loading...",
    },
    order: [[0, "desc"]], // Sort by ID column descending by default
    columns: [
      { data: "full_name" },
      { data: "position_title" },
      { data: "employment_type" },
      { data: "salary_rate_type" },
      { data: "date_hired" },
      { data: "contact_number" },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
                        <div class="action-buttons">
                            <button class="btn btn-info btn-sm view-employee" data-id="${row.id}" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-primary btn-sm" onclick=editEmployee(${row.id}) data-id="" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-employee" data-id="${row.id}" title="Delete">
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

// Function to load positions into the dropdown
function loadPositions() {
  $.ajax({
    url: "api/position/get_positions.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        const positionsDropdown = $("#position_id");
        positionsDropdown
          .empty()
          .append(
            '<option value="" selected disabled>Select Position</option>'
          );

        $.each(response.data, function (index, position) {
          positionsDropdown.append(
            `<option value="${position.id}">${position.title}</option>`
          );
        });
      } else {
        showToast("error", "Error loading positions: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      showToast("error", "Failed to load positions. Please try again.");
    },
  });
}

// Function to view employee details
function viewEmployee(employeeId) {
  $.ajax({
    url: "api/employee/get_employee.php",
    type: "GET",
    data: { id: employeeId },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        const employee = response.data;

        // Set employee data in the view modal
        $("#view_full_name").text(employee.full_name);
        $("#view_position").text(employee.position_title);
        $("#view_employment_type").text(employee.employment_type);
        $("#view_date_hired").text(formatDate(employee.date_hired));
        $("#view_salary_rate").text(employee.salary_rate_type);
        $("#view_overtime_rate").text(
          "₱" + parseFloat(employee.overtime_rate).toFixed(2) + "/hour"
        );
        $("#view_contact_number").text(employee.contact_number);
        $("#view_email_address").text(employee.email_address || "N/A");

        // Set government IDs if available
        if (employee.government_ids) {
          $("#view_sss_number").text(
            employee.government_ids.sss_number || "N/A"
          );
          $("#view_pagibig_number").text(
            employee.government_ids.pagibig_number || "N/A"
          );
          $("#view_philhealth_number").text(
            employee.government_ids.philhealth_number || "N/A"
          );
          $("#view_tin_number").text(
            employee.government_ids.tin_number || "N/A"
          );

          // Generate document links
          let documentHtml = "";

          if (employee.government_ids.sss_file_path) {
            documentHtml += generateDocumentLink(
              "SSS Document",
              "../../" + employee.government_ids.sss_file_path
            );
          }

          if (employee.government_ids.pagibig_file_path) {
            documentHtml += generateDocumentLink(
              "Pag-IBIG Document",
              "../../" + employee.government_ids.pagibig_file_path
            );
          }

          if (employee.government_ids.philhealth_file_path) {
            documentHtml += generateDocumentLink(
              "PhilHealth Document",
              "../../" + employee.government_ids.philhealth_file_path
            );
          }

          if (employee.government_ids.tin_file_path) {
            documentHtml += generateDocumentLink(
              "TIN Document",
              "../../" + employee.government_ids.tin_file_path
            );
          }

          if (documentHtml === "") {
            documentHtml =
              '<div class="col-md-12"><p>No documents uploaded</p></div>';
          }

          $("#document_links").html(documentHtml);
        } else {
          // No government IDs
          $(
            "#view_sss_number, #view_pagibig_number, #view_philhealth_number, #view_tin_number"
          ).text("N/A");
          $("#document_links").html(
            '<div class="col-md-12"><p>No documents uploaded</p></div>'
          );
        }

        // Set employee ID for edit button
        $("#editFromViewBtn").data("employee-id", employeeId);

        // Show the modal
        $("#viewEmployeeModal").modal("show");
      } else {
        showToast(
          "error",
          "Error loading employee details: " + response.message
        );
      }
    },
    error: function (xhr, status, error) {
      showToast("error", "Failed to load employee details. Please try again.");
    },
  });
}

// Helper function to generate document link HTML
function generateDocumentLink(title, filePath) {
  return `
        <div class="col-md-6 mb-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-pdf text-danger me-2 fa-2x"></i>
                <div>
                    <span>${title}</span><br>
                    <a href="${filePath}" target="_blank" class="small">View Document</a>
                </div>
            </div>
        </div>
    `;
}

// Helper function to format date
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

// Function to register a new employee
function registerEmployee() {
  // Validate form
  if (!validateEmployeeForm()) {
    return false;
  }

  // Get the form element
  const form = document.getElementById("addEmployeeForm");
  
  // Check if form exists
  if (!form) {
    showToast("error", "Form not found. Please refresh the page and try again.");
    return false;
  }
  
  // Create FormData object for file uploads
  const formData = new FormData(form);
  
  // Debug log what's in the FormData
  console.log("Form data being submitted:");
  
  // Check form fields
  const fields = ['full_name', 'position_id', 'employment_type', 'salary_rate_type', 'date_hired', 
    'overtime_rate', 'contact_number', 'email_address'];
    
  fields.forEach(field => {
    console.log(`${field}: ${formData.get(field)}`);
    // Make sure the field is properly added to formData
    if (field === 'employment_type' || field === 'salary_rate_type') {
      // For radio buttons, get the checked value
      const checkedRadio = document.querySelector(`input[name="${field}"]:checked`);
      if (checkedRadio) {
        formData.set(field, checkedRadio.value);
      }
    } else {
      // For other inputs, get the value from the element
      const element = document.getElementById(field);
      if (element && element.value) {
        formData.set(field, element.value);
      }
    }
  });
  
  // Check file inputs and ensure they're properly added
  const fileFields = ['sss_file', 'pagibig_file', 'philhealth_file', 'tin_file'];
  let fileTooLarge = false;
  
  fileFields.forEach(fileField => {
    const fileInput = document.getElementById(fileField);
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
      const file = fileInput.files[0];
      console.log(`${fileField}: ${file.name} (${file.size} bytes)`);
      
      // Check if file size is too large (set to 15MB to be safe with PHP's default 20MB after our changes)
      if (file.size > 15 * 1024 * 1024) {
        showToast("error", `File ${file.name} is too large. Maximum size is 15MB.`);
        fileTooLarge = true;
      }
    } else {
      console.log(`${fileField}: No file selected`);
    }
  });
  
  if (fileTooLarge) {
    return false;
  }

  // Show loading indicator
  $("#saveEmployeeBtn")
    .prop("disabled", true)
    .html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
    );

  // Send AJAX request with explicit settings
  $.ajax({
    url: "api/employee/register_employee.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false, // Important for FormData
    contentType: false, // Important for FormData
    cache: false,      // Prevent caching
    timeout: 60000,    // Set timeout to 60 seconds for large files
    success: function (response) {
      console.log("Server response:", response);
      
      if (response.status === "success") {
        // Show success message
        showToast("success", "Employee registered successfully");

        // Reset form and close modal
        $("#addEmployeeForm")[0].reset();
        $("#addEmployeeModal").modal("hide");

        // Reload the employees table or add the new employee to the table
        reloadEmployeesTable();
      } else {
        showToast("error", "Error registering employee: " + response.message);
        console.error("Registration failed:", response);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText
      });
      
      try {
        const response = JSON.parse(xhr.responseText);
        showToast("error", "Registration failed: " + (response.message || error));
      } catch (e) {
        showToast("error", "Failed to register employee. Server error or timeout.");
      }
    },
    complete: function () {
      // Reset button state
      $("#saveEmployeeBtn").prop("disabled", false).text("Register Employee");
    },
  });
}

// Function to validate the employee form
function validateEmployeeForm() {
  let isValid = true;

  // Required fields
  const requiredFields = [
    { id: "full_name", message: "Full Name is required" },
    { id: "position_id", message: "Position is required" },
    { id: "date_hired", message: "Date Hired is required" },
    { id: "overtime_rate", message: "Overtime Rate is required" },
    { id: "contact_number", message: "Contact Number is required" },
  ];

  // Check each required field
  requiredFields.forEach((field) => {
    const input = $(`#${field.id}`);
    if (!input.val()) {
      input.addClass("is-invalid");
      if (!input.next(".invalid-feedback").length) {
        input.after(`<div class="invalid-feedback">${field.message}</div>`);
      }
      isValid = false;
    } else {
      input.removeClass("is-invalid");
    }
  });

  // Validate email if provided
  const email = $("#email_address").val();
  if (email && !isValidEmail(email)) {
    $("#email_address").addClass("is-invalid");
    if (!$("#email_address").next(".invalid-feedback").length) {
      $("#email_address").after(
        '<div class="invalid-feedback">Please enter a valid email address</div>'
      );
    }
    isValid = false;
  }

  return isValid;
}

// Helper function to validate email format
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Function to reload the employees table
function reloadEmployeesTable() {
  if (employeesTable) {
    employeesTable.ajax.reload();
  }
}

// Function to show toast notifications
function showToast(type, message) {
  // You can use a toast library or Bootstrap toasts
  // For simplicity, using alert for now
  if (type === "error") {
    alert("Error: " + message);
  } else {
    alert(message);
  }
}

// Function to edit employee (populate edit modal)
function editEmployee(employeeId) {
  $.ajax({
    url: "api/employee/get_employee.php",
    type: "GET",
    data: { id: employeeId },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        const employee = response.data;
        // Set hidden ID
        $("#edit_employee_id").val(employee.id);
        // Set text fields
        $("#edit_full_name").val(employee.full_name);
        $("#edit_date_hired").val(employee.date_hired);
        $("#edit_overtime_rate").val(employee.overtime_rate);
        $("#edit_contact_number").val(employee.contact_number);
        $("#edit_email_address").val(employee.email_address || "");
        // Set position (wait for positions to be loaded)
        if ($("#edit_position_id option").length > 1) {
          $("#edit_position_id").val(employee.position_id);
        } else {
          // If not loaded yet, load and then set
          $.get(
            "api/position/get_positions.php",
            function (posResp) {
              if (posResp.status === "success") {
                const dropdown = $("#edit_position_id");
                dropdown
                  .empty()
                  .append(
                    '<option value="" selected disabled>Select Position</option>'
                  );
                $.each(posResp.data, function (index, position) {
                  dropdown.append(
                    `<option value="${position.id}">${position.title}</option>`
                  );
                });
                dropdown.val(employee.position_id);
              }
            },
            "json"
          );
        }
        // Set employment type radio
        $(
          `input[name='edit_employment_type'][value='${employee.employment_type}']`
        ).prop("checked", true);
        // Set salary rate type radio
        $(
          `input[name='edit_salary_rate_type'][value='${employee.salary_rate_type}']`
        ).prop("checked", true);
        // Set government IDs
        if (employee.government_ids) {
          $("#edit_sss_number").val(employee.government_ids.sss_number || "");
          $("#edit_pagibig_number").val(
            employee.government_ids.pagibig_number || ""
          );
          $("#edit_philhealth_number").val(
            employee.government_ids.philhealth_number || ""
          );
          $("#edit_tin_number").val(employee.government_ids.tin_number || "");
          // Optionally, show links to existing files (cannot pre-populate file inputs)
          // You can add code here to show file links if needed
        } else {
          $(
            "#edit_sss_number, #edit_pagibig_number, #edit_philhealth_number, #edit_tin_number"
          ).val("");
        }
        // Show the modal
        $("#editEmployeeModal").modal("show");
      } else {
        showToast(
          "error",
          "Error loading employee details: " + response.message
        );
      }
    },
    error: function (xhr, status, error) {
      showToast("error", "Failed to load employee details. Please try again.");
    },
  });
}

// Update the employee edited information of the employee
function updateEmployee() {
  // Validate form
  if (!validateEditEmployeeForm()) {
    return false;
  }

  // Create FormData object for file uploads
  const formData = new FormData($("#editEmployeeForm")[0]);
  
  // Ensure the employee ID is included
  const employeeId = $("#edit_employee_id").val();
  if (!employeeId) {
    showToast("error", "Missing employee ID. Please try again.");
    return false;
  }
  
  // Explicitly append important fields to ensure they're included
  formData.set('edit_employee_id', employeeId);
  
  // Debug what's in the FormData
  console.log("Employee ID being submitted:", employeeId);
  
  // Check file inputs and their data
  const fileInputs = ['edit_sss_file', 'edit_pagibig_file', 'edit_philhealth_file', 'edit_tin_file'];
  let hasFiles = false;
  
  fileInputs.forEach(inputId => {
    const fileInput = document.getElementById(inputId);
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
      hasFiles = true;
      const file = fileInput.files[0];
      console.log(`File ${inputId}:`, {
        name: file.name,
        size: file.size,
        type: file.type
      });
    }
  });
  
  if (hasFiles) {
    console.log("Form has files to upload");
  }

  // Show loading indicator
  $("#updateEmployeeBtn")
    .prop("disabled", true)
    .html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
    );

  // Send AJAX request
  $.ajax({
    url: "api/employee/update_employee.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false, // Important for FormData
    contentType: false, // Important for FormData
    success: function (response) {
      console.log("Update response:", response);
      
      if (response.success) {
        // Show success message
        showToast("success", "Employee updated successfully");

        // Reset form and close modal
        $("#editEmployeeForm")[0].reset();
        $("#editEmployeeModal").modal("hide");

        // Reload the employees table
        reloadEmployeesTable();
      } else {
        showToast("error", "Error updating employee: " + response.message);
        console.error("Update failed:", response);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText
      });
      
      try {
        const response = JSON.parse(xhr.responseText);
        showToast("error", "Update failed: " + (response.message || error));
      } catch (e) {
        showToast("error", "Failed to update employee. Server error occurred.");
      }
    },
    complete: function () {
      // Reset button state
      $("#updateEmployeeBtn").prop("disabled", false).text("Update Employee");
    }
  });
}

// Function to validate the edit employee form
function validateEditEmployeeForm() {
  let isValid = true;

  // Required fields
  const requiredFields = [
    { id: "edit_full_name", message: "Full Name is required" },
    { id: "edit_position_id", message: "Position is required" },
    { id: "edit_date_hired", message: "Date Hired is required" },
    { id: "edit_overtime_rate", message: "Overtime Rate is required" },
    { id: "edit_contact_number", message: "Contact Number is required" },
  ];

  // Check each required field
  requiredFields.forEach((field) => {
    const input = $(`#${field.id}`);
    if (!input.val()) {
      input.addClass("is-invalid");
      if (!input.next(".invalid-feedback").length) {
        input.after(`<div class="invalid-feedback">${field.message}</div>`);
      }
      isValid = false;
    } else {
      input.removeClass("is-invalid");
    }
  });

  // Validate email if provided
  const email = $("#edit_email_address").val();
  if (email && !isValidEmail(email)) {
    $("#edit_email_address").addClass("is-invalid");
    if (!$("#edit_email_address").next(".invalid-feedback").length) {
      $("#edit_email_address").after(
        '<div class="invalid-feedback">Please enter a valid email address</div>'
      );
    }
    isValid = false;
  }

  return isValid;
}
