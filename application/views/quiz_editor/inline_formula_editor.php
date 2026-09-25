<?php
/**
 * Inline MathML Editor - Edit formulas directly in browser
 * Click to edit any <math> tags in the question/answer
 */
?>

<!-- Inline Formula Editor Modal -->
<div class="modal fade" id="inlineFormulaModal" tabindex="-1" aria-labelledby="inlineFormulaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inlineFormulaLabel">
                    <i class="bi bi-calculator"></i> Edit Formula
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left: Symbol Insert Panel -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Add Symbols</h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div id="inline-symbol-menu"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Right: Editor -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-pencil"></i> MathML Code (Edit Here)</h6>
                            </div>
                            <div class="card-body">
                                <textarea id="inlineFormulaCode" class="form-control font-monospace" rows="10" placeholder="Edit MathML here&#10;Example: &lt;mi&gt;x&lt;/mi&gt;&lt;mo&gt;+&lt;/mo&gt;&lt;mn&gt;2&lt;/mn&gt;&#10;&#10;Tip: Click symbols below to insert MathML tags" style="font-size: 0.9em; resize: vertical;"></textarea>
                                <small class="text-muted d-block mt-2">
                                    <strong>Tags:</strong> &lt;mi&gt;variable &lt;mo&gt;operator &lt;mn&gt;number &lt;mfrac&gt;fraction &lt;msup&gt;power &lt;msub&gt;subscript &lt;msqrt&gt;sqrt
                                </small>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-eye"></i> Live Formula Preview</h6>
                            </div>
                            <div class="card-body">
                                <div id="inlineFormulaPreview" class="border rounded p-4" style="min-height: 100px; background: #f9f9f9; text-align: center; font-size: 1.3rem; line-height: 2; display: flex; align-items: center; justify-content: center; user-select: none;">
                                    [Formula will appear here]
                                </div>
                                <small class="text-muted d-block mt-2">✓ Updates as you type. Click symbols to insert. Formula renders above.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveInlineFormula()">
                    <i class="bi bi-check-circle"></i> Update Formula
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Ensure MathJax is available -->
<script>
// Verify MathJax is loaded
if (typeof MathJax === 'undefined') {
    console.warn('MathJax not yet loaded, loading now...');
    var script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js';
    script.async = true;
    document.head.appendChild(script);
} else {
    console.log('MathJax already available');
}
</script>
<script>
let currentFormulaElement = null;
let currentFormulaIndex = null;

// Initialize inline formula editor
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing inline formula editor...');
    
    // Try Bootstrap Modal API first
    const modal = document.getElementById('inlineFormulaModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            console.log('Modal showing - building symbol menu');
            buildInlineSymbolMenu();
            setupFormulaCodeListener();
        });
    }
    
    // Fallback: also bind to jQuery if available
    if (typeof $ !== 'undefined') {
        $('#inlineFormulaModal').on('show.bs.modal', function() {
            console.log('jQuery modal showing');
            buildInlineSymbolMenu();
            setupFormulaCodeListener();
        });
    }
});

