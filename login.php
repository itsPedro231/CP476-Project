<?php
session_start();
require_once 'includes/db_connect.php';

$host = 'localhost';
$dbUser = 'root';
$dbPassword = 'Anahita0712*';
$dbName = 'cp476b_db';

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ''; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($inputPassword, $hashedPassword)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $inputUsername;
            header("Location: home.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Username does not exist.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login">

        <h1>Member Login</h1>

        <?php if ($error): ?>
            <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="" class="form login-form">

            <label class="form-label" for="username">Username</label>
            <div class="form-group">
                <input class="form-input" type="text" name="username" id="username" placeholder="Username" required>
            </div>

            <label class="form-label" for="password">Password</label>
            <div class="form-group">
                <input class="form-input" type="password" name="password" id="password" placeholder="Password" required>
            </div>

            <button class="btn blue" type="submit">Login</button>

        </form>

    </div>
</body>
</html>