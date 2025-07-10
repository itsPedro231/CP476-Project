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

<!-- SEARCH FORM -->
<h3>Search Inventory</h3>
<form method="POST" action="">
    <input type="text" name="search" placeholder="Enter Product ID or supplier Name" value="<?= htmlspecialchars($_POST['search'] ?? '') ?>" required>
    <input type="submit" value="Search">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search'])) {
    $searchTerm = '%' . $_POST['search'] . '%';

    $stmt = $conn->prepare("SELECT InventoryID, ProductID, ProductName, Quantity, Price, Status, SupplierName FROM InventoryTable WHERE ProductID LIKE ? OR SupplierName LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        echo '<thead><tr><th>Product ID</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Status</th><th>Supplier Name</th><th>Actions</th></tr></thead><tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['ProductID']) . '</td>';
            echo '<td>' . htmlspecialchars($row['ProductName']) . '</td>';
            echo '<td>' . htmlspecialchars($row['Quantity']) . '</td>';
            echo '<td>' . htmlspecialchars($row['Price']) . '</td>';
            echo '<td>' . htmlspecialchars($row['Status']) . '</td>';
            echo '<td>' . htmlspecialchars($row['SupplierName']) . '</td>';
            echo '<td>
                <a href="update_inventory.php?id=' . urlencode($row['InventoryID']) . '">Update</a> |
                <a href="delete_inventory.php?id=' . urlencode($row['InventoryID']) . '" onclick="return confirm(\'Are you sure?\')">Delete</a>
                </td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No results found.</p>';
    }
}
?>
