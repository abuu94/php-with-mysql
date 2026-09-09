<?php
   
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
   
   if(!$conn ) {
      die('Could not connect: ' . mysql_error());
   }
   
   echo 'Connected successfully';
   
   $sql = 'CREATE Database test_db';
   $retval = mysqli_query( $conn,$sql );
   
   if(!$retval ) {
      die('Could not create database: ' . mysqli_error($conn));
   }
   
   echo "Database test_db created successfully\n";
   
   mysqli_close($conn);
?>

