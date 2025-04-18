<?php
// Disable error display in output


require_once '../../../database/config.php';

header('Content-Type: application/json');

// Verify database connection
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

try {
    // Get all products with their category and brand names
    $query = "SELECT et.* , ec.name as category_name
             FROM expense_transactions et
             LEFT JOIN expense_categories ec ON et.category_id = ec.category_id";


    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $expenses = [];
    while ($row = $result->fetch_assoc()) {
        // Convert database field names to camelCase for JavaScript
        $expenses[] = [
            'transaction_id' => $row['transaction_id'],
            'category_id' => $row['category_id'],
            'categoryName' => $row['category_name'],
            'expenseName' => $row['expense_name'],
            'amount' => (float)$row['amount'],
            'transactionDate' => $row['transaction_date'],
            'receiptPath' => $row['receipt_path'],
            'notes' => $row['notes'],
            'Date' => $row['created_at'],
        ];
    }

    // Calculate expense summary statistics
    $summary = [
        'total_expenses' => 0,
        'monthlyTotal' => 0,
        'todayTotal' => 0,
        'pendingReceipts' => 0
    ];

    foreach ($expenses as $expense) {
        $summary['total_expenses'] += $expense['amount'];

        $transactionDate = new DateTime($expense['transactionDate']);
        $currentDate = new DateTime();

        if ($transactionDate->format('Y-m') === $currentDate->format('Y-m')) {
            $summary['monthlyTotal'] += $expense['amount'];
        }

        if ($transactionDate->format('Y-m-d') === $currentDate->format('Y-m-d')) {
            $summary['todayTotal'] += $expense['amount'];
        }
    }

    // get expense categories
    $expenseCategories = [];
    $query = "SELECT category_id, name FROM expense_categories";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $expenseCategories[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'expenses' => $expenses,
            'summary' => $summary,
            'expenseCategories' => $expenseCategories
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
