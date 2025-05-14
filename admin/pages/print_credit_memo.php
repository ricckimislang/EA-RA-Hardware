<?php
declare(strict_types=1);
require_once __DIR__ . '/../../database/config.php';

// Check if credit code is provided
if (!isset($_GET['code']) || empty($_GET['code'])) {
    echo "Credit code is required!";
    exit;
}

$creditCode = htmlspecialchars(trim($_GET['code']));

// Fetch credit memo data
$stmt = $conn->prepare("
    SELECT 
        sc.credit_id,
        sc.return_id,
        sc.credit_amount,
        sc.credit_code,
        sc.issue_date,
        sc.expiry_date,
        sc.used_amount,
        sc.is_active,
        rt.transaction_id,
        rt.customer_name,
        rt.contact_number,
        rt.notes,
        u.username as processed_by_username
    FROM store_credits sc
    JOIN return_transactions rt ON sc.return_id = rt.return_id
    JOIN users u ON rt.processed_by = u.id
    WHERE sc.credit_code = ?
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("s", $creditCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Credit memo not found!";
    exit;
}

$credit = $result->fetch_assoc();
$stmt->close();

// Get returned items information
$stmt = $conn->prepare("
    SELECT 
        ri.quantity,
        ri.unit_price,
        ri.subtotal,
        ri.condition,
        ri.reason_code,
        ri.other_reason,
        p.name as product_name,
        p.sku
    FROM return_items ri
    JOIN products p ON ri.product_id = p.product_id
    WHERE ri.return_id = ?
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("i", $credit['return_id']);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate remaining credit
$remainingCredit = $credit['credit_amount'] - $credit['used_amount'];

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Memo - <?php echo htmlspecialchars($creditCode); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .memo-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .memo-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .memo-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .memo-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .memo-info-block {
            flex: 1;
        }
        .memo-info-item {
            margin-bottom: 5px;
        }
        .memo-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .memo-items th, .memo-items td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .memo-items th {
            background-color: #f9f9f9;
        }
        .memo-total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }
        .memo-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .barcode {
            text-align: center;
            margin: 20px 0;
        }
        .print-buttons {
            text-align: center;
            margin: 20px 0;
        }
        .btn {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin: 0 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        @media print {
            .print-buttons, 
            .no-print {
                display: none;
            }
            body {
                background-color: white;
            }
            .memo-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="memo-container">
        <div class="print-buttons no-print">
            <button class="btn" onclick="window.print()">Print Credit Memo</button>
            <button class="btn" onclick="window.close()">Close</button>
        </div>
        
        <div class="memo-header">
            <h1>EA-RA Hardware</h1>
            <p>Tupi, South Cotabato<br>
            Phone: +63 917 123 4567<br>
            Email: earahardware@gmail.com</p>
            <div class="memo-title">STORE CREDIT MEMO</div>
        </div>
        
        <div class="memo-info">
            <div class="memo-info-block">
                <div class="memo-info-item"><strong>Credit Code:</strong> <?php echo htmlspecialchars($credit['credit_code']); ?></div>
                <div class="memo-info-item"><strong>Issue Date:</strong> <?php echo date('F j, Y', strtotime($credit['issue_date'])); ?></div>
                <div class="memo-info-item"><strong>Expiry Date:</strong> <?php echo date('F j, Y', strtotime($credit['expiry_date'])); ?></div>
                <div class="memo-info-item"><strong>Original Transaction:</strong> <?php echo htmlspecialchars($credit['transaction_id']); ?></div>
            </div>
            <div class="memo-info-block">
                <div class="memo-info-item"><strong>Customer:</strong> <?php echo htmlspecialchars($credit['customer_name'] ?? 'Not specified'); ?></div>
                <div class="memo-info-item"><strong>Contact:</strong> <?php echo htmlspecialchars($credit['contact_number'] ?? 'Not specified'); ?></div>
                <div class="memo-info-item"><strong>Processed By:</strong> <?php echo htmlspecialchars($credit['processed_by_username']); ?></div>
            </div>
        </div>
        
        <h3>Returned Items</h3>        
        <?php if (!empty($credit['notes'])): ?>
        <div style="margin-top: 20px;">
            <strong>Description:</strong> <?php echo htmlspecialchars($credit['notes']); ?>
        </div>
        <?php endif; ?>
        <table class="memo-items">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['sku']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format((float)$item['unit_price'], 2); ?></td>
                    <td>₱<?php echo number_format((float)$item['subtotal'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="memo-total">
            <div><strong>Credit Amount:</strong> ₱<?php echo number_format((float)$credit['credit_amount'], 2); ?></div>
            <?php if ($credit['used_amount'] > 0): ?>
            <div><strong>Used Amount:</strong> ₱<?php echo number_format((float)$credit['used_amount'], 2); ?></div>
            <?php endif; ?>
            <div><strong>Remaining Credit:</strong> ₱<?php echo number_format((float)$remainingCredit, 2); ?></div>
        </div>

        
        <div class="barcode">
            <!-- Placeholder for barcode -->
            <div style="padding: 10px; background: #f9f9f9; display: inline-block; font-family: monospace; font-size: 16px;">
                *<?php echo htmlspecialchars($credit['credit_code']); ?>*
            </div>
        </div>
        
        <div class="memo-footer">
            <p>This store credit is valid until <?php echo date('F j, Y', strtotime($credit['expiry_date'])); ?>.<br>
            Present this memo when redeeming credit. Credit can only be used for purchases at EA-RA Hardware.<br>
            Not redeemable for cash. No replacements will be issued for lost or stolen credit memos.</p>
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html> 