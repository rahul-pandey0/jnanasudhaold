<?php
$active_menu = 'screens';
$title = 'Add Screen';

require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="<?php echo base_url('dashboard'); ?>">Home</a>
        </li>
        <li class="breadcrumb-item active">Add Screen</li>
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

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

   <div class="row mt-4">
        <div class="col-md-6"> 

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add Screen</h5>
                <a href="<?php echo base_url('Screen/index'); ?>" class="btn btn-secondary btn-sm">
                    Back
                </a>
            </div>

            <div class="card-body">
                <form method="post" action="<?php echo base_url('Screen/add'); ?>">

                  
                  <!--  <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Screen ID</label>
                            <input type="number" name="screen_id" class="form-control"
                                   value="<?php echo isset($_POST['screen_id']) ? $_POST['screen_id'] : ''; ?>" required>
                        </div>
                    </div>-->
                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Main Menu</label>
                        <input type="text"
                            name="main_menu"
                            class="form-control"
                            placeholder="Enter main menu name"
                            value="<?php echo isset($screen_data['main_menu']) ? htmlspecialchars($screen_data['main_menu']) : ''; ?>"
                            required>
                    </div>
                </div>

                    
             <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Screen Name</label>
                            <input type="text" name="screen_name" class="form-control" placeholder="Enter screen name"
                                   value="<?php echo isset($_POST['screen_name']) ? $_POST['screen_name'] : ''; ?>" required>
                        </div>
                    </div>

                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Link</label>
                           <input type="text" name="link" class="form-control" value="<?php echo isset($screen_data['link']) && $screen_data['link'] !== '' ? $screen_data['link'] : '#'; ?>">
                </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary me-2">
                            Save
                        </button>
                        <a href="<?php echo base_url('Screen/index'); ?>" class="btn btn-danger">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
