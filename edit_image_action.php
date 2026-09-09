<?php
include("config/db.php");
header("Content-Type: application/json");

// Debugging log
error_log("Edit image action called...");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$id          = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!$id || $title === '' || $description === '') {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

// Step 1: Fetch current file_path
$sql = "SELECT file_path FROM images WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $current_file_path);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$current_file_path) {
    echo json_encode(["status" => "error", "message" => "Image record not found."]);
    exit;
}

// Step 2: Handle optional new image upload
$new_file_path = $current_file_path;
if (!empty($_FILES['image']['name'])) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $uploadDir . uniqid() . "_" . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        // Delete old file if exists
        if (file_exists($current_file_path)) {
            unlink($current_file_path);
        }
        $new_file_path = $targetFilePath;
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload new image."]);
        exit;
    }
}

// Step 3: Update record in database
$sql = "UPDATE images SET title=?, description=?, file_path=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $new_file_path, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Image updated successfully."]);
} else {
    error_log("Update failed: " . mysqli_error($conn));
    echo json_encode(["status" => "error", "message" => "Update failed. Please try again later."]);
}

mysqli_stmt_close($stmt);
?>
