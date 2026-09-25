<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
$mathml = isset($question['question_mathml']) ? $question['question_mathml'] : '<math></math>';
?>
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
    <li class="breadcrumb-item active">Edit MathML Question</li>
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
<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
     <h5 class="mb-0"><i class="bi bi-function"></i> Inline Formula Editor</h5>
     <a href="<?php echo base_url('questions/edit_mathml/' . (int)$question['id']); ?>" class="btn btn-sm btn-secondary">Reload</a>
  </div>
  <div class="card-body">
    <form method="POST" autocomplete="off" onsubmit="syncMathML()">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Formula (Interactive)</label>
          <div id="mathfield" class="form-control" style="min-height:120px; font-size:1.25rem; overflow:auto;"></div>
          <small class="text-muted">Type LaTeX or directly manipulate structure; it will render live.</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Rendered Preview</label>
          <div id="preview" class="border rounded p-2 bg-light" style="min-height:120px;"></div>
          <small class="text-muted">Preview updates as you edit. Uses MathJax to render MathML.</small>
        </div>
        <input type="hidden" name="mathml" id="mathmlHidden">
      </div>
      <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
        <button type="button" class="btn btn-outline-secondary" onclick="resetEditor()"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-header">
    <h6 class="mb-0">Stored MathML</h6>
  </div>
  <div class="card-body">
     <div class="mb-2">
       <strong>Rendered:</strong>
       <div id="storedRendered" class="border rounded p-2 bg-light" style="min-height:80px;"></div>
     </div>
     <strong>Raw (readonly):</strong>
     <pre style="white-space:pre-wrap; font-size:0.75rem; background:#f8f9fa; padding:0.75rem; border:1px solid #e1e5ea; border-radius:4px;" id="rawMathML"><?php echo htmlspecialchars($mathml); ?></pre>
  </div>
</div>
<script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>
<script>
  // Configure MathJax for MathML input & CHTML output
  window.MathJax = {
    loader: { load: ['input/mml','output/chtml'] },
    startup: {
      ready: () => {
        MathJax.startup.defaultReady();
        // After MathJax is ready, render the stored block
        renderStored();
      }
    }
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/mml-chtml.js"></script>
<script>
  const initialMathML = `<?php echo str_replace('`','\`',$mathml); ?>`;
  let mf;
  function ensureNamespace(src){
    if (!src) return '';
    // Add MathML namespace if missing
    if (src.indexOf('<math') !== -1 && src.indexOf('xmlns=') === -1) {
      return src.replace('<math', '<math xmlns="http://www.w3.org/1998/Math/MathML"');
    }
    return src;
  }
  function initEditor(){
    mf = MathLive.makeMathField('mathfield', {
      smartFence: true,
      virtualKeyboardMode: 'onfocus',
      virtualKeyboardTheme: 'material',
      formats: ['latex','mathml'],
      onContentDidChange: () => updatePreview()
    });
    try {
      if (initialMathML && initialMathML.trim() !== '') {
        mf.setValue(initialMathML, {format:'mathml'});
      }
    } catch(e){ console.warn('MathML set failed, starting empty', e); }
    updatePreview();
  }
  function updatePreview(){
    const mathml = ensureNamespace(mf.getValue('mathml'));
    const preview = document.getElementById('preview');
    preview.innerHTML = mathml;
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([preview]).catch(err => console.error('MathJax typeset error', err));
    }
    document.getElementById('mathmlHidden').value = mathml;
  }
  function resetEditor(){
    mf.setValue(initialMathML || '', {format:'mathml'});
    updatePreview();
  }
  function syncMathML(){
    document.getElementById('mathmlHidden').value = ensureNamespace(mf.getValue('mathml'));
  }
  function renderStored(){
    const el = document.getElementById('storedRendered');
    el.innerHTML = ensureNamespace(initialMathML || '<math xmlns="http://www.w3.org/1998/Math/MathML"></math>');
    if (window.MathJax && MathJax.typesetPromise) {
      MathJax.typesetPromise([el]).catch(err => console.error('MathJax typeset error', err));
    }
  }
  window.addEventListener('DOMContentLoaded', () => { initEditor(); });
</script>
<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
