<?php
include("config/db.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$resource_name = trim($_POST['resource_name'] ?? '');
$description   = trim($_POST['description'] ?? '');
$maintainer    = trim($_POST['maintainer'] ?? '');
$url           = trim($_POST['url'] ?? '');

if ($resource_name === '' || $description === '' || $maintainer === '' || $url === '') {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

$sql = "INSERT INTO resources (resource_name, description, maintainer, url) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

mysqli_stmt_bind_param($stmt, "ssss", $resource_name, $description, $maintainer, $url);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Resource added successfully."]);
} else {
    if (mysqli_errno($conn) == 1062) {
        echo json_encode(["status" => "error", "message" => "Resource name already exists."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed."]);
    }
}

mysqli_stmt_close($stmt);
?>
