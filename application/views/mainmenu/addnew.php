<?php
$active_menu = 'Main_menu';
$title = 'Add New Menu';
require_once APPPATH . 'views/layout/header.php';
?>
<style>
.menu-form .form-group {
    margin-bottom: 15px;
}
.menu-form label {
    margin-bottom: 5px;
    display: block;
    font-weight: 600;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

</style>


<div class="row mt-4">
<div class="col-md-6">
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add New Menu</h5>
</div>

<div class="card-body">

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?php echo $_SESSION['success']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?php echo $_SESSION['error']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error']); endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul>
        <?php foreach ($errors as $err): ?>
            <li><?php echo $err; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>


<form method="post" class="menu-form">
<!-- ROLE  -->
    <div class="form-group">
        <label>Role</label>
        <select name="role_id" class="form-control" required>
            <option value="">Select Role</option>
            <?php foreach($roles as $r): ?>
            <option value="<?php echo $r['role_id']; ?>" 
                <?php echo (($_POST['role_id'] ?? '') == $r['role_id']) ? 'selected' : ''; ?>>
                <?php echo $r['role_name']; ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- MAIN MENU  -->
    <div class="form-group">
        <label>Main Menu</label>
        <input type="text" name="main_menu" class="form-control" 
               value="<?php echo $_POST['main_menu'] ?? ''; ?>" placeholder="Enter main menu" required>
    </div>

    <!-- NAVIGATION  -->
    <div class="form-group">
        <label>Navigation</label>
        <input type="text" name="navigation" class="form-control" 
               value="<?php echo $_POST['navigation'] ?? ''; ?>" placeholder="Enter navigation URL" required>
    </div>

    <!-- MENU TEXT  -->
    <div class="form-group">
        <label>Menu Text</label>
        <input type="text" name="menu_text" class="form-control" 
               value="<?php echo $_POST['menu_text'] ?? ''; ?>" placeholder="Enter menu text" required>
    </div>

    <!-- AVAILABLE  -->
    <div class="form-group">
        <label>Available</label>
        <select name="available" class="form-control">
            <option value="Yes" <?php echo (($_POST['available'] ?? '') == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No" <?php echo (($_POST['available'] ?? '') == 'No') ? 'selected' : ''; ?>>No</option>
        </select>
    </div>

    <!-- ORDER  -->
    <div class="form-group">
        <label>Order No</label>
        <input type="number" name="order_no" class="form-control" 
               value="<?php echo $_POST['order_no'] ?? ''; ?>" required>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?php echo base_url('Main_menu/add'); ?>" class="btn btn-danger">Cancel</a>
    </div>

</form>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<?php require_once APPPATH . 'views/layout/footer.php'; ?>
