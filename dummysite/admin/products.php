<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

// Vulnerable database connection
$conn = new mysqli('localhost', 'admin', 'insecure_password123', 'company_db');

// Check if form was submitted to add a product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // SQL Injection vulnerability - no prepared statements
    $query = "INSERT INTO products (name, description, price, stock) VALUES ('$name', '$description', $price, $stock)";
    $result = $conn->query($query);
    
    if (!$result) {
        $error = "Error adding product: " . $conn->error;
    } else {
        $success = "Product added successfully!";
    }
}

// Delete product - vulnerable to CSRF
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
}

// SQL Injection vulnerability in search
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $result = $conn->query($query);
    
    if (!$result) {
        $error = "Error in search: " . $conn->error;
    }
} else {
    $result = $conn->query("SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Product Management</h1>
    <a href="index.php">Back to Dashboard</a>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <h2>Add New Product</h2>
    <form action="" method="POST">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Description: <textarea name="description"></textarea></label><br>
        <label>Price: <input type="number" name="price" step="0.01" required></label><br>
        <label>Stock: <input type="number" name="stock" required></label><br>
        <button type="submit" name="add_product">Add Product</button>
    </form>
    
    <h2>Product List</h2>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>$" . $row["price"] . "</td>";
                echo "<td>" . $row["stock"] . "</td>";
                echo "<td>
                    <a href='edit_product.php?id=" . $row["id"] . "'>Edit</a> |
                    <a href='?delete=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found</td></tr>";
        }
        ?>
    </table>
    
    <!-- Debug info accidentally left in -->
    <!--
    TODO: Implement proper input validation and prepared statements
    Database details: localhost, admin, insecure_password123, company_db
    -->
</body>
</html>