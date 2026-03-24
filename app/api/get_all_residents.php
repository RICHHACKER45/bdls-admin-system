<?php
// app/api/get_all_residents.php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Kunin lahat ng users na 'resident' ang role
    $query = "SELECT id, first_name, last_name, date_of_birth FROM users WHERE role = 'resident' ORDER BY first_name ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $residents = $stmt->fetchAll();

    // I-compute ang Edad (Age) gamit ang PHP DateTime
    foreach ($residents as &$resident) {
        if (!empty($resident['date_of_birth'])) {
            $dob = new DateTime($resident['date_of_birth']);
            $now = new DateTime(); // Current date (e.g., March 24, 2026)
            $age = $now->diff($dob)->y; // Kunin ang year difference
            $resident['age'] = $age;
        } else {
            // Kung walk-in at walang inilagay na birthday
            $resident['age'] = 'N/A';
        }
    }

    echo json_encode(['success' => true, 'data' => $residents]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>