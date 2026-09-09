<?php
// include("../includes/auth_check.php");
include("config/db.php");

$id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM students WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<?php include("includes/header.php"); ?>

<div class="container mt-4">
  <h2>View Student Details</h2>
  <?php if ($row): ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title text-primary">
          <?= $row['first_name'] . " " . $row['last_name'] ?>
        </h5>
        <p><strong>Level:</strong> <?= $row['studentlevel'] ?></p>
        <p><strong>University:</strong> <?= $row['university'] ?></p>
        <p><strong>Email:</strong> <?= $row['email'] ?></p>
        <p><strong>Address:</strong> <?= $row['address'] ?></p>
        <p><strong>Phone:</strong> <?= $row['phonenumber'] ?></p>
        <!-- Password is hidden for security -->
      </div>
      <div class="card-footer d-flex gap-2">
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Edit</a>
        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
           onclick="return confirm('Are you sure you want to delete this record?');">
           Delete
        </a>
        <a href="index.php" class="btn btn-secondary btn-sm">Back to List</a>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">Student not found.</div>
  <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
