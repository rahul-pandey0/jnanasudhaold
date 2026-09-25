<?php
/**
 * MathML Editor Modal - Visual MathML Input UI
 * Similar to MathQuill but for MathML with live preview
 */
?>

<!-- MathML Editor Modal -->
<div class="modal fade" id="mathmlEditorModal" tabindex="-1" aria-labelledby="mathmlEditorLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mathmlEditorLabel">
                    <i class="bi bi-calculator"></i> MathML Editor - Visual Math Input
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 85vh; overflow-y: auto;">
                <div class="row">
                    <!-- Left Column: Editor -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-pencil-square"></i> MathML Input</h6>
                            </div>
                            <div class="card-body">
                                <!-- MathML Editor Textarea -->
                                <label class="form-label"><strong>MathML Code:</strong></label>
                                <textarea id="mathmlTextarea" class="form-control font-monospace" rows="12" placeholder="MathML will appear here&#10;Example: &lt;mi&gt;x&lt;/mi&gt;&lt;mo&gt;+&lt;/mo&gt;&lt;mn&gt;2&lt;/mn&gt;" style="font-size: 0.9em;"></textarea>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> Click symbols below to build MathML or edit directly
                                </small>

                                <!-- Current MathML Display -->
                                <div class="mt-3 p-2 bg-light border rounded">
                                    <strong>Full MathML:</strong>
                                    <code id="mathml-full" class="d-block mt-2 small" style="word-break: break-all; max-height: 60px; overflow-y: auto;">&lt;math&gt;&lt;/math&gt;</code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Symbol Menu & Preview -->
                    <div class="col-lg-6">
                        <!-- Live Preview Panel -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-eye"></i> Live Preview</h6>
                            </div>
                            <div class="card-body">
                                <div id="mathml-preview" class="border rounded p-4" style="min-height: 150px; background: #f9f9f9; text-align: center; font-size: 1.3rem; line-height: 2;">
                                    [Preview will appear here]
                                </div>
                            </div>
                        </div>

                        <!-- Symbol Menu with Categories -->
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="bi bi-menu-button"></i> Math Symbols</h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div id="symbol-menu"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveMathMLEditor()">
                    <i class="bi bi-check-circle"></i> Insert Math
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include MathJax -->
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<!-- MathML Editor Script -->
<script>
let currentMathMLContent = '';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize when modal opens
    $('#mathmlEditorModal').on('show.bs.modal', function() {
        buildSymbolMenu();
        setupTextareaListener();
        updatePreview();
    });
});

// Setup textarea listener for live updates
function setupTextareaListener() {
    const textarea = document.getElementById('mathmlTextarea');
    textarea.addEventListener('input', function() {
        updatePreview();
    });
}

// Insert MathML element
function insertMathML(mathmlCode) {
    const textarea = document.getElementById('mathmlTextarea');
    textarea.value += mathmlCode;
    textarea.focus();
    updatePreview();
}

// Update live preview
function updatePreview() {
    const textarea = document.getElementById('mathmlTextarea');
    const content = textarea.value.trim();
    const preview = document.getElementById('mathml-preview');
    
    if (!content) {
        preview.innerHTML = '[Preview will appear here]';
        document.getElementById('mathml-full').textContent = '<math></math>';
        return;
    }
    
    // Create full MathML with wrapper
    let fullMathML = '<math xmlns="http://www.w3.org/1998/Math/MathML">' + content + '</math>';
    document.getElementById('mathml-full').textContent = fullMathML;
    
    // Show preview
    preview.innerHTML = fullMathML;
    
    // Render with MathJax
    if (window.MathJax && typeof window.MathJax.typesetPromise === 'function') {
        try {
            if (typeof window.MathJax.typesetClear === 'function') {
                window.MathJax.typesetClear([preview]);
            }
            window.MathJax.typesetPromise([preview]).catch(err => console.error('MathJax error:', err));
        } catch (e) {
            console.error('Preview error:', e);
        }
    }
}

