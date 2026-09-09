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