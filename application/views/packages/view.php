<?php
$active_menu = 'packages';
$title = isset($title) ? $title : 'Package Details';
$package = isset($package) ? $package : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('packages'); ?>">Packages</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($package['package_name'] ?? 'Package'); ?></li>
    </ol>
</nav>

<!-- Success/Error Messages -->
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

<!-- Package Details Card -->
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h5 class="mb-0"><i class="bi bi-box-seam"></i> Package Details</h5>
            <div>
                <a href="<?php echo base_url('packages'); ?>" class="btn btn-secondary btn-sm" title="Back to List">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
               <a href="<?php echo base_url('packages/edit/' . $package['id']); ?>" class="btn btn-warning btn-sm" title="Edit Package">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary btn-sm" title="Dashboard">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <!-- Basic Package Information -->
            <div class="col-md-6">
                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Basic Information</h6>
                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Package ID:</strong>
                        <span style="font-size: 0.7rem;"><?php echo $package['id'] ?? 'N/A'; ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Package Name:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['package_name'] ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Subject:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['subject_name'] ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Type:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['type'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Price & Tax Information -->
            <div class="col-md-6">
                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Pricing & Taxes</h6>
                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Price:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['price'] ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">SGST:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['sgst_amt'] ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">CGST:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['cgst_amt'] ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">IGST:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['igst_amt'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Duration -->
        <div class="row mb-2">
            <div class="col-md-6">
                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Duration</h6>
                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Start Date:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['start_date'] ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">End Date:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['end_date'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-6">
                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Status</h6>
                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Status:</strong>
                        <span style="font-size: 0.7rem;">
                            <?php if (($package['status'] ?? 0) == 1): ?>
                                <span class="badge-active">Active</span>
                            <?php else: ?>
                                <span class="badge-inactive">Inactive</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="row mb-2">
            <div class="col-md-6">
                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Created:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['creation_date'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div style="background-color: #f8f9fa; padding: 0.5rem; border-radius: 4px;">
                    <div style="margin-bottom: 0.3rem;">
                        <strong style="font-size: 0.7rem;">Modified:</strong>
                        <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($package['modified_date'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <h6 style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Quick Actions</h6>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                   <a href="<?php echo base_url('packages/edit/' . $package['id']); ?>" class="btn btn-warning" title="Edit Package">
                        <i class="bi bi-pencil"></i> Edit Package
                    </a>
                    <?php if (($package['status'] ?? 0) == 1): ?>
                        <a href="<?php echo base_url('packages/disable/' . $package['id']); ?>" class="btn btn-danger" title="Disable" onclick="return confirm('Disable this package?')">
                            <i class="bi bi-lock"></i> Disable Package
                        </a>
                    <?php else: ?>
                        <a href="<?php echo base_url('packages/enable/' . $package['id']); ?>" class="btn btn-success" title="Enable">
                            <i class="bi bi-unlock"></i> Enable Package
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary" title="Go to Dashboard">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a href="<?php echo base_url('packages'); ?>" class="btn btn-secondary" title="Back to List">
                        <i class="bi bi-list"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
