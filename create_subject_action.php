<?php
include("config/db.php");
header("Content-Type: application/json");

// Debugging log
error_log("Create subject action called...");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$subject_name   = trim($_POST['subject_name'] ?? '');
$category       = trim($_POST['category'] ?? '');
$subject_level  = trim($_POST['subject_level'] ?? '');
$instructor     = trim($_POST['instructor'] ?? '');
$description    = trim($_POST['description'] ?? '');

if ($subject_name === '' || $category === '' || $subject_level === '' || $instructor === '' || $description === '') {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

$sql = "INSERT INTO subjects (subject_name, category, subject_level, instructor, description) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

mysqli_stmt_bind_param($stmt, "sssss", $subject_name, $category, $subject_level, $instructor, $description);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Subject added successfully."]);
} else {
    // Handle duplicate subject_name error (MySQL error code 1062)
    if (mysqli_errno($conn) == 1062) {
        echo json_encode(["status" => "error", "message" => "Subject name already exists."]);
    } else {
        error_log("Insert failed: " . mysqli_error($conn));
        echo json_encode(["status" => "error", "message" => "Insert failed. Please try again later."]);
    }
}

mysqli_stmt_close($stmt);
?>
