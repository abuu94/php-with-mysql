<?php
   
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
   
   if(!$conn ) {
      die('Could not connect: ' . mysqli_error($conn));
   }
   
   echo 'Connected successfully\n';
   
   $query_file = 'sql_query.txt';
   
   $fp = fopen($query_file, 'r');
   $sql = fread($fp, filesize($query_file));
   fclose($fp); 
   
    mysqli_select_db(  $conn ,'test_db' );
   
    
   
	
	 $retval = mysqli_query( $conn, $sql);
	 
	  if(!$retval ) {
      die('Could not create table: ' . mysqli_error($conn));
   }
   
   echo "Tables  created successfully\n";
   
   mysqli_close($conn);
?>

