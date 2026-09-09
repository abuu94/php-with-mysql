<?php 
session_start();

if (isset($_SESSION['student_id']) || isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

include("includes/header.php"); 
include("config/db.php");

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['student_name'] = $row['first_name'] . " " . $row['last_name'];
            header("Location: home.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>





<div class="container mt-5">
  <h2 class="mb-4">Login</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required />
    </div>

    <button type="submit" name="login" class="btn btn-success">Login</button>
    <p class="mt-3">Don't have an account? <a href="signup.php">Signup here</a></p>
  </form>
</div>


  <?php include("includes/footer.php"); ?>