// Build symbol menu
function buildSymbolMenu() {
    const symbolMenu = document.getElementById('symbol-menu');
    
    const categories = {
        'Operators': [
            {label: '+', mathml: '<mo>+</mo>'},
            {label: '−', mathml: '<mo>−</mo>'},
            {label: '×', mathml: '<mo>×</mo>'},
            {label: '÷', mathml: '<mo>÷</mo>'},
            {label: '=', mathml: '<mo>=</mo>'},
            {label: '≠', mathml: '<mo>≠</mo>'},
            {label: '<', mathml: '<mo><</mo>'},
            {label: '>', mathml: '<mo>></mo>'},
            {label: '≤', mathml: '<mo>≤</mo>'},
            {label: '≥', mathml: '<mo>≥</mo>'},
            {label: '±', mathml: '<mo>±</mo>'},
            {label: '·', mathml: '<mo>·</mo>'},
        ],
        'Structure': [
            {label: 'Fraction', mathml: '<mfrac><mrow><mi>a</mi></mrow><mrow><mi>b</mi></mrow></mfrac>'},
            {label: 'Power', mathml: '<msup><mrow><mi>a</mi></mrow><mrow><mi>b</mi></mrow></msup>'},
            {label: 'Subscript', mathml: '<msub><mrow><mi>a</mi></mrow><mrow><mi>i</mi></mrow></msub>'},
            {label: '√', mathml: '<msqrt><mrow><mi>x</mi></mrow></msqrt>'},
            {label: 'ⁿ√', mathml: '<mroot><mrow><mi>x</mi></mrow><mrow><mi>n</mi></mrow></mroot>'},
            {label: '| |', mathml: '<mo>|</mo><mrow><mi>x</mi></mrow><mo>|</mo>'},
        ],
        'Calculus': [
            {label: '∫', mathml: '<mo>∫</mo>'},
            {label: '∑', mathml: '<mo>∑</mo>'},
            {label: '∏', mathml: '<mo>∏</mo>'},
            {label: '∂', mathml: '<mo>∂</mo>'},
            {label: '∇', mathml: '<mo>∇</mo>'},
            {label: 'lim', mathml: '<mi>lim</mi>'},
            {label: 'd/dx', mathml: '<mfrac><mrow><mi>d</mi></mrow><mrow><mi>dx</mi></mrow></mfrac>'},
        ],
        'Greek Letters': [
            {label: 'α', mathml: '<mi>α</mi>'},
            {label: 'β', mathml: '<mi>β</mi>'},
            {label: 'γ', mathml: '<mi>γ</mi>'},
            {label: 'δ', mathml: '<mi>δ</mi>'},
            {label: 'ε', mathml: '<mi>ε</mi>'},
            {label: 'θ', mathml: '<mi>θ</mi>'},
            {label: 'λ', mathml: '<mi>λ</mi>'},
            {label: 'μ', mathml: '<mi>μ</mi>'},
            {label: 'π', mathml: '<mi>π</mi>'},
            {label: 'σ', mathml: '<mi>σ</mi>'},
            {label: 'φ', mathml: '<mi>φ</mi>'},
            {label: 'ω', mathml: '<mi>ω</mi>'},
            {label: 'Σ', mathml: '<mo>Σ</mo>'},
            {label: 'Π', mathml: '<mo>Π</mo>'},
        ],
        'Geometry': [
            {label: '∠', mathml: '<mo>∠</mo>'},
            {label: '°', mathml: '<mo>°</mo>'},
            {label: '△', mathml: '<mo>△</mo>'},
            {label: '⊥', mathml: '<mo>⊥</mo>'},
            {label: '∥', mathml: '<mo>∥</mo>'},
            {label: '≅', mathml: '<mo>≅</mo>'},
            {label: '∼', mathml: '<mo>∼</mo>'},
        ],
        'Trigonometry': [
            {label: 'sin', mathml: '<mi>sin</mi>'},
            {label: 'cos', mathml: '<mi>cos</mi>'},
            {label: 'tan', mathml: '<mi>tan</mi>'},
            {label: 'csc', mathml: '<mi>csc</mi>'},
            {label: 'sec', mathml: '<mi>sec</mi>'},
            {label: 'cot', mathml: '<mi>cot</mi>'},
            {label: 'arcsin', mathml: '<mi>arcsin</mi>'},
            {label: 'arccos', mathml: '<mi>arccos</mi>'},
            {label: 'arctan', mathml: '<mi>arctan</mi>'},
        ],
        'Logic & Sets': [
            {label: '∈', mathml: '<mo>∈</mo>'},
            {label: '⊂', mathml: '<mo>⊂</mo>'},
            {label: '∪', mathml: '<mo>∪</mo>'},
            {label: '∩', mathml: '<mo>∩</mo>'},
            {label: '∅', mathml: '<mo>∅</mo>'},
            {label: '∀', mathml: '<mo>∀</mo>'},
            {label: '∃', mathml: '<mo>∃</mo>'},
            {label: '∧', mathml: '<mo>∧</mo>'},
            {label: '∨', mathml: '<mo>∨</mo>'},
            {label: '¬', mathml: '<mo>¬</mo>'},
            {label: '⇒', mathml: '<mo>⇒</mo>'},
            {label: '⇔', mathml: '<mo>⇔</mo>'},
        ],
        'Algebra': [
            {label: 'log', mathml: '<mi>log</mi>'},
            {label: 'ln', mathml: '<mi>ln</mi>'},
            {label: 'exp', mathml: '<mi>exp</mi>'},
            {label: '!', mathml: '<mo>!</mo>'},
            {label: 'max', mathml: '<mi>max</mi>'},
            {label: 'min', mathml: '<mi>min</mi>'},
            {label: 'gcd', mathml: '<mi>gcd</mi>'},
        ]
    };
    
    let html = '';
    for (const [categoryName, symbols] of Object.entries(categories)) {
        html += `
            <div class="category mb-2" data-category="${categoryName}">
                <div class="category-header" onclick="toggleCategory(this)" style="cursor: pointer; background-color: #f0f0f0; padding: 10px; border: 1px solid #ccc; user-select: none;">
                    <i class="bi bi-chevron-right"></i> <strong>${categoryName}</strong>
                </div>
                <div class="submenu mt-2" style="display: none; padding-left: 10px;">
        `;
        
        symbols.forEach(symbol => {
            html += `<button type="button" class="btn btn-outline-primary btn-sm m-1" onclick="insertMathML('${escapeHtml(symbol.mathml)}')" title="${symbol.mathml}">
                ${symbol.label}
            </button>`;
        });
        
        html += `</div></div>`;
    }
    
    symbolMenu.innerHTML = html;
}

