<?php
$active_menu = 'Branding';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Branding / Logo</li>
                    </ol>
                </nav>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-upload"></i> Upload Logo (PNG preferred)</h6>
                            </div>
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-2">
                                        <label class="form-label">Select Image</label>
                                        <input type="file" name="logo" accept="image/png,image/jpeg" class="form-control" required>
                                        <div class="form-text">Max 2MB. JPGs will be converted to PNG (if supported).</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-image"></i> Current Logo</h6>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($current_logo_url)): ?>
                                    <img src="<?php echo htmlspecialchars($current_logo_url); ?>" alt="Logo" style="max-height:120px; width:auto;" />
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
        <small>&copy; 2025 Branding</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>