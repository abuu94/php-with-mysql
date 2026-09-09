<?php include("../includes/header.php"); ?>
        <h2 class="mb-4">Login</h2>
        <form method="POST" class="card p-4 shadow-sm">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" />
          </div>

          <a href="home.html" class="btn btn-success">Login</a>
          <p>Don't have an account? <a href="/signup">Signup here</a></p>
        </form>
  <?php include("../includes/footer.php"); ?>

<?php
// session_start();
// include("../config/db.php");

// if (isset($_POST['login'])) {
//     $email    = mysqli_real_escape_string($conn, $_POST['email']);
//     $password = $_POST['password'];

//     $sql = "SELECT * FROM students WHERE email='$email'";
//     $result = mysqli_query($conn, $sql);

//     if (mysqli_num_rows($result) == 1) {
//         $row = mysqli_fetch_assoc($result);

//         if (password_verify($password, $row['password'])) {
//             $_SESSION['student_id'] = $row['id'];
//             $_SESSION['student_name'] = $row['first_name'] . " " . $row['last_name'];
//             header("Location: ../students/index.php");
//             exit();
//         } else {
//             echo "Invalid password.";
//         }
//     }else {
//         echo "No user found with that email.";
//     }

// }
?>

<!-- <!doctype html> -->
<!-- <html> -->
<!-- <head><title>Login</title></head> -->
<!-- <body> -->
<!-- <h2>Login</h2> -->
<!-- <form method="post"> -->
    <!-- <input type="email" name="email" placeholder="Email" required><br> -->
    <!-- <input type="password" name="password" placeholder="Password" required><br> -->
    <!-- <button type="submit" name="login">Login</button> -->
<!-- </form> -->
<!-- </body> -->
<!-- </html> -->



