<?php
// This file handles the SQL DB login verifications for each page.

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', 'lamia123', 'user_system');
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
