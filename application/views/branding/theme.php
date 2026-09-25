<?php
$active_menu = 'Branding';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Branding / Theme Options</li>
                    </ol>
                </nav>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-palette"></i> Theme Colors (Session-only)</h6>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-2">
                                        <label class="form-label">Primary Color</label>
                                        <input type="color" name="primary_color" value="<?php echo htmlspecialchars($primary_color); ?>" class="form-control form-control-color" title="Choose primary color">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Secondary Color</label>
                                        <input type="color" name="secondary_color" value="<?php echo htmlspecialchars($secondary_color); ?>" class="form-control form-control-color" title="Choose secondary color">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Preview</label>
                                        <div style="height:40px; border-radius:4px; background: linear-gradient(135deg, <?php echo htmlspecialchars($primary_color); ?> 0%, <?php echo htmlspecialchars($secondary_color); ?> 100%);"></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="border-top bg-white text-center text-muted">
        <small>&copy; 2025 Branding Theme</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
