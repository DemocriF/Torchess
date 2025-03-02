<?php
// api/join_queue.php - Handle joining the matchmaking queue

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON response header
header('Content-Type: application/json');

// Get database connection
require_once '../includes/config.php';

// Start session and verify user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get JSON data from request and validate
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
if (!isset($data['timeControl'])) {
    echo json_encode(['error' => 'Missing time control']);
    exit;
}
$timeControl = intval($data['timeControl']);

// Optional: Wrap this process in a transaction to avoid race conditions
// $conn->begin_transaction();

// Insert user into the queue. Assumes user_id is UNIQUE in matchmaking_queue.
$stmt = $conn->prepare("INSERT INTO matchmaking_queue 
                       (user_id, time_control, join_time) 
                       VALUES (?, ?, NOW())
                       ON DUPLICATE KEY UPDATE time_control = ?, join_time = NOW()");
$stmt->bind_param("iii", $userId, $timeControl, $timeControl);
$stmt->execute();

// Check if there's another player waiting with the same time control
$stmt = $conn->prepare("SELECT user_id FROM matchmaking_queue 
                       WHERE user_id != ? 
                         AND time_control = ? 
                         AND matched = 0
                       ORDER BY join_time ASC 
                       LIMIT 1");
$stmt->bind_param("ii", $userId, $timeControl);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Found a match!
    $opponent = $result->fetch_assoc();
    $opponentId = $opponent['user_id'];

    // Determine colors (randomly assign white to one of the two)
    $whitePlayer = (rand(0, 1) === 0) ? $userId : $opponentId;
    $blackPlayer = ($whitePlayer === $userId) ? $opponentId : $userId;
    $color = ($whitePlayer === $userId) ? 'w' : 'b';

    // Create a new game record
    $stmt = $conn->prepare("INSERT INTO games 
                          (white_player_id, black_player_id, time_control, start_time, status) 
                          VALUES (?, ?, ?, NOW(), 'active')");
    $stmt->bind_param("iii", $whitePlayer, $blackPlayer, $timeControl);
    $stmt->execute();
    $gameId = $conn->insert_id;

    // Mark both players as matched in the queue
    $stmt = $conn->prepare("UPDATE matchmaking_queue 
                          SET matched = 1, game_id = ? 
                          WHERE user_id IN (?, ?)");
    $stmt->bind_param("iii", $gameId, $userId, $opponentId);
    $stmt->execute();

    // Optional: Commit transaction
    // $conn->commit();

    // Return immediate match result
    echo json_encode([
        'status'   => 'immediate_match',
        'game_id'  => $gameId,
        'color'    => $color
    ]);
} else {
    // No match yet, return queued status
    echo json_encode(['status' => 'queued']);
}

$conn->close();
?>

