
<?php
include("config/db.php");

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "DELETE FROM resources WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: home.php#resources?msg=deleted");
  
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid Resource ID.";
}
?>


<?php

