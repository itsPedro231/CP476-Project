<?php
/* 
If users select the "Search Inventory" button they land here
Users have the ability to search by Product ID, Name or Supplier
The search is flexible (not case-sensitive, allows for partial word matches, etc) 
Using mySQLi prepared statements and "?" parameters we query the DB.
If there are no records that match the input, users are notified. 
*/

session_start();
require_once 'check_login.php';

$query = $_GET['query'] ?? '';
?>

<form method="get" action="search.php">
    <input type="text" name="query" placeholder="Search by Product ID, Name, or Supplier", size="32">
    <input type="submit" value="Search">
</form>

<?php
if ($query !== '') {
    $like = "%$query%";
    $sql = $conn->prepare("SELECT * FROM InventoryTable WHERE ProductID = ? OR ProductName LIKE ? OR SupplierName LIKE ?");
    $sql->bind_param("iss", $query, $like, $like);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ProductID</th><th>ProductName</th><th>Quantity</th><th>Price</th><th>Status</th><th>SupplierName</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['ProductID']}</td>
                <td>{$row['ProductName']}</td>
                <td>{$row['Quantity']}</td>
                <td>{$row['Price']}</td>
                <td>{$row['Status']}</td>
                <td>{$row['SupplierName']}</td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No matching records found.</p>";
    }
}
?>

