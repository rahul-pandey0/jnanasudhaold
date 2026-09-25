<?php
$active_menu = 'quiz_rank_notify';
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('notifications'); ?>">Notifications</a></li>
                        <li class="breadcrumb-item active">Quiz Rank Notify</li>
                    </ol>
                </nav>

                <?php if (!empty($_GET['status'])): ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_GET['status']); ?></div>
                <?php endif; ?>
                <?php if (!empty($_GET['error'])): ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <div class="row">
                    <!-- Step 1 + Step 2 -->
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-trophy"></i> Step 1 — Select Quiz &amp; Batch</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Quiz Type</label>
                                    <select class="form-select" id="quizType" onchange="onQuizTypeChange()">
                                        <option value="general">General (Physics+Chemistry+Biology)</option>
                                        <option value="jee">JEE Rank</option>
                                        <option value="subjectmath">Subject Math</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Quiz</label>
                                    <select class="form-select" id="quizSelect">
                                        <option value="">— select quiz —</option>
                                        <?php foreach ($quizzes as $id => $q): ?>
                                            <option value="<?php echo (int)$id; ?>"><?php echo htmlspecialchars($q['Quiz_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3" id="batchWrap">
                                    <label class="form-label fw-semibold">Batch</label>
                                    <select class="form-select" id="batchSelect">
                                        <option value="all">All Batches</option>
                                        <?php foreach ($batches as $b): ?>
                                            <option value="<?php echo htmlspecialchars($b); ?>"><?php echo htmlspecialchars($b); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button class="btn btn-outline-primary w-100" onclick="loadPreview()">
                                    <i class="bi bi-search"></i> Fetch &amp; Preview Results
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Compose -->
                        <div class="card" id="composeCard" style="display:none;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-bell"></i> Step 2 — Compose &amp; Send</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?php echo base_url('notifications/quiz_rank_send'); ?>" id="sendForm" onsubmit="return prepareSubmit()">
                                    <input type="hidden" name="quiz_id"        id="hiddenQuizId">
                                    <input type="hidden" name="batch"          id="hiddenBatch">
                                    <input type="hidden" name="quiz_type"      id="hiddenQuizType">
                                    <input type="hidden" name="selected_users" id="hiddenUsers">

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Title</label>
                                        <input type="text" class="form-control" id="notifTitle" name="title" required placeholder="e.g. Your Quiz Result is out!">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Body</label>
                                        <textarea class="form-control" id="notifBody" name="body" rows="3" required placeholder="Hi {name}, you ranked {rank} with {total} marks in {quiz}!"></textarea>
                                        <div class="form-text" id="placeholderHint">Placeholders: <code>{name}</code> <code>{rank}</code> <code>{physics}</code> <code>{chemistry}</code> <code>{biology}</code> <code>{total}</code> <code>{quiz}</code></div>
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
                                                    <i class="bi bi-phone-vibrate"></i> FCM Push + Inbox
                                                    <small class="text-muted d-block">Push to registered devices</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-send"></i> Send to <span id="userCount">0</span> selected users
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Preview table -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-table"></i> Rank Preview</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-sm btn-outline-success" onclick="checkAll(true)"  id="btnCheckAll"   style="display:none;">Check All</button>
                                    <button class="btn btn-sm btn-outline-danger"  onclick="checkAll(false)" id="btnUncheckAll" style="display:none;">Uncheck All</button>
                                    <span class="badge bg-secondary" id="rowCount">0 rows</span>
                                </div>
                            </div>
                            <div class="card-body p-0" style="max-height:560px;overflow-y:auto;">
                                <div id="previewLoading" class="text-center py-4" style="display:none;">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">Fetching results…</p>
                                </div>
                                <div id="previewEmpty" class="text-center py-4 text-muted" style="display:none;">
                                    <i class="bi bi-inbox fs-3"></i><br>No results found.
                                </div>
                                <table class="table table-sm table-hover mb-0" id="previewTable" style="display:none;">
                                    <thead class="table-light sticky-top" id="previewThead">
                                        <tr id="previewHeaderRow">
                                            <th><input type="checkbox" id="checkAllBox" checked onchange="checkAll(this.checked)" title="Select all"></th>
                                            <th>#</th>
                                            <th>Rank</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Batch</th>
                                            <th>Physics</th>
                                            <th>Chemistry</th>
                                            <th>Biology</th>
                                            <th>Total</th>
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
    var previewRows = [];

    var DEFAULT_BODIES = {
        general:     'Hi {name}, your rank is {rank}. Physics: {physics}, Chemistry: {chemistry}, Biology: {biology}, Total: {total} in {quiz}.',
        jee:         'Hi {name}, your JEE rank is {rank}. Physics: {physics}, Chemistry: {chemistry}, Biology: {biology}, Total: {total} in {quiz}.',
        subjectmath: 'Hi {name}, your score in {quiz} — Attempted: {attempted}, Correct: {correct}, Wrong: {wrong}, Marks: {mark}.'
    };

    function updatePlaceholderHint(type) {
        var el = document.getElementById('placeholderHint');
        if (!el) return;
        if (type === 'subjectmath') {
            el.innerHTML = 'Placeholders: <code>{name}</code> <code>{rank}</code> <code>{quiz}</code> <code>{attempted}</code> <code>{correct}</code> <code>{wrong}</code> <code>{mark}</code>';
        } else {
            el.innerHTML = 'Placeholders: <code>{name}</code> <code>{rank}</code> <code>{quiz}</code> <code>{physics}</code> <code>{chemistry}</code> <code>{biology}</code> <code>{total}</code>';
        }
    }

    function onQuizTypeChange() {
        var type = document.getElementById('quizType').value;
        document.getElementById('batchWrap').style.display = (type === 'subjectmath') ? 'none' : '';
        updatePlaceholderHint(type);
    }

    function resolveTpl(tpl, row) {
        return tpl
            .replace(/\{name\}/g,       row.name          || '')
            .replace(/\{rank\}/g,       row.rank           || '')
            .replace(/\{total\}/g,      row.total          || '')
            .replace(/\{quiz\}/g,       row.Quiz_name      || '')
            .replace(/\{physics\}/g,    row.physicsmark    || '0')
            .replace(/\{chemistry\}/g,  row.chemistrymark  || '0')
            .replace(/\{biology\}/g,    row.biologymark    || '0')
            .replace(/\{mark\}/g,       row.mark           || '0')
            .replace(/\{attempted\}/g,  row.attempted      || '0')
            .replace(/\{correct\}/g,    row.correct        || '0')
            .replace(/\{wrong\}/g,      row.wrong          || '0');
    }

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function updateSelectedCount() {
        var checked = document.querySelectorAll('.rowCheck:checked').length;
        document.getElementById('userCount').textContent = checked;

        // sync header checkbox state
        var total = previewRows.length;
        var hdr   = document.getElementById('checkAllBox');
        hdr.checked       = checked === total;
        hdr.indeterminate = checked > 0 && checked < total;
    }

    function checkAll(state) {
        document.querySelectorAll('.rowCheck').forEach(function(cb) { cb.checked = state; });
        document.getElementById('checkAllBox').checked       = state;
        document.getElementById('checkAllBox').indeterminate = false;
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

    function renderRows() {
        var quizType = document.getElementById('quizType').value;
        var isSubjectMath = (quizType === 'subjectmath');

        // Update table header
        var headerRow = document.getElementById('previewHeaderRow');
        if (isSubjectMath) {
            headerRow.innerHTML =
                '<th><input type="checkbox" id="checkAllBox" checked onchange="checkAll(this.checked)" title="Select all"></th>' +
                '<th>#</th>' +
                '<th>Rank</th>' +
                '<th>Name</th>' +
                '<th>Mobile</th>' +
                '<th>Batch</th>' +
                '<th class="text-center">Attempted</th>' +
                '<th class="text-center">Correct</th>' +
                '<th class="text-center">Wrong</th>' +
                '<th class="text-center">Marks</th>' +
                '<th>Message Preview</th>';
        } else {
            headerRow.innerHTML =
                '<th><input type="checkbox" id="checkAllBox" checked onchange="checkAll(this.checked)" title="Select all"></th>' +
                '<th>#</th>' +
                '<th>Rank</th>' +
                '<th>Name</th>' +
                '<th>Mobile</th>' +
                '<th>Batch</th>' +
                '<th class="text-center">Physics</th>' +
                '<th class="text-center">Chemistry</th>' +
                '<th class="text-center">Biology</th>' +
                '<th class="text-center">Total</th>' +
                '<th>Message Preview</th>';
        }

        var tbody = document.getElementById('previewBody');
        tbody.innerHTML = '';
        previewRows.forEach(function(row, i) {
            var uid = esc(row.user_id || '');
            var tr  = document.createElement('tr');
            var dataCols = isSubjectMath
                ? '<td class="text-center">' + esc(row.attempted || '0') + '</td>' +
                  '<td class="text-center">' + esc(row.correct   || '0') + '</td>' +
                  '<td class="text-center">' + esc(row.wrong     || '0') + '</td>' +
                  '<td class="text-center fw-semibold">' + esc(row.mark || '0') + '</td>'
                : '<td class="text-center">' + esc(row.physicsmark   || '0') + '</td>' +
                  '<td class="text-center">' + esc(row.chemistrymark || '0') + '</td>' +
                  '<td class="text-center">' + esc(row.biologymark   || '0') + '</td>' +
                  '<td class="text-center fw-semibold">' + esc(row.total || '0') + '</td>';
            tr.innerHTML =
                '<td class="text-center">' +
                    '<input type="checkbox" class="rowCheck" data-uid="' + uid + '" checked onchange="updateSelectedCount()">' +
                '</td>' +
                '<td>' + (i + 1) + '</td>' +
                '<td><strong>' + esc(row.rank || '-') + '</strong></td>' +
                '<td>' + esc(row.name || uid) + '</td>' +
                '<td>' + esc(row.phone || '') + '</td>' +
                '<td>' + esc(row.batch || '') + '</td>' +
                dataCols +
                '<td class="msg-preview text-muted small">—</td>';
            tbody.appendChild(tr);
        });
        updateSelectedCount();
        refreshMessageColumn();
    }

    function prepareSubmit() {
        var selected = [];
        document.querySelectorAll('.rowCheck:checked').forEach(function(cb) {
            selected.push(cb.getAttribute('data-uid'));
        });
        if (selected.length === 0) {
            alert('Please select at least one user.');
            return false;
        }
        document.getElementById('hiddenUsers').value = JSON.stringify(selected);
        return true;
    }

    function loadPreview() {
        var quizId = document.getElementById('quizSelect').value;
        var batch  = document.getElementById('batchSelect').value;

        if (!quizId) { alert('Please select a quiz first.'); return; }

        document.getElementById('previewTable').style.display   = 'none';
        document.getElementById('previewEmpty').style.display   = 'none';
        document.getElementById('previewLoading').style.display = '';
        document.getElementById('composeCard').style.display    = 'none';
        document.getElementById('btnCheckAll').style.display    = 'none';
        document.getElementById('btnUncheckAll').style.display  = 'none';
        previewRows = [];

        var quizType = document.getElementById('quizType').value;

        var fd = new FormData();
        fd.append('quiz_id',   quizId);
        fd.append('batch',     batch);
        fd.append('quiz_type', quizType);

        fetch('<?php echo base_url('notifications/quiz_rank_preview'); ?>', {
            method: 'POST', body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(rows) {
            document.getElementById('previewLoading').style.display = 'none';

            if (!rows || rows.length === 0) {
                document.getElementById('previewEmpty').style.display = '';
                return;
            }

            previewRows = rows;

            // Always set title default if empty; always reset body to type-appropriate default
            var titleEl = document.getElementById('notifTitle');
            var bodyEl  = document.getElementById('notifBody');
            if (!titleEl.value) titleEl.value = 'Your Quiz Result is Out!';
            bodyEl.value = DEFAULT_BODIES[quizType] || DEFAULT_BODIES.general;

            updatePlaceholderHint(quizType);

            document.getElementById('hiddenQuizId').value        = quizId;
            document.getElementById('hiddenBatch').value         = batch;
            document.getElementById('hiddenQuizType').value      = quizType;
            document.getElementById('composeCard').style.display = '';

            renderRows();  // called after compose card visible so fields have values

            document.getElementById('previewTable').style.display   = '';
            document.getElementById('rowCount').textContent          = rows.length + ' rows';
            document.getElementById('btnCheckAll').style.display     = '';
            document.getElementById('btnUncheckAll').style.display   = '';
        })
        .catch(function(err) {
            document.getElementById('previewLoading').style.display = 'none';
            alert('Error fetching results: ' + err);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['notifTitle', 'notifBody'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('input', refreshMessageColumn);
        });
    });
    </script>
</body>
</html>
