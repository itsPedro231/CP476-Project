<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("Product not specified.");
}

$inventoryId = intval($_GET['id']);

$host = 'localhost';
$dbUser = 'root';
$dbPassword = 'Anahita0712*';
$dbName = 'cp476b_db';
$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM InventoryTable WHERE InventoryID = ?");
$stmt->bind_param("i", $inventoryId);

if ($stmt->execute()) {
    header("Location: home.php");
    exit;
} else {
    echo "Failed to delete record: " . $stmt->error;
}
?>