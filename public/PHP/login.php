<?php
// login.php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    header("Location: profile.html?error=" . urlencode("Please fill in all fields."));
    exit();
  }

  // Prepare a statement to prevent SQL injection
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verify the password (assuming passwords are hashed)
    if (password_verify($password, $user['password'])) {
      // Password is correct; set session variables
      $_SESSION['user_id'] = $user['id'];
      header("Location: PHP/account.php");
      exit();
    } else {
      header("Location: ../HTML/profile.html?error=" . urlencode("Incorrect password."));
      exit();
    }
  } else {
    header("Location: ../HTML/profile.html?error=" . urlencode("User not found."));
    exit();
  }
  $stmt->close();
} else {
  header("Location: ../HTML/profile.html");
  exit();
}
?>
