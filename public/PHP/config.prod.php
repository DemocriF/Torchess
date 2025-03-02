<?php
// config.prod.php
$servername = "localhost";
$db_username = "Fardin";
$db_password = "Fardin1380";
$dbname = "torchess_db";    // production database name (can be different if needed)

// Create connection
$conn = new mysqli("127.0.0.1", "db_user", "db_password", "torchess_db", 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
