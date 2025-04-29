// admin/users.php
<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

// Vulnerable database connection
$conn = new mysqli('localhost', 'admin', 'insecure_password123', 'company_db');

// SQL Injection vulnerability
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $query = "SELECT * FROM users WHERE username LIKE '%$search%'";
    $result = $conn->query($query);
} else {
    $result = $conn->query("SELECT * FROM users");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
</head>
<body>
    <h1>User Management</h1>
    <a href="index.php">Back to Dashboard</a>
    
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["created_at"] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>
</html>