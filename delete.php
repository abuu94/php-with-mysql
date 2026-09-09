<?php
// include("../includes/auth_check.php");
include("config/db.php");

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "DELETE FROM students WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: home.php#students?msg=deleted");
       
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid student ID.";
}
?>
