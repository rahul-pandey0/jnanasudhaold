<?php
$active_menu = 'questions';
$title = isset($title) ? $title : 'Quiz Questions';
$questions = isset($questions) ? $questions : array();
$categories = isset($categories) ? $categories : array();
$selected_category = isset($selected_category) ? $selected_category : '';
$total = isset($total) ? (int)$total : 0;
$limit = isset($limit) ? (int)$limit : 25;
$page = isset($current_page) ? (int)$current_page : 1;
$pages = max(1, (int)ceil($total / max(1, $limit)));

require_once APPPATH . 'views/layout/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb mb-2">
    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
    <li class="breadcrumb-item active"><?php echo htmlspecialchars($title); ?></li>
  </ol>
</nav>
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
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-question-circle"></i> Quiz Questions</h5>
    <a href="<?php echo base_url('questions/create'); ?>" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Create Question</a>
    <form method="GET" action="<?php echo base_url('questions'); ?>" class="d-flex align-items-center" style="gap:0.5rem;">
      <select name="category" class="form-select form-select-sm" style="min-width: 16rem;">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($selected_category === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
        <?php endforeach; ?>
      </select>
      <select name="limit" class="form-select form-select-sm" style="max-width:7rem;">
        <?php foreach ([10,25,50,100] as $l): ?>
          <option value="<?php echo $l; ?>" <?php echo ($limit === $l) ? 'selected' : ''; ?>>Show <?php echo $l; ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
      <?php if (!empty($selected_category)): ?>
        <a href="<?php echo base_url('questions'); ?>" class="btn btn-secondary btn-sm">Clear</a>
      <?php endif; ?>
    </form>
  </div>
  <div class="card-body">
    <style>
      .qq-table-wrap { overflow-x:auto; }
      .qq-table { min-width: 1400px; }
      .qq-table th, .qq-table td { white-space:nowrap; vertical-align:middle; }
      .qq-table tbody td { padding:0.45rem 0.6rem; }
      .col-id{width:70px;} .col-name{width:350px;} .col-cat{width:200px;} .col-mark{width:70px;} .col-level{width:70px;} .col-type{width:90px;} .col-actions{width:200px;}
    </style>
    <div class="qq-table-wrap">
      <table class="table table-hover qq-table">
        <thead>
          <tr>
            <th class="col-id">ID</th>
            <th class="col-name">Question Name</th>
            <th class="col-cat">Category</th>
            <th class="col-mark">Mark</th>
            <th class="col-level">Level</th>
            <th class="col-type">Type</th>
            <th class="col-actions">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($selected_category === ''): ?>
            <tr><td colspan="7" class="text-muted">Select a category and click Search to view questions.</td></tr>
          <?php elseif (!empty($questions)): ?>
            <?php foreach ($questions as $q): ?>
              <tr>
                <td><?php echo (int)$q['id']; ?></td>
                <td class="qname-cell"><?php echo $q['question_name']; ?></td>
                <td><?php echo htmlspecialchars($q['category']); ?></td>
                <td><?php echo htmlspecialchars($q['mark']); ?></td>
                <td><?php echo htmlspecialchars($q['level']); ?></td>
                <td><?php echo htmlspecialchars($q['qtype']); ?></td>
                <td class="col-actions">
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="openEditor(<?php echo (int)$q['id']; ?>, this)" title="Quick inline edit"><i class="bi bi-pencil"></i> Quick</button>
                    <a href="<?php echo base_url('questions/edit_quiz/' . (int)$q['id']); ?>" class="btn btn-outline-info" title="Full page edit"><i class="bi bi-pencil-square"></i> Full</a>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteQuestion(<?php echo (int)$q['id']; ?>)" title="Delete question"><i class="bi bi-trash"></i></button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-muted">No questions found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if ($selected_category !== '' && $pages > 1): ?>
      <nav aria-label="Questions pagination" class="mt-2">
        <ul class="pagination pagination-sm mb-0">
          <?php
            $base = base_url('questions');
            $qs = $_GET;
            $qs['limit'] = $limit;
            for ($p = 1; $p <= $pages; $p++) {
              $qs['page'] = $p;
              $url = $base . '?' . http_build_query($qs);
              $active = ($p === $page) ? 'active' : '';
              echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . htmlspecialchars($url) . '">' . $p . '</a></li>';
            }
          ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</div>
<!-- Inline Editor Modal -->
<div class="modal fade" id="qqEditorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Question <span id="qqEditorId"></span></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <style>
          #qqHtmlEditor img.img-selected { outline: 2px solid #ffc107; outline-offset: 2px; }
          /* Improve MathLive caret visibility and field readability */
          #mathfield { background:#ffffff; line-height: normal !important; font-size: 1.25rem; padding: .5rem .75rem; border: 1px solid #ced4da; border-radius: .375rem; }
          #mathfield .ML__caret, #mathfield .ML__virtual-cursor { border-left: 2px solid #111 !important; }
          #mathfield .ML__selection { background: rgba(102,126,234,0.15) !important; }
          #mathfield .ML__mathlive, #mathfield .ML__fieldcontainer, #mathfield .ML__placeholder { line-height: normal !important; }
          /* Prevent glyph overlap in MathJax preview */
          #qqRender { font-size: 1.125rem; }
          #qqRender mjx-container[jax="CHTML"] { line-height: 1.25; }
          #mfPreview { font-size: 1.125rem; }
          #mfPreview mjx-container[jax="CHTML"] { line-height: 1.25; }
          /* Make editor content easier to read */
          #qqHtmlEditor { font-size: 1rem; line-height: 1.5; }
        </style>
        <div class="mb-2">
          <label class="form-label">Question HTML (editable)</label>
          <div id="qqHtmlEditor" class="form-control" contenteditable="true" style="min-height:200px; overflow:auto;"></div>
          <small class="text-muted">Math formulas will render via MathJax; you can insert new formulas and replace all images.</small>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
          <button type="button" class="btn btn-outline-primary btn-sm" onclick="showFormulaInserter()"><i class="bi bi-plus-square"></i> Insert Formula</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleKeyboard()"><i class="bi bi-keyboard"></i> Keyboard</button>
          <small class="text-muted">Tip: Click an existing formula to edit it.</small>
          <div id="mathfield" class="form-control d-none" style="min-height:100px; font-size:1.1rem;"></div>
          <!-- Fallback textarea-based editor (used when MathLive cannot load) -->
          <div id="mfFallback" class="d-none w-100">
            <label class="form-label mt-2">Formula (MathML)</label>
            <textarea id="mfFallbackText" class="form-control" style="min-height:100px; font-family:Consolas,'Courier New',monospace; font-size:0.95rem;"></textarea>
          </div>
          <div id="mfToolbar" class="d-none" style="width:100%;">
            <div class="d-flex flex-wrap gap-1 mt-1">
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('frac')">a/b</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('sup')">x^y</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('sub')">x_y</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('sqrt')">√</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('nthroot')">n√</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('int')">∫</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('sum')">∑</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('prod')">∏</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('limit')">lim</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('matrix2')">[2x2]</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('alpha')">α</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('beta')">β</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('gamma')">γ</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('vec')">→</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('abs')">|x|</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('paren')">( )</button>
              <button type="button" class="btn btn-light btn-sm" onclick="mfInsert('brack')">[ ]</button>
            </div>
            <div class="mt-2 border rounded p-2 bg-white"><small class="text-muted">Formula Preview <span style="font-size:0.8rem; color:#666;">(click to edit)</span></small>
              <div id="mfPreview" style="min-height:40px; cursor:pointer;" onclick="editPreviewFormula()"></div>
            </div>
          </div>
          <button type="button" id="insertFormulaBtn" class="btn btn-primary btn-sm d-none" onclick="insertFormulaIntoEditor()"><i class="bi bi-check2"></i> Insert</button>
          <button type="button" id="applyEditBtn" class="btn btn-success btn-sm d-none" onclick="applyFormulaEdit()"><i class="bi bi-check2-circle"></i> Apply Edit</button>
          <div class="ms-auto d-flex align-items-center gap-2">
            <input type="file" id="qqImageInput" accept="image/*" class="form-control form-control-sm" style="max-width:220px;">
            <button type="button" class="btn btn-outline-info btn-sm" onclick="replaceSelectedImage()" title="Replace only the selected image"><i class="bi bi-image"></i> Replace Selected</button>
            <button type="button" class="btn btn-warning btn-sm" onclick="replaceAllImages()"><i class="bi bi-image"></i> Replace All Images</button>
          </div>
        </div>
        <div class="border rounded p-2 bg-light">
          <strong>Live Render:</strong>
          <div id="qqRender" style="min-height:120px;"></div>
        </div>
      </div>
      <div class="modal-footer">
        <form id="qqSaveForm" method="POST" action="<?php echo base_url('questions/update_quiz_html'); ?>" class="d-flex align-items-center gap-2">
          <input type="hidden" name="id" id="qqEditorHiddenId">
          <input type="hidden" name="html" id="qqEditorHiddenHtml">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="prepareSave()"><i class="bi bi-save"></i> Save</button>
        </form>
      </div>
    </div>
  </div>
 </div>
<script>
  const baseUrl = '<?php echo base_url(); ?>';
  let qqModal, mf, selectedMathNode = null, selectedImageNode = null;
  let editorListenersAttached = false;
  let savedRange = null; // to restore caret position in editor when inserting
  let useFallback = false; // when MathLive cannot be loaded
  function getMfMathML(){
    if (!mf) return '';
    // Prefer standard 'math-ml' key, fallback to 'mathml' if needed
    try { return mf.getValue('math-ml'); } catch(e) {}
    try { return mf.getValue('mathml'); } catch(e) {}
    // Fallback to LaTeX wrapped plainly if MathML not available
    try { return mf.getValue('latex'); } catch(e) {}
    return '';
  }
  function ensureMathLive(){
    return new Promise(function(resolve, reject){
      if (window.MathLive) { resolve(); return; }
      // Ensure core CSS
      if (!document.getElementById('mathlive-core-css')) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/mathlive/dist/mathlive.core.css';
        link.id = 'mathlive-core-css';
        document.head.appendChild(link);
      }
      const urls = [
        'https://cdn.jsdelivr.net/npm/mathlive/dist/mathlive.min.js',
        'https://unpkg.com/mathlive/dist/mathlive.min.js'
      ];
      let i = 0;
      const tryLoad = () => {
        if (window.MathLive) { resolve(); return; }
        if (i >= urls.length) { reject(new Error('MathLive failed to load')); return; }
        let s = document.getElementById('mathlive-lib');
        if (s) { s.remove(); }
        s = document.createElement('script');
        s.src = urls[i++];
        s.id = 'mathlive-lib';
        s.onload = () => resolve();
        s.onerror = () => tryLoad();
        document.head.appendChild(s);
      };
      tryLoad();
    });
  }
  function openEditor(id, btn){
    const row = btn.closest('tr');
    const cell = row.querySelector('.qname-cell');
    document.getElementById('qqEditorId').textContent = '#' + id;
    document.getElementById('qqEditorHiddenId').value = id;
    const editor = document.getElementById('qqHtmlEditor');
    editor.innerHTML = cell ? cell.innerHTML : '';
    // place caret at end by default and save initial selection
    placeCaretAtEnd(editor);
    saveSelection();
    // attach handlers once
    if (!editorListenersAttached) {
      editor.addEventListener('click', function(ev){
        const img = ev.target.closest && ev.target.closest('img');
        if (img) {
          if (selectedImageNode && selectedImageNode !== img) {
            selectedImageNode.classList.remove('img-selected');
          }
          selectedImageNode = img;
          img.classList.add('img-selected');
          ev.preventDefault();
          ev.stopPropagation();
          return;
        }
        if (selectedImageNode) { selectedImageNode.classList.remove('img-selected'); selectedImageNode = null; }
        const m = ev.target.closest && ev.target.closest('math');
        if (m) { editExistingFormula(m); }
      });
      // Track and save selection/caret within editor for correct formula insert
      const capture = () => saveSelection();
      editor.addEventListener('mouseup', capture);
      editor.addEventListener('keyup', capture);
      editor.addEventListener('keydown', capture);
      editor.addEventListener('input', capture);
      editor.addEventListener('blur', capture);
      editor.addEventListener('click', capture);
      // Track global selection changes, but only persist if inside editor
      document.addEventListener('selectionchange', function(){
        const sel = window.getSelection();
        if (!sel || sel.rangeCount === 0) return;
        let node = sel.anchorNode;
        while (node && node !== editor) { node = node.parentNode; }
        if (node === editor) saveSelection();
      });
      const debounced = debounce(updateRenderFromEditor, 250);
      editor.addEventListener('input', debounced);
      editor.addEventListener('keyup', debounced);
      editorListenersAttached = true;
    }
    // Render preview
    ensureMathMLNamespaceFor(editor);
    const render = document.getElementById('qqRender');
    render.innerHTML = editor.innerHTML;
    ensureMathMLNamespaceFor(render);
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([render]).catch(err => console.error(err));
    }
    const modalEl = document.getElementById('qqEditorModal');
    qqModal = new bootstrap.Modal(modalEl);
    qqModal.show();
  }
  function showFormulaInserter(){
    const field = document.getElementById('mathfield');
    field.classList.remove('d-none');
    document.getElementById('mfToolbar').classList.remove('d-none');
    document.getElementById('insertFormulaBtn').classList.remove('d-none');
    document.getElementById('applyEditBtn').classList.add('d-none');
    ensureMathLive().then(() => {
      if (!mf) initMathfield();
      // focus the mathfield so caret is visible
      setTimeout(() => { try { mf.focus(); } catch(e){} }, 0);
    }).catch(() => {
      // Switch to fallback editor
      useFallback = true;
      activateFallbackEditor();
    });
  }
  function insertFormulaIntoEditor(){
    const editor = document.getElementById('qqHtmlEditor');
    let mathml = getMfMathML();
    mathml = ensureNamespace(mathml);
    // Insert at saved caret position inside the editor
    editor.focus();
    if (!restoreSelection()) {
      placeCaretAtEnd(editor);
    }
    insertHtmlAtCursor(mathml);
    // Update render
    const render = document.getElementById('qqRender');
    render.innerHTML = editor.innerHTML;
    ensureMathMLNamespaceFor(render);
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([render]).catch(err => console.error(err));
    }
    // Save the new caret location after inserted content
    saveSelection();
  }
  function editExistingFormula(node){
    const field = document.getElementById('mathfield');
    document.getElementById('mfToolbar').classList.remove('d-none');
    ensureMathLive().then(() => {
      field.classList.remove('d-none');
      if (!mf) initMathfield();
      selectedMathNode = node;
      try { mf.setValue(node.outerHTML, {format:'math-ml'}); }
      catch(e1) { try { mf.setValue(node.outerHTML, {format:'mathml'}); } catch(e2) { console.warn(e2); } }
      document.getElementById('insertFormulaBtn').classList.add('d-none');
      document.getElementById('applyEditBtn').classList.remove('d-none');
      setTimeout(() => { try { mf.focus(); } catch(e){} }, 0);
    }).catch(() => {
      useFallback = true;
      activateFallbackEditor();
      selectedMathNode = node;
      const ta = document.getElementById('mfFallbackText');
      ta.value = node.outerHTML;
      updateFallbackPreview();
      document.getElementById('insertFormulaBtn').classList.add('d-none');
      document.getElementById('applyEditBtn').classList.remove('d-none');
    });
  }
  function applyFormulaEdit(){
    if (!selectedMathNode) return;
    let mathml = getMfMathML();
    mathml = ensureNamespace(mathml);
    // Replace the selected node
    selectedMathNode.outerHTML = mathml;
    selectedMathNode = null;
    // Refresh render
    const editor = document.getElementById('qqHtmlEditor');
    const render = document.getElementById('qqRender');
    render.innerHTML = editor.innerHTML;
    ensureMathMLNamespaceFor(render);
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([render]).catch(err => console.error(err));
    }
    // Reset buttons
    document.getElementById('applyEditBtn').classList.add('d-none');
    document.getElementById('insertFormulaBtn').classList.remove('d-none');
  }
  function toggleKeyboard(){
    ensureMathLive().then(() => {
      if (!mf) initMathfield();
      try { mf.executeCommand('toggleVirtualKeyboard'); } catch(e) { console.warn(e); }
    }).catch(() => {
      useFallback = true;
      activateFallbackEditor();
    });
  }
  function initMathfield(){
    mf = MathLive.makeMathField('mathfield', {
      smartFence: true,
      smartSuperscript: true,
      smartMode: true,
      virtualKeyboardMode: 'onfocus',
      virtualKeyboardTheme: 'material',
      virtualKeyboardLayout: 'advanced',
      formats: ['latex','math-ml'],
      onContentDidChange: () => {
        // live formula preview
        const prev = document.getElementById('mfPreview');
        if (!prev) return;
        let mathml = ensureNamespace(getMfMathML());
        prev.innerHTML = mathml;
        if (window.MathJax && MathJax.typesetPromise) {
          MathJax.typesetPromise([prev]).catch(err => console.error(err));
        }
      }
    });
  }
  function mfInsert(kind){
    if (useFallback) { mfInsertTemplate(kind); return; }
    if (!window.MathLive) {
      ensureMathLive().then(() => mfInsert(kind)).catch(() => { useFallback = true; activateFallbackEditor(); mfInsertTemplate(kind); });
      return;
    }
    if (!mf) initMathfield();
    try { mf.focus(); } catch(e){}
    const map = {
      frac: '\\frac{}{}',
      sup: '^{ }',
      sub: '_{ }',
      sqrt: '\\sqrt{}',
      nthroot: '\\sqrt[n]{}',
      int: '\\int',
      sum: '\\sum',
      prod: '\\prod',
      limit: '\\lim_{x\\to 0}',
      matrix2: '\\begin{matrix}a & b\\\\ c & d\\end{matrix}',
      alpha: '\\alpha',
      beta: '\\beta',
      gamma: '\\gamma',
      vec: '\\vec{v}',
      abs: '\\left| x \\right|',
      paren: '\\left(  \\right)',
      brack: '\\left[  \\right]'
    };
    const latex = map[kind] || '';
    if (latex) mf.insert(latex);
  }
  function activateFallbackEditor(){
    const field = document.getElementById('mathfield');
    const fb = document.getElementById('mfFallback');
    const ta = document.getElementById('mfFallbackText');
    field.classList.add('d-none');
    fb.classList.remove('d-none');
    document.getElementById('mfToolbar').classList.remove('d-none');
    document.getElementById('insertFormulaBtn').classList.remove('d-none');
    // Bind live preview and cursor fixes once
    if (!ta.dataset.bound) {
      ta.addEventListener('input', updateFallbackPreview);
      // Restore cursor position after input (fixes delete/backspace issues)
      ta.addEventListener('keydown', fixCursorAfterKey);
      ta.addEventListener('keyup', fixCursorAfterKey);
      ta.dataset.bound = '1';
    }
    if (!ta.value.trim()) {
      ta.value = '<math xmlns="http://www.w3.org/1998/Math/MathML"><mi>x</mi></math>';
      updateFallbackPreview();
    }
    setTimeout(() => ta.focus(), 0);
  }
  function fixCursorAfterKey(e){
    const ta = e.target;
    // Ensure cursor visibility by resetting selection range
    const pos = ta.selectionStart;
    setTimeout(() => {
      ta.setSelectionRange(pos, pos);
    }, 0);
  }
  function updateFallbackPreview(){
    const ta = document.getElementById('mfFallbackText');
    const prev = document.getElementById('mfPreview');
    let mathml = ensureNamespace(ta.value);
    prev.innerHTML = mathml;
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([prev]).catch(err => console.error(err));
    }
  }
  function getMfMathML(){
    if (useFallback) {
      const ta = document.getElementById('mfFallbackText');
      return ta ? ta.value : '';
    }
    if (!mf) return '';
    try { return mf.getValue('math-ml'); } catch(e) {}
    try { return mf.getValue('mathml'); } catch(e) {}
    try { return mf.getValue('latex'); } catch(e) {}
    return '';
  }
  function mfInsertTemplate(kind){
    const snippets = {
      frac: '<math><mfrac><mi>a</mi><mi>b</mi></mfrac></math>',
      sup: '<math><msup><mi>x</mi><mi>y</mi></msup></math>',
      sub: '<math><msub><mi>x</mi><mi>y</mi></msub></math>',
      sqrt: '<math><msqrt><mi>x</mi></msqrt></math>',
      nthroot: '<math><mroot><mi>x</mi><mi>n</mi></mroot></math>',
      int: '<math><mo>∫</mo><mi>f</mi><mi>dx</mi></math>',
      sum: '<math><mo>∑</mo></math>',
      prod: '<math><mo>∏</mo></math>',
      limit: '<math><mrow><mi>lim</mi><mrow><mi>x</mi><mo>→</mo><mn>0</mn></mrow></mrow></math>',
      matrix2: '<math><mfenced><mtable><mtr><mtd><mi>a</mi></mtd><mtd><mi>b</mi></mtd></mtr><mtr><mtd><mi>c</mi></mtd><mtd><mi>d</mi></mtd></mtr></mtable></mfenced></math>',
      alpha: '<math><mi>α</mi></math>',
      beta: '<math><mi>β</mi></math>',
      gamma: '<math><mi>γ</mi></math>',
      vec: '<math><mover><mi>v</mi><mo>→</mo></mover></math>',
      abs: '<math><mrow><mo>|</mo><mi>x</mi><mo>|</mo></mrow></math>',
      paren: '<math><mrow><mo>(</mo><mi>x</mi><mo>)</mo></mrow></math>',
      brack: '<math><mrow><mo>[</mo><mi>x</mi><mo>]</mo></mrow></math>'
    };
    const ta = document.getElementById('mfFallbackText');
    const el = document.createElement('div');
    el.innerHTML = ensureNamespace(snippets[kind] || '<math><mi>x</mi></math>');
    insertTextAtTextareaCursor(ta, el.innerHTML);
    updateFallbackPreview();
  }
  function insertTextAtTextareaCursor(textarea, text){
    if (!textarea) return;
    const start = textarea.selectionStart || 0;
    const end = textarea.selectionEnd || 0;
    const before = textarea.value.substring(0, start);
    const after = textarea.value.substring(end);
    textarea.value = before + text + after;
    const pos = start + text.length;
    textarea.focus();
    // Double-ensure cursor position is set and preserved
    setTimeout(() => {
      textarea.setSelectionRange(pos, pos);
    }, 0);
  }
  function replaceAllImages(){
    const fileInput = document.getElementById('qqImageInput');
    if (!fileInput.files || !fileInput.files[0]) { alert('Choose an image file'); return; }
    const file = fileInput.files[0];
    const reader = new FileReader();
    reader.onload = function(e){
      const dataUrl = e.target.result;
      const editor = document.getElementById('qqHtmlEditor');
      const imgs = editor.querySelectorAll('img');
      imgs.forEach(img => { img.setAttribute('src', dataUrl); });
      // Update render
      const render = document.getElementById('qqRender');
      render.innerHTML = editor.innerHTML;
      ensureMathMLNamespaceFor(render);
      if (window.MathJax && MathJax.typesetPromise) {
        MathJax.typesetPromise([render]).catch(err => console.error(err));
      }
    };
    reader.readAsDataURL(file);
  }
  function replaceSelectedImage(){
    if (!selectedImageNode) { alert('Click an image inside the editor to select it first.'); return; }
    const fileInput = document.getElementById('qqImageInput');
    if (!fileInput.files || !fileInput.files[0]) { alert('Choose an image file'); return; }
    const file = fileInput.files[0];
    const reader = new FileReader();
    reader.onload = function(e){
      const dataUrl = e.target.result;
      selectedImageNode.setAttribute('src', dataUrl);
      const editor = document.getElementById('qqHtmlEditor');
      const render = document.getElementById('qqRender');
      render.innerHTML = editor.innerHTML;
      ensureMathMLNamespaceFor(render);
      if (window.MathJax && MathJax.typesetPromise) {
        MathJax.typesetPromise([render]).catch(err => console.error(err));
      }
    };
    reader.readAsDataURL(file);
  }
  function prepareSave(){
    const editor = document.getElementById('qqHtmlEditor');
    ensureMathMLNamespaceFor(editor);
    document.getElementById('qqEditorHiddenHtml').value = editor.innerHTML;
  }
  function ensureNamespace(src){
    if (!src) return '';
    if (src.indexOf('<math') !== -1 && src.indexOf('xmlns=') === -1) {
      return src.replace('<math', '<math xmlns="http://www.w3.org/1998/Math/MathML"');
    }
    return src;
  }
  function ensureMathMLNamespaceFor(container){
    if (!container) return;
    const nodes = container.querySelectorAll('math');
    nodes.forEach(function(el){
      if (!el.getAttribute('xmlns')) {
        el.setAttribute('xmlns','http://www.w3.org/1998/Math/MathML');
      }
    });
  }
  function updateRenderFromEditor(){
    const editor = document.getElementById('qqHtmlEditor');
    const render = document.getElementById('qqRender');
    if (!editor || !render) return;
    ensureMathMLNamespaceFor(editor);
    render.innerHTML = editor.innerHTML;
    ensureMathMLNamespaceFor(render);
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([render]).catch(err => console.error(err));
    }
  }
  function insertHtmlAtCursor(html){
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) {
      document.getElementById('qqHtmlEditor').insertAdjacentHTML('beforeend', html);
      return;
    }
    const range = sel.getRangeAt(0);
    range.deleteContents();
    const frag = range.createContextualFragment(html);
    const lastNode = frag.lastChild;
    range.insertNode(frag);
    // move caret after inserted content
    if (lastNode) {
      const newRange = document.createRange();
      newRange.setStartAfter(lastNode);
      newRange.collapse(true);
      sel.removeAllRanges();
      sel.addRange(newRange);
      // Force caret visibility by immediately saving the selection
      setTimeout(() => saveSelection(), 0);
    }
  }
  function editPreviewFormula(){
    const preview = document.getElementById('mfPreview');
    if (!preview || !preview.innerHTML.trim()) return;
    // Extract the math element from preview
    const mathEl = preview.querySelector('math');
    if (!mathEl) return;
    editExistingFormula(mathEl.cloneNode(true));
  }
  function saveSelection(){
    const editor = document.getElementById('qqHtmlEditor');
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;
    // Ensure selection is inside editor
    let container = sel.anchorNode;
    while (container && container !== editor) { container = container.parentNode; }
    if (!container) return;
    savedRange = sel.getRangeAt(0).cloneRange();
  }
  function restoreSelection(){
    if (!savedRange) return false;
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(savedRange);
    return true;
  }
  function placeCaretAtEnd(el){
    if (!el) return;
    el.focus();
    const range = document.createRange();
    range.selectNodeContents(el);
    range.collapse(false);
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
    // Save this position immediately
    savedRange = range.cloneRange();
  }
  function debounce(fn, wait){
    let t; return function(){ clearTimeout(t); t = setTimeout(() => fn.apply(this, arguments), wait); };
  }
