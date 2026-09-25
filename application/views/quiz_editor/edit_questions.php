<?php
$active_menu = 'quiz_editor';
$title = isset($title) ? $title : 'Edit Quiz Questions';
$category = isset($category) ? $category : '';
$questions = isset($questions) ? $questions : array();

require_once APPPATH . 'views/layout/header.php';
?>
<style>
    .editable-content { 
        border: 1px dashed #ccc; 
        padding: 15px; 
        min-height: 50px;
        line-height: 1.8;
        font-size: 1.05rem;
    }
    .question-card { 
        margin-bottom: 30px; 
        border: 1px solid #dee2e6;
    }
    .answer-item {
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .save-indicator {
        display: none;
        color: #28a745;
        font-size: 0.9em;
    }
    .save-indicator.show { display: inline-block; }
    .edit-btn { font-size: 0.85em; }
    .editor-container {
        margin-top: 10px;
        display: none;
    }
    .editor-container.show {
        display: block;
    }
</style>

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('quiz_editor'); ?>">Quiz Editor</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($category); ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-pencil-square"></i> Edit Quiz Questions - <?php echo htmlspecialchars($category); ?></h4>
        <a href="<?php echo base_url('quiz_editor'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Categories</a>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> <strong>Tip:</strong> Click "Edit" to open the editor dialog. Type normally for text. Use &lt;math&gt; tags for formulas (e.g., &lt;math&gt;&lt;mi&gt;x&lt;/mi&gt;&lt;/math&gt;). Live preview shows rendered output.
    </div>

    <?php if (!empty($questions)): ?>
        <?php $qno = 1; foreach ($questions as $q): ?>
            <div class="card question-card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Question <?php echo $qno; ?> (ID: <?php echo $q['id']; ?>)</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Question Text:</label>
                        <div class="editable-content" 
                             id="question-<?php echo $q['id']; ?>" 
                             data-type="question" 
                             data-id="<?php echo $q['id']; ?>"
                             contenteditable="false">
                            <?php echo $q['question_name']; ?>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-primary edit-btn" onclick="openEditor('question-<?php echo $q['id']; ?>')">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <span class="save-indicator ms-2" id="indicator-question-<?php echo $q['id']; ?>">
                                <i class="bi bi-check-circle-fill"></i> Saved!
                            </span>
                        </div>
                    </div>

                    <?php if (!empty($q['answers'])): ?>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Answers:</label>
                            <?php foreach ($q['answers'] as $ans): ?>
                                <div class="answer-item">
                                    <div class="editable-content" 
                                         id="answer-<?php echo $ans['id']; ?>" 
                                         data-type="answer" 
                                         data-id="<?php echo $ans['id']; ?>"
                                         contenteditable="false">
                                        <?php echo $ans['question_answer']; ?>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-primary edit-btn" onclick="openEditor('answer-<?php echo $ans['id']; ?>')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <span class="save-indicator ms-2" id="indicator-answer-<?php echo $ans['id']; ?>">
                                            <i class="bi bi-check-circle-fill"></i> Saved!
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php $qno++; endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">No questions found for this category.</div>
    <?php endif; ?>
</div>

<!-- Modal Editor -->
<div class="modal fade" id="editorModal" tabindex="-1" aria-labelledby="editorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editorModalLabel">Edit Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Edit (HTML/MathML):</label>
                        <div class="mb-2 btn-group" role="group">
                            <button type="button" class="btn btn-success btn-sm" onclick="editFormulaInline()">
                                <i class="bi bi-plus-circle"></i> Add Formula
                            </button>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#mathmlEditorModal">
                                <i class="bi bi-calculator"></i> Symbol Palette
                            </button>
                        </div>
                        <textarea id="editorTextarea" class="form-control font-monospace" rows="15" style="font-size: 0.9em;"></textarea>
                        <small class="text-muted">
                            Tip: Click "Add Formula" or use "Symbol Palette" to insert MathML. Example: &lt;math&gt;&lt;mi&gt;x&lt;/mi&gt;&lt;mo&gt;+&lt;/mo&gt;&lt;mn&gt;2&lt;/mn&gt;&lt;/math&gt;
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Live Preview:</label>
                        <div id="editorPreview" class="border rounded p-3" style="min-height: 300px; background: #f8f9fa; line-height: 1.8; font-size: 1.05rem;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEditor()">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
let currentElement = null;

function openEditor(elementId) {
    const elem = document.getElementById(elementId);
    currentElement = elem;
    
    // Get current HTML
    const currentHtml = elem.innerHTML.trim();
    
    // Set textarea content
    document.getElementById('editorTextarea').value = currentHtml;
    
    // Update preview
    updatePreview();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editorModal'));
    modal.show();
}

function updatePreview() {
    const textarea = document.getElementById('editorTextarea');
    const preview = document.getElementById('editorPreview');
    
    // Set preview content
    preview.innerHTML = textarea.value;
    
    // Render MathML
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise([preview]).catch(err => console.error('MathJax error:', err));
    }
}

// Auto-update preview on typing (debounced)
let previewTimeout;
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('editorTextarea');
    textarea.addEventListener('input', function() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 500);
    });
});

function saveEditor() {
    const textarea = document.getElementById('editorTextarea');
    const newHtml = textarea.value.trim();
    
    const type = currentElement.getAttribute('data-type');
    const id = currentElement.getAttribute('data-id');
    const indicator = document.getElementById('indicator-' + currentElement.id);
    
    // Show loading in button
    const saveBtn = event.target;
    const originalHtml = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
    
    // Determine endpoint
    const endpoint = type === 'question' ? 'save_question' : 'save_answer';
    
    // Send AJAX request
    fetch('<?php echo base_url('quiz_editor/'); ?>' + endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + encodeURIComponent(id) + '&html=' + encodeURIComponent(newHtml)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update displayed content
            currentElement.innerHTML = newHtml;
            
            // Show success indicator
            indicator.classList.add('show');
            setTimeout(() => indicator.classList.remove('show'), 3000);
            
            // Re-render MathML in the main page
            if (typeof MathJax !== 'undefined') {
                MathJax.typesetPromise([currentElement]);
            }
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('editorModal')).hide();
        } else {
            alert('Error saving: ' + (data.error || 'Unknown error'));
        }
        
        // Reset button
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHtml;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving content. Please try again.');
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHtml;
    });
}

// Initial MathJax rendering
window.addEventListener('load', function() {
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise();
    }
});
</script>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>

<!-- Include MathML Editors -->
<?php require_once APPPATH . 'views/quiz_editor/mathml_editor_modal.php'; ?>
<?php require_once APPPATH . 'views/quiz_editor/inline_formula_editor.php'; ?>
