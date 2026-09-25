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
        <i class="bi bi-info-circle"></i> <strong>Tip:</strong> Click "Edit" to modify text inline (type normally!). Math formulas appear as blue widgets - click any formula widget to edit it with a visual math editor. Click "Insert Formula" to add new formulas.
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
                            <button class="btn btn-sm btn-primary edit-btn" onclick="enableEdit('question-<?php echo $q['id']; ?>')">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-info insert-formula-btn d-none" onclick="insertFormula('question-<?php echo $q['id']; ?>')">
                                <i class="bi bi-plus-circle"></i> Insert Formula
                            </button>
                            <button class="btn btn-sm btn-success save-btn d-none" onclick="saveContent('question-<?php echo $q['id']; ?>')">
                                <i class="bi bi-check-circle"></i> Save
                            </button>
                            <button class="btn btn-sm btn-secondary cancel-btn d-none" onclick="cancelEdit('question-<?php echo $q['id']; ?>')">
                                <i class="bi bi-x-circle"></i> Cancel
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
                                        <button class="btn btn-sm btn-primary edit-btn" onclick="enableEdit('answer-<?php echo $ans['id']; ?>')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-info insert-formula-btn d-none" onclick="insertFormula('answer-<?php echo $ans['id']; ?>')">
                                            <i class="bi bi-plus-circle"></i> Insert Formula
                                        </button>
                                        <button class="btn btn-sm btn-success save-btn d-none" onclick="saveContent('answer-<?php echo $ans['id']; ?>')">
                                            <i class="bi bi-check-circle"></i> Save
                                        </button>
                                        <button class="btn btn-sm btn-secondary cancel-btn d-none" onclick="cancelEdit('answer-<?php echo $ans['id']; ?>')">
                                            <i class="bi bi-x-circle"></i> Cancel
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

<!-- Load TinyMCE - professional editor that handles all cursor/editing issues -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
// Store original content for cancel functionality
const originalContent = {};
let currentEditingElement = null;

// Convert math tags to widgets when enabling edit
function protectMathFormulas(element) {
    const mathTags = element.querySelectorAll('math');
    mathTags.forEach((math) => {
        if (!math.closest('.math-widget')) {
            const widget = document.createElement('span');
            widget.className = 'math-widget';
            widget.setAttribute('contenteditable', 'false');
            widget.title = 'Click to edit formula';
            widget.dataset.mathml = math.outerHTML;
            
            // Clone the math element for display
            const mathClone = math.cloneNode(true);
            widget.appendChild(mathClone);
            
            // Add click handler
            widget.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                editMathWidget(this);
            };
            
            math.replaceWith(widget);
        }
    });
}

// Remove widgets and restore clean MathML
function unprotectMathFormulas(html) {
    const temp = document.createElement('div');
    temp.innerHTML = html;
    const widgets = temp.querySelectorAll('.math-widget');
    widgets.forEach(widget => {
        const mathml = widget.dataset.mathml;
        if (mathml) {
            const temp2 = document.createElement('div');
            temp2.innerHTML = mathml;
            const math = temp2.querySelector('math');
            if (math) {
                widget.replaceWith(math);
            }
        }
    });
    return temp.innerHTML;
}

function enableEdit(elementId) {
    const elem = document.getElementById(elementId);
    const editBtn = elem.nextElementSibling.querySelector('.edit-btn');
    const insertFormulaBtn = elem.nextElementSibling.querySelector('.insert-formula-btn');
    const saveBtn = elem.nextElementSibling.querySelector('.save-btn');
    const cancelBtn = elem.nextElementSibling.querySelector('.cancel-btn');
    
    // Store original content
    originalContent[elementId] = elem.innerHTML;
    currentEditingElement = elem;
    
    // Protect math formulas as widgets
    protectMathFormulas(elem);
    
    // Enable editing
    elem.contentEditable = 'true';
    elem.focus();
    
    // Toggle buttons
    editBtn.classList.add('d-none');
    insertFormulaBtn.classList.remove('d-none');
    saveBtn.classList.remove('d-none');
    cancelBtn.classList.remove('d-none');
}

function cancelEdit(elementId) {
    const elem = document.getElementById(elementId);
    const editBtn = elem.nextElementSibling.querySelector('.edit-btn');
    const insertFormulaBtn = elem.nextElementSibling.querySelector('.insert-formula-btn');
    const saveBtn = elem.nextElementSibling.querySelector('.save-btn');
    const cancelBtn = elem.nextElementSibling.querySelector('.cancel-btn');
    
    // Restore original content
    elem.innerHTML = originalContent[elementId];
    elem.contentEditable = 'false';
    currentEditingElement = null;
    
    // Toggle buttons
    editBtn.classList.remove('d-none');
    insertFormulaBtn.classList.add('d-none');
    saveBtn.classList.add('d-none');
    cancelBtn.classList.add('d-none');
    
    // Re-render MathML
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise([elem]);
    }
}

