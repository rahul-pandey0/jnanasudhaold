<?php
$active_menu = 'Branding';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Branding / View Logo</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-image"></i> Current Logo</h6>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($current_logo_url)): ?>
                                    <img src="<?php echo htmlspecialchars($current_logo_url); ?>" alt="Logo" style="max-height:140px; width:auto;" />
                                <?php else: ?>
                                    <div class="text-muted">No logo uploaded yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="border-top bg-white text-center text-muted">
        <small>&copy; 2025 Branding View</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
