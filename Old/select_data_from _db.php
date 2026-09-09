<?php
   
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
   
   if(!$conn ) {
      die('Could not connect: ' . mysqli_error($conn));
   }
   
   echo 'Connected successfully<br>';
   
    $sql = 'SELECT emp_id, emp_name, emp_salary FROM employee';
    mysqli_select_db(  $conn ,'test_db' );
	 $retval = mysqli_query( $conn, $sql);
	 
	  if(!$retval ) {
      die('Could not retrieve data: ' . mysqli_error($conn));
   }
   
   while($row = $retval->fetch_assoc()) {
      echo "EMP ID :{$row['emp_id']}  <br> ".
         "EMP NAME : {$row['emp_name']} <br> ".
         "EMP SALARY : {$row['emp_salary']} <br> ".
         "--------------------------------<br>";
   }
   
   echo "Fetched Data successfully\n";
   
   mysqli_close($conn);
?>

