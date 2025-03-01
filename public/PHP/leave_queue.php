<?php
// api/leave_queue.php - Handle leaving the matchmaking queue

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON response header
header('Content-Type: application/json');

// Get database connection
require_once '../includes/db_connect.php';

// Start session to get user ID and verify login
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// Remove user from the queue if not yet matched
$stmt = $conn->prepare("DELETE FROM matchmaking_queue WHERE user_id = ? AND matched = 0");
$stmt->bind_param("i", $userId);
$stmt->execute();

echo json_encode(['status' => 'success']);

$conn->close();
?>

