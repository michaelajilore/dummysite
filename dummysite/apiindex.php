<?php
header('Content-Type: application/json');

// No authentication required - vulnerability
$action = $_GET['action'] ?? '';

// Database connection
$conn = new mysqli('localhost', 'admin', 'insecure_password123', 'company_db');

switch($action) {
    case 'users':
        // Returns all users - data leak
        $result = $conn->query("SELECT * FROM users");
        $users = [];
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $users]);
        break;
        
    case 'user':
        // SQL injection vulnerability
        $id = $_GET['id'] ?? 0;
        $result = $conn->query("SELECT * FROM users WHERE id = $id");
        $user = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $user]);
        break;
        
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>