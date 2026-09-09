<?php 
include("includes/header.php");
include("config/db.php");

// if (isset($_POST['signup'])) {
//     $name     = mysqli_real_escape_string($conn, $_POST['name']);
//     $email    = mysqli_real_escape_string($conn, $_POST['email']);
//     $password = mysqli_real_escape_string($conn, $_POST['password']);

//     // Hash password
//    $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

//    $sql = "INSERT INTO students (first_name,last_name,studentlevel,university,email,address,phonenumber,password)
//         VALUES ('$first','$last','$level','$uni','$email','$addr','$phone','$hashed')";

   
//     if (mysqli_query($conn, $sql)) {
//         header("Location: login.php");
//         exit();
//         // echo "Signup successful. <a href='login.php'>Login here</a>";
//     } else {
//         echo "Error: " . mysqli_error($conn);
//     }
// }

?>
        <h2 class="mb-4">Signup</h2>
        <form  class="card p-4 shadow-sm"  id="createForm">
           <div class="row mb-3">
          
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Firstname</label>
              <input type="text" name="first_name" class="form-control" />
            </div>
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Lastname</label>
              <input type="text" name="last_name" class="form-control" />
            </div>
          </div>
          <div class="row mb-3">
           
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" />
            </div>
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" />
            </div>
          </div>
          <div class="row mb-3">
           
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control" />
            </div>
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Phonenumber</label>
              <input type="number" name="phonenumber" class="form-control" />
            </div>
          </div>

       

          <div class="row mb-3">
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">Level</label>
              <select
                  name="studentlevel"
                class="form-select form-select-sm mb-3"
                aria-label=".form-select-lg example"
              >
                <option selected>Open this select menu</option>
                <option value="Certificate">Certificate</option>
                <option value="Diploma">Diploma</option>
                <option value="Degree">Degree</option>
              </select>
            </div>
            <div class="col-md-6 themed-grid-col">
              <label class="form-label">University</label>
              <select
              name="university"
                class="form-select form-select-sm mb-3"
                aria-label=".form-select-lg example"
              >
                <option selected>Open this select menu</option>
                <option value="SUZA">Suza</option>
                <option value="ZU">ZU</option>
                <option value="SUMEIT">Sumeit</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3">
          Already have an account? <a href="login.php">Login here</a>
        </p>
<?php include("includes/footer.php"); ?>


<script>
document.getElementById("createForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "create_action.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert("Student added successfully!");
            // Refresh table on index.php without reload
            window.location.href = "index.php";
        }
    };
    xhr.send(formData);
});
</script>