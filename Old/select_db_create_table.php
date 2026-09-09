<?php
   
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
   
   if(!$conn ) {
      die('Could not connect: ' . mysql_error());
   }
   
   echo 'Connected successfully';
   
    $sql = 'CREATE TABLE employee( '.
      'emp_id INT NOT NULL AUTO_INCREMENT, '.
      'emp_name VARCHAR(20) NOT NULL, '.
      'emp_address  VARCHAR(20) NOT NULL, '.
      'emp_salary   INT NOT NULL, '.
      'join_date    timestamp(6) NOT NULL, '.
      'primary key ( emp_id ))';
   
    mysqli_select_db(  $conn ,'test_db' );
	
	 $retval = mysqli_query( $conn, $sql);
	 
	  if(!$retval ) {
      die('Could not create table: ' . mysqli_error($conn));
   }
   
   echo "Table employee created successfully\n";
   
   mysqli_close($conn);
?>

