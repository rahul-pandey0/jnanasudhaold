<?php
$mode = isset($mode) ? $mode : 'create';
$question = isset($question) ? $question : null;
$title = isset($title) ? $title : 'Create/Edit Question';

require_once APPPATH . 'views/layout/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb mb-2">
    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo base_url('questions'); ?>">Questions</a></li>
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
  <div class="card-header">
    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> <?php echo htmlspecialchars($title); ?></h5>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo ($mode === 'create') ? base_url('questions/create') : base_url('questions/edit_quiz/' . (int)$question['id']); ?>" id="qqForm">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="qqCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="qqCategory" name="category" value="<?php echo ($question && isset($question['category'])) ? htmlspecialchars($question['category']) : ''; ?>" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="qqType" class="form-label">Question Type</label>
            <select class="form-select" id="qqType" name="qtype">
              <?php foreach(['MCQ','Short Answer','Essay','Match the Following','True/False'] as $t): ?>
                <option value="<?php echo htmlspecialchars($t); ?>" <?php echo ($question && isset($question['qtype']) && $question['qtype'] === $t) ? 'selected' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="mb-3">
            <label for="qqMark" class="form-label">Mark</label>
            <input type="number" class="form-control" id="qqMark" name="mark" min="0" step="0.5" value="<?php echo ($question && isset($question['mark'])) ? (float)$question['mark'] : '1'; ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="mb-3">
            <label for="qqPenalty" class="form-label">Penalty</label>
            <input type="number" class="form-control" id="qqPenalty" name="penalty" min="0" step="0.5" value="<?php echo ($question && isset($question['penalty'])) ? (float)$question['penalty'] : '0'; ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="mb-3">
            <label for="qqLevel" class="form-label">Level</label>
            <select class="form-select" id="qqLevel" name="level">
              <?php foreach([1,2,3,4,5] as $lv): ?>
                <option value="<?php echo $lv; ?>" <?php echo ($question && isset($question['level']) && (int)$question['level'] === $lv) ? 'selected' : ''; ?>><?php echo $lv; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <style>
        #qqHtmlEditor { min-height: 200px; overflow: auto; line-height: 1.5; font-size: 1rem; white-space: pre-wrap; word-wrap: break-word; }
        #qqHtmlEditor img.img-selected { outline: 2px solid #ffc107; outline-offset: 2px; }
        #mfFallback { width: 100%; }
        #mfFallbackText { font-family: Consolas, 'Courier New', monospace; font-size: 0.95rem; min-height: 100px; }
        #qqRender { font-size: 1.125rem; min-height: 100px; padding: 1rem; border: 1px solid #e9ecef; border-radius: 0.375rem; background: #fafbfc; }
        #qqRender mjx-container[jax="CHTML"] { line-height: 1.25; }
        #mfPreview { font-size: 1.125rem; min-height: 60px; padding: 0.75rem; border: 1px solid #dee2e6; background: #ffffff; margin-top: 0.5rem; }
        #mfPreview mjx-container[jax="CHTML"] { line-height: 1.25; }
      </style>
      <div class="mb-3">
        <label class="form-label">Question <span class="text-danger">*</span></label>
        <div id="qqHtmlEditor" class="form-control" contenteditable="true" style="min-height:200px; overflow:auto;"></div>
        <small class="text-muted">Math formulas render via MathJax; use toolbar to insert formulas, or click existing formulas to edit.</small>
      </div>
      <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="showFormulaInserter()"><i class="bi bi-plus-square"></i> Insert Formula</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleKeyboard()"><i class="bi bi-keyboard"></i> Keyboard</button>
        <small class="text-muted">Tip: Click an existing formula to edit it.</small>
        <div id="mathfield" class="form-control d-none" style="min-height:100px; font-size:1.1rem;"></div>
        <!-- Fallback textarea-based editor -->
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
          <div class="mt-2 border rounded p-2 bg-white"><small class="text-muted">Formula Preview</small>
            <div id="mfPreview" style="min-height:40px;"></div>
          </div>
        </div>
        <button type="button" id="insertFormulaBtn" class="btn btn-primary btn-sm d-none" onclick="insertFormulaIntoEditor()"><i class="bi bi-check2"></i> Insert</button>
        <button type="button" id="applyEditBtn" class="btn btn-success btn-sm d-none" onclick="applyFormulaEdit()"><i class="bi bi-check2-circle"></i> Apply Edit</button>
        <button type="button" class="btn btn-warning btn-sm d-none" id="clearFormulaBtn" onclick="clearFormula()"><i class="bi bi-trash"></i> Clear Formula</button>
        <div class="ms-auto d-flex align-items-center gap-2">
          <input type="file" id="qqImageInput" accept="image/*" class="form-control form-control-sm" style="max-width:220px;">
          <button type="button" class="btn btn-outline-info btn-sm" onclick="replaceSelectedImage()" title="Replace only the selected image"><i class="bi bi-image"></i> Replace Selected</button>
          <button type="button" class="btn btn-warning btn-sm" onclick="replaceAllImages()"><i class="bi bi-image"></i> Replace All Images</button>
        </div>
      </div>
      <div class="border rounded p-2 bg-light mb-3">
        <strong>Live Render:</strong>
        <div id="qqRender" style="min-height:120px;"></div>
      </div>
      <input type="hidden" name="html" id="qqEditorHiddenHtml">
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Question</button>
        <a href="<?php echo base_url('questions'); ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>
