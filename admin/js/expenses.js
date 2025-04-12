$('#expenseTable').DataTable({
    responsive: true,
    dom: 'Bfrtlip',
    buttons: [
        {
            extend: 'print',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(:last-child)' // Exclude actions column
            }
        },
        {
            extend: 'excel',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(:last-child)' // Exclude actions column
            }
        }
    ],
    processing: true,
    language: {
        processing: "Loading...",
    },
    order: [[0, "desc"]], // Sort by date column descending by default
    columns: [
        { data: "date" },
        { data: "category" },
        { data: "payee" },
        { data: "amount" },
        { data: "receipt" },
        { data: "notes" },
        { data: "actions", orderable: false },
    ],
    columnDefs: [{
        targets: 3, // Amount column
        render: function (data, type, row) {
            let cleanData = typeof data === "string"
                ? data.replace(/[₱,]/g, '')
                : data;
            const formatted = '₱' + parseFloat(cleanData).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            
            if (type === 'excel' || type === 'print') {
                return 'PHP ' + parseFloat(cleanData).toFixed(2);
            }
            return formatted;
        }
    }],
    pageLength: 10,
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
    ],
});
