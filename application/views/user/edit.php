<?php

$active_menu = 'users'; // This will highlight the Users menu
$title = isset($title) ? $title : 'Edit User';
$user = isset($user) ? $user : array();
$errors = isset($errors) ? $errors : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Users</a></li>
        <li class="breadcrumb-item active">Edit User</li>
    </ol>
</nav>
    <!-- Edit Form -->
      <div class="row mt-7">
        <div class="col-md-8"> 
               <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit User Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars(isset($user['first_name']) ? $user['first_name'] : ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars(isset($user['last_name']) ? $user['last_name'] : ''); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars(isset($user['email']) ? $user['email'] : ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars(isset($user['phone']) ? $user['phone'] : ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars(isset($user['city']) ? $user['city'] : ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars(isset($user['state']) ? $user['state'] : ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pincode</label>
                                        <input type="text" class="form-control" name="pincode" value="<?php echo htmlspecialchars(isset($user['pincode']) ? $user['pincode'] : ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">College Name</label>
                                        <input type="text" class="form-control" name="college_name" value="<?php echo htmlspecialchars(isset($user['college_name']) ? $user['college_name'] : ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Roll No</label>
                                        <input type="text" class="form-control" name="rollno" value="<?php echo htmlspecialchars(isset($user['rollno']) ? $user['rollno'] : ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars(isset($user['address']) ? $user['address'] : ''); ?></textarea>
                            </div>

                            <hr style="margin: 0.5rem 0;">

                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Save Changes
                                </button>
                                <a href="<?php echo base_url('users/view/' . $user['user_id']); ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    

<?php require_once APPPATH . 'views/layout/footer.php'; ?>