<script>
  const baseUrl = '<?php echo base_url(); ?>';
  let mf, selectedMathNode = null, selectedImageNode = null;
  let editorListenersAttached = false;
  let savedRange = null;
  let useFallback = false;

  // Load the shared math editor functions from the main list
  function getMfMathML(){
    if (useFallback) {
      const ta = document.getElementById('mfFallbackText');
      return ta ? ta.value : '';
    }
    if (!mf) return '';
    try { 
      let ml = mf.getValue('math-ml'); 
      return ml && ml.trim() ? ml : '';
    } catch(e) {}
    try { 
      let ml = mf.getValue('mathml'); 
      return ml && ml.trim() ? ml : '';
    } catch(e) {}
    try { 
      // Get LaTeX and convert to MathML
      let latex = mf.getValue('latex'); 
      if (latex && latex.trim()) {
        return '<math xmlns="http://www.w3.org/1998/Math/MathML"><mi>' + htmlEscape(latex) + '</mi></math>';
      }
    } catch(e) {}
    return '';
  }
  function htmlEscape(text) {
    const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
    return text.replace(/[&<>"']/g, m => map[m]);
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
  function ensureMathLive(){
    return new Promise(function(resolve, reject){
      if (window.MathLive) { resolve(); return; }
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
  // Initialize editor on load
  window.addEventListener('DOMContentLoaded', function(){
    const editor = document.getElementById('qqHtmlEditor');
    const q = <?php echo json_encode($question); ?>;
    if (q && q.question_name) {
      editor.innerHTML = q.question_name;
    }
    ensureMathMLNamespaceFor(editor);
    placeCaretAtEnd(editor);
    saveSelection();
    attachEditorListeners();
    updateRenderFromEditor();
    ensureMathMLNamespace();
  });
  function attachEditorListeners(){
    const editor = document.getElementById('qqHtmlEditor');
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
    const capture = () => saveSelection();
    editor.addEventListener('mouseup', capture);
    editor.addEventListener('keyup', capture);
    editor.addEventListener('keydown', capture);
    editor.addEventListener('input', capture);
    editor.addEventListener('blur', capture);
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
  }
  function showFormulaInserter(){
    const field = document.getElementById('mathfield');
    field.classList.remove('d-none');
    document.getElementById('mfToolbar').classList.remove('d-none');
    document.getElementById('insertFormulaBtn').classList.remove('d-none');
    document.getElementById('applyEditBtn').classList.add('d-none');
    document.getElementById('clearFormulaBtn').classList.remove('d-none');
    ensureMathLive().then(() => {
      if (!mf) initMathfield();
      setTimeout(() => { try { mf.focus(); } catch(e){} }, 0);
    }).catch(() => {
      useFallback = true;
      activateFallbackEditor();
    });
  }
  function editExistingFormula(node){
    const field = document.getElementById('mathfield');
    document.getElementById('mfToolbar').classList.remove('d-none');
    document.getElementById('clearFormulaBtn').classList.remove('d-none');
    document.getElementById('insertFormulaBtn').classList.add('d-none');
    document.getElementById('applyEditBtn').classList.remove('d-none');
    
    ensureMathLive().then(() => {
      field.classList.remove('d-none');
      if (!mf) initMathfield();
      selectedMathNode = node;
      
      // Clear mathfield first, then set value
      try { mf.setValue(''); } catch(e) {}
      setTimeout(() => {
        try { 
          mf.setValue(node.outerHTML, {format:'math-ml'}); 
        } catch(e1) { 
          try { 
            mf.setValue(node.outerHTML, {format:'mathml'}); 
          } catch(e2) { 
            console.warn('Error setting formula:', e2); 
          } 
        }
        // Update preview
        const prev = document.getElementById('mfPreview');
        if (prev) {
          let mathml = ensureNamespace(getMfMathML());
          prev.innerHTML = mathml;
          if (window.MathJax && MathJax.typesetPromise) {
            MathJax.typesetPromise([prev]).catch(err => console.error(err));
          }
        }
        try { mf.focus(); } catch(e){}
      }, 50);
    }).catch(() => {
      useFallback = true;
      activateFallbackEditor();
      selectedMathNode = node;
      const ta = document.getElementById('mfFallbackText');
      ta.value = node.outerHTML;
      
      // Update preview for fallback
      const prev = document.getElementById('mfPreview');
      if (prev) {
        prev.innerHTML = node.outerHTML;
        if (window.MathJax && MathJax.typesetPromise) {
          MathJax.typesetPromise([prev]).catch(err => console.error(err));
        }
      }
    });
  }
  function insertFormulaIntoEditor(){
    const editor = document.getElementById('qqHtmlEditor');
    let mathml = getMfMathML();
    
    // Validate and ensure proper MathML format
    if (!mathml || mathml.trim() === '' || mathml === '<math></math>') {
      alert('Please create a formula first');
      return;
    }
    
    mathml = ensureNamespace(mathml);
    
    editor.focus();
    if (!restoreSelection()) {
      placeCaretAtEnd(editor);
    }
    insertHtmlAtCursor(mathml);
    
    // Update main render
    const render = document.getElementById('qqRender');
    render.innerHTML = editor.innerHTML;
    ensureMathMLNamespaceFor(render);
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([render]).catch(err => console.error(err));
    }
    
    saveSelection();
    clearFormula();
  }
  function applyFormulaEdit(){
    if (!selectedMathNode) return;
    let mathml = getMfMathML();
    mathml = ensureNamespace(mathml);
    
    if (!mathml || mathml === '<math></math>' || mathml.trim() === '') {
      alert('Please enter a formula before applying');
      return;
    }
    
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
    
    // Clear formula editor
    clearFormula();
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
  function clearFormula(){
    const mathfield = document.getElementById('mathfield');
    const mfFallback = document.getElementById('mfFallback');
    const preview = document.getElementById('mfPreview');
    
    // Clear mathfield
    if (mf) {
      try { mf.setValue(''); } catch(e) {}
    }
    
    // Clear fallback textarea
    const ta = document.getElementById('mfFallbackText');
    if (ta) ta.value = '';
    
    // Clear preview
    if (preview) preview.innerHTML = '';
    
    // Hide formula toolbar
    document.getElementById('mfToolbar').classList.add('d-none');
    mathfield.classList.add('d-none');
    mfFallback.classList.add('d-none');
    document.getElementById('insertFormulaBtn').classList.add('d-none');
    document.getElementById('applyEditBtn').classList.add('d-none');
    document.getElementById('clearFormulaBtn').classList.add('d-none');
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
    if (!ta.dataset.bound) {
      ta.addEventListener('input', updateFallbackPreview);
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
    if (lastNode) {
      const newRange = document.createRange();
      newRange.setStartAfter(lastNode);
      newRange.collapse(true);
      sel.removeAllRanges();
      sel.addRange(newRange);
    }
  }
  function saveSelection(){
    const editor = document.getElementById('qqHtmlEditor');
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;
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
  }
  function debounce(fn, wait){
    let t; return function(){ clearTimeout(t); t = setTimeout(() => fn.apply(this, arguments), wait); };
  }
  // On form submit, save the HTML to hidden field
  document.getElementById('qqForm').addEventListener('submit', function(e){
    const editor = document.getElementById('qqHtmlEditor');
    ensureMathMLNamespaceFor(editor);
    document.getElementById('qqEditorHiddenHtml').value = editor.innerHTML;
  });
  // Configure MathJax for MathML rendering
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
  function ensureMathMLNamespace(){
    var nodes = document.querySelectorAll('math');
    nodes.forEach(function(el){
      if (!el.getAttribute('xmlns')) {
        el.setAttribute('xmlns','http://www.w3.org/1998/Math/MathML');
      }
    });
  }
</script>
<link rel="stylesheet" href="https://unpkg.com/mathlive/dist/mathlive.core.css">
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/mml-chtml.js"></script>
<?php require_once APPPATH . 'views/layout/footer.php'; ?>
