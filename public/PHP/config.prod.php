<?php
// Example config.prod.php

// Use "127.0.0.1" and specify the port if needed
$db_host = '127.0.0.1';
$db_port = 3306; // Default MariaDB/MySQL port
$db_user = 'Fardin';       // The user you just created
$db_pass = 'Fardin1380';    // The password you set for db_user
$db_name = 'torchess_db';     // Your database name

// Create the connection using MySQLi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

