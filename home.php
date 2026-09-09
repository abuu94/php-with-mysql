<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include("config/db.php");

if (!isset($_SESSION['student_id']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = $_SESSION['student_id'];
}


// Pagination settings
$limit = 5; // records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Fetch students with LIMIT + OFFSET
$sql = "SELECT * FROM students ORDER BY id ASC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Count total records for students to calculate total pages
$total_sql = "SELECT COUNT(*) AS total FROM students";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);



// Subjects Tab
// Fetch students with LIMIT + OFFSET
$subject_sql = "SELECT * FROM subjects ORDER BY id ASC LIMIT $limit OFFSET $offset";
$subject_result = mysqli_query($conn, $subject_sql);


// Count total records for students to calculate total pages
$subject_total_sql = "SELECT COUNT(*) AS total FROM subjects";
$subject_total_result = mysqli_query($conn, $subject_total_sql);
$subject_total_row = mysqli_fetch_assoc($subject_total_result);
$subject_total_records = $subject_total_row['total'];
$subject_total_pages = ceil($subject_total_records / $limit);



// Resources Tab
// Fetch students with LIMIT + OFFSET
$resource_sql = "SELECT * FROM resources ORDER BY id ASC LIMIT $limit OFFSET $offset";
$resource_result = mysqli_query($conn, $resource_sql);

// Count total records for resources to calculate total pages
$resource_total_sql = "SELECT COUNT(*) AS total FROM resources";
$resource_total_result = mysqli_query($conn, $resource_total_sql);
$resource_total_row = mysqli_fetch_assoc($resource_total_result);
$resource_total_records = $resource_total_row['total'];
$resource_total_pages = ceil($resource_total_records / $limit);




// Images Tab
// Fetch images with LIMIT + OFFSET


// $image_limit = 3; // records per page
// $image_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// if ($image_page < 1) $image_page = 1;
// $image_offset = ($image_page - 1) * $image_limit;


$image_sql = "SELECT * FROM images ORDER BY id ASC LIMIT $limit OFFSET $offset";
// $image_sql = "SELECT * FROM images ORDER BY id ASC LIMIT $image_limit OFFSET $image_offset";
$image_result = mysqli_query($conn, $image_sql);

// Count total records for images to calculate total pages
$image_total_sql = "SELECT COUNT(*) AS total FROM images";
$image_total_result = mysqli_query($conn, $image_total_sql);
$image_total_row = mysqli_fetch_assoc($image_total_result);
$image_total_records = $image_total_row['total'];
$image_total_pages = ceil($image_total_records / $limit);


$image_sql_small = "SELECT * FROM images ORDER BY id ASC ";
$image_result_small = mysqli_query($conn, $image_sql_small);

$current_student_id = (int) ($_SESSION['student_id'] ?? $_SESSION['user_id']);
$reaction_counts = [];
$reaction_sql = "SELECT image_id,
            SUM(reaction = 'like') AS total_likes,
            SUM(reaction = 'dislike') AS total_dislikes,
            MAX(CASE WHEN student_id = ? THEN reaction ELSE NULL END) AS user_reaction
         FROM image_reactions
         GROUP BY image_id";
$reaction_stmt = mysqli_prepare($conn, $reaction_sql);
if ($reaction_stmt) {
  mysqli_stmt_bind_param($reaction_stmt, "i", $current_student_id);
  mysqli_stmt_execute($reaction_stmt);
  $reaction_result = mysqli_stmt_get_result($reaction_stmt);
  while ($reaction_row = mysqli_fetch_assoc($reaction_result)) {
    $reaction_counts[(int) $reaction_row['image_id']] = $reaction_row;
  }
  mysqli_stmt_close($reaction_stmt);
}



$studentCount = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM students"));
$subjectCount = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM subjects"));
$resourceCount = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM resources"));








?>


