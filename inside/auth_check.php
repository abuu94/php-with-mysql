<?php
session_start();

if (!isset($_SESSION['student_id']) && !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = $_SESSION['student_id'];
}
?>




            