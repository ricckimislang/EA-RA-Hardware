<?php
require_once '../../../../database/config.php';

header('Content-Type: application/json');

// Start a session to get current user ID (if available)
session_start();
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

try {
    // Validate required fields
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("Cash advance ID is required");
    }
    
    if (!isset($_POST['status']) || empty($_POST['status'])) {
        throw new Exception("Status is required");
    }
    
    // Extract and sanitize inputs
    $advanceId = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);
    $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
    
    // Validate status
    if (!in_array($status, ['approved', 'rejected'])) {
        throw new Exception("Invalid status. Status must be 'approved' or 'rejected'");
    }
    
    // Check if the cash advance exists and is in pending status
    $checkSql = "SELECT id, status, employee_id FROM cash_advances WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('i', $advanceId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        throw new Exception("Cash advance not found");
    }
    
    $advance = $checkResult->fetch_assoc();
    
    if ($advance['status'] !== 'pending') {
        throw new Exception("Cannot update status. Cash advance is already {$advance['status']}");
    }
    
    // Prepare approval date only if approving
    $approvalDate = ($status === 'approved') ? date('Y-m-d') : null;
    
    // Update the cash advance status
    $sql = "UPDATE cash_advances 
            SET status = ?, 
                approval_date = ?, 
                approved_by = ?, 
                notes = CONCAT(notes, IF(notes = '', '', '\n\n'), ?)
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    
    // Add approval info to notes
    $statusNote = "[$status on " . date('Y-m-d H:i:s') . "] $notes";
    
    // Bind parameters
    $stmt->bind_param('ssisi', $status, $approvalDate, $currentUserId, $statusNote, $advanceId);
    
    if ($stmt->execute()) {
        // Return success response
        echo json_encode([
            'status' => 'success',
            'message' => "Cash advance request $status successfully",
            'data' => [
                'id' => $advanceId,
                'new_status' => $status,
                'approval_date' => $approvalDate
            ]
        ]);
    } else {
        throw new Exception("Error updating cash advance status: " . $conn->error);
    }
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 