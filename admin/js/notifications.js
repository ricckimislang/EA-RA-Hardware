/**
 * notifications.js (Toastr Version)
 * Centralized Notification System for EA-RA Hardware Management System
 * Now using Toastr.js for a sleeker, consistent UX.
 */

// Set up default Toastr options
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": 300,
    "hideDuration": 1000,
    "timeOut": 4000,
    "extendedTimeOut": 1000,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

/**
 * Main notification function
 * @param {string} message - The message to display
 * @param {string} type - Type of notification ('info', 'success', 'warning', 'error')
 */
function showNotification(message, type) {
    console.log(`Notification (${type}): ${message}`);

    switch (type) {
        case 'success':
            toastr.success(message, 'Success');
            break;
        case 'error':
            toastr.error(message, 'Error');
            break;
        case 'warning':
            toastr.warning(message, 'Warning');
            break;
        default:
            toastr.info(message, 'Information');
    }
}

/**
 * Show a loading spinner notification (manual control)
 * Returns a function to hide it.
 * @param {string} message - Message to show with the spinner
 * @returns {function} hideLoading
 */
function showLoadingNotification(message = "Loading...") {
    const loadingId = `loading-${Date.now()}`;
    toastr.info(`<div class="d-flex align-items-center">
        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
        <div>${message}</div>
    </div>`, 'Loading', {
        "timeOut": 0,
        "extendedTimeOut": 0,
        "tapToDismiss": false,
        "closeButton": false,
        "escapeHtml": false
    }).attr('id', loadingId);

    return function hideLoading() {
        $('#' + loadingId).fadeOut(300, function () {
            $(this).remove();
        });
    };
}

/**
 * Show a confirmation dialog using SweetAlert2 (optional improvement)
 * @param {string} message - Confirmation message
 * @param {function} confirmCallback - Function to execute on confirm
 * @param {function} cancelCallback - Function to execute on cancel
 */
function showConfirmDialog(message, confirmCallback, cancelCallback = null) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is required for showConfirmDialog.');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof confirmCallback === 'function') {
                confirmCallback();
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            if (typeof cancelCallback === 'function') {
                cancelCallback();
            }
        }
    });
}
