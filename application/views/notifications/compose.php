<?php
$active_menu = 'notifications';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Send Notification</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-bell"></i> Send FCM Notification</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="<?php echo base_url('notifications/quiz_rank'); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-trophy"></i> Quiz Rank Notify
                                    </a>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-phone"></i> <?php echo (int)(isset($token_count) ? $token_count : 0); ?> devices
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">

                                <?php if (!empty($_GET['status'])): ?>
                                    <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_GET['status']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($_GET['error'])): ?>
                                    <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
                                <?php endif; ?>

                                <form method="POST" action="<?php echo base_url('notifications/send'); ?>">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Target Type</label>
                                            <select class="form-select" name="target_type" id="targetType" onchange="updateTargetValue(this.value)">
                                                <option value="fcm_registered">FCM Registered Only</option>
                                                <option value="role">By Role</option>
                                                <option value="batch">By Batch</option>
                                                <option value="individual">Individual User</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="targetValueWrap" style="display:none;">
                                            <label class="form-label fw-semibold" id="targetValueLabel">Value</label>

                                            <!-- Role select -->
                                            <select class="form-select" name="target_value" id="roleSelect" style="display:none;">
                                                <option value="">— select role —</option>
                                                <?php foreach ((isset($roles) ? $roles : array()) as $r): ?>
                                                    <option value="<?php echo htmlspecialchars($r); ?>"><?php echo htmlspecialchars($r); ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <!-- Batch select -->
                                            <select class="form-select" name="target_value" id="batchSelect" style="display:none;">
                                                <option value="">— select batch —</option>
                                                <?php foreach ((isset($batches) ? $batches : array()) as $b): ?>
                                                    <option value="<?php echo htmlspecialchars($b); ?>"><?php echo htmlspecialchars($b); ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <!-- Individual input -->
                                            <input type="text" class="form-control" name="target_value" id="individualInput" placeholder="Phone number, username or user ID" style="display:none;">
                                        </div>
                                    </div>

                                    <!-- Send Mode — shown for batch, role, individual -->
                                    <div class="mb-3" id="sendModeWrap" style="display:none;">
                                        <label class="form-label fw-semibold">Send Mode</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="send_mode" id="modeNormal" value="normal" checked>
                                                <label class="form-check-label" for="modeNormal">
                                                    <i class="bi bi-inbox"></i> Normal
                                                    <small class="text-muted d-block">Saves to inbox only (no FCM push)</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="send_mode" id="modeFcm" value="fcm">
                                                <label class="form-check-label" for="modeFcm">
                                                    <i class="bi bi-phone-vibrate"></i> FCM Push + Inbox
                                                    <small class="text-muted d-block">Pushes to registered devices &amp; saves to inbox</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Title</label>
                                        <input type="text" class="form-control" name="title" required placeholder="Notification title">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Body</label>
                                        <textarea class="form-control" name="body" rows="3" required placeholder="Notification message..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Image URL <span class="text-muted fw-normal">(optional)</span></label>
                                        <input type="url" class="form-control" name="image_url" placeholder="https://example.com/image.png">
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i> Send Notification
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Target Types</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>FCM Registered Only</strong> — sends only to active users who have a registered device token.</p>
                                <p class="mb-2"><strong>By Role</strong> — sends to all active users with a specific role.</p>
                                <p class="mb-2"><strong>By Batch</strong> — sends to all active users in a specific batch.</p>
                                <p class="mb-2"><strong>Individual</strong> — sends to a single user by ID or phone.</p>
                                <hr class="my-2">
                                <p class="mb-1"><strong>Send Mode</strong> (for Batch / Role / Individual):</p>
                                <p class="mb-1"><strong>Normal</strong> — saves to inbox only; no FCM push.</p>
                                <p class="mb-2"><strong>FCM Push + Inbox</strong> — pushes to registered devices and saves to inbox.</p>
                                <p class="mb-0 text-muted small">One record is logged in <code>fcm_notifications</code>; individual rows are written to <code>user_notifications</code>.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <footer class="border-top bg-white text-center text-muted">
        <small>&copy; <?php echo date('Y'); ?> Admin Panel. All rights reserved.</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Types that need no secondary value (no value picker, no mode picker)
    var noValueTypes = ['fcm_registered'];
    // Types that show the send-mode radio (normal vs FCM)
    var modeTypes    = ['batch', 'role', 'individual'];

    function updateTargetValue(type) {
        var wrap            = document.getElementById('targetValueWrap');
        var roleSelect      = document.getElementById('roleSelect');
        var batchSelect     = document.getElementById('batchSelect');
        var individualInput = document.getElementById('individualInput');
        var label           = document.getElementById('targetValueLabel');
        var modeWrap        = document.getElementById('sendModeWrap');

        // hide all sub-inputs, remove their name so they don't submit
        [roleSelect, batchSelect, individualInput].forEach(function(el) {
            el.style.display = 'none';
            el.removeAttribute('name');
        });

        if (noValueTypes.indexOf(type) !== -1) {
            wrap.style.display     = 'none';
            modeWrap.style.display = 'none';
        } else if (type === 'role') {
            wrap.style.display     = '';
            modeWrap.style.display = '';
            label.textContent      = 'Role';
            roleSelect.style.display = '';
            roleSelect.setAttribute('name', 'target_value');
        } else if (type === 'batch') {
            wrap.style.display     = '';
            modeWrap.style.display = '';
            label.textContent      = 'Batch';
            batchSelect.style.display = '';
            batchSelect.setAttribute('name', 'target_value');
        } else if (type === 'individual') {
            wrap.style.display     = '';
            modeWrap.style.display = '';
            label.textContent      = 'User ID / Phone';
            individualInput.style.display = '';
            individualInput.setAttribute('name', 'target_value');
        }
    }
    updateTargetValue(document.getElementById('targetType').value);
    </script>
</body>
</html>
