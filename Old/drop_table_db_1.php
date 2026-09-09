<?php
   
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
   
   if(!$conn ) {
      die('Could not connect: ' . mysqli_error($conn));
   }
   
   echo 'Connected successfully\n';
   
   $sql = 'DROP TABLE property';
    mysqli_select_db(  $conn ,'test_db' );
   
    
   
	
	 $retval = mysqli_query( $conn, $sql);
	 
	  if(!$retval ) {
      die('Could not drop table: ' . mysqli_error($conn));
   }
   
   echo "Drop Table successfully\n";
   
   mysqli_close($conn);
?>

