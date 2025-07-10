<?php
/*
This file is where users land after a successful login.
Users can see the full HTML inventory table as well as three buttons: search, update, delete
*/
session_start();
require_once 'check_login.php';

// Fetch inventory data
$sql = "SELECT * FROM InventoryTable ORDER BY ProductID ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<body>
<h2>Inventory Home Page</h2>
<p>
    <a href="search.php"><button>Search Inventory</button></a>
    <a href="update.php"><button>Update Inventory</button></a>
    <a href="delete.php"><button>Delete Inventory</button></a>
</p>

<?php
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    // Table attributes
    while ($fieldinfo = $result->fetch_field()) {
        echo "<th>{$fieldinfo->name}</th>";
    }
    // Table entries
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No inventory data found.";
}

$conn->close();
?>
</body>
</html>
