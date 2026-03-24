<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $query = "
        SELECT al.action, al.created_at, u.first_name, u.last_name 
        FROM audit_logs al
        JOIN users u ON al.admin_id = u.id
        ORDER BY al.created_at DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>