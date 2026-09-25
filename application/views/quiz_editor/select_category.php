<?php
$active_menu = 'quiz_editor';
$title = isset($title) ? $title : 'Quiz Editor - Select Category';
$categories = isset($categories) ? $categories : array();

require_once APPPATH . 'views/layout/header.php';
?>
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
            <li class="breadcrumb-item active">Quiz Editor</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Quiz Editor - Select Category</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Select a category to edit quiz questions inline with MathML support.</p>
            
            <div class="list-group">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo base_url('quiz_editor/index/' . urlencode($cat)); ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-folder"></i> <?php echo htmlspecialchars($cat); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No categories found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
