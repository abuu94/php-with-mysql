<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "POST requests only"]);
        exit;
}

$student_id = (int) ($_SESSION['student_id'] ?? $_SESSION['user_id'] ?? 0);
$image_id = filter_input(INPUT_POST, 'image_id', FILTER_VALIDATE_INT);
$reaction = $_POST['reaction'] ?? '';

if ($student_id < 1 || !$image_id || !in_array($reaction, ['like', 'dislike'], true)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
        exit;
}

include("config/db.php");

$sql = "INSERT INTO image_reactions (student_id, image_id, reaction)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE reaction = VALUES(reaction), reacted_at = CURRENT_TIMESTAMP";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Unable to save reaction"]);
        exit;
}

mysqli_stmt_bind_param($stmt, "iis", $student_id, $image_id, $reaction);
if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Unable to save reaction"]);
        exit;
}
mysqli_stmt_close($stmt);

$sql = "SELECT SUM(reaction = 'like') AS total_likes,
                           SUM(reaction = 'dislike') AS total_dislikes
                FROM image_reactions
                WHERE image_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $image_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $total_likes, $total_dislikes);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

echo json_encode([
        "status" => "success",
        "total_likes" => (int) ($total_likes ?? 0),
        "total_dislikes" => (int) ($total_dislikes ?? 0),
        "user_reaction" => $reaction
]);
