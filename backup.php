<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

// Vulnerable database connection
$conn = new mysqli('localhost', 'admin', 'insecure_password123', 'company_db');

// Directory for backups
$backup_dir = "../backup/";

// Function to create database backup
function create_backup($conn, $backup_dir) {
    // Create filename with timestamp
    $filename = "db_backup_" . date("Y-m-d_H-i-s") . ".sql";
    $file_path = $backup_dir . $filename;
    
    // Get all tables
    $tables = array();
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    // Open file for writing - vulnerable to directory traversal if $backup_dir is manipulated
    $handle = fopen($file_path, 'w');
    
    // Add header
    fwrite($handle, "-- MySQL dump \n");
    fwrite($handle, "-- Database: company_db\n");
    fwrite($handle, "-- Generated on: " . date("Y-m-d H:i:s") . "\n\n");
    
    // Export tables one by one
    foreach ($tables as $table) {
        fwrite($handle, "-- Table structure for table `$table`\n");
        
        // Get create table statement
        $res = $conn->query("SHOW CREATE TABLE `$table`");
        $row = $res->fetch_row();
        fwrite($handle, $row[1] . ";\n\n");
        
        // Get table data
        $res = $conn->query("SELECT * FROM `$table`");
        if ($res->num_rows > 0) {
            fwrite($handle, "-- Data for table `$table`\n");
            
            while ($row = $res->fetch_assoc()) {
                $sql = "INSERT INTO `$table` VALUES (";
                foreach ($row as $value) {
                    $sql .= "'" . $conn->real_escape_string($value) . "',";
                }
                $sql = rtrim($sql, ",") . ");\n";
                fwrite($handle, $sql);
            }
            fwrite($handle, "\n");
        }
    }
    
    fclose($handle);
    return $filename;
}

// Handle backup creation
$backup_message = "";
if (isset($_POST['create_backup'])) {
    try {
        $filename = create_backup($conn, $backup_dir);
        $backup_message = "Backup created successfully: " . $filename;
    } catch (Exception $e) {
        $backup_message = "Error creating backup: " . $e->getMessage();
    }
}

// Handle backup restoration - insecure
$restore_message = "";
if (isset($_POST['restore_backup']) && isset($_POST['backup_file'])) {
    $backup_file = $_POST['backup_file'];
    
    // Vulnerable to arbitrary file inclusion
    $file_path = $backup_dir . $backup_file;
    
    // Read file content
    $sql = file_get_contents($file_path);
    
    // Execute SQL commands
    if ($conn->multi_query($sql)) {
        $restore_message = "Backup restored successfully!";
    } else {
        $restore_message = "Error restoring backup: " . $conn->error;
    }
}

// Handle backup deletion - vulnerable to path traversal
if (isset($_GET['delete'])) {
    $delete_file = $_GET['delete'];
    $file_path = $backup_dir . $delete_file;
    
    if (unlink($file_path)) {
        $delete_message = "Backup deleted successfully";
    } else {
        $delete_message = "Error deleting backup";
    }
}

// Get list of backup files
$backup_files = array();
if (is_dir($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $file) {
        if ($file != "." && $file != ".." && pathinfo($file, PATHINFO_EXTENSION) == "sql") {
            $backup_files[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Backup</title>
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
    </style>
</head>
<body>
    <h1>Database Backup & Restore</h1>
    <a href="index.php">Back to Dashboard</a>
    
    <h2>Create Backup</h2>
    <?php if (!empty($backup_message)): ?>
        <p><?php echo $backup_message; ?></p>
    <?php endif; ?>
    
    <form method="post">
        <button type="submit" name="create_backup">Create Database Backup</button>
    </form>
    
    <h2>Available Backups</h2>
    <?php if (!empty($delete_message)): ?>
        <p><?php echo $delete_message; ?></p>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>Filename</th>
            <th>Size</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($backup_files as $file): ?>
        <tr>
            <td><?php echo $file; ?></td>
            <td><?php echo round(filesize($backup_dir . $file) / 1024, 2); ?> KB</td>
            <td><?php echo date("Y-m-d H:i:s", filemtime($backup_dir . $file)); ?></td>
            <td>
                <a href="<?php echo "../backup/" . $file; ?>" download>Download</a> |
                <a href="?delete=<?php echo $file; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($backup_files)): ?>
        <tr>
            <td colspan="4">No backup files found</td>
        </tr>
        <?php endif; ?>
    </table>
    
    <h2>Restore from Backup</h2>
    <?php if (!empty($restore_message)): ?>
        <p><?php echo $restore_message; ?></p>
    <?php endif; ?>
    
    <form method="post">
        <select name="backup_file">
            <?php foreach ($backup_files as $file): ?>
            <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="restore_backup">Restore Database</button>
    </form>
    
    <!-- Debug information accidentally left in -->
    <!--
    DB Connection Details:
    Host: localhost
    User: admin
    Password: insecure_password123
    Database: company_db
    Backup Directory: /var/www/html/backup/
    -->
</body>
</html>