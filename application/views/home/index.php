<?php
// Home landing view
$active_menu = 'home';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Landing</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-house"></i> Welcome</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">This is the new landing page. Use the menu on the left to navigate. The previous implicit dashboard-at-root route has been replaced.</p>
                                <div class="alert alert-info mb-0"><i class="bi bi-info-circle"></i> Quick Tip: Bookmark the Dashboard if you need analytics fast.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="border-top bg-white text-center text-muted">
        <small>&copy; 2025 Admin Panel. All rights reserved.</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
