<?php
$active_menu = 'users';
$user_email = isset($user_email) ? $user_email : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin');
$title = isset($title) ? $title : 'User Management';
$users = isset($users) ? $users : array();
$total = isset($total) ? $total : 0;
$page = isset($current_page) ? (int)$current_page : 1;
$limit = isset($limit) ? (int)$limit : 10;
$search = isset($search) ? $search : '';
$stats = isset($stats) ? $stats : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
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

                <!-- Statistics -->
                <div class="row" style="margin-bottom: 0.5rem;">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h6>Total Users</h6>
                            <h3><?php echo isset($stats['total']) ? $stats['total'] : 0; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <h6>Active</h6>
                            <h3><?php echo isset($stats['active']) ? $stats['active'] : 0; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                            <h6>Inactive</h6>
                            <h3><?php echo isset($stats['inactive']) ? $stats['inactive'] : 0; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                            <h6>Registered</h6>
                            <h3><?php echo ceil($total / $limit); ?></h3>
                        </div>
                    </div>
                </div>

                <!-- User List Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="row" style="margin: 0;">
                            <div class="col-md-6">
                                <h5 class="mb-0"><i class="bi bi-people"></i> User List</h5>
                            </div>
                            <div class="col-md-6" style="padding: 0;">
                                <form method="GET" action="<?php echo base_url('users'); ?>" class="d-flex flex-wrap" style="gap: 0.25rem;">
                                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" style="font-size: 0.65rem; min-width: 8rem;">
                                    <select name="status" class="form-select form-select-sm" style="max-width: 9rem;">
                                        <option value="">All</option>
                                        <option value="active" <?php echo (!empty($status) && $status === 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (!empty($status) && $status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                    <select name="college" class="form-select form-select-sm" style="max-width: 12rem;">
                                        <option value="">All Colleges</option>
                                        <?php if (!empty($colleges)): foreach ($colleges as $c): ?>
                                            <option value="<?php echo htmlspecialchars($c); ?>" <?php echo (!empty($filter_college) && $filter_college === $c) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <select name="batch" class="form-select form-select-sm" style="max-width: 9rem;">
                                        <option value="">All Batches</option>
                                        <?php if (!empty($batches)): foreach ($batches as $b): ?>
                                            <option value="<?php echo htmlspecialchars($b); ?>" <?php echo (!empty($filter_batch) && $filter_batch === $b) ? 'selected' : ''; ?>><?php echo htmlspecialchars($b); ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <select name="standard" class="form-select form-select-sm" style="max-width: 9rem;">
                                        <option value="">All Standards</option>
                                        <?php if (!empty($standards)): foreach ($standards as $s): ?>
                                            <option value="<?php echo htmlspecialchars($s); ?>" <?php echo (!empty($filter_standard) && $filter_standard === $s) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s); ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <?php
                                    $roleNames = [
                                            '1' => 'Admin',
                                            '2' => 'student',
                                            '3' => 'Teacher',
                                            '4' => 'Finance Admin',
                                            '5' =>'library admin'
                                             ];?>
                               <select name="role_id" class="form-select form-select-sm" style="max-width: 9rem;">
                                        <option value="">role id</option>
                                        <?php if (!empty($role_id)): foreach ($role_id as $r): ?>
                                            <option value="<?php echo htmlspecialchars($r); ?>" <?php echo (!empty($filter_role_id) && $filter_role_id === $r) ? 'selected' : ''; ?>><?php echo htmlspecialchars(isset($roleNames[$r]) ? $roleNames[$r] : $r); ?></option>
                                       
                                            <?php endforeach; endif; ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                                    <?php if (!empty($search) || !empty($status) || !empty($filter_college) || !empty($filter_batch) || !empty($filter_standard)): ?>
                                        <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary btn-sm">Clear</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <style>
                            .user-table-wrapper { overflow-x:auto; }
                            .user-table { min-width:1580px; }
                            .user-table th, .user-table td { white-space:nowrap; vertical-align:middle; }
                            .user-table tbody td { padding:0.35rem 0.5rem; }
                            .col-id{width:70px;} .col-name{width:180px;} .col-email{width:220px;} .col-phone{width:140px;}
                            .col-status{width:90px;} .col-college{width:180px;} .col-batch{width:120px;} .col-acm{width:120px;}
                            .col-city{width:130px;} .col-roll{width:140px;} .col-comm{width:160px;} .col-password{width:180px;} .col-actions{width:260px;}
                            .user-actions { display:flex; flex-wrap:nowrap; gap:0.25rem; align-items:center; }
                            .user-actions .btn { padding:0.25rem 0.4rem; line-height:1; }
                            .comm-form { display:flex; flex-wrap:nowrap; gap:0.25rem; align-items:center; }
                            .comm-form input { padding:0.2rem 0.35rem; }
                            .comm-form .btn { padding:0.25rem 0.45rem; }
                            .password-form { display:flex; flex-wrap:nowrap; gap:0.25rem; align-items:center; }
                            .password-form input { padding:0.2rem 0.35rem; }
                            .password-form .btn { padding:0.25rem 0.45rem; }
                        </style>
                        <div class="user-table-wrapper">
                            <table class="table table-hover user-table">
                                <thead>
                                    <tr>
                                        <th class="col-id">ID</th>
                                        <th class="col-name">Name</th>
                                        <th class="col-email">Email</th>
                                        <th class="col-phone">Phone</th>
                                        <th class="col-status">Status</th>
                                        <th class="col-college">College</th>
                                        <th class="col-batch">Batch</th>
                                        <th class="col-acm">ACM</th>
                                        <th class="col-roll">Roll No</th>
                                        <th class="col-comm">Comm Mobile</th>
                                        <th class="col-password">Password</th>
                                        <th class="col-actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo $user['user_id']; ?></td>
                                                <td><strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars(isset($user['email']) ? $user['email'] : 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars(isset($user['phone']) ? $user['phone'] : 'N/A'); ?></td>
                                                <td>
                                                    <?php if ($user['user_status'] == 1): ?>
                                                        <span class="badge-active">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge-inactive">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars(isset($user['college_name']) ? $user['college_name'] : 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars(isset($user['batch']) ? $user['batch'] : 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars(isset($user['accommodation']) ? $user['accommodation'] : 'N/A'); ?></td>
                                               
                                                <td><?php echo htmlspecialchars(isset($user['rollno']) ? $user['rollno'] : 'N/A'); ?></td>
                                                 
                                                <td>
                                                    <form method="POST" action="<?php echo base_url('users/update_comm_count'); ?>" class="comm-form">
                                                        <input type="hidden" name="user_id" value="<?php echo (int)$user['user_id']; ?>">
                                                        <?php $commVal = isset($user['no_of_communication']) ? trim($user['no_of_communication']) : ''; ?>
                                                        <input type="text" name="no_of_communication" value="<?php echo htmlspecialchars($commVal); ?>" class="form-control form-control-sm" placeholder="Enter communication mobile" style="max-width: 9rem;" title="Communication mobile (stored in no_of_communication)">
                                                        <?php if ($commVal === ''): ?>
                                                            <span class="text-muted small" title="no_of_communication is empty">Empty</span>
                                                        <?php endif; ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Save">
                                                            <i class="bi bi-check2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="POST" action="<?php echo base_url('users/update_password'); ?>" class="password-form">
                                                        <input type="hidden" name="user_id" value="<?php echo (int)$user['user_id']; ?>">
                                                        <?php $currentPassword = isset($user['actual_password']) ? $user['actual_password'] : ''; ?>
                                                        <input type="text" name="actual_password" value="<?php echo htmlspecialchars($currentPassword); ?>" class="form-control form-control-sm" placeholder="Enter new password" style="max-width: 10rem;" title="User's actual password">
                                                        <?php if (empty($currentPassword)): ?>
                                                            <span class="text-muted small" title="No password set">No Password</span>
                                                        <?php endif; ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Update Password">
                                                            <i class="bi bi-key"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="user-actions">
                                                        <a href="<?php echo base_url('users/view/' . $user['user_id']); ?>" class="btn btn-primary btn-sm" title="View" data-bs-toggle="tooltip">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('users/edit/' . $user['user_id']); ?>" class="btn btn-warning btn-sm" title="Edit" data-bs-toggle="tooltip">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('users/change_password/' . $user['user_id']); ?>" class="btn btn-info btn-sm" title="Change Password" data-bs-toggle="tooltip">
                                                            <i class="bi bi-key"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('users/communication/' . $user['user_id']); ?>" class="btn btn-secondary btn-sm" title="Communication" data-bs-toggle="tooltip">
                                                            <i class="bi bi-chat-dots"></i>
                                                        </a>
                                                        <?php if ($user['user_status'] == 1): ?>
                                                            <a href="<?php echo base_url('users/disable/' . $user['user_id']); ?>" class="btn btn-danger btn-sm" title="Disable" data-bs-toggle="tooltip" onclick="return confirm('Disable this user?')">
                                                                <i class="bi bi-lock"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?php echo base_url('users/enable/' . $user['user_id']); ?>" class="btn btn-success btn-sm" title="Enable" data-bs-toggle="tooltip">
                                                                <i class="bi bi-unlock"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">No users found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div style="padding: 0.5rem 0; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #dee2e6; font-size: 0.65rem;">
                            <div class="datatable-info">
                                <?php
                                $start = ($page - 1) * $limit + 1;
                                $end = min($page * $limit, $total);
                                echo 'Showing ' . $start . ' to ' . $end . ' of ' . $total . ' entries';
                                if (!empty($search)) {
                                    echo ' (filtered)';
                                }
                                ?>
                            </div>

                            <!-- Pagination Links -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0" style="gap: 0.1rem;">
                                    <!-- First Page -->
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo base_url('users?page=1' 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>" title="First">
                                                <i class="bi bi-chevron-double-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo base_url('users?page=' . ($page - 1) 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>" title="Previous">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-double-left"></i></span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <?php
                                    $total_pages = ceil($total > 0 ? $total / $limit : 1);
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($total_pages, $page + 2);

                                    if ($start_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo base_url('users?page=1' 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>">1</a>
                                        </li>
                                        <?php if ($start_page > 2): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?php echo ($i === $page ? 'active' : ''); ?>">
                                            <a class="page-link" href="<?php echo base_url('users?page=' . $i 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($end_page < $total_pages): ?>
                                        <?php if ($end_page < $total_pages - 1): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo base_url('users?page=' . $total_pages 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>">
                                                <?php echo $total_pages; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Next Page -->
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo base_url('users?page=' . ($page + 1) 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>" title="Next">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo base_url('users?page=' . $total_pages 
                                                . (!empty($search) ? '&search=' . urlencode($search) : '')
                                                . (!empty($status) ? '&status=' . urlencode($status) : '')
                                                . (!empty($filter_college) ? '&college=' . urlencode($filter_college) : '')
                                                . (!empty($filter_batch) ? '&batch=' . urlencode($filter_batch) : '')
                                                . (!empty($filter_standard) ? '&standard=' . urlencode($filter_standard) : '')); ?>" title="Last">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-double-right"></i></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="border-top bg-white text-center text-muted">
        <small>&copy; 2025 Admin Dashboard. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>