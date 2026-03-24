<?php
// app/api/get_logbook.php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Pinalitan natin ang updated_at ng created_at para tumugma sa database natin
    $query = "
        SELECT 
            sr.queue_number, 
            u.first_name, 
            u.last_name, 
            dt.name AS document_name, 
            sr.status, 
            sr.created_at 
        FROM service_requests sr
        JOIN users u ON sr.resident_id = u.id
        JOIN document_types dt ON sr.document_type_id = dt.id
        WHERE sr.status = 'Released'
        ORDER BY sr.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $logbook = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $logbook]);

} catch (PDOException $e) {
    // Temporary debug mode: I-papalabas natin ang totoong error para makita mo kung may mali pa
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>