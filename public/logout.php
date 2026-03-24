<?php
// public/logout.php
session_start();
require_once '../app/config/database.php'; // I-connect ang database para gumana ang logger

// I-record ang logout bago sirain ang session
if (isset($_SESSION['admin_id'])) {
    logAction($pdo, $_SESSION['admin_id'], "Logged out of the system.");
}

session_unset();     // Alisin ang lahat ng variables
session_destroy();   // Sirain ang buong session

// Ibalik sa login page
header("Location: login");
exit;
?>