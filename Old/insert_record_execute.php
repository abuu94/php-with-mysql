<?php
         if(isset($_POST['add'])) {
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
            
            if(! $conn ) {
               die('Could not connect: ' . mysqli_error($conn));
            }
            
            if(! get_magic_quotes_gpc() ) {
               $emp_name = addslashes ($_POST['emp_name']);
               $emp_address = addslashes ($_POST['emp_address']);
            }else {
               $emp_name = $_POST['emp_name'];
               $emp_address = $_POST['emp_address'];
            }
            
            $emp_salary = $_POST['emp_salary'];
            
            $sql = "INSERT INTO employee ". "(emp_name,emp_address, emp_salary, 
               join_date) ". "VALUES('$emp_name','$emp_address',$emp_salary, NOW())";
               
            mysqli_select_db($conn,'test_db');
            $retval = mysqli_query($conn, $sql );
            
            if(! $retval ) {
               die('Could not enter data: ' . mysqli_error($conn));
            }
            
			header("location:add_employee.php");
            //echo "Entered data successfully\n";
            
            mysqli_close($conn);
         }else {
           echo "ERROR\n";
         }
      ?>
