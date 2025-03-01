<?php
// api/check_match.php - Check if a match has been found

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

// Check if the user has been matched by joining with the games table
$stmt = $conn->prepare("SELECT q.game_id, g.white_player_id, g.black_player_id 
                       FROM matchmaking_queue q
                       JOIN games g ON q.game_id = g.id
                       WHERE q.user_id = ? AND q.matched = 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gameId = $row['game_id'];

    // Determine the player's color
    $color = ($row['white_player_id'] == $userId) ? 'w' : 'b';

    echo json_encode([
        'matched' => true,
        'game_id' => $gameId,
        'color'   => $color
    ]);
} else {
    echo json_encode(['matched' => false]);
}

$conn->close();
?>

