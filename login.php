<?php
/*
This file handles the HTML login form and PHP web logic on the initial page that users land on. 
It verifies form input against predefined login credentials for the mySQL DB.
If the input matches, a mySQLi connection is opened and the user is directed to our homepage.
Otherwise let the user know that they have inputted invalid login credentials. 
*/

session_start();

// MySQL database login credentials
$host = 'localhost';
$dbUser = 'root';
$dbPassword = 'lamia123';
$dbName = 'user_system';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    if ($inputUsername === $dbUser && $inputPassword === $dbPassword) {
        // Create mySQLi Connection
        $conn = new mysqli($host, $dbUser, $dbPassword, $dbName);
        // If the connection fails, its a DB connection error
        if ($conn->connect_error) {
            die('Database connection failed: ' . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
        // Login success
        $_SESSION['loggedin'] = true;
        header('Location: home.php');
        exit;
    // Credentials don't match
    } else {
        $error = 'Invalid Login Credentials';
    }
}

// HTML Login Form 
?>
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
