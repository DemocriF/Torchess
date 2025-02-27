<?php
// config.prod.php
$servername = "your_production_db_host";
$db_username = "your_production_username";
$db_password = "your_production_password";
$dbname = "torchess_db";    // production database name (can be different if needed)

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
