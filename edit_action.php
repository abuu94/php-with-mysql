<?php
include("config/db.php");
header("Content-Type: application/json");

// Debugging log
error_log("Edit action called...");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$id    = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$level = trim($_POST['studentlevel'] ?? '');
$uni   = trim($_POST['university'] ?? '');
$email = trim($_POST['email'] ?? '');
$addr  = trim($_POST['address'] ?? '');
$phone = trim($_POST['phonenumber'] ?? '');
$password = $_POST['password'] ?? '';

if (!$id || $first === '' || $last === '' || $level === '' || $uni === '' || $email === '' || $addr === '' || $phone === '') {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

// SQL with or without password
if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE students 
            SET first_name=?, last_name=?, studentlevel=?, university=?, email=?, address=?, phonenumber=?, password=? 
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $first, $last, $level, $uni, $email, $addr, $phone, $hashed, $id);
} else {
    $sql = "UPDATE students 
            SET first_name=?, last_name=?, studentlevel=?, university=?, email=?, address=?, phonenumber=? 
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssi", $first, $last, $level, $uni, $email, $addr, $phone, $id);
}

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database prepare failed."]);
    exit;
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Student updated successfully."]);
} else {
    // Handle duplicate email error (MySQL error code 1062)
    if (mysqli_errno($conn) == 1062) {
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed. Please try again later."]);
    }
}

mysqli_stmt_close($stmt);
?>

