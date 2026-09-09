<?php
include("../config/db.php");

if (isset($_POST['signup'])) {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash password
   $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

   $sql = "INSERT INTO students (first_name,last_name,studentlevel,university,email,address,phonenumber,password)
        VALUES ('$first','$last','$level','$uni','$email','$addr','$phone','$hashed')";

   
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
        exit();
        // echo "Signup successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html>
<head><title>Signup</title></head>
<body>
<h2>Signup</h2>
<form method="post">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="signup">Signup</button>
</form>
</body>
</html>