</script>
<link rel="stylesheet" href="https://unpkg.com/mathlive/dist/mathlive.core.css">
<script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>
<script>
  // Configure MathJax for MathML input & CHTML output
  window.MathJax = {
    loader: { load: ['input/mml','output/chtml'] },
    chtml: { scale: 1.25 },
    startup: {
      ready: () => {
        MathJax.startup.defaultReady();
        ensureMathMLNamespace();
        if (window.MathJax && MathJax.typesetPromise) {
          MathJax.typesetPromise().catch(err => console.error('MathJax typeset error', err));
        }
      }
    }
  };
  function deleteQuestion(id){
    if (!confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
      return;
    }
    // Create a form to submit delete request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo base_url('questions/delete'); ?>';
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'id';
    idInput.value = id;
    form.appendChild(idInput);
    
    document.body.appendChild(form);
    form.submit();
  }
  function ensureMathMLNamespace(){
    var nodes = document.querySelectorAll('math');
    nodes.forEach(function(el){
      if (!el.getAttribute('xmlns')) {
        el.setAttribute('xmlns','http://www.w3.org/1998/Math/MathML');
      }
    });
  }
  document.addEventListener('DOMContentLoaded', function(){
    // In case MathJax loads later, ensure namespace first
    ensureMathMLNamespace();
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/mml-chtml.js"></script>
<?php require_once APPPATH . 'views/layout/footer.php'; ?>
