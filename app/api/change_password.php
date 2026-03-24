<?php
// app/api/change_password.php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// Check kung totoong naka-login
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $current = trim($data->current_password ?? '');
    $new = trim($data->new_password ?? '');
    $confirm = trim($data->confirm_password ?? '');

    // 1. Basic Validation
    if (empty($current) || empty($new) || empty($confirm)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }
    if ($new !== $confirm) {
        echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
        exit;
    }
    if (strlen($new) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit;
    }

    try {
        // 2. Kunin ang lumang password hash mula sa database
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['admin_id']]);
        $user = $stmt->fetch();

        // 3. I-verify kung tama ang tinype na current password
        if (!password_verify($current, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
            exit;
        }

        // 4. SECURITY LENS: I-hash ang bagong password at i-save
        $newHash = password_hash($new, PASSWORD_BCRYPT);
        $updateStmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        
        if ($updateStmt->execute([':password' => $newHash, ':id' => $_SESSION['admin_id']])) {
            // ---> ITANIM ANG CCTV CAMERA DITO <---
            logAction($pdo, $_SESSION['admin_id'], "Changed account password.");
            
            echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update database.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error.']);
    }
}
?>