
<?php
include("config/db.php");
header("Content-Type: application/json");

// Debugging log
error_log("Delete image action called...");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(["status" => "error", "message" => "Invalid image ID."]);
    exit;
}

// Step 1: Fetch file_path from database
$sql = "SELECT file_path FROM images WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $file_path);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$file_path) {
    echo json_encode(["status" => "error", "message" => "Image not found."]);
    exit;
}

// Step 2: Delete file from server
if (file_exists($file_path)) {
    if (!unlink($file_path)) {
        error_log("Failed to delete file: " . $file_path);
        echo json_encode(["status" => "error", "message" => "Failed to delete image file from server."]);
        exit;
    }
}

// Step 3: Delete record from database
$sql = "DELETE FROM images WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Image deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No image found with the given ID."]);
    }
} else {
    error_log("Delete failed: " . mysqli_error($conn));
    echo json_encode(["status" => "error", "message" => "Delete failed. Please try again later."]);
}

mysqli_stmt_close($stmt);
?>

<?php
// include("config/db.php");

// $id = $_GET['id'] ?? 0;

// if ($id > 0) {
//     $sql = "DELETE FROM subjects WHERE id=$id";
//     if (mysqli_query($conn, $sql)) {
//         header("Location: home.php#subjects?msg=deleted");
  
//         exit();
//     } else {
//         echo "Error deleting record: " . mysqli_error($conn);
//     }
// } else {
//     echo "Invalid Subject ID.";
// }
?>
