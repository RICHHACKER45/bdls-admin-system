<?php
// app/api/get_residents_by_doc.php
header('Content-Type: application/json');
require_once '../config/database.php';

// Check kung may ipinasang doc_id yung URL
if (!isset($_GET['doc_id']) || empty($_GET['doc_id'])) {
    echo json_encode(['success' => false, 'data' => []]);
    exit;
}

try {
    // Kukunin natin ang mga unique na pangalan ng users na nag-request ng specific document
    $query = "
        SELECT DISTINCT u.first_name, u.last_name 
        FROM users u
        JOIN service_requests sr ON u.id = sr.resident_id
        WHERE sr.document_type_id = :doc_id
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':doc_id', $_GET['doc_id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $residents = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $residents]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>