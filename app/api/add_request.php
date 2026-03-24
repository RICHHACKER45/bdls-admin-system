<?php
// app/api/add_request.php

// 1. BUKSAN ANG SESSION PARA MAKILALA ANG ADMIN
session_start(); 
header('Content-Type: application/json');
require_once '../config/database.php';

// SECURITY LENS: I-block ang mga walang ID Lace (hindi naka-login)
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

try {
    $data = json_decode(file_get_contents("php://input"));

    // Validation para sa manual typing
    if (empty($data->first_name) || empty($data->last_name) || empty($data->document_type_id)) {
        echo json_encode(['success' => false, 'message' => 'First Name, Last Name, and Document are required.']);
        exit;
    }

    // SECURITY LENS: Simulan ang Transaction. Kapag may nag-fail sa baba, i-ro-rollback lahat.
    $pdo->beginTransaction();

    // STEP 1: I-save ang Walk-in Resident sa `users` table
    $role = 'resident';
    $userQuery = "INSERT INTO users (role, first_name, last_name) VALUES (:role, :first_name, :last_name)";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute([
        ':role' => $role,
        ':first_name' => htmlspecialchars(trim($data->first_name)), // Basic XSS protection
        ':last_name' => htmlspecialchars(trim($data->last_name))
    ]);
    
    // Kunin ang ID ng kakagawa lang na user
    $new_resident_id = $pdo->lastInsertId();

    // STEP 2: Gumawa ng Queue Number at i-save ang Service Request
    $queue_number = 'W-' . date('Ymd') . '-' . rand(100, 999); // 'W' for Walk-in
    $status = 'Pending';

    $reqQuery = "INSERT INTO service_requests (resident_id, document_type_id, queue_number, status) 
                 VALUES (:resident_id, :document_type_id, :queue_number, :status)";
    $reqStmt = $pdo->prepare($reqQuery);
    $reqStmt->execute([
        ':resident_id' => $new_resident_id,
        ':document_type_id' => $data->document_type_id,
        ':queue_number' => $queue_number,
        ':status' => $status
    ]);

    // I-commit (i-save nang tuluyan) kung walang naging error
    $pdo->commit();

    // 2. ITANIM ANG CCTV CAMERA DITO (Pagkatapos ng commit para sure na pumasok na sa DB)
    logAction($pdo, $_SESSION['admin_id'], "Generated Walk-in Request Queue # " . $queue_number);

    echo json_encode([
        'success' => true, 
        'message' => 'Walk-in request generated!',
        'queue_number' => $queue_number
    ]);

} catch (PDOException $e) {
    // I-cancel lahat ng idinagdag kung may nagka-error!
    $pdo->rollBack(); 
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}
?>