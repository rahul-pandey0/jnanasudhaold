<?php
$active_menu = 'user_notify';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('notifications'); ?>">Notifications</a></li>
                        <li class="breadcrumb-item active">User Notify</li>
                    </ol>
                </nav>

                <?php if (!empty($_GET['status'])): ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_GET['status']); ?></div>
                <?php endif; ?>
                <?php if (!empty($_GET['error'])): ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <div class="row">
                    <!-- Left: target + compose -->
                    <div class="col-md-4">

                        <!-- Step 1 -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-people"></i> Step 1 — Select Target</h5>
                            </div>
                            <div class="card-body">

                                <!-- Target type toggle -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Target Type</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="targetTypeRadio" id="typeBatch" value="batch" checked onchange="onTypeChange()">
                                        <label class="btn btn-outline-primary" for="typeBatch">By Batch</label>

                                        <input type="radio" class="btn-check" name="targetTypeRadio" id="typeRole" value="role" onchange="onTypeChange()">
                                        <label class="btn btn-outline-primary" for="typeRole">By Role</label>
                                    </div>
                                </div>

                                <!-- Batch selector -->
                                <div id="batchWrap" class="mb-3">
                                    <label class="form-label fw-semibold">Batch</label>
                                    <select class="form-select" id="batchSelect" onchange="autoLoad()">
                                        <option value="">— select batch —</option>
                                        <?php foreach ($batches as $b): ?>
                                            <option value="<?php echo htmlspecialchars($b['batch']); ?>"><?php echo htmlspecialchars($b['batch']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Role selector -->
                                <div id="roleWrap" class="mb-3" style="display:none;">
                                    <label class="form-label fw-semibold">Role</label>
                                    <select class="form-select" id="roleSelect" onchange="autoLoad()">
                                        <option value="">— select role —</option>
                                        <?php foreach ($roles as $r): ?>
                                            <option value="<?php echo (int)$r['role_id']; ?>">
                                                <?php echo htmlspecialchars($r['role_name']); ?> (<?php echo (int)$r['role_id']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- Step 2: Compose -->
                        <div class="card" id="composeCard" style="display:none;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-bell"></i> Step 2 — Compose &amp; Send</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?php echo base_url('notifications/user_notify_send'); ?>" id="sendForm" onsubmit="return prepareSubmit()">
                                    <input type="hidden" name="selected_users" id="hiddenUsers">

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Title</label>
                                        <input type="text" class="form-control" id="notifTitle" name="title" required placeholder="e.g. Important Update">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Body</label>
                                        <textarea class="form-control" id="notifBody" name="body" rows="4" required placeholder="Hi {name}, ..."></textarea>
                                        <div class="form-text">Placeholders: <code>{name}</code> <code>{phone}</code> <code>{batch}</code> <code>{role}</code></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Send Mode</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="send_mode" id="modeNormal" value="normal">
                                                <label class="form-check-label" for="modeNormal">
                                                    <i class="bi bi-inbox"></i> Normal
                                                    <small class="text-muted d-block">Inbox only</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="send_mode" id="modeFcm" value="fcm" checked>
                                                <label class="form-check-label" for="modeFcm">
                                                    <i class="bi bi-phone-vibrate"></i> FCM + Inbox
                                                    <small class="text-muted d-block">Push to devices</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-send"></i> Send to <span id="userCount">0</span> users
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right: user preview table -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0" id="previewTitle"><i class="bi bi-table"></i> User Preview</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-sm btn-outline-success" onclick="checkAll(true)"  id="btnCheckAll"   style="display:none;">Check All</button>
                                    <button class="btn btn-sm btn-outline-danger"  onclick="checkAll(false)" id="btnUncheckAll" style="display:none;">Uncheck All</button>
                                    <span class="badge bg-secondary" id="rowCount">0 rows</span>
                                </div>
                            </div>
                            <div class="card-body p-0" style="max-height:600px;overflow-y:auto;">
                                <div id="previewLoading" class="text-center py-4" style="display:none;">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">Loading users…</p>
                                </div>
                                <div id="previewEmpty" class="text-center py-4 text-muted" style="display:none;">
                                    <i class="bi bi-inbox fs-3"></i><br>No users found.
                                </div>
                                <table class="table table-sm table-hover mb-0" id="previewTable" style="display:none;">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th><input type="checkbox" id="checkAllBox" checked onchange="checkAll(this.checked)"></th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Batch</th>
                                            <th>Role</th>
                                            <th>Message Preview</th>
                                        </tr>
                                    </thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
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
    var previewRows    = [];
    var previewUrl     = '<?php echo base_url('notifications/user_notify_preview'); ?>';

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function resolveTpl(tpl, row) {
        return tpl
            .replace(/\{name\}/g,  row.name      || '')
            .replace(/\{phone\}/g, row.phone      || '')
            .replace(/\{batch\}/g, row.batch      || '')
            .replace(/\{role\}/g,  row.role_name  || '');
    }

    function getTargetType() {
        return document.querySelector('input[name="targetTypeRadio"]:checked').value;
    }

    function onTypeChange() {
        var type = getTargetType();
        document.getElementById('batchWrap').style.display = (type === 'batch') ? '' : 'none';
        document.getElementById('roleWrap').style.display  = (type === 'role')  ? '' : 'none';
        // reset table
        document.getElementById('previewTable').style.display  = 'none';
        document.getElementById('previewEmpty').style.display  = 'none';
        document.getElementById('composeCard').style.display   = 'none';
        document.getElementById('btnCheckAll').style.display   = 'none';
        document.getElementById('btnUncheckAll').style.display = 'none';
        document.getElementById('rowCount').textContent        = '0 rows';
        previewRows = [];
    }

    function autoLoad() {
        var type = getTargetType();
        var val  = type === 'batch'
            ? document.getElementById('batchSelect').value
            : document.getElementById('roleSelect').value;
        if (!val) return;

        var sel  = type === 'batch'
            ? document.getElementById('batchSelect')
            : document.getElementById('roleSelect');
        var label = type === 'batch'
            ? 'Batch: ' + val
            : 'Role: ' + (sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : val);

        fetchUsers(type, val, label);
    }

    function fetchUsers(type, value, label) {
        document.getElementById('previewTable').style.display   = 'none';
        document.getElementById('previewEmpty').style.display   = 'none';
        document.getElementById('previewLoading').style.display = '';
        document.getElementById('composeCard').style.display    = 'none';
        document.getElementById('btnCheckAll').style.display    = 'none';
        document.getElementById('btnUncheckAll').style.display  = 'none';
        previewRows = [];

        var fd = new FormData();
        fd.append('target_type',  type);
        fd.append('target_value', value);

        fetch(previewUrl, { method: 'POST', body: fd })
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function(rows) {
            document.getElementById('previewLoading').style.display = 'none';
            if (!rows || rows.length === 0) {
                document.getElementById('previewEmpty').style.display = '';
                return;
            }
            previewRows = rows;
            renderRows(label);
        })
        .catch(function(err) {
            document.getElementById('previewLoading').style.display = 'none';
            document.getElementById('previewEmpty').style.display   = '';
            document.getElementById('previewEmpty').innerHTML = '<i class="bi bi-exclamation-circle fs-3 text-danger"></i><br>Error loading users: ' + err;
        });
    }

    function renderRows(label) {
        var tbody = document.getElementById('previewBody');
        tbody.innerHTML = '';
        previewRows.forEach(function(row, i) {
            var uid = row.user_id || '';
            var tr  = document.createElement('tr');
            tr.innerHTML =
                '<td class="text-center"><input type="checkbox" class="rowCheck" data-uid="' + esc(String(uid)) + '" checked onchange="updateSelectedCount()"></td>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + esc(row.name  || '') + '</td>' +
                '<td>' + esc(row.phone || '') + '</td>' +
                '<td>' + esc(row.batch || '') + '</td>' +
                '<td>' + esc(row.role_name || '') + '</td>' +
                '<td class="msg-preview small text-muted">—</td>';
            tbody.appendChild(tr);
        });

        document.getElementById('rowCount').textContent         = previewRows.length + ' rows';
        document.getElementById('previewTitle').innerHTML       = '<i class="bi bi-table"></i> ' + esc(label);
        document.getElementById('btnCheckAll').style.display    = '';
        document.getElementById('btnUncheckAll').style.display  = '';
        document.getElementById('previewTable').style.display   = '';

        // Show compose with default template before refreshing preview
        document.getElementById('composeCard').style.display = '';
        var titleEl = document.getElementById('notifTitle');
        var bodyEl  = document.getElementById('notifBody');
        if (!titleEl.value) titleEl.value = 'Important Message';
        if (!bodyEl.value)  bodyEl.value  = 'Hi {name}, this is an important update for you.';

        updateSelectedCount();
        refreshMessageColumn();
    }

    function updateSelectedCount() {
        var checked = document.querySelectorAll('.rowCheck:checked').length;
        document.getElementById('userCount').textContent = checked;
        var hdr = document.getElementById('checkAllBox');
        hdr.checked       = checked === previewRows.length;
        hdr.indeterminate = checked > 0 && checked < previewRows.length;
    }

    function checkAll(state) {
        document.querySelectorAll('.rowCheck').forEach(function(cb) { cb.checked = state; });
        var hdr = document.getElementById('checkAllBox');
        hdr.checked       = state;
        hdr.indeterminate = false;
        updateSelectedCount();
    }

    function refreshMessageColumn() {
        if (!previewRows.length) return;
        var titleTpl = document.getElementById('notifTitle').value;
        var bodyTpl  = document.getElementById('notifBody').value;
        document.querySelectorAll('#previewBody tr').forEach(function(tr, i) {
            var row  = previewRows[i];
            if (!row) return;
            var cell = tr.querySelector('.msg-preview');
            if (!cell) return;
            var title = esc(resolveTpl(titleTpl, row));
            var body  = esc(resolveTpl(bodyTpl,  row));
            cell.innerHTML = title
                ? '<span class="fw-semibold">' + title + '</span>' +
                  (body ? '<br><span class="text-muted">' + body + '</span>' : '')
                : '<span class="text-muted">—</span>';
        });
    }

    function prepareSubmit() {
        var selected = [];
        document.querySelectorAll('.rowCheck:checked').forEach(function(cb) {
            selected.push(cb.getAttribute('data-uid'));
        });
        if (selected.length === 0) { alert('Please select at least one user.'); return false; }
        document.getElementById('hiddenUsers').value = JSON.stringify(selected);
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['notifTitle','notifBody'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('input', refreshMessageColumn);
        });
    });
    </script>
</body>
</html>
