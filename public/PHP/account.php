<?php
// account.php
session_start();
if (!isset($_SESSION['user_id'])) {
  // If not logged in, redirect to the login page
  header("Location: profile.html");
  exit();
}

require_once 'config.php';

// Retrieve user data from the database using the session user_id
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, id_attachment FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account - TorChess</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #eaeaea;
      padding: 20px;
    }
    .account-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      max-width: 500px;
      margin: 0 auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 { margin-bottom: 20px; }
    .info { margin-bottom: 10px; }
    .logout {
      display: inline-block;
      padding: 10px 15px;
      background-color: #007BFF;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
    }
    .logout:hover { background-color: #0056b3; }
  </style>
</head>
<body>
  <div class="account-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <div class="info">
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
      <?php if ($user['id_attachment']): ?>
        <p>
          <strong>ID Attachment:</strong>
          <a href="uploads/<?php echo htmlspecialchars($user['id_attachment']); ?>" target="_blank">
            View Uploaded ID
          </a>
        </p>
      <?php endif; ?>
    </div>
    <a class="logout" href="logout.php">Log Out</a>
  </div>
</body>
</html>
