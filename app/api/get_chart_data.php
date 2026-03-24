<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $query = "SELECT status, COUNT(*) as total FROM service_requests GROUP BY status";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>