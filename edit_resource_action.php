<?php
include("config/db.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$id            = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$resource_name = trim($_POST['resource_name'] ?? '');
$description   = trim($_POST['description'] ?? '');
$maintainer    = trim($_POST['maintainer'] ?? '');
$url           = trim($_POST['url'] ?? '');

if (!$id || $resource_name === '' || $description === '' || $maintainer === '' || $url === '') {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

$sql = "UPDATE resources SET resource_name=?, description=?, maintainer=?, url=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

mysqli_stmt_bind_param($stmt, "ssssi", $resource_name, $description, $maintainer, $url, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Resource updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed."]);
}

mysqli_stmt_close($stmt);
?>
