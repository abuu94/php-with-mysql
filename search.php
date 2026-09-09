<?php
include("config/db.php");

$search = mysqli_real_escape_string($conn, $_GET['q'] ?? '');

$sql = "SELECT * FROM students 
        WHERE first_name LIKE '%$search%' 
           OR last_name LIKE '%$search%' 
           OR email LIKE '%$search%' 
           OR university LIKE '%$search%' 
        ORDER BY id ASC ";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['studentlevel']}</td>
                <td>{$row['university']}</td>
                <td>{$row['email']}</td>
                <td>{$row['address']}</td>
                <td>{$row['phonenumber']}</td>
                <td>
                  <div class='btn-group btn-group-sm'>
                    <a href='view.php?id={$row['id']}' class='btn btn-info'>View</a>
                    <a href='edit.php?id={$row['id']}' class='btn btn-success'>Edit</a>
                    <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Delete</a>
                  </div>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
}
?>
