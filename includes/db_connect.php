<?php
// Database configuration
$host = 'localhost';
$dbUser = 'root';
$dbPassword = 'Anahita0712*';  // Replace with your actual password
$dbName = 'cp476b_db';

// Create a new mysqli object
$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Optional: set charset to utf8mb4 for better Unicode support
$conn->set_charset("utf8mb4");
?>