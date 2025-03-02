<?php
// account.php
session_start();
require_once 'config.php';  // Ensure this includes the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/signin.html?error=" . urlencode("Not logged in"));
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve user information
$stmt = $conn->prepare("SELECT username, id_attachment, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account - TorChess</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eaeaea; padding: 20px; }
        .profile-container { background-color: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        h1 { color: #333; }
        .logout { margin-top: 20px; }
        .logout a { text-decoration: none; color: #007BFF; }
    </style>
</head>
<body>
<div class="profile-container">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Account created on: <?php echo htmlspecialchars($user['created_at']); ?></p>
    <?php if (!empty($user['id_attachment'])): ?>
        <p>ID Attachment: <?php echo htmlspecialchars($user['id_attachment']); ?></p>
    <?php endif; ?>
    <div class="logout">
        <a href="../PHP/logout.php">Logout</a>
    </div>
</div>
</body>
</html>
