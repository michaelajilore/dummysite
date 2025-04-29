<?php
// Theme functions
function theme_setup() {
    // Theme setup code
}
add_action('after_setup_theme', 'theme_setup');

// Insecure database connection (bad practice)
function custom_db_connect() {
    $connection = mysqli_connect('localhost', 'admin', 'insecure_password123', 'wp_database');
    if (!$connection) {
        // Error exposure
        die('Database connection failed: ' . mysqli_connect_error());
    }
    return $connection;
}

// Vulnerable to SQL injection
function get_custom_posts($category) {
    $db = custom_db_connect();
    $query = "SELECT * FROM wp_posts WHERE post_category = '$category'";
    $result = mysqli_query($db, $query);
    return $result;
}
?>