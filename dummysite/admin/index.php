<?php
session_start();
// Basic authentication with credentials in code
$admin_user = "admin";
$admin_pass = "admin123";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION["admin"] = true;
    } else {
        $error = "Invalid username or password!";
    }
}

if (!isset($_SESSION["admin"])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Login</title>
    <meta name="robots" content="noindex"> <!-- Often forgotten or misconfigured -->
</head>
<body>
    <h1>Admin Login</h1>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Username: <input type="text" name="username"></label><br>
        <label>Password: <input type="password" name="password"></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="users.php">Manage Users</a></li>
            <li><a href="products.php">Manage Products</a></li>
            <li><a href="backup.php">Database Backup</a></li>
            <li><a href="config.php">System Configuration</a></li>
            <li><a href="logs.php">System Logs</a></li>
        </ul>
    </nav>
</body>
</html>