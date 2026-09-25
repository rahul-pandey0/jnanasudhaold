<?php
$active_menu = 'users';
$user_email = isset($user_email) ? $user_email : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin');
$title = isset($title) ? $title : 'User Details';
$user = isset($user) ? $user : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Users</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></li>
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

                <!-- User Details Card -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 class="mb-0"><i class="bi bi-person-circle"></i> User Details</h5>
                            <div>
                                <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary btn-sm" title="Back to List">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <a href="<?php echo base_url('users/edit/' . $user['user_id']); ?>" class="btn btn-warning btn-sm" title="Edit User">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary btn-sm" title="Dashboard">
                                    <i class="bi bi-house-door"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" style="margin-bottom: 1rem;">
                           <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Basic Information</h6>

                                <div class="d-flex align-items-start gap-3" 
                                    style="background:#f8f9fa; padding:10px; border-radius:4px;">

                                    <!-- Profile Photo -->
                                    <?php if (!empty($user['profile_photo'])): ?>
                                        <img src="<?php echo base_url('uploads/profile_photos/' . $user['profile_photo']); ?>" 
                                            alt="Profile Photo" 
                                            style="width:110px; height:150px; object-fit:cover; border-radius:4px;">
                                    <?php else: ?>
                                        <div style="width:110px; height:150px; background:#ddd; border-radius:4px;">No Photo</div>
                                    <?php endif; ?>

                                    <!-- Basic Info Text -->
                                    <div>
                                        <div><strong style="font-size:0.75rem;">User ID:</strong> 
                                            <span style="font-size:0.75rem;"><?php echo $user['user_id']; ?></span>
                                        </div>

                                        <div><strong style="font-size:0.75rem;">First Name:</strong> 
                                            <span style="font-size:0.75rem;"><?php echo htmlspecialchars($user['first_name']); ?></span>
                                        </div>

                                        <div><strong style="font-size:0.75rem;">Last Name:</strong> 
                                            <span style="font-size:0.75rem;"><?php echo htmlspecialchars($user['last_name']); ?></span>
                                        </div>

                                        <div><strong style="font-size:0.75rem;">User Name:</strong> 
                                            <span style="font-size:0.75rem;"><?php echo htmlspecialchars(isset($user['user_name']) ? $user['user_name'] : 'N/A'); ?></span>
                                        </div>

                                        <div><strong style="font-size:0.75rem;">Email:</strong> 
                                            <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" 
                                            style="font-size:0.75rem;"><?php echo htmlspecialchars($user['email']); ?></a>
                                        </div>

                                        <div><strong style="font-size:0.75rem;">Phone:</strong> 
                                            <a href="tel:<?php echo htmlspecialchars($user['phone']); ?>" 
                                            style="font-size:0.75rem;"><?php echo htmlspecialchars($user['phone']); ?></a>
                                        </div>

                                        <div><strong style="font-size:0.75rem;">Aadhar:</strong> 
                                            <span style="font-size:0.75rem;"><?php echo htmlspecialchars(isset($user['aadhar_no']) ? $user['aadhar_no'] : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="col-md-6">
                                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Academic Information</h6>
                                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">College Name:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['college_name']) ? $user['college_name'] : 'N/A'); ?></span>
                                    </div>
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">Roll No:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['rollno']) ? $user['rollno'] : 'N/A'); ?></span>
                                    </div>
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">Department:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['department']) ? $user['department'] : 'N/A'); ?></span>
                                    </div>
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">Year:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['year']) ? $user['year'] : 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row" style="margin-bottom: 1rem;">
                            <div class="col-md-12">
                                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Address Information</h6>
                                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">Address:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['address']) ? $user['address'] : 'N/A'); ?></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div style="margin-bottom: 0.3rem;">
                                                <strong style="font-size: 0.7rem;">City:</strong>
                                                <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['city']) ? $user['city'] : 'N/A'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-bottom: 0.3rem;">
                                                <strong style="font-size: 0.7rem;">State:</strong>
                                                <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['state']) ? $user['state'] : 'N/A'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-bottom: 0.3rem;">
                                                <strong style="font-size: 0.7rem;">Pincode:</strong>
                                                <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['pincode']) ? $user['pincode'] : 'N/A'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-bottom: 0.3rem;">
                                                <strong style="font-size: 0.7rem;">Status:</strong>
                                                <span style="font-size: 0.7rem;">
                                                    <?php if ($user['user_status'] == 1): ?>
                                                        <span class="badge-active">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge-inactive">Inactive</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="row" style="margin-bottom: 1rem;">
                            <div class="col-md-6">
                                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">Created:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['creation_date']) ? $user['creation_date'] : 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                                    <div style="margin-bottom: 0.3rem;">
                                        <strong style="font-size: 0.7rem;">Modified:</strong>
                                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars(isset($user['modified_date']) ? $user['modified_date'] : 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Quick Actions</h6>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="<?php echo base_url('users/edit/' . $user['user_id']); ?>" class="btn btn-warning" title="Edit User">
                                        <i class="bi bi-pencil"></i> Edit Profile
                                    </a>
                                    <a href="<?php echo base_url('users/change_password/' . $user['user_id']); ?>" class="btn btn-info" title="Change Password">
                                        <i class="bi bi-key"></i> Change Password
                                    </a>
                                    <a href="<?php echo base_url('users/communication/' . $user['user_id']); ?>" class="btn btn-secondary" title="Communication">
                                        <i class="bi bi-chat-dots"></i> Communications
                                    </a>
                                    
                                    <a href="<?php echo base_url('users/upload_photo/' . $user['user_id']); ?>" class="btn btn-warning" title="upload profile photo">
                                        <i class="bi bi-camera"></i> upload profile photo
                                    </a>
                                    
                                    <a href="<?php echo base_url('users/student_profile/' . $user['phone']); ?>" class="btn btn-info" title="view student profile">
                                        <i class="bi bi-eye"></i> view student profile
                                    </a>
                                    <?php if ($user['user_status'] == 1): ?>
                                        <a href="<?php echo base_url('users/disable/' . $user['user_id']); ?>" class="btn btn-danger" title="Disable" onclick="return confirm('Disable this user?')">
                                            <i class="bi bi-lock"></i> Disable User
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('users/enable/' . $user['user_id']); ?>" class="btn btn-success" title="Enable">
                                            <i class="bi bi-unlock"></i> Enable User
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary" title="Go to Dashboard">
                                        <i class="bi bi-house-door"></i> Dashboard
                                    </a>
                                    <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary" title="Back to List">
                                        <i class="bi bi-list"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>