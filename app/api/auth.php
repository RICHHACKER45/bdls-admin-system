<?php
// app/api/auth.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Hanapin ang user sa database
    $query = "SELECT id, first_name, password, role FROM users WHERE username = :username AND role = 'admin' LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch();

    // SECURITY LENS: I-verify ang Bcrypt Hash
    if ($user && password_verify($password, $user['password'])) {
        // Kapag tama ang password, bigyan ng "ID Lace" (Session Variables)
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['first_name'];

        // ---> ITANIM ANG CCTV CAMERA DITO <---
        logAction($pdo, $user['id'], "Logged into the system.");
        
        // Ipasok siya sa dashboard
        header("Location: ../../public/index");
        exit;
    } else {
        // Kapag mali, ibalik sa login page at lagyan ng error message sa URL
        header("Location: ../../public/login?error=1");
        exit;
    }
} else {
    header("Location: ../../public/login");
    exit;
}
?>