// Build simplified symbol menu for inline editing
function buildInlineSymbolMenu() {
    const menu = document.getElementById('inline-symbol-menu');
    if (!menu) {
        console.error('Symbol menu container not found');
        return;
    }
    
    let html = '';
    
    // Operators
    html += '<div class="mb-3">';
    html += '<strong class="d-block mb-2 text-primary">Operators</strong>';
    const operators = [
        {label: '+', tag: '<mo>+</mo>'},
        {label: '−', tag: '<mo>−</mo>'},
        {label: '×', tag: '<mo>×</mo>'},
        {label: '÷', tag: '<mo>÷</mo>'},
        {label: '=', tag: '<mo>=</mo>'},
        {label: '≠', tag: '<mo>≠</mo>'},
        {label: '<', tag: '<mo>&lt;</mo>'},
        {label: '>', tag: '<mo>&gt;</mo>'},
        {label: '±', tag: '<mo>±</mo>'},
    ];
    operators.forEach(op => {
        html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('${op.tag.replace(/'/g, "\\'")}'" title="${op.tag}">${op.label}</button>`;
    });
    html += '</div>';
    
    // Greek Letters
    html += '<div class="mb-3">';
    html += '<strong class="d-block mb-2 text-primary">Greek</strong>';
    const greek = ['α', 'β', 'γ', 'δ', 'ε', 'θ', 'λ', 'μ', 'π', 'σ', 'φ', 'ω'];
    greek.forEach(letter => {
        html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('<mi>${letter}</mi>')" title="${letter}">${letter}</button>`;
    });
    html += '</div>';
    
    // Functions
    html += '<div class="mb-3">';
    html += '<strong class="d-block mb-2 text-primary">Functions</strong>';
    const functions = ['sin', 'cos', 'tan', 'log', 'ln', 'exp', 'max', 'min'];
    functions.forEach(fn => {
        html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('<mi>${fn}</mi>')" title="${fn}">${fn}</button>`;
    });
    html += '</div>';
    
    // Calculus
    html += '<div class="mb-3">';
    html += '<strong class="d-block mb-2 text-primary">Calculus</strong>';
    const calculus = [
        {label: '∫', tag: '<mo>∫</mo>'},
        {label: '∑', tag: '<mo>∑</mo>'},
        {label: '∏', tag: '<mo>∏</mo>'},
        {label: '∂', tag: '<mo>∂</mo>'},
        {label: '∇', tag: '<mo>∇</mo>'},
    ];
    calculus.forEach(item => {
        html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('${item.tag.replace(/'/g, "\\'")}'" title="${item.tag}">${item.label}</button>`;
    });
    html += '</div>';
    
    // Structures
    html += '<div class="mb-3">';
    html += '<strong class="d-block mb-2 text-primary">Structures</strong>';
    html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('<mfrac><mrow>a</mrow><mrow>b</mrow></mfrac>')" title="Fraction">Fraction</button>`;
    html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('<msup><mrow>a</mrow><mrow>b</mrow></msup>')" title="Power">Power</button>`;
    html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('<msub><mrow>a</mrow><mrow>i</mrow></msub>')" title="Subscript">Subscript</button>`;
    html += `<button type="button" class="btn btn-sm btn-outline-secondary m-1" onclick="insertInlineSymbol('<msqrt><mrow>x</mrow></msqrt>')" title="Square Root">√</button>`;
    html += '</div>';
    
    menu.innerHTML = html;
}

// Setup formula code listener for live preview
function setupFormulaCodeListener() {
    const textarea = document.getElementById('inlineFormulaCode');
    if (!textarea) {
        console.error('Formula code textarea not found');
        return;
    }
    
    console.log('Setting up textarea listener');
    
    // Remove previous listeners to avoid duplicates
    const newTextarea = textarea.cloneNode(true);
    textarea.parentNode.replaceChild(newTextarea, textarea);
    
    // Add new listener
    newTextarea.addEventListener('input', function() {
        console.log('Textarea input event fired');
        updateInlinePreview();
    });
}

// Insert symbol into formula code
function insertInlineSymbol(mathml) {
    const textarea = document.getElementById('inlineFormulaCode');
    
    // Get cursor position
    const startPos = textarea.selectionStart;
    const endPos = textarea.selectionEnd;
    const beforeText = textarea.value.substring(0, startPos);
    const afterText = textarea.value.substring(endPos);
    
    // Insert at cursor position
    textarea.value = beforeText + mathml + afterText;
    
    // Move cursor after inserted text
    const newPos = startPos + mathml.length;
    textarea.selectionStart = newPos;
    textarea.selectionEnd = newPos;
    
    // Update preview
    updateInlinePreview();
    
    // Keep focus on textarea
    textarea.focus();
}

// Update inline preview
function updateInlinePreview() {
    const code = document.getElementById('inlineFormulaCode').value.trim();
    const preview = document.getElementById('inlineFormulaPreview');
    
    if (!code) {
        preview.innerHTML = '[Formula will appear here]';
        return;
    }
    
    // Wrap with math tags if not present
    let mathml = code;
    if (!code.startsWith('<math')) {
        mathml = '<math xmlns="http://www.w3.org/1998/Math/MathML">' + code + '</math>';
    }
    
    // Set preview content
    preview.innerHTML = mathml;
    
    console.log('Preview updating with:', mathml);
    
    // Force MathJax to render
    if (window.MathJax && typeof window.MathJax.typesetPromise === 'function') {
        try {
            if (typeof window.MathJax.typesetClear === 'function') {
                window.MathJax.typesetClear([preview]);
            }
            window.MathJax.typesetPromise([preview])
                .then(() => console.log('MathJax rendered preview'))
                .catch(err => console.error('MathJax error:', err));
        } catch (e) {
            console.error('Preview rendering error:', e);
        }
    }
}

// Handle preview blur - sync back to textarea
function onFormulaPreviewBlur() {
    const preview = document.getElementById('inlineFormulaPreview');
    const textarea = document.getElementById('inlineFormulaCode');
    const content = preview.innerHTML.trim();
    
    if (content && content !== '[Click here or paste formula]') {
        // Extract inner content if wrapped with math tags
        let innerContent = content;
        if (content.includes('<math')) {
            innerContent = content.replace(/<math[^>]*>/i, '').replace(/<\/math>/i, '').trim();
        }
        
        textarea.value = innerContent;
        console.log('Preview blur - synced to textarea:', innerContent);
    }
}

