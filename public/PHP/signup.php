<?php
// signup.php
session_start();
require_once 'config.php';

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

  // Validate the file upload but do not store it.
  if (!isset($_FILES['id_attachment']) || $_FILES['id_attachment']['error'] !== UPLOAD_ERR_OK) {
    header("Location: signup.html?error=" . urlencode("Please upload your ID file."));
    exit();
  }

  // Optional: Validate file type and size
  $fileType = $_FILES['id_attachment']['type'];
  $fileSize = $_FILES['id_attachment']['size'];
  $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  $maxSize = 5 * 1024 * 1024; // 5 MB

  if (!in_array($fileType, $allowedTypes)) {
    header("Location: signup.html?error=" . urlencode("Invalid file type."));
    exit();
  }
  if ($fileSize > $maxSize) {
    header("Location: signup.html?error=" . urlencode("File size too large."));
    exit();
  }

  // Discard the uploaded file (remove from temporary location)
  @unlink($_FILES['id_attachment']['tmp_name']);

  // Since we don't store the file, set id_attachment as an empty string (or NULL)
  $id_attachment = "";

  // Hash the password securely
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  // If you want to store NULL instead of an empty string, modify the query accordingly.
  $stmt = $conn->prepare("INSERT INTO users (username, password, id_attachment) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $hashedPassword, $id_attachment);
  if ($stmt->execute()) {
    // Registration successful; log the user in
    $_SESSION['user_id'] = $stmt->insert_id;
    header("Location: ../HTML/MainPage.html");
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
