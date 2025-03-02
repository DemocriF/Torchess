<?php
// signup.php
session_start();
require_once 'config.php';  // Ensure this file is in the same PHP directory

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    header("Location: signup.html?error=" . urlencode("Please fill in all fields."));
    exit();
  }

  // Check if the username already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    header("Location: signup.html?error=" . urlencode("Username already exists."));
    exit();
  }
  $stmt->close();

  // Remove file handling entirely (no file upload is required now)

  // Hash the password securely
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  // Updated SQL to remove id_attachment column
  $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $hashedPassword);
  if ($stmt->execute()) {
    // Registration successful; log the user in
    $_SESSION['user_id'] = $stmt->insert_id;
    // Redirect to MainPage.html (adjust path as needed)
    header("Location: /HTML/MainPage.html");
    exit();
  } else {
    header("Location: signup.html?error=" . urlencode("Signup failed. Please try again."));
    exit();
  }
  $stmt->close();
} else {
  header("Location: signup.html");
  exit();
}
?>