// Handle preview paste - clean up and sync
function onFormulaPreviewPaste(event) {
    event.preventDefault();
    
    const text = (event.clipboardData || window.clipboardData).getData('text/html') || 
                 (event.clipboardData || window.clipboardData).getData('text/plain');
    
    console.log('Pasted:', text);
    
    if (document.queryCommandSupported('insertText')) {
        document.execCommand('insertText', false, text);
    } else {
        document.execCommand('paste', false, text);
    }
    
    // Sync to textarea after paste
    setTimeout(function() {
        onFormulaPreviewInput();
    }, 100);
}

// Handle preview input - sync to textarea
function onFormulaPreviewInput() {
    const preview = document.getElementById('inlineFormulaPreview');
    const textarea = document.getElementById('inlineFormulaCode');
    let content = preview.innerHTML.trim();
    
    if (!content || content === '[Click here or paste formula]') {
        return;
    }
    
    // Extract just the content without outer math tags
    let innerContent = content;
    if (content.includes('<math')) {
        innerContent = content.replace(/<math[^>]*>/i, '').replace(/<\/math>/i, '').trim();
    }
    
    textarea.value = innerContent;
    console.log('Preview input - synced to textarea:', innerContent);
    
    // Re-render with MathJax
    setTimeout(function() {
        if (window.MathJax && typeof window.MathJax.typesetPromise === 'function') {
            try {
                if (typeof window.MathJax.typesetClear === 'function') {
                    window.MathJax.typesetClear([preview]);
                }
                window.MathJax.typesetPromise([preview]).catch(err => console.error('MathJax error:', err));
            } catch (e) {
                console.error('Error:', e);
            }
        }
    }, 100);
}

// Save inline formula and update textarea
function saveInlineFormula() {
    const code = document.getElementById('inlineFormulaCode').value.trim();
    if (!code) {
        alert('Please enter formula code');
        return;
    }
    
    // Create full MathML
    const fullMathML = '<math xmlns="http://www.w3.org/1998/Math/MathML">' + code + '</math>';
    
    // Get the main editor textarea
    const editorTextarea = document.getElementById('editorTextarea');
    let content = editorTextarea.value;
    
    // If editing existing formula, replace it
    if (currentFormulaIndex !== null) {
        // Find and replace the specific math tag
        const mathRegex = /<math[^>]*>[\s\S]*?<\/math>/g;
        let matches = [...content.matchAll(mathRegex)];
        if (currentFormulaIndex < matches.length) {
            const match = matches[currentFormulaIndex];
            content = content.substring(0, match.index) + fullMathML + content.substring(match.index + match[0].length);
        }
    } else {
        // Add new formula
        content += '\n' + fullMathML;
    }
    
    editorTextarea.value = content;
    
    // Trigger preview update
    const event = new Event('input', { bubbles: true });
    editorTextarea.dispatchEvent(event);
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('inlineFormulaModal')).hide();
    
    // Re-render formulas in preview
    if (typeof MathJax !== 'undefined') {
        setTimeout(() => MathJax.typesetPromise(), 100);
    }
}

// Open inline formula editor for new formula
function editFormulaInline() {
    currentFormulaIndex = null;
    document.getElementById('inlineFormulaCode').value = '';
    updateInlinePreview();
    const modal = new bootstrap.Modal(document.getElementById('inlineFormulaModal'));
    modal.show();
}

// Edit existing formula (called when clicking on formula in preview)
function editExistingFormula(mathmlContent, index) {
    currentFormulaIndex = index;
    
    // Extract inner content (without outer <math> tags)
    let innerContent = mathmlContent.replace(/<math[^>]*>/i, '').replace(/<\/math>/i, '').trim();
    
    document.getElementById('inlineFormulaCode').value = innerContent;
    updateInlinePreview();
    
    const modal = new bootstrap.Modal(document.getElementById('inlineFormulaModal'));
    modal.show();
}
</script>

<style>
    #inlineFormulaPreview {
        word-break: break-word;
    }
    
    .formula-badge {
        display: inline-block;
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        padding: 4px 8px;
        border-radius: 3px;
        cursor: pointer;
        margin: 2px;
        transition: all 0.2s;
    }
    
    .formula-badge:hover {
        background: #cce5ff;
        border-color: #80c0ff;
        box-shadow: 0 0 5px rgba(0, 100, 200, 0.3);
    }
</style>
