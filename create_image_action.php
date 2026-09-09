<?php
include("config/db.php");
header("Content-Type: application/json");

// Debugging log
error_log("Create image action called...");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

// Validate required fields
if ($title === '' || $description === '' || empty($_FILES['image']['name'])) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

// Handle file upload
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // create folder if not exists
}

$fileName = basename($_FILES['image']['name']);
$targetFilePath = $uploadDir . uniqid() . "_" . $fileName; // unique filename

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
    // Save to database
    $sql = "INSERT INTO images (title, description, file_path) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $targetFilePath);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Image uploaded successfully."]);
    } else {
        if (mysqli_errno($conn) == 1062) {
            echo json_encode(["status" => "error", "message" => "Image title already exists."]);
        } else {
            error_log("Insert failed: " . mysqli_error($conn));
            echo json_encode(["status" => "error", "message" => "Insert failed. Please try again later."]);
        }
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to upload image."]);
}
?>
