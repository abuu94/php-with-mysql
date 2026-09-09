<html>
   
   <head>
      <title>Paging Using PHP</title>
   </head>
   
   <body>
      <?php
         $dbhost = 'localhost';
         $dbuser = 'root';
         $dbpass = '';
		 $PHP_SELF = &$_SERVER['localhost/usefulbookdb/'];
		// $PHP_SELF = &$_SERVER['PHP_SELF'];
         
         $rec_limit = 10;
         $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
         
         if(! $conn ) {
            die('Could not connect: ' . mysqli_error());
         }
         mysqli_select_db($conn, 'test_db');
         
         /* Get total number of records */
         $sql = "SELECT count(emp_id) FROM employee ";
         $retval = mysqli_query($conn, $sql );
         
         if(! $retval ) {
            die('Could not get data: ' . mysqli_error($conn));
         }
		 
		 //$row = $retval->fetch_assoc();
		 $row = mysqli_fetch_array($retval, MYSQLI_NUM );
         
         $rec_count = $row[0];
         
         if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
         
         $left_rec = $rec_count - ($page * $rec_limit);
         $sql = "SELECT emp_id, emp_name, emp_salary ". 
            "FROM employee ".
            "LIMIT $offset, $rec_limit";
            
         $retval = mysqli_query(  $conn,$sql);
         
         if(! $retval ) {
            die('Could not get data: ' . mysqli_error($conn));
         }
         
         while( $row = $retval->fetch_assoc()) {
            echo "EMP ID :{$row['emp_id']}  <br> ".
               "EMP NAME : {$row['emp_name']} <br> ".
              " EMP SALARY : {$row['emp_salary']} <br> ".
               "--------------------------------<br>";
         }
         
         if( $page > 0 ) {
            $last = $page - 2;
            echo "<a href = \"$PHP_SELF?page = $last\">Last 10 Records</a> |";
            echo "<a href = \"$PHP_SELF?page = $page\">Next 10 Records</a>";
         }else if( $page == 0 ) {
            echo "<a href = \"$PHP_SELF?page = $page\">Next 10 Records</a>";
         }else if( $left_rec < $rec_limit ) {
            $last = $page - 2;
            echo "<a href = \"$PHP_SELF?page = $last\">Last 10 Records</a>";
         }
         
         mysqli_close($conn);
      ?>
      
   </body>
</html>
