<?php
// Error display enabled - BAD PRACTICE
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vulnerable database connection
$servername = "localhost";
$username = "admin";
$password = "insecure_password123"; 
$dbname = "company_db";

// Unhandled connection with potential error disclosure
$conn = new mysqli($servername, $username, $password, $dbname);

// SQL Injection vulnerability in search
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    // Direct injection of user input into SQL - VULNERABLE
    $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $result = $conn->query($query);
    
    if (!$result) {
        // SQL error exposure
        echo "Error in query: " . $conn->error;
    }
}

include 'header.php';
?>

<div class="main-content">
    <h1>Welcome to Example Company</h1>
    
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    
    <?php
    // Display search results with potential SQL errors exposed
    if (isset($result) && $result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row["name"] . " - $" . $row["price"] . "</li>";
        }
        echo "</ul>";
    } elseif (isset($result)) {
        echo "<p>No results found.</p>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>