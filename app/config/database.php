<?php
// app/config/database.php

$host = 'localhost';
$username = 'root';
$password = 'Lookatme_45'; // Default XAMPP is empty
$dbname = 'bdls_db';

try {
    // 1. Connect first without specifying the database name
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Check if our database already exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    $dbExists = $stmt->fetch();

    if (!$dbExists) {
        // 3. If it doesn't exist, create it and use it
        $pdo->exec("CREATE DATABASE `$dbname`");
        $pdo->exec("USE `$dbname`");

        // 4. Locate the init.sql file and execute it to build tables & seed data
        $sqlFilePath = __DIR__ . '/../../database/init.sql';
        
        if (file_exists($sqlFilePath)) {
            $sql = file_get_contents($sqlFilePath);
            $pdo->exec($sql);
        }
    } else {
        // If it already exists, just connect to it
        $pdo->exec("USE `$dbname`");
    }

    // Set default fetch mode to associative array for cleaner API responses
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// ==========================================
// GLOBAL AUDIT LOGGER FUNCTION
// ==========================================
function logAction($pdo, $admin_id, $action_details) {
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (admin_id, action) VALUES (:admin_id, :action)");
        $stmt->execute([
            ':admin_id' => $admin_id,
            ':action' => $action_details
        ]);
    } catch (PDOException $e) {
        // Silent fail para hindi mag-crash ang main process kung may log error
        error_log("Audit Log Failed: " . $e->getMessage()); 
    }
}
?>