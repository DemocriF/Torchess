<?php
// config.php

$servername = "localhost";
$db_username = "root";      // default for XAMPP
$db_password = "";          // default is usually empty
$dbname = "torchess_db";    // ensure this matches the name of your database

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
