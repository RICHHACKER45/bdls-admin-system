<?php
// app/api/get_requests.php

// 1. Set headers so the browser knows we are sending JSON data, not HTML
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // For local development

// 2. Include the database connection securely
require_once '../config/database.php';

try {
    // 3. The Relational Query (JOIN)
    // We join service_requests with users and document_types to get readable names
    $query = "
        SELECT
            sr.id,    
            sr.queue_number, 
            u.first_name, 
            u.last_name, 
            dt.name AS document_name, 
            sr.status, 
            sr.created_at 
        FROM service_requests sr
        JOIN users u ON sr.resident_id = u.id
        JOIN document_types dt ON sr.document_type_id = dt.id
        WHERE sr.status != 'Released'
        ORDER BY sr.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // 4. Fetch all results as an associative array
    $requests = $stmt->fetchAll();

    // 5. Output the result as JSON
    echo json_encode([
        'success' => true,
        'data' => $requests
    ]);

} catch (PDOException $e) {
    // If there's an error, return a JSON error message securely
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred.' // Do not echo $e->getMessage() in production
    ]);
}
?>