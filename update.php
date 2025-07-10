<?php
/* 
If users select the "Update Inventory" button they land here
Users have the ability to update quantity, price or status if they enter a valid Product ID and Supplier Name (composite keys of inventory table)
The search is not flexible, input must be exact (except for lower/upper case)
Input from the form is also validated to ensure logical entries
Using mySQLi prepared statements and "?" parameters we update entries in the DB.
If update is successful or not successful users are notified in both cases.
*/

session_start();
require_once 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $productID = $_POST['product_id'];
    $supplierName = $_POST['supplier_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Input Validation
    $errors = [];
    if (!is_numeric($quantity) || $quantity <= 0 || $quantity > 100000) {
        $errors[] = "Quantity must be a number between 1 and 100000.";
    }
    if (!is_numeric($price) || $price <= 0 || $price > 1000000) {
        $errors[] = "Price must be a positive number under 1,000,000.";
    }
    if (!in_array($status, ['A', 'B', 'C'])) {
        $errors[] = "Status must be a single character: A, B, or C.";
    }
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        echo "<form action='home.php' method='get'>
        <button type='submit'>Return to Home</button>
        </form>";
        exit;
    }

    $sql = $conn->prepare("UPDATE InventoryTable SET Quantity = ?, Price = ?, Status = ? WHERE ProductID = ? AND SupplierName = ?");
    $sql->bind_param("idsis", $quantity, $price, $status, $productID, $supplierName);
    $sql->execute();

    echo "Record updated successfully.";
    echo "<form action='home.php' method='get'>
    <button type='submit'>Return to Home</button>
    </form>";

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['product_id'];
    $supplierName = $_POST['supplier_name'];

    $sql = $conn->prepare("SELECT * FROM InventoryTable WHERE ProductID = ? AND SupplierName = ?");
    $sql->bind_param("is", $productID, $supplierName);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        echo "<form method='post' action='update.php'>
            <input type='hidden' name='product_id' value='{$row['ProductID']}'>
            <input type='hidden' name='supplier_name' value='{$row['SupplierName']}'>
            Quantity: <input type='number' name='quantity' value='{$row['Quantity']}'><br>
            Price: <input type='text' name='price' value='{$row['Price']}'><br>
            Status: <input type='text' name='status' value='{$row['Status']}'><br>
            <input type='submit' name='update' value='Update'>
        </form>";
    } else {
        echo "Record not found.";
    }
} else {
    // Initial update form for user to enter ProductID and SupplierName
    echo "<form method='post' action='update.php'>
        Product ID: <input type='number' name='product_id' required><br>
        Supplier Name: <input type='text' name='supplier_name' required><br>
        <input type='submit' value='Find Record'>
    </form>";
}
?>
