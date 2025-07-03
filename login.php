<?php
// Start the session
session_start();

// Define your MySQL database login credentials
$host = 'localhost';
$dbUser = 'root';
$dbPassword = 'ENTER_YOUR_UNIQUE_DB_PASS_HERE';
$dbName = 'user_system';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Check if username and password match the MySQL credentials
    if ($inputUsername === $dbUser && $inputPassword === $dbPassword) {
        // Try to connect to MySQLi to verify credentials work
        $conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die('Database connection failed: ' . $conn->connect_error);
        }

        // Login success
        $_SESSION['loggedin'] = true;
        header('Location: home.php'); // Redirect to the main page
        exit;
    } else {
        $error = 'Invalid Login Credentials';
    }
}
?>

<!-- HTML Login Form -->
<!DOCTYPE html>
<html>
<head>
    <title>MySQL Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
