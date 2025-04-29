<?php
function debugSystem() {
    // System information exposure
    $debug = [
        'PHP Version' => phpversion(),
        'Server Software' => $_SERVER['SERVER_SOFTWARE'],
        'Server IP' => $_SERVER['SERVER_ADDR'],
        'Document Root' => $_SERVER['DOCUMENT_ROOT'],
        'System' => php_uname(),
        'Extensions' => get_loaded_extensions(),
        'Included Files' => get_included_files(),
        'Environment Variables' => $_ENV,
        'Server Variables' => $_SERVER
    ];
    
    echo "<h1>Debug Information - CONFIDENTIAL</h1>";
    echo "<pre>";
    print_r($debug);
    echo "</pre>";
}

// Should be protected but isn't
debugSystem();
?>