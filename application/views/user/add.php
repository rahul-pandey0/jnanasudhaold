<?php
$active_menu = 'users';
$title = isset($title) ? $title : 'Add User';
$user = isset($user) ? $user : array();
$errors = isset($errors) ? $errors : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Users</a></li>
        <li class="breadcrumb-item active">Add User</li>
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

<!-- Manual Validation Errors -->
<?php 
$errors = isset($errors) && is_array($errors) ? $errors : array();
if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

 <div class="row mt-6">
<div class="col-md-7"> 
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Add User</h5>
        <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form action="<?php echo base_url('users/add'); ?>" method="post">

            <!-- Basic Info -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>First Name:</label>
                    <input type="text" name="first_name" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['first_name']) ? $user['first_name'] : (isset($_POST['first_name']) ? $_POST['first_name'] : '')); ?>">
                </div>
                <div class="col-md-6">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['last_name']) ? $user['last_name'] : (isset($_POST['last_name']) ? $_POST['last_name'] : '')); ?>">
                </div>
            </div>
          <!-- Contact -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['email']) ? $user['email'] : (isset($_POST['email']) ? $_POST['email'] : '')); ?>">
                </div>
                <div class="col-md-6">
                    <label>Phone:</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['phone']) ? $user['phone'] : (isset($_POST['phone']) ? $_POST['phone'] : '')); ?>">
                </div>
            </div>
             <hr>
            <!-- Status & College -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="1" <?php echo (((isset($user['status']) ? $user['status'] : (isset($_POST['status']) ? $_POST['status'] : '')) == '1') ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo (((isset($user['status']) ? $user['status'] : (isset($_POST['status']) ? $_POST['status'] : '')) == '0') ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
              <div class="col-md-6">
                        <label>College Code:</label>
                        <select name="college_code" class="form-control">
                            <option value="">Select College Code</option>
                            <?php if(!empty($college_code)) { 
                                foreach($college_code as $clg){ ?>
                                    <option value="<?php echo $clg['college_code']; ?>"
                                        <?php echo ((isset($_POST['college_code']) ? $_POST['college_code'] : '') == $clg['college_code']) ? 'selected' : ''; ?>>
                                        <?php echo $clg['college_code']; ?>
                                    </option>
                            <?php }} ?>
                        </select>
                    </div>

            <!-- Batch & Roll -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Batch/Year:</label>
                    <input type="text" name="batch" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['batch']) ? $user['batch'] : (isset($_POST['batch']) ? $_POST['batch'] : '')); ?>">
                </div>
                <div class="col-md-6">
                    <label>Roll Number:</label>
                    <input type="text" name="roll_no" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['roll_no']) ? $user['roll_no'] : (isset($_POST['roll_no']) ? $_POST['roll_no'] : '')); ?>">
                </div>
            </div>
             <hr>
            <!--subject and acm-->
             <div class="row mb-3">
                 <div class="col-md-6">
                    <label>subject:</label>
                    <input type="text" name="subject_name" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['subject_name']) ? $user['subject_name'] : (isset($_POST['subject_name']) ? $_POST['subject_name'] : '')); ?>">
                </div>
                 <div class="col-md-6">
                    <label>ACM:</label>
                    <input type="text" name="acm" class="form-control"
                           value="<?php echo htmlspecialchars(isset($user['accommodation']) ? $user['accommodation'] : (isset($_POST['acm']) ? $_POST['acm'] : '')); ?>">
                </div>
            </div>
            <hr>
            <!--role-->
           <div class="row mb-3">
            <div class="col-md-6">
                    <label>Role:</label>
                    <select name="role_id" class="form-control">
                        <option value="">Select Role</option>
                        <option value="1" <?php echo (((isset($user['role_id']) ? $user['role_id'] : (isset($_POST['role_id']) ? $_POST['role_id'] : '')) == '1') ? 'selected' : ''); ?>>Admin</option>
                        <option value="3" <?php echo (((isset($user['role_id']) ? $user['role_id'] : (isset($_POST['role_id']) ? $_POST['role_id'] : '')) == '3') ? 'selected' : ''); ?>>Teachers</option>
                    </select>
                </div>
            </div>

                <hr>
            <!-- Password and confirm password -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>
            </div>
                <!-- Buttons -->
            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-save"></i> Save
                </button>
                <a href="<?php echo base_url('users'); ?>" class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>