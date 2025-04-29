<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

// Vulnerable file reading - allows path traversal
$log_file = $_GET['file'] ?? 'server.log';
$log_path = "../logs/" . $log_file;  // Vulnerable to path traversal

// Function to read logs - unprotected
function read_log($file_path) {
    if (file_exists($file_path)) {
        return file_get_contents($file_path);
    } else {
        return "Log file not found!";
    }
}

// Read the log file content
$log_content = read_log($log_path);

// Get available logs
$available_logs = array_diff(scandir("../logs/"), array('..', '.'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Logs</title>
    <style>
        pre {
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
            overflow: auto;
            height: 400px;
        }
    </style>
</head>
<body>
    <h1>System Logs</h1>
    <a href="index.php">Back to Dashboard</a>
    
    <h2>Available Logs</h2>
    <ul>
        <?php foreach($available_logs as $log): ?>
        <li><a href="?file=<?php echo $log; ?>"><?php echo $log; ?></a></li>
        <?php endforeach; ?>
    </ul>
    
    <h2>Log Content: <?php echo htmlspecialchars($log_file); ?></h2>
    
    <!-- Custom log viewer with vulnerability -->
    <form action="" method="GET">
        <input type="text" name="file" placeholder="Enter log filename..." value="<?php echo htmlspecialchars($log_file); ?>">
        <button type="submit">View Log</button>
    </form>
    
    <pre><?php echo htmlspecialchars($log_content); ?></pre>
    
    <!-- Debug information accidentally left in -->
    <!-- 
    TODO: Remove this before production
    Admin credentials: admin/admin123
    Log directory: /var/www/html/logs/
    -->
</body>
</html>