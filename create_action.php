<?php
include("config/db.php");

$first = mysqli_real_escape_string($conn, $_POST['first_name']);
$last  = mysqli_real_escape_string($conn, $_POST['last_name']);
$level = mysqli_real_escape_string($conn, $_POST['studentlevel']);
$uni   = mysqli_real_escape_string($conn, $_POST['university']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$addr  = mysqli_real_escape_string($conn, $_POST['address']);
$phone = mysqli_real_escape_string($conn, $_POST['phonenumber']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO students (first_name,last_name,studentlevel,university,email,address,phonenumber,password)
        VALUES ('$first','$last','$level','$uni','$email','$addr','$phone','$hashed')";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($conn);
}
?>




