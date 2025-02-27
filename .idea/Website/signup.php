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

  // Handle file upload for the ID attachment
  $upload_dir = "uploads/";
  if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
  }
  $id_attachment = '';
  if (isset($_FILES['id_attachment']) && $_FILES['id_attachment']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['id_attachment']['tmp_name'];
    $fileName = $_FILES['id_attachment']['name'];
    $fileSize = $_FILES['id_attachment']['size'];
    $fileType = $_FILES['id_attachment']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // (Optional) Validate file type and size here
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $dest_path = $upload_dir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
      $id_attachment = $newFileName;
    } else {
      header("Location: signup.html?error=" . urlencode("Error moving the uploaded file."));
      exit();
    }
  } else {
    header("Location: signup.html?error=" . urlencode("Please upload your ID file."));
    exit();
  }

  // Hash the password securely
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  $stmt = $conn->prepare("INSERT INTO users (username, password, id_attachment) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $hashedPassword, $id_attachment);
  if ($stmt->execute()) {
    // Registration successful; log the user in
    $_SESSION['user_id'] = $stmt->insert_id;
    header("Location: account.php");
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
