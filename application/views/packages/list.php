<?php
$active_menu = 'packages';
$title = isset($title) ? $title : 'Package Management';
$packages = isset($packages) ? $packages : array();
$total = isset($total) ? $total : 0;
$page = isset($current_page) ? $current_page : 1;
$limit = isset($limit) ? $limit : 10;
$search = isset($search) ? $search : '';
$status = isset($status) ? $status : '';
$stats = isset($stats) ? $stats : array();
$type = isset($package_type) ? $package_type : '';

require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item active"><?php echo $title; ?></li>
    </ol>
</nav>

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error']); endif; ?>

<div class="row mb-2">
    <div class="col-md-3">
        <div class="stat-card"><h6>Total Packages</h6><h3><?php echo isset($stats['total']) ? $stats['total'] : 0; ?></h3></div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#28a745 0%,#20c997 100%);">
            <h6>Active</h6><h3><?php echo isset($stats['active']) ? $stats['active'] : 0; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#dc3545 0%,#fd7e14 100%);">
            <h6>Inactive</h6><h3><?php echo isset($stats['inactive']) ? $stats['inactive'] : 0; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#17a2b8 0%,#20c997 100%);">
            <h6>Pages</h6><h3><?php echo ceil($total / $limit); ?></h3>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row" style="margin:0;">
            <div class="col-md-6"><h5 class="mb-0"><i class="bi bi-box-seam"></i> Package List</h5></div>
            <div class="col-md-6" style="padding:0;">
                <form method="GET" action="<?php echo base_url('packages'); ?>" class="d-flex flex-wrap" style="gap:0.25rem;">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" style="font-size:0.65rem;min-width:8rem;">
                    <select name="status" class="form-select form-select-sm" style="max-width:9rem;">
                        <option value="">All</option>
                        <option value="active" <?php echo $status==='active'?'selected':''; ?>>Active</option>
                        <option value="inactive" <?php echo $status==='inactive'?'selected':''; ?>>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    <?php if (!empty($search) || !empty($status)): ?>
                        <a href="<?php echo base_url('packages'); ?>" class="btn btn-secondary btn-sm">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="user-table-wrapper">
            <table class="table table-hover user-table">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">Package Name</th>
                        <th class="col-email">Subject</th>
                        <th class="col-phone">Start Date</th>
                        <th class="col-status">End Date</th>
                        <th class="col-college">Price</th>
                        <th class="col-batch">SGST</th>
                        <th class="col-acm">CGST</th>
                        <th class="col-roll">IGST</th>
                        <th class="col-comm">Type</th>
                        <th class="col-status">Status</th> 
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($packages)): ?>
                        <?php foreach ($packages as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['package_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($p['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($p['price']); ?></td>
                            <td><?php echo htmlspecialchars($p['sgst_amt']); ?></td>
                            <td><?php echo htmlspecialchars($p['cgst_amt']); ?></td>
                            <td><?php echo htmlspecialchars($p['igst_amt']); ?></td>
                           <td><?php echo htmlspecialchars($p['type']); ?></td>

                            <td>
                                <?php if ($p['status'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="user-actions">
                                    <a href="<?php echo base_url('packages/view/' . $p['id']); ?>" class="btn btn-primary btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="<?php echo base_url('packages/edit/' . $p['id']); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>

                                    <?php if ($p['status']==1): ?>
                                        <a href="<?php echo base_url('packages/disable/' . $p['id']); ?>" class="btn btn-danger btn-sm" title="Disable" onclick="return confirm('Disable this package?')"><i class="bi bi-lock"></i></a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('packages/enable/' . $p['id']); ?>" class="btn btn-success btn-sm" title="Enable"><i class="bi bi-unlock"></i></a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="12" class="text-center text-muted">No packages found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 0.5rem 0; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #dee2e6; font-size: 0.65rem;">
            <div class="datatable-info">
                <?php
                // FIX: use actual number of packages on the current page
                $start = ($page - 1) * $limit + 1;
                $end = ($start - 1) + count($packages);

                echo 'Showing ' . $start . ' to ' . $end . ' of ' . $total . ' entries';
                if (!empty($search)) echo ' (filtered)';
                ?>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination mb-0" style="gap: 0.1rem;">
                    <?php
                    $total_pages = ceil($total > 0 ? $total/$limit : 1);

                    // First/Prev
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="'.base_url('packages?page=1&search='.$search.'&status='.$status.'&type='.$type).'"><i class="bi bi-chevron-double-left"></i></a></li>';
                        echo '<li class="page-item"><a class="page-link" href="'.base_url('packages?page='.($page-1).'&search='.$search.'&status='.$status.'&type='.$type).'"><i class="bi bi-chevron-left"></i></a></li>';
                    } else {
                        echo '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-double-left"></i></span></li>';
                        echo '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>';
                    }

                    // Page numbers
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);

                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="'.base_url('packages?page=1&search='.$search.'&status='.$status.'&type='.$type).'">1</a></li>';
                        if ($start_page > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    for ($i = $start_page; $i <= $end_page; $i++) {
                        echo '<li class="page-item '.($i==$page?'active':'').'"><a class="page-link" href="'.base_url('packages?page='.$i.'&search='.$search.'&status='.$status.'&type='.$type).'">'.$i.'</a></li>';
                    }

                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages-1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        echo '<li class="page-item"><a class="page-link" href="'.base_url('packages?page='.$total_pages.'&search='.$search.'&status='.$status.'&type='.$type).'">'.$total_pages.'</a></li>';
                    }

                    // Next/Last
                    if ($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="'.base_url('packages?page='.($page+1).'&search='.$search.'&status='.$status.'&type='.$type).'"><i class="bi bi-chevron-right"></i></a></li>';
                        echo '<li class="page-item"><a class="page-link" href="'.base_url('packages?page='.$total_pages.'&search='.$search.'&status='.$status.'&type='.$type).'"><i class="bi bi-chevron-double-right"></i></a></li>';
                    } else {
                        echo '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>';
                        echo '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-double-right"></i></span></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>

    </div>
</div>

<footer class="border-top bg-white text-center text-muted">
    <small>&copy; 2025 Admin Dashboard. All rights reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>