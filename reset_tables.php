<?php
/*
This file serves as a way for our group to reset the tables with the original data provided in Supplier.txt and Product.txt
Data is grabbed directly from these txt files and whenever this page is refreshed the DB is reset.
*/ 

session_start();
require_once 'check_login.php';

$conn->query("SET FOREIGN_KEY_CHECKS=0");
$conn->query("DROP TABLE IF EXISTS InventoryTable");
$conn->query("DROP TABLE IF EXISTS ProductTable");
$conn->query("DROP TABLE IF EXISTS SupplierTable");
$conn->query("SET FOREIGN_KEY_CHECKS=1");

// Create SupplierTable
$sql = "CREATE TABLE IF NOT EXISTS SupplierTable (
    SupplierID INT PRIMARY KEY, -- <<PK>>
    SupplierName VARCHAR(255),
    Address VARCHAR(255),
    Phone VARCHAR(50),
    Email VARCHAR(255)
)";
$conn->query($sql);

// Create ProductTable
$sql = "CREATE TABLE ProductTable (
    ProductID INT,
    ProductName VARCHAR(255),
    Description VARCHAR(255),
    Price DECIMAL(10,2),
    Quantity INT,
    Status CHAR(1),
    SupplierID INT,
    PRIMARY KEY (ProductID, SupplierID), -- <<Composite PK>>
    FOREIGN KEY (SupplierID) REFERENCES SupplierTable(SupplierID) -- <<FK>>
)";
$conn->query($sql);

// Create InventoryTable
$sql = "CREATE TABLE InventoryTable (
    ProductID INT,
    ProductName VARCHAR(255),
    Quantity INT,
    Price DECIMAL(10,2),
    Status CHAR(1),
    SupplierName VARCHAR(255),
    PRIMARY KEY (ProductID, SupplierName) -- <<Composite PK>>
)";

$conn->query($sql);


// Load Supplier Data
$supplierFile = fopen('Supplier.txt', 'r');
while (($line = fgets($supplierFile)) !== false) {
    $parts = array_map('trim', explode(',', $line));
    $sql = "INSERT INTO SupplierTable (SupplierID, SupplierName, Address, Phone, Email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issss', $parts[0], $parts[1], $parts[2], $parts[3], $parts[4]);
    $stmt->execute();
}
fclose($supplierFile);

// Load Product Data
$productFile = fopen('Product.txt', 'r');
while (($line = fgets($productFile)) !== false) {
    $parts = array_map('trim', explode(',', $line));
    $sql = "INSERT INTO ProductTable (ProductID, ProductName, Description, Price, Quantity, Status, SupplierID) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issdiss', $parts[0], $parts[1], $parts[2], $parts[3], $parts[4], $parts[5], $parts[6]);
    $stmt->execute();
}
fclose($productFile);

// Populate InventoryTable

$sql = "
INSERT INTO InventoryTable (ProductID, ProductName, Quantity, Price, Status, SupplierName)
SELECT 
    p.ProductID,
    p.ProductName,
    p.Quantity,
    p.Price,
    p.Status,
    s.SupplierName
FROM ProductTable p
JOIN SupplierTable s ON p.SupplierID = s.SupplierID
ORDER BY p.ProductID ASC;
";
$conn->query($sql);

// Create a function to display the tables

function displayTable($conn, $tableName) {
    echo "<h2>$tableName</h2>";
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";

        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>{$fieldinfo->name}</th>";
        }

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No data found in $tableName.";
    }
}

displayTable($conn, 'SupplierTable');
displayTable($conn, 'ProductTable');
displayTable($conn, 'InventoryTable');

$conn->close();
?>
