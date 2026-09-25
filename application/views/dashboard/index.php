<?php
// DEBUG: Check what variables are available
echo "<!-- DEBUG: user_role = " . (isset($user_role) ? $user_role : 'NOT SET') . " -->";
echo "<!-- DEBUG: user_name = " . (isset($user_name) ? $user_name : 'NOT SET') . " -->";
echo "<!-- DEBUG: top_menus count = " . (isset($top_menus) ? count($top_menus) : 'NOT SET') . " -->";

// Set active menu for sidebar highlight
$active_menu = 'dashboard';
// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo isset($title) ? $title : 'Dashboard'; ?></li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard Overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card border-primary">
                                            <div class="card-body" style="padding: 0.5rem;">
                                                <h6 class="text-muted mb-1" style="font-size: 0.65rem;">Total Users</h6>
                                                <h3 class="mb-0" style="font-size: 1.2rem;">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-success">
                                            <div class="card-body" style="padding: 0.5rem;">
                                                <h6 class="text-muted mb-1" style="font-size: 0.65rem;">Active</h6>
                                                <h3 class="mb-0" style="font-size: 1.2rem;">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-warning">
                                            <div class="card-body" style="padding: 0.5rem;">
                                                <h6 class="text-muted mb-1" style="font-size: 0.65rem;">Pending</h6>
                                                <h3 class="mb-0" style="font-size: 1.2rem;">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-danger">
                                            <div class="card-body" style="padding: 0.5rem;">
                                                <h6 class="text-muted mb-1" style="font-size: 0.65rem;">Inactive</h6>
                                                <h3 class="mb-0" style="font-size: 1.2rem;">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> <strong>Welcome!</strong> You are now logged in to the admin dashboard. Ready to manage your data?
                                </div>
                            </div>
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
