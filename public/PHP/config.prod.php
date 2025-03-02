<?php
// Example config.prod.php
// Set the session cookie parameters to be valid for the entire domain and root path.
session_set_cookie_params([
    'lifetime' => 0,             // Session cookie expires when the browser closes
    'path' => '/',               // Available throughout the entire domain
    'domain' => $_SERVER['HTTP_HOST'],  // Use the current host (e.g., 18.117.167.197)
    'secure' => false,           // Set to true if you are using HTTPS
    'httponly' => true,
    'samesite' => 'Lax'          // or 'Strict' if you prefer; 'Lax' is usually a good balance
]);
session_start();
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

