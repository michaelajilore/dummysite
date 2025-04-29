<?php
// Database Settings
$config = [
    'db_host' => 'localhost',
    'db_user' => 'admin',
    'db_pass' => 'insecure_password123',
    'db_name' => 'company_db',
    
    // API Keys - Would be exposed
    'api_key' => 'ak_live_51abcXYZ123456789',
    'google_maps_key' => 'AIzaSyA1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6',
    'smtp_user' => 'notifications@example.com',
    'smtp_pass' => 'email_password_123',
    
    // Site settings
    'debug_mode' => true,
    'maintenance_mode' => false
];

// Debug output - would expose all configuration
if ($config['debug_mode']) {
    echo "<pre>";
    print_r($config);
    echo "</pre>";
}
?>