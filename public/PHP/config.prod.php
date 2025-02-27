<?php
// config.prod.php
$servername = "localhost";
$db_username = "Fardin";
$db_password = "Fardin1380";
$dbname = "torchess_db";    // production database name (can be different if needed)

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
