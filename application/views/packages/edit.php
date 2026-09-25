<?php
$active_menu = 'packages';
$title = isset($title) ? $title : 'Edit Package';
$package = isset($package) ? $package : array();

// Include header (navbar + sidebar)
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('packages'); ?>">Packages</a></li>
        <li class="breadcrumb-item active">Edit Package</li>
    </ol>
</nav>

<!-- Success / Error Messages -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Edit Package Card -->
<div class="card">
    <div class="card-header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Package</h5>
            <div>
                <a href="<?php echo base_url('packages/view/' . $package['id']); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <a href="<?php echo base_url('packages'); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-list"></i> List
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="">
            
            <div class="row">
                <!-- Package Name -->
                <div class="col-md-6 mb-2">
                    <label class="form-label">Package Name</label>
                    <input type="text" class="form-control" name="package_name"
                        value="<?php echo htmlspecialchars($package['package_name'] ?? ''); ?>" required>
                </div>

                <!-- Package Amount -->
                <div class="col-md-6 mb-2">
                    <label class="form-label">Package Amount</label>
                    <input type="text" class="form-control" name="packageamount"
                        value="<?php echo htmlspecialchars($package['packageamount'] ?? ''); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Price -->
                <div class="col-md-6 mb-2">
                    <label class="form-label">Price</label>
                    <input type="text" class="form-control" name="price"
                        value="<?php echo htmlspecialchars($package['price'] ?? ''); ?>" required>
                </div>

                <!-- Subject -->
                <div class="col-md-6 mb-2">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-control" name="subject_name"
                        value="<?php echo htmlspecialchars($package['subject_name'] ?? ''); ?>">
                </div>
            </div>

            <div class="row">
                <!-- SGST -->
                <div class="col-md-4 mb-2">
                    <label class="form-label">SGST Amount</label>
                    <input type="text" class="form-control" name="sgst_amt"
                        value="<?php echo htmlspecialchars($package['sgst_amt'] ?? '0'); ?>">
                </div>

                <!-- CGST -->
                <div class="col-md-4 mb-2">
                    <label class="form-label">CGST Amount</label>
                    <input type="text" class="form-control" name="cgst_amt"
                        value="<?php echo htmlspecialchars($package['cgst_amt'] ?? '0'); ?>">
                </div>

                <!-- IGST -->
                <div class="col-md-4 mb-2">
                    <label class="form-label">IGST Amount</label>
                    <input type="text" class="form-control" name="igst_amt"
                        value="<?php echo htmlspecialchars($package['igst_amt'] ?? '0'); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Start Date -->
                <div class="col-md-6 mb-2">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date"
                        value="<?php echo htmlspecialchars($package['start_date'] ?? ''); ?>">
                </div>

                <!-- End Date -->
                <div class="col-md-6 mb-2">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date"
                        value="<?php echo htmlspecialchars($package['end_date'] ?? ''); ?>">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-2">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="1" <?php echo (($package['status'] ?? 0) == 1 ? 'selected' : ''); ?>>Active</option>
                    <option value="0" <?php echo (($package['status'] ?? 0) == 0 ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>
            <a href="<?php echo base_url('packages/view/' . $package['id']); ?>" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>

        </form>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