<?php include("inside/header.php"); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
          <div class="tab-content mt-3">
           

            <div class="tab-pane fade show active" id="dashboard">
              <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
              >
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group me-2">
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-secondary"
                    >
                      Share
                    </button>
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-secondary"
                    >
                      Export
                    </button>
                  </div>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                  >
                    <span data-feather="calendar"></span>
                    This week
                  </button>
                </div>
              </div>

              <canvas
                class="my-4 w-100"
                id="myChart"
                width="900"
                height="380"
              ></canvas>
            </div>
         
             <div class="tab-pane fade" id="subjects">
              <div  class="alert alert-info d-flex align-items-center" role="alert"> <strong>Subject Management</strong></div>

              <!-- Search + Add Subject on same line -->
              <div class="d-flex justify-content-between mb-3">
                <!-- Search box -->
                <div class="input-group me-2" style="max-width: 800px">
                    <input
                      type="text"
                       id="searchSubjectBox"
                      class="form-control form-control"
                      placeholder="Search subjects..."
                    />
                </div>

                <!-- Add Subject button -->
                 <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                  + Add New Subject
                </button>
              </div>

                  <!-- Table -->
                  <div class="table-responsive">
                      <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                         <div class="alert alert-success">Record deleted successfully!</div>
                      <?php endif; ?>
                  
                      <table   id="subjectsTable"  class="table table-striped table-hover table-bordered ">
                        <thead class="table-primary">
                        <tr>
                          <th>#</th>
                          <th>Subject Name</th>
                          <th>Category</th>
                          <th>Level</th>
                          <th>Instructor</th>
                          <th>Description </th>
                          <th>Action</th>
                        </tr>      
                        </thead>
                        <tbody>

                        <?php if (mysqli_num_rows($subject_result) > 0): ?>
                            <?php while ($subject_row = mysqli_fetch_assoc($subject_result)): ?>
                          <tr>
                            <td><?= $subject_row['id'] ?></td>
                            <td><?= $subject_row['subject_name'] ?></td>
                            <td><?= $subject_row['category'] ?></td>
                            <td><?= $subject_row['subject_level'] ?></td>
                            <td><?= $subject_row['instructor'] ?></td>
                            <td><?= $subject_row['description'] ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewSubjectModal<?= $subject_row['id'] ?>">
                                        View
                                    </button>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editSubjectModal<?= $subject_row['id'] ?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSubjectModal<?= $subject_row['id'] ?>">
                                        Delete
                                    </button>


                                </div>
                            </td>
                          </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">No records found</td></tr>
                        <?php endif; ?>
                        </tbody>
                      </table>

                       <nav>
                                       <ul class="pagination justify-content-center">
                                         <?php if ($page > 1): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page-1 ?>#resources">&laquo;</a>
                                           </li>
                                         <?php endif; ?>
                    
                                         <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                           <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                             <a class="page-link" href="?page=<?= $i ?>#resources"><?= $i ?></a>
                                           </li>
                                         <?php endfor; ?>
                    
                                         <?php if ($page < $total_pages): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page+1 ?>#resources">&raquo;</a>
                                           </li>
                                         <?php endif; ?>
                                       </ul>
                                     </nav>
                  </div>

                  <!-- Dynamic Pagination -->
                  
                  


                    <!-- Add Subject Modal -->
                  <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Add New Subject</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <form id="createSubjectFormModal">
                            <input type="text" name="subject_name" class="form-control mb-2" placeholder="Subject Name" required>
                            <input type="text" name="category" class="form-control mb-2" placeholder="Category" required>
                            <input type="text" name="subject_level" class="form-control mb-2" placeholder="Level" required>
                            <input type="text" name="instructor" class="form-control mb-2" placeholder="Instructor" required>
                            <input type="text" name="description" class="form-control mb-2" placeholder="Description" required>
                           
                            <div class="d-flex justify-content-end gap-2">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Save Subject</button>

                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php if (mysqli_num_rows($subject_result) > 0): ?>
                  <?php mysqli_data_seek($subject_result, 0); ?>
                  <?php while ($subject_row = mysqli_fetch_assoc($subject_result)): ?>
                      <!-- View Modal -->
                      <div class="modal fade" id="viewSubjectModal<?= $subject_row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">View Subject</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <dl class="row mb-0">
                                <dt class="col-sm-4">Subject Name</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($subject_row['subject_name']) . ' ' . htmlspecialchars($subject_row['category']) ?></dd>

                                <dt class="col-sm-4">Category</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($subject_row['category']) ?></dd>

                                <dt class="col-sm-4">Level</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($subject_row['subject_level']) ?></dd>

                                <dt class="col-sm-4">Instructor Name</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($subject_row['instructor']) ?></dd>

                                <dt class="col-sm-4">Description</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($subject_row['description']) ?></dd>

                     
                              </dl>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Edit Modal -->
                      <div class="modal fade" id="editSubjectModal<?= $subject_row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Edit Subject</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <form id="editSubjectForm<?= $subject_row['id'] ?>">
                                <input type="hidden" name="id" value="<?= $subject_row['id'] ?>">
                                <input type="text" name="subject_name" class="form-control mb-2" value="<?= htmlspecialchars($subject_row['subject_name']) ?>" required>
                                <input type="text" name="category" class="form-control mb-2" value="<?= htmlspecialchars($subject_row['category']) ?>" required>
                                <input type="text" name="subject_level" class="form-control mb-2" value="<?= htmlspecialchars($subject_row['subject_level']) ?>" required>
                                <input type="text" name="instructor" class="form-control mb-2" value="<?= htmlspecialchars($subject_row['instructor']) ?>" required>
                                <input type="text" name="description" class="form-control mb-2" value="<?= htmlspecialchars($subject_row['description']) ?>" required>
                               
                                <div class="d-flex justify-content-end gap-2">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-success">Update Subject</button>
                                 
                              
                              </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Delete Modal -->
                      <div class="modal fade" id="deleteSubjectModal<?= $subject_row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Delete Subject</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <p>Are you sure you want to delete <strong><?= htmlspecialchars($subject_row['subject_name']) . ' ' . htmlspecialchars($subject_row['category']) ?></strong>?</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              
                             <a href="delete_subject_action.php?id=<?= $subject_row['id'] ?>" class="btn btn-danger" >Delete</a>
                            </div>
                          </div>
                        </div>
                      </div>
                  <?php endwhile; ?>
              <?php endif; ?>
              </div>
          
          
          
              <div class="tab-pane fade" id="students">
              <div  class="alert alert-info d-flex align-items-center" role="alert"> <strong>User Management</strong></div>

              <!-- Search + Add User on same line -->
              <div class="d-flex justify-content-between mb-3">
                <!-- Search box -->
                <div class="input-group me-2" style="max-width: 800px">
                    <input
                      type="text"
                       id="searchStudentBox"
                      class="form-control form-control"
                      placeholder="Search users..."
                    />
                </div>

                <!-- Add User button -->
                 <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                  + Add New User
                </button>
              </div>

                  <!-- Table -->
                  <div class="table-responsive">
                      <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success">Record deleted successfully!</div>
                  <?php endif; ?>
                  
                      <table  id="studentsTable" class="table table-striped table-hover table-bordered ">
                        <thead class="table-primary">
                          <tr>
                            <th>#</th>
                            <th>First</th>
                            <th>Last</th>
                            <th>Level</th>
                            <th>University</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Phonenumber</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                          <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['first_name'] ?></td>
                            <td><?= $row['last_name'] ?></td>
                            <td><?= $row['studentlevel'] ?></td>
                            <td><?= $row['university'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['phonenumber'] ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewUserModal<?= $row['id'] ?>">
                                        View
                                    </button>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $row['id'] ?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $row['id'] ?>">
                                        Delete
                                    </button>
                                </div>
                            </td>
                          </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">No records found</td></tr>
                        <?php endif; ?>
                        </tbody>
                      </table>

                       <nav>
                                       <ul class="pagination justify-content-center">
                                         <?php if ($page > 1): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page-1 ?>#resources">&laquo;</a>
                                           </li>
                                         <?php endif; ?>
                    
                                         <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                           <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                             <a class="page-link" href="?page=<?= $i ?>#resources"><?= $i ?></a>
                                           </li>
                                         <?php endfor; ?>
                    
                                         <?php if ($page < $total_pages): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page+1 ?>#resources">&raquo;</a>
                                           </li>
                                         <?php endif; ?>
                                       </ul>
                                     </nav>
                  </div>

                  <!-- Dynamic Pagination -->
                  
               


                    <!-- Add User Modal -->
                  <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Add New User</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <form id="createFormModal">
                            <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
                            <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
                            <input type="text" name="studentlevel" class="form-control mb-2" placeholder="Level" required>
                            <input type="text" name="university" class="form-control mb-2" placeholder="University" required>
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            <input type="text" name="address" class="form-control mb-2" placeholder="Address" required>
                            <input type="text" name="phonenumber" class="form-control mb-2" placeholder="Phone" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                            <div class="d-flex justify-content-end gap-2">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Save User</button>

                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

              <?php if (mysqli_num_rows($result) > 0): ?>
                  <?php mysqli_data_seek($result, 0); ?>
                  <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <!-- View Modal -->
                      <div class="modal fade" id="viewUserModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">View User</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <dl class="row mb-0">
                                <dt class="col-sm-4">Full Name</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) ?></dd>

                                <dt class="col-sm-4">Level</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($row['studentlevel']) ?></dd>

                                <dt class="col-sm-4">University</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($row['university']) ?></dd>

                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($row['email']) ?></dd>

                                <dt class="col-sm-4">Address</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($row['address']) ?></dd>

                                <dt class="col-sm-4">Phone Number</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($row['phonenumber']) ?></dd>
                              </dl>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Edit Modal -->
                      <div class="modal fade" id="editUserModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Edit User</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <form id="editForm<?= $row['id'] ?>">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="text" name="first_name" class="form-control mb-2" value="<?= htmlspecialchars($row['first_name']) ?>" required>
                                <input type="text" name="last_name" class="form-control mb-2" value="<?= htmlspecialchars($row['last_name']) ?>" required>
                                <input type="text" name="studentlevel" class="form-control mb-2" value="<?= htmlspecialchars($row['studentlevel']) ?>" required>
                                <input type="text" name="university" class="form-control mb-2" value="<?= htmlspecialchars($row['university']) ?>" required>
                                <input type="email" name="email" class="form-control mb-2" value="<?= htmlspecialchars($row['email']) ?>" required>
                                <input type="text" name="address" class="form-control mb-2" value="<?= htmlspecialchars($row['address']) ?>" required>
                                <input type="text" name="phonenumber" class="form-control mb-2" value="<?= htmlspecialchars($row['phonenumber']) ?>" required>
                                <input type="password" name="password" class="form-control mb-2" placeholder="New Password (leave blank if unchanged)">
                                <div class="d-flex justify-content-end gap-2">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-success">Update User</button>
                                 
                              
                              </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Delete Modal -->
                      <div class="modal fade" id="deleteUserModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Delete User</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <p>Are you sure you want to delete <strong><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) ?></strong>?</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger" >Delete</a>
                              <!-- <a href="delete.php?id=<? //= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a> -->
                            </div>
                          </div>
                        </div>
                      </div>
                  <?php endwhile; ?>
              <?php endif; ?>

            </div>
            <div class="tab-pane fade" id="resources">
              <div  class="alert alert-info d-flex align-items-center" role="alert"> <strong>Resource Management</strong></div>

              <div  class="d-flex justify-content-between mb-3">

                <!-- Search box -->
                <div class="input-group me-2" style="max-width: 800px">
                    <input
                      type="text"
                       id="searchResourceBox"
                      class="form-control form-control"
                      placeholder="Search resources..."
                    />
                </div>

                <!-- Add Resource button -->
                 <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                  + Add New Resource
                </button>
              </div>

              <div class="table-responsive">
                    <!-- Resources DataTable -->
                <table id="resourcesTable" class="table table-striped table-hover table-bordered">
                  <thead class="table-primary text-center">
                    <tr>
                      <th>#</th>
                      <th>Resource Name</th>
                      <th>Description</th>
                      <th>Maintainer</th>
                      <th>URL</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
        
                          <tbody>

                        <?php if (mysqli_num_rows($resource_result) > 0): ?>
                            <?php while ($resource_row = mysqli_fetch_assoc($resource_result)): ?>
                          <tr>
                            <td><?= $resource_row['id'] ?></td>
                            <td><?= $resource_row['resource_name'] ?></td>
                            <td><?= $resource_row['description'] ?></td>
                            <td><?= $resource_row['maintainer'] ?></td>
                            <td><a href="<?= $resource_row['url'] ?>" target="_blank">Link</a></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewResourceModal<?= $resource_row['id'] ?>">
                                        View
                                    </button>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editResourceModal<?= $resource_row['id'] ?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteResourceModal<?= $resource_row['id'] ?>">
                                        Delete
                                    </button>


                                </div>
                            </td>
                          </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">No records found</td></tr>
                        <?php endif; ?>
                        </tbody>
                </table>
                
                                   <nav>
                                       <ul class="pagination justify-content-center">
                                         <?php if ($page > 1): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page-1 ?>#resources">&laquo;</a>
                                           </li>
                                         <?php endif; ?>
                    
                                         <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                           <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                             <a class="page-link" href="?page=<?= $i ?>#resources"><?= $i ?></a>
                                           </li>
                                         <?php endfor; ?>
                    
                                         <?php if ($page < $total_pages): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page+1 ?>#resources">&raquo;</a>
                                           </li>
                                         <?php endif; ?>
                                       </ul>
                                     </nav>
                </div>




                          <!-- Add Resource Modal -->
                  
                    <div class="modal fade" id="addResourceModal" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <form id="createResourceFormModal" class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Add Resource</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <label class="form-label">Resource Name</label>
                              <input type="text" name="resource_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Description</label>
                              <textarea name="description" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Maintainer</label>
                              <input type="text" name="maintainer" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">URL</label>
                              <input type="url" name="url" class="form-control" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Resource</button>
                          </div>
                        </form>
                      </div>
                    </div>
            
                    <?php if (mysqli_num_rows($resource_result) > 0): ?>
                                            <?php mysqli_data_seek($resource_result, 0); ?>
                                            <?php while ($resource_row = mysqli_fetch_assoc($resource_result)): ?>
                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewResourceModal<?= $resource_row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title">View Resource</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <dl class="resource_row mb-0">
                                                          <dt class="col-sm-4">Resource Name</dt>
                                                          <dd class="col-sm-8"><?= htmlspecialchars($resource_row['resource_name']) . ' ' . htmlspecialchars($resource_row['maintainer']) ?></dd>
            
                                                          <dt class="col-sm-4">Description</dt>
                                                          <dd class="col-sm-8"><?= htmlspecialchars($resource_row['description']) ?></dd>
            
                                                          <dt class="col-sm-4">Maintainer</dt>
                                                          <dd class="col-sm-8"><?= htmlspecialchars($resource_row['maintainer']) ?></dd>
            
                                                          <dt class="col-sm-4">URL</dt>
                                                          <dd class="col-sm-8"><?= htmlspecialchars($resource_row['url']) ?></dd>
            
                                                        </dl>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
            
                                                
                                              <!-- Edit Resource Modal -->
                                            <div class="modal fade" id="editResourceModal<?= $resource_row['id'] ?>" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <form id="editResourceForm<?= $resource_row['id'] ?>" class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Edit Resource</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $resource_row['id'] ?>">
                                                    <div class="mb-3">
                                                      <label class="form-label">Resource Name</label>
                                                      <input type="text" name="resource_name" class="form-control" value="<?= htmlspecialchars($resource_row['resource_name']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                      <label class="form-label">Description</label>
                                                      <textarea name="description" class="form-control" required><?= htmlspecialchars($resource_row['description']) ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                      <label class="form-label">Maintainer</label>
                                                      <input type="text" name="maintainer" class="form-control" value="<?= htmlspecialchars($resource_row['maintainer']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                      <label class="form-label">URL</label>
                                                      <input type="url" name="url" class="form-control" value="<?= htmlspecialchars($resource_row['url']) ?>" required>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Update Resource</button>
                                                  </div>
                                                </form>
                                              </div>
                                            </div>
            
            
                                                                  
                                                                  <!-- Delete Resource Modal -->
                                            <div class="modal fade" id="deleteResourceModal<?= $resource_row['id'] ?>" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Delete Resource</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <p>Are you sure you want to delete 
                                                      <strong><?= htmlspecialchars($resource_row['resource_name']) ?></strong>?
                                                    </p>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete_resource_action.php?id=<?= $resource_row['id'] ?>" class="btn btn-danger" >Delete</a>
                                                  
                                                  
                                                    <!-- <button type="button" class="btn btn-danger delete-resource-btn" data-id="<?= $resource_row['id'] ?>">
                                                      Delete
                                                    </button> -->
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
            
                        <?php endwhile; ?>
                    <?php endif; ?>
           
           
              </div>


            <div class="tab-pane fade" id="images">
                  <div  class="alert alert-info d-flex align-items-center" role="alert"> <strong>Image Management</strong></div>

                  <!-- Search + Add Image on same line -->
                  <div class="d-flex justify-content-between mb-3">
                    <!-- Search box -->
                    <div class="input-group me-2" style="max-width: 800px">
                        <input
                          type="text"
                          id="searchImageBox"
                          class="form-control form-control"
                          placeholder="Search images..."
                        />
                    </div>

                    <!-- Add Image button -->
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addImageModal">
                      + Add New Image
                    </button>
                  </div>

                      <!-- Table -->

                      <div id="imagesTableContainer" class="table-responsive">
                        <table id="imagesTable" class="table table-striped table-hover table-bordered align-middle">
                          <thead class="table-primary text-center">
                            <tr>
                              <th style="width:5%;">#</th>
                              <th style="width:15%;">Image</th>
                              <th style="width:20%;">Title</th>
                              <th style="width:40%;">Description</th>
                              <th style="width:20%;">Action</th>
                            </tr>      
                          </thead>
                          <tbody>
                            <?php if (mysqli_num_rows($image_result) > 0): ?>
                              <?php while ($image_row = mysqli_fetch_assoc($image_result)): ?>
                                <tr id="imageRow<?= $image_row['id'] ?>">
                                  <td class="text-center"><?= $image_row['id'] ?></td>
                                  <td class="text-center">
                                    <img src="<?= htmlspecialchars($image_row['file_path']) ?>" 
                                        alt="<?= htmlspecialchars($image_row['title']) ?>" 
                                        class="img-thumbnail shadow-sm" 
                                        style="width:100px; height:80px; object-fit:cover;">
                                  </td>
                                  <td><?= htmlspecialchars($image_row['title']) ?></td>
                                  <td class="text-truncate" style="max-width:300px;">
                                    <?= htmlspecialchars($image_row['description']) ?>
                                  </td>
                                  <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                      <button type="button" class="btn btn-info" 
                                              data-bs-toggle="modal" 
                                              data-bs-target="#viewImageModal<?= $image_row['id'] ?>">
                                        View
                                      </button>
                                      <button type="button" class="btn btn-success" 
                                              data-bs-toggle="modal" 
                                              data-bs-target="#editImageModal<?= $image_row['id'] ?>">
                                        Edit
                                      </button>
                                      <button type="button" class="btn btn-danger" 
                                              data-bs-toggle="modal" 
                                              data-bs-target="#deleteImageModal<?= $image_row['id'] ?>">
                                        Delete
                                      </button>
                                    </div>
                                  </td>
                                </tr>
                              <?php endwhile; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="5" class="text-center text-muted">No records found</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                                     <nav>
                                       <ul class="pagination justify-content-center">
                                         <?php if ($page > 1): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page-1 ?>#images">&laquo;</a>
                                           </li>
                                         <?php endif; ?>
                    
                                         <?php for ($i = 1; $i <= $image_total_pages; $i++): ?>
                                           <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                             <a class="page-link" href="?page=<?= $i ?>#images"><?= $i ?></a>
                                           </li>
                                         <?php endfor; ?>
                    
                                         <?php if ($page < $image_total_pages): ?>
                                           <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page+1 ?>#images">&raquo;</a>
                                           </li>
                                         <?php endif; ?>
                                       </ul>
                                     </nav>
                  
                                  <!-- hapa juu -->
                      </div>

                    

                      <!-- Dynamic Pagination -->

                      <!-- Add Image Modal -->
                  <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Add New Image</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <!-- Important: enctype="multipart/form-data" for file upload -->
                          <form id="createImageFormModal" enctype="multipart/form-data">
                            <input type="text" name="title" class="form-control mb-2" placeholder="Image Title" required>

                            <textarea name="description" class="form-control mb-2" placeholder="Description" required></textarea>

                            <input type="file" name="image" class="form-control mb-3" accept="image/*" required>

                            <div class="d-flex justify-content-end gap-2">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Save Image</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php if (mysqli_num_rows($image_result) > 0): ?>
                      <?php mysqli_data_seek($image_result, 0); ?>
                      <?php while ($image_row = mysqli_fetch_assoc($image_result)): ?>
                          <!-- View Modal -->
                          <!-- View Image Modal -->
                          <div class="modal fade" id="viewImageModal<?= $image_row['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">View Image</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                  <!-- Image Preview -->
                                  <div class="text-center mb-3">
                                    <img src="<?= htmlspecialchars($image_row['file_path']) ?>" 
                                        alt="<?= htmlspecialchars($image_row['title']) ?>" 
                                        class="img-fluid rounded shadow-sm" 
                                        style="max-height:400px; object-fit:contain;">
                                  </div>

                                  <!-- Image Details -->
                                  <dl class="row mb-0">
                                    <dt class="col-sm-4">Image Title</dt>
                                    <dd class="col-sm-8"><?= htmlspecialchars($image_row['title']) ?></dd>

                                    <dt class="col-sm-4">Description</dt>
                                    <dd class="col-sm-8"><?= htmlspecialchars($image_row['description']) ?></dd>

                                    <!-- Optional: Category if exists -->
                                    <?php if (!empty($image_row['category'])): ?>
                                    <dt class="col-sm-4">Category</dt>
                                    <dd class="col-sm-8"><?= htmlspecialchars($image_row['category']) ?></dd>
                                    <?php endif; ?>

                                    <dt class="col-sm-4">Uploaded At</dt>
                                    <dd class="col-sm-8"><?= htmlspecialchars($image_row['uploaded_at']) ?></dd>
                                  </dl>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>

                        

                       
                          <!-- Edit Image Modal -->
                            <div class="modal fade" id="editImageModal<?= $image_row['id'] ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Edit Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body">
                                    <!-- Important: enctype for file upload -->
                                    <form id="editImageForm<?= $image_row['id'] ?>" enctype="multipart/form-data">
                                      <input type="hidden" name="id" value="<?= $image_row['id'] ?>">

                                      <input type="text" name="title" class="form-control mb-2" 
                                            value="<?= htmlspecialchars($image_row['title']) ?>" required>

                                      <textarea name="description" class="form-control mb-2" required><?= htmlspecialchars($image_row['description']) ?></textarea>

                                      <!-- Current image preview -->
                                      <div class="text-center mb-3">
                                        <img src="<?= htmlspecialchars($image_row['file_path']) ?>" 
                                            alt="<?= htmlspecialchars($image_row['title']) ?>" 
                                            class="img-fluid rounded shadow-sm" 
                                            style="max-height:200px; object-fit:contain;">
                                      </div>

                                      <!-- Optional: upload new image to replace -->
                                      <input type="file" name="image" class="form-control mb-3" accept="image/*">

                                      <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Update Image</button>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>

                    

                     
                        <!-- Delete Image Modal -->
                          <div class="modal fade" id="deleteImageModal<?= $image_row['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Delete Image</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                  <p>
                                    Are you sure you want to delete 
                                    <strong><?= htmlspecialchars($image_row['title']) ?></strong>?
                                  </p>
                                  <!-- Preview image -->
                                  <img src="<?= htmlspecialchars($image_row['file_path']) ?>" 
                                      alt="<?= htmlspecialchars($image_row['title']) ?>" 
                                      class="img-fluid rounded shadow-sm mt-2" 
                                      style="max-height:200px; object-fit:contain;">
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <!-- Trigger AJAX delete -->
                                  <button type="button" class="btn btn-danger delete-image-btn" data-id="<?= $image_row['id'] ?>">
                                    Delete
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>

                      <?php endwhile; ?>
                  <?php endif; ?>
              </div>




              <div class="tab-pane fade" id="reactions" role="tabpanel" aria-labelledby="reactions-tab">
                  <div  class="alert alert-info d-flex align-items-center" role="alert"> <strong>Reactions</strong></div>
                  <!-- mwanzo -->
                    <div class="bd-example">
                      <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                          <?php 
                          $i = 0;
                          foreach ($image_result as $image_row): ?>
                            <button type="button" data-bs-target="#carouselExampleCaptions" 
                                    data-bs-slide-to="<?= $i ?>" 
                                    class="<?= $i === 0 ? 'active' : '' ?>" 
                                    aria-current="<?= $i === 0 ? 'true' : 'false' ?>" 
                                    aria-label="Slide <?= $i+1 ?>"></button>
                          <?php 
                          $i++;
                          endforeach; ?>
                        </div>

                        <!-- Carousel Inner -->
                        <div class="carousel-inner">
                          <?php 
                          $i = 0;
                          foreach ($image_result as $image_row): ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                              <img src="<?= htmlspecialchars($image_row['file_path']) ?>" 
                                  class="d-block w-100 img-fluid" 
                                  alt="<?= htmlspecialchars($image_row['title']) ?>" 
                                  style="height:500px; object-fit:cover;">
                              <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                <h5><?= htmlspecialchars($image_row['title']) ?></h5>
                                <p><?= htmlspecialchars($image_row['description']) ?></p>
                              </div>
                            </div>
                          <?php 
                          $i++;
                          endforeach; ?>
                        </div>

                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      </div>
                    </div>
                  <!-- mwisho -->
                  
                  <!-- Horizontal Scrollable Gallery -->
                <div class="container-sm mt-4">
                  <h5 class="mb-3">Scrollable Image Gallery</h5>

                            <div class="d-flex overflow-auto" 
                                  style="white-space: nowrap; gap: 10px; padding-bottom: 10px">

                                <?php  $i=0; foreach ($image_result_small as $image_row): ?>
                                
                                
                                    <?php $image_reaction = $reaction_counts[(int) $image_row['id']] ?? []; ?>
                                    <img src="<?= htmlspecialchars($image_row['file_path']) ?>" 
                                        class="img-thumbnail gallery-img shadow-sm" 
                                        style="height: 150px; width: 150px; object-fit: cover;" 
                                        alt="<?= htmlspecialchars($image_row['title']) ?>"
                                      data-id="<?= (int) $image_row['id'] ?>"
                                      data-likes="<?= (int) ($image_reaction['total_likes'] ?? 0) ?>"
                                      data-dislikes="<?= (int) ($image_reaction['total_dislikes'] ?? 0) ?>"
                                      data-user-reaction="<?= htmlspecialchars($image_reaction['user_reaction'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                
                                <?php  $i++; endforeach; ?>

                              </div>


                          <!-- Image Modal with Navigation -->
                          <div
                            class="modal fade"
                            id="imageModal"
                            tabindex="-1"
                            aria-hidden="true"
                          >
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                              <div class="modal-content bg-dark">
                                <div class="modal-body text-center position-relative">
                                  <button
                                    class="btn btn-dark position-absolute top-50 start-0 translate-middle-y"
                                    id="prevImage"
                                  >
                                    ‹
                                  </button>
                                  <img
                                    id="modalImage"
                                    src=""
                                    class="img-fluid"
                                    alt="Selected Image"
                                  />
                                  <button
                                    class="btn btn-dark position-absolute top-50 end-0 translate-middle-y"
                                    id="nextImage"
                                  >
                                    ›
                                  </button>
                                </div>

                                <div class="modal-footer">
                                  <button class="btn btn-sm btn-outline-success reaction-btn" 
                                      data-action="like" type="button" aria-pressed="false">
                                    👍 <span id="likeCount">0</span>
                                  </button>
                                  <button class="btn btn-sm btn-outline-danger reaction-btn" 
                                      data-action="dislike" type="button" aria-pressed="false">
                                    👎 <span id="dislikeCount">0</span>
                                  </button>

                                  <button
                                    type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal"
                                  >
                                    Close
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                          <!-- Mwisho wa Horizonatl Gallery -->

                
              </div>


             


          



          
            </div>
</main>
<?php include("inside/footer.php"); ?>
     