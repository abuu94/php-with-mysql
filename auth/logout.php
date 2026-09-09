<?php
session_start();

// Haribu session zote
session_unset();
session_destroy();

// Rudisha user kwenye login page
header("Location: login.php");
exit();
?>