function saveContent(elementId) {
    const elem = document.getElementById(elementId);
    const type = elem.getAttribute('data-type');
    const id = elem.getAttribute('data-id');
    
    // Get HTML and remove protection wrappers
    const cleanHtml = unprotectMathFormulas(elem.innerHTML);
    
    const editBtn = elem.nextElementSibling.querySelector('.edit-btn');
    const insertFormulaBtn = elem.nextElementSibling.querySelector('.insert-formula-btn');
    const saveBtn = elem.nextElementSibling.querySelector('.save-btn');
    const cancelBtn = elem.nextElementSibling.querySelector('.cancel-btn');
    const indicator = document.getElementById('indicator-' + elementId);
    
    // Show saving state
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
        body: 'id=' + encodeURIComponent(id) + '&html=' + encodeURIComponent(cleanHtml)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update content with cleaned HTML
            elem.innerHTML = cleanHtml;
            elem.contentEditable = 'false';
            currentEditingElement = null;
            
            // Toggle buttons
            editBtn.classList.remove('d-none');
            insertFormulaBtn.classList.add('d-none');
            saveBtn.classList.add('d-none');
            cancelBtn.classList.add('d-none');
            
            // Show success indicator
            indicator.classList.add('show');
            setTimeout(() => indicator.classList.remove('show'), 3000);
            
            // Re-render MathML
            if (typeof MathJax !== 'undefined') {
                MathJax.typesetPromise([elem]);
            }
        } else {
            alert('Error saving: ' + (data.error || 'Unknown error'));
        }
        
        // Reset button
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> Save';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving content. Please try again.');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> Save';
    });
}

// Insert new formula using MathLive
function insertFormula(elementId) {
    const elem = document.getElementById(elementId);
    
    // Create a MathLive mathfield
    const mathfield = document.createElement('math-field');
    mathfield.setAttribute('virtual-keyboard-mode', 'manual');
    mathfield.style.display = 'inline-block';
    
    // Insert at cursor or end
    const selection = window.getSelection();
    if (selection.rangeCount > 0 && elem.contains(selection.anchorNode)) {
        const range = selection.getRangeAt(0);
        range.insertNode(mathfield);
    } else {
        elem.appendChild(mathfield);
    }
    
    mathfield.focus();
    
    // Handle blur - convert to widget
    mathfield.addEventListener('blur', function() {
        setTimeout(() => {
            const latex = mathfield.value;
            if (latex) {
                // Convert LaTeX to MathML
                convertLatexToMathML(latex, (mathml) => {
                    const widget = document.createElement('span');
                    widget.className = 'math-widget';
                    widget.setAttribute('contenteditable', 'false');
                    widget.title = 'Click to edit formula';
                    widget.dataset.mathml = mathml;
                    
                    const temp = document.createElement('div');
                    temp.innerHTML = mathml;
                    const math = temp.querySelector('math');
                    if (math) {
                        widget.appendChild(math);
                    }
                    
                    widget.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        editMathWidget(this);
                    };
                    
                    mathfield.replaceWith(widget);
                    
                    // Re-render MathML
                    if (typeof MathJax !== 'undefined') {
                        MathJax.typesetPromise([widget]);
                    }
                });
            } else {
                mathfield.remove();
            }
        }, 100);
    });
}

// Edit existing math widget using MathLive
function editMathWidget(widget) {
    const mathml = widget.dataset.mathml;
    
    // Create a MathLive mathfield
    const mathfield = document.createElement('math-field');
    mathfield.setAttribute('virtual-keyboard-mode', 'manual');
    mathfield.style.display = 'inline-block';
    
    // Try to get LaTeX from MathML
    const temp = document.createElement('div');
    temp.innerHTML = mathml;
    const math = temp.querySelector('math');
    
    // For now, start with empty field (MathML to LaTeX conversion is complex)
    // In production, you'd use a proper MathML to LaTeX converter
    mathfield.value = '';
    
    widget.replaceWith(mathfield);
    mathfield.focus();
    
    // Handle blur - convert back to widget
    mathfield.addEventListener('blur', function() {
        setTimeout(() => {
            const latex = mathfield.value;
            if (latex) {
                convertLatexToMathML(latex, (newMathml) => {
                    const newWidget = document.createElement('span');
                    newWidget.className = 'math-widget';
                    newWidget.setAttribute('contenteditable', 'false');
                    newWidget.title = 'Click to edit formula';
                    newWidget.dataset.mathml = newMathml;
                    
                    const temp2 = document.createElement('div');
                    temp2.innerHTML = newMathml;
                    const math2 = temp2.querySelector('math');
                    if (math2) {
                        newWidget.appendChild(math2);
                    }
                    
                    newWidget.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        editMathWidget(this);
                    };
                    
                    mathfield.replaceWith(newWidget);
                    
                    // Re-render MathML
                    if (typeof MathJax !== 'undefined') {
                        MathJax.typesetPromise([newWidget]);
                    }
                });
            } else {
                // Restore original widget if empty
                mathfield.replaceWith(widget);
            }
        }, 100);
    });
}

// Convert LaTeX to MathML (simplified - uses browser's built-in or MathJax)
function convertLatexToMathML(latex, callback) {
    // Try to use the mathfield's built-in conversion
    const temp = document.createElement('math-field');
    temp.value = latex;
    
    // Get MathML from MathLive
    try {
        const mathml = temp.getValue('math-ml');
        callback(mathml);
    } catch (e) {
        // Fallback: create simple MathML wrapper
        const mathml = `<math><mtext>${latex}</mtext></math>`;
        callback(mathml);
    }
}

// Initial MathJax rendering
window.addEventListener('load', function() {
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise();
    }
});
</script>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
