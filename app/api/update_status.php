<?php
// app/api/update_status.php

// 1. BUKSAN ANG SESSION PARA MAKILALA ANG ADMIN
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// SECURITY LENS: I-block ang mga walang ID Lace
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
    exit;
}

// 2. Strict Method Check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid method.']);
    exit;
}

try {
    // 3. Kunin ang JSON payload
    $data = json_decode(file_get_contents("php://input"));

    // Validation
    if (empty($data->id) || empty($data->status)) {
        echo json_encode(['success' => false, 'message' => 'Missing ID or Status.']);
        exit;
    }

    // --- NEW: KUNIN ANG QUEUE NUMBER PARA SA MAGANDANG AUDIT LOG ---
    $qStmt = $pdo->prepare("SELECT queue_number FROM service_requests WHERE id = :id");
    $qStmt->execute([':id' => $data->id]);
    $requestData = $qStmt->fetch();
    $queue_number = $requestData ? $requestData['queue_number'] : "Unknown ID";

    // 4. SECURITY LENS: The UPDATE Query with Parameter Binding
    $query = "UPDATE service_requests SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':status', $data->status, PDO::PARAM_STR);
    $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);

    // 5. Execute and return result
    if ($stmt->execute()) {
        
        // --- ITANIM ANG CCTV CAMERA DITO ---
        logAction($pdo, $_SESSION['admin_id'], "Updated Queue # " . $queue_number . " to " . $data->status);

        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
?>