<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
require_once 'includes/db_connect.php';

$sql = "SELECT * FROM InventoryTable ORDER BY ProductID ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>Inventory</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Supplier Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['ProductID'] ?></td>
                <td><?= $row['ProductName'] ?></td>
                <td><?= $row['Quantity'] ?></td>
                <td><?= $row['Price'] ?></td>
                <td><?= $row['Status'] ?></td>
                <td><?= $row['SupplierName'] ?></td>
                <td>
                    <a href="update_inventory.php?id=<?= $row['InventoryID'] ?>">Update</a> |
                    <a href="delete_inventory.php?id=<?= $row['InventoryID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>