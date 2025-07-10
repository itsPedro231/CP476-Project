<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

$host = 'localhost';
$dbUser = 'root';
$dbPassword = 'Anahita0712*';
$dbName = 'cp476b_db';
$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Product not specified.");
}

$inventoryId = intval($_GET['id']);

// Fetch existing data
$stmt = $conn->prepare("SELECT ProductName, Quantity, Price, Status, SupplierName FROM InventoryTable WHERE InventoryID = ?");
$stmt->bind_param("i", $inventoryId);
$stmt->execute();
$stmt->bind_result($name, $qty, $price, $status, $supplier);
if (!$stmt->fetch()) {
    die("No record found.");
}
$stmt->close();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'];
    $newQty = intval($_POST['qty']);
    $newPrice = floatval($_POST['price']);
    $newStatus = $_POST['status'];
    $newSupplier = $_POST['supplier'];

    $stmt = $conn->prepare("UPDATE InventoryTable SET ProductName=?, Quantity=?, Price=?, Status=?, SupplierName=? WHERE InventoryID=?");
    $stmt->bind_param("sidssi", $newName, $newQty, $newPrice, $newStatus, $newSupplier, $inventoryId);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit;
    } else {
        $error = "Failed to update record: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Inventory</title>
</head>
<body>
    <h2>Update Inventory Item</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Product Name: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required></label><br><br>
        <label>Quantity: <input type="number" name="qty" value="<?= htmlspecialchars($qty) ?>" required></label><br><br>
        <label>Price: <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>" required></label><br><br>
        <label>Status: <input type="text" name="status" maxlength="1" value="<?= htmlspecialchars($status) ?>" required></label><br><br>
        <label>Supplier Name: <input type="text" name="supplier" value="<?= htmlspecialchars($supplier) ?>" required></label><br><br>
        <input type="submit" value="Update">
        <a href="home.php">Cancel</a>
    </form>
</body>
</html>