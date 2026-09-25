<?php
$active_menu = 'roles';
$title = isset($title) ? $title : 'Role Management';
$roles = isset($roles) ? $roles : array();
$total = isset($total) ? $total : count($roles);
$page  = isset($current_page) ? (int)$current_page : 1;
$limit = isset($limit) ? (int)$limit : 10;

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

<div class="card">
    <div class="card-header">
        <div class="row" style="margin:0;">
            <div class="col-md-6">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i> Role List
                </h5>
            </div>
            <div class="col-md-6 text-end">
            <a href="<?php echo base_url('Roles/add'); ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Add Role
            </a>
        </div>
        </div>
    </div>

    <div class="card-body">
       <style>
.role-table-wrapper { overflow-x:auto; }
.role-table { min-width:1000px; } 
.role-table th, .role-table td { 
    white-space: nowrap; 
    vertical-align: middle; 
    padding: 0.35rem 0.5rem; 
}


.col-role-id { width: 80px; }
.col-role-name { width: 250px; }
.col-status { width: 120px; }

.role-actions { display:flex; flex-wrap:nowrap; gap:0.25rem; align-items:center; }
.role-actions .btn { padding:0.25rem 0.4rem; line-height:1; }


.badge-active { display:inline-block; padding:0.25em 0.4em; color:#fff; background-color:#28a745; border-radius:0.25rem; font-size:0.75rem; }
.badge-inactive { display:inline-block; padding:0.25em 0.4em; color:#fff; background-color:#dc3545; border-radius:0.25rem; font-size:0.75rem; }
</style>


        <div class="role-table-wrapper">
            <table class="table table-hover role-table">
                <thead>
                    <tr>
                        <th class="col-role-id">Role ID</th>
                        <th class="col-role-name">Role Name</th>
                        <th class="col-status">Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $r): ?>
                            <tr>
                                <td><?php echo $r['role_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($r['role_name']); ?></strong></td>
                               <td> <?php if (strtolower($r['status']) == 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted">
                                No roles found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding:0.5rem 0; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #dee2e6; font-size:0.65rem;">
            <div>
                <?php
                $start = ($page - 1) * $limit + 1;
                $end   = min($page * $limit, $total);
                echo "Showing $start to $end of $total entries";
                ?>
            </div>

            <?php
            $total_pages = ceil($total / $limit);
            if ($total_pages > 1):
            ?>
            <nav>
                <ul class="pagination mb-0">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo base_url('Roles?page=' . ($page - 1)); ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page ? 'active' : ''); ?>">
                        <a class="page-link" href="<?php echo base_url('Roles?page=' . $i); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo base_url('Roles?page=' . ($page + 1)); ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>

                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
