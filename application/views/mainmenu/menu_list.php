<?php
$active_menu = 'main_menu';
$title = isset($title) ? $title : 'Main Menu Management';
$menus = isset($menus) ? $menus : array();
$total = isset($total) ? $total : count($menus);
$page  = isset($current_page) ? (int)$current_page : 1;
$limit = isset($limit) ? (int)$limit : 10;

require_once APPPATH . 'views/layout/header.php';
?>
<nav aria-label="breadcrumb">
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
                    <i class="bi bi-list"></i> Main Menu List
                </h5>
                </div>
             <div class="col-md-6 text-end">
                <a href="<?php echo base_url('Main_menu/add'); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Menu
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <style>
            .menu-table-wrapper { overflow-x:auto; }
            .menu-table { min-width:1200px; }
            .menu-table th, .menu-table td {
                white-space:nowrap;
                vertical-align:middle;
                padding:0.35rem 0.5rem;
            }
            .col-role{width:80px;}
            .col-main{width:200px;}
            .col-nav{width:260px;}
            .col-text{width:240px;}
            .col-avail{width:110px;}
            .col-order{width:90px;}
        </style>

        <div class="menu-table-wrapper">
            <table class="table table-hover menu-table">
                <thead>
                    <tr>
                        <th class="col-role">Role ID</th>
                        <th class="col-main">Main Menu</th>
                        <th class="col-nav">Navigation</th>
                        <th class="col-text">Menu Text</th>
                        <th class="col-avail">Available</th>
                        <th class="col-order">Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menus)): ?>
                        <?php foreach ($menus as $m): ?>
                            <tr>
                                <td><?php echo $m['ROLE_ID']; ?></td>
                                <td><strong><?php echo htmlspecialchars($m['MAIN_MENU']); ?></strong></td>
                                <td><?php echo htmlspecialchars($m['NAVIGATION']); ?></td>
                                <td><?php echo htmlspecialchars($m['MENUTEXT']); ?></td>
                                <td>
                                    <?php if (strtoupper($m['AVAILABLE']) === 'YES'): ?>
                                        <span class="badge bg-success">YES</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">NO</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $m['ORDER_NO']; ?></td>
                                <td>
                                    <!-- Edit -->
                                  <!-- <a href="<?php echo base_url('Main_menu/edit/'.$m['id']); ?>"
                                    class="btn btn-warning btn-sm"
                                    title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>-->

                                    <!-- Enable / Disable -->
                                    <?php if (strtoupper($m['AVAILABLE']) === 'YES'): ?>
                                        <a href="<?php echo base_url('Main_menu/disable/'.$m['id']); ?>"
                                        class="btn btn-secondary btn-sm"
                                        onclick="return confirm('Disable this menu?')"
                                        title="Disable">
                                            <i class="bi bi-lock"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('Main_menu/enable/'.$m['id']); ?>"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Enable this menu?')"
                                        title="Enable">
                                            <i class="bi bi-unlock"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No menu records found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination  -->
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
                            <a class="page-link" href="<?php echo base_url('Main_menu?page=' . ($page - 1)); ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page ? 'active' : ''); ?>">
                            <a class="page-link" href="<?php echo base_url('Main_menu?page=' . $i); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo base_url('Main_menu?page=' . ($page + 1)); ?>">
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

<?php

require_once APPPATH . 'views/layout/footer.php';
?>
