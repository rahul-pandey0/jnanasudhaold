
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8f8f8; }
        .quiz-container { display: flex; min-height: 100vh; }
        .quiz-main { flex: 1; padding: 30px 40px 30px 60px; background: #fff; }
        .quiz-sidebar { width: 320px; background: #f4f6fa; border-left: 1px solid #ddd; padding: 30px 20px; box-sizing: border-box; }
        .question { margin-bottom: 30px; }
        .question-title { font-size: 1.1em; margin-bottom: 10px; }
        .answers { margin-left: 20px; }
        .mcq-option { margin-bottom: 8px; }
        .action-btns { margin-top: 25px; display: flex; gap: 12px; flex-wrap: wrap; }
        .action-btns button, .action-btns input[type=submit] { padding: 10px 18px; border: none; border-radius: 3px; font-size: 1em; cursor: pointer; }
        .save-next { background: #2ecc40; color: #fff; }
        .save-review { background: #f39c12; color: #fff; }
        .clear-response { background: #fff; color: #333; border: 1px solid #bbb; }
        .review-next { background: #2980b9; color: #fff; }
        .submit-btn { background: #27ae60; color: #fff; margin-top: 20px; }
        .navigator-title { font-weight: bold; margin-bottom: 10px; }
        .navigator-grid { display: flex; flex-wrap: wrap; gap: 6px; }
        .navigator-btn { width: 36px; height: 36px; border-radius: 4px; border: 1px solid #bbb; background: #fff; color: #333; font-weight: bold; cursor: pointer; text-align: center; line-height: 36px; font-size: 1em; }
        .navigator-btn.answered { background: #2ecc40; color: #fff; border: none; }
        .navigator-btn.not-answered { background: #e74c3c; color: #fff; border: none; }
        .navigator-btn.review { background: #8e44ad; color: #fff; border: none; }
        .navigator-btn.current { border: 2px solid #2980b9; }
        .legend { margin: 18px 0 10px 0; }
        .legend span { display: inline-block; width: 18px; height: 18px; border-radius: 3px; margin-right: 6px; vertical-align: middle; }
        .legend-label { margin-right: 18px; font-size: 0.97em; }
        .nav-controls { margin-top: 18px; display: flex; gap: 10px; }
        .nav-controls button { background: #fff; border: 1px solid #bbb; color: #333; padding: 7px 16px; border-radius: 3px; cursor: pointer; }
        .nav-btn { background: #fff; border: 1px solid #bbb; color: #333; padding: 7px 16px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .nav-btn:hover { background: #f0f0f0; }
        a.navigator-btn { text-decoration: none; display: block; }
    </style>
    <script>
    // Disable right-click context menu
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    // Disable copy, cut, paste, drag
    document.addEventListener('copy', function(e) { e.preventDefault(); });
    document.addEventListener('cut', function(e) { e.preventDefault(); });
    document.addEventListener('paste', function(e) { e.preventDefault(); });
    document.addEventListener('dragstart', function(e) { e.preventDefault(); });
    // Disable text selection
    document.addEventListener('selectstart', function(e) { e.preventDefault(); });
    // For older browsers
    document.onselectstart = function() { return false; };
    document.onmousedown = function(e) { if (e.detail > 1) e.preventDefault(); };
    </script>
</head>
<body>
<div class="quiz-container">
    <div class="quiz-main">
        <h2 style="margin-top:0;">Quiz</h2>
        <?php
        // Navigation logic: get current question index from GET param, default 0
        $total = count($questions ?? []);
        $current = isset($_GET['q']) ? max(0, min($total-1, intval($_GET['q']))) : 0;
        ?>
        <form method="post" action="?q=<?php echo $current; ?>">
        <?php if (!empty($questions)): ?>
            <?php $q = $questions[$current]; $qno = $current+1; ?>
                <div class="question">
                    <div class="question-title"><strong>Question No : <?php echo $qno; ?></strong></div>
                    <div style="margin-bottom:10px;"><?php echo $q['question_name']; ?></div>
                    <div class="answers">
                        <?php if (!empty($q['answers'])): ?>
                            <?php foreach ($q['answers'] as $ans): ?>
                                <div class="mcq-option">
                                    <label>
                                        <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="<?php echo $ans['id']; ?>">
                                        <?php echo $ans['question_answer']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <em>No answers found.</em>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="action-btns">
                    <button type="submit" class="save-next">SAVE & NEXT</button>
                    <button type="submit" class="save-review">SAVE & MARK FOR REVIEW</button>
                    <button type="button" class="clear-response">CLEAR RESPONSE</button>
                    <button type="submit" class="review-next">MARK FOR REVIEW & NEXT</button>
                </div>
                <div class="nav-controls">
                    <?php if ($current > 0): ?>
                        <a href="?q=<?php echo $current-1; ?>" class="nav-btn">&lt;&lt; BACK</a>
                    <?php endif; ?>
                    <?php if ($current < $total-1): ?>
                        <a href="?q=<?php echo $current+1; ?>" class="nav-btn">NEXT &gt;&gt;</a>
                    <?php endif; ?>
                </div>
            <button type="submit" class="submit-btn">SUBMIT</button>
        <?php else: ?>
            <p>No questions found for this category.</p>
        <?php endif; ?>
        </form>
    </div>
    <div class="quiz-sidebar">
        <div class="navigator-title">Question Navigator</div>
        <div class="navigator-grid">
            <?php 
            for ($i = 1; $i <= $total; $i++): 
                $class = 'navigator-btn';
                if ($i-1 == $current) $class .= ' current';
                // For demo, color code: 1 answered, 2 not answered, 3 review, rest default
                if ($i == 1) $class .= ' answered';
                elseif ($i == 2) $class .= ' not-answered';
                elseif ($i == 3) $class .= ' review';
            ?>
                <a href="?q=<?php echo $i-1; ?>" class="<?php echo $class; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <div class="nav-controls" style="margin-top:18px;">
            <?php if ($current > 0): ?>
                <a href="?q=<?php echo $current-1; ?>" class="nav-btn">&lt;&lt; PREV</a>
            <?php endif; ?>
            <?php if ($current < $total-1): ?>
                <a href="?q=<?php echo $current+1; ?>" class="nav-btn">NEXT &gt;&gt;</a>
            <?php endif; ?>
        </div>
        <div class="legend">
            <span style="background:#2ecc40;"></span><span class="legend-label">Answered</span>
            <span style="background:#e74c3c;"></span><span class="legend-label">Not Answered</span>
            <span style="background:#8e44ad;"></span><span class="legend-label">Marked for Review</span>
            <span style="background:#fff; border:1px solid #bbb;"></span><span class="legend-label">Not Visited</span>
        </div>
    </div>
</div>
</body>
</html>
