<?php
$active_menu = 'roles';
$title = 'Add Role';

require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="<?php echo base_url('dashboard'); ?>">Home</a>
        </li>
        <li class="breadcrumb-item active">Add Role</li>
    </ol>
</nav>

<!-- Success Message -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Error Message -->
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

 <div class="row mt-4">
<div class="col-md-6"> 
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add Role</h5>
        <a href="<?php echo base_url('Roles/index'); ?>" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>

    <div class="card-body">
        <form method="post" action="<?php echo base_url('roles/add'); ?>">

            <div class="row mb-3">
              <div class="col-md-4">
            <label class="form-label">Role ID</label>
            <input type="text" class="form-control" 
                value="<?php echo isset($next_role_id) ? $next_role_id : 'Auto-generated'; ?>" 
                readonly>
        </div>
    <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Role Name</label>
                <input type="text" name="role_name" class="form-control"
                       value="<?php echo isset($_POST['role_name']) ? $_POST['role_name'] : ''; ?>" required>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary me-2">
                    Save
                </button>
                <a href="<?php echo base_url('Roles/index'); ?>" class="btn btn-danger">
                    Cancel
                </a>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
