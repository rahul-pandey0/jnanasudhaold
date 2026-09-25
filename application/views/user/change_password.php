<?php
$active_menu = 'users';
$user_email = isset($user_email) ? $user_email : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin');
$title = isset($title) ? $title : 'Change Password';
$user = isset($user) ? $user : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Users</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('users/view/' . $user['user_id']); ?>"><?php echo htmlspecialchars($user['first_name']); ?></a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </nav>

                <!-- Success/Error Messages -->
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Change Password Form -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-key"></i> Change Password</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?php echo base_url('users/change_password/' . $user['user_id']); ?>">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>User:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                                        <small class="form-text text-muted">Minimum 6 characters</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                                    </div>

                                    <div class="mb-3">
                                        <div style="background-color: #fff3cd; padding: 0.75rem; border-radius: 4px; border-left: 4px solid #ffc107;">
                                            <small><strong>⚠️ Note:</strong> Passwords are encrypted with MD5. Make sure to save the new password securely.</small>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check"></i> Update Password
                                        </button>
                                        <a href="<?php echo base_url('users/view/' . $user['user_id']); ?>" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Cancel
                                        </a>
                                        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-outline-primary">
                                            <i class="bi bi-house-door"></i> Dashboard
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="col-md-6">
                        <div class="card" style="background-color: #e7f3ff; border-color: #667eea;">
                            <div class="card-header" style="background-color: #667eea; color: white;">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Password Requirements</h6>
                            </div>
                            <div class="card-body">
                                <ul style="margin-bottom: 0; font-size: 0.7rem;">
                                    <li>Minimum 6 characters</li>
                                    <li>Passwords must match</li>
                                    <li>Passwords are securely encrypted</li>
                                    <li>User will need to login again with new password</li>
                                    <li>Keep the new password in a secure place</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" style="margin-top: 1rem;">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person-circle"></i> User Information</h6>
                            </div>
                            <div class="card-body" style="font-size: 0.7rem;">
                                <div style="margin-bottom: 0.5rem;">
                                    <strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                </div>
                                <div style="margin-bottom: 0.5rem;">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                                </div>
                                <div>
                                    <strong>Status:</strong> 
                                    <?php if ($user['user_status'] == 1): ?>
                                        <span class="badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
