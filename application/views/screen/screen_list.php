<?php
$active_menu = 'screens';
$title = isset($title) ? $title : 'Screen Management';
$screens = isset($screens) ? $screens : array();
$total_records = isset($total) ? $total : count($screens);
$current_page  = isset($current_page) ? (int)$current_page : 1;
$limit         = isset($limit) ? (int)$limit : 10;
$total_pages   = ceil($total_records / $limit);

require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            
            <a href="<?php echo base_url('dashboard'); ?>">Home</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $title; ?></li>
    </ol>
</nav>

<!-- Success/Error Messages -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
 <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">
                    <i class="bi bi-window"></i> <?php echo $title; ?>
                </h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?php echo base_url('Screen/add'); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Screen
                </a>
            </div>
        </div>
    </div>
 <div class="card-body">
        <div class="table-responsive screen-table-wrapper">
            <table class="table table-hover table-sm">
                <thead>
                    <tr style="background-color:#f8f9fa;">
                        <th>ID</th>
                        <th>Main menu</th>
                        <th>Screen Name</th>
                        <th>Link</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($screens)): ?>
                        <?php foreach ($screens as $s): ?>
                            <tr>
                                <td><?php echo (int)$s['screen_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($s['main_menu']); ?></strong></td>
                                <td><strong><?php echo htmlspecialchars($s['screen_name']); ?></strong></td>
                                <td>
                                <a href="<?php echo base_url($s['link']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($s['link']); ?>
                                </a>
                            </td>

                                <td>
                                    <?php if ($s['status'] == 'Active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="screen-actions">
                                    <!-- Edit -->
                                    <a href="<?php echo base_url('Screen/edit/' . $s['screen_id']); ?>" 
                                       class="btn btn-warning btn-sm" title="Edit Screen">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Enable / Disable -->
                                    <?php if ($s['status'] == 'Active'): ?>
                                        <a href="<?php echo base_url('Screen/disable/' . $s['screen_id']); ?>" 
                                           class="btn btn-secondary btn-sm" 
                                           title="Disable Screen"
                                           onclick="return confirm('Are you sure you want to disable this screen?');">
                                            <i class="bi bi-lock"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('Screen/enable/' . $s['screen_id']); ?>" 
                                           class="btn btn-success btn-sm" 
                                           title="Enable Screen"
                                           onclick="return confirm('Are you sure you want to enable this screen?');">
                                            <i class="bi bi-unlock"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No screens found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

       <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <?php
                    $start = ($current_page - 1) * $limit + 1;
                    $end = min($current_page * $limit, $total_records);
                ?>
                <div style="padding: 0.5rem 0; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #dee2e6; font-size: 0.85rem;">
                    <!-- Left: Info text -->
                    <div class="datatable-info">
                        Showing <?php echo $start; ?> to <?php echo $end; ?> of <?php echo $total_records; ?> entries
                    </div>

                    <!-- Right: Pagination links -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0" style="gap: 0.2rem;">

                            <!-- First & Previous -->
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('screen?page=1'); ?>">First</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('screen?page=' . ($current_page - 1)); ?>">Previous</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">First</span></li>
                                <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                                $start_page = max(1, $current_page - 2);
                                $end_page = min($total_pages, $current_page + 2);
                            ?>
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo base_url('screen?page=' . $i); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next & Last -->
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('screen?page=' . ($current_page + 1)); ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('screen?page=' . $total_pages); ?>">Last</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">Next</span></li>
                                <li class="page-item disabled"><span class="page-link">Last</span></li>
                            <?php endif; ?>

                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
<?php require_once APPPATH . 'views/layout/footer.php'; ?>
