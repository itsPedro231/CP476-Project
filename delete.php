<?php
/* 
If users select the "Delete Inventory" button they land here
Users have the ability to delete a full entry if they enter a valid Product ID and Supplier Name (composite keys of inventory table)
The search is not flexible, input must be exact (except for lower/upper case)
Using mySQLi prepared statements and "?" parameters we delete from the DB.
If deletion is successful or not successful users are notified in both cases.
*/
session_start();
require_once 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['product_id'];
    $supplierName = $_POST['supplier_name'];

    $sql = $conn->prepare("DELETE FROM InventoryTable WHERE ProductID = ? AND SupplierName = ?");

    $sql->bind_param("is", $productID, $supplierName);
    $sql->execute();

    if ($sql->affected_rows > 0) {
        echo "Record deleted successfully.<br><br>";
    } else {
        echo "No matching record found to delete.<br><br>";
    }

    echo "<form action='home.php' method='get'>
    <button type='submit'>Return to Home</button>
    </form>";

} else {
    // This is the input delete form that users must fill out to start deletion
    echo "<form method='post' action='delete.php'>
        Product ID: <input type='number' name='product_id' required><br>
        Supplier Name: <input type='text' name='supplier_name' required><br>
        <input type='submit' value='Delete Record'>
    </form>";
}
?>