// Toggle category
function toggleCategory(header) {
    const category = header.closest('.category');
    const submenu = category.querySelector('.submenu');
    const icon = header.querySelector('i');
    
    if (submenu.style.display === 'none') {
        submenu.style.display = 'block';
        icon.classList.remove('bi-chevron-right');
        icon.classList.add('bi-chevron-down');
    } else {
        submenu.style.display = 'none';
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-right');
    }
}

// Escape HTML for safe insertion
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Save MathML and insert into editor
function saveMathMLEditor() {
    const textarea = document.getElementById('mathmlTextarea');
    const content = textarea.value.trim();
    
    if (!content) {
        alert('Please enter or build MathML content');
        return;
    }
    
    // Create full MathML
    const fullMathML = '<math xmlns="http://www.w3.org/1998/Math/MathML">' + content + '</math>';
    
    // Insert into main editor
    const editorElement = document.getElementById('editorTextarea');
    if (editorElement) {
        editorElement.value += '\n' + fullMathML;
        
        // Trigger update preview
        const event = new Event('input', { bubbles: true });
        editorElement.dispatchEvent(event);
    }
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('mathmlEditorModal')).hide();
}

// Open MathML editor
function openMathMLEditor() {
    const modal = new bootstrap.Modal(document.getElementById('mathmlEditorModal'));
    modal.show();
}
</script>

<style>
    .category-header:hover {
        background-color: #e9ecef !important;
    }
    
    .submenu {
        animation: slideDown 0.2s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #mathml-preview {
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-outline-primary:hover {
        transform: scale(1.05);
        transition: transform 0.1s ease;
    }
</style>
