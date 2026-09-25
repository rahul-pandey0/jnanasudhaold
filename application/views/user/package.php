<?php
$student = isset($student) && is_array($student) ? $student : [];
$package = isset($package) && is_array($package) ? $package : [];
$title = "Package Details";

require_once APPPATH.'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Students</a></li>
        <li class="breadcrumb-item active">
            <?php echo htmlspecialchars(($student['first_name'] ?? 'packages').' '.($student['last_name'] ?? '')); ?>
        </li>
    </ol>
</nav>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-box-seam"></i> Package Details</h5>
        <div>
            <a href="<?php echo base_url('users/student_profile/' . ($student['user_id'] ?? '')); ?>" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
    <table>
    <thead>
        <tr>
           <!-- <th>package id</th>-->
            <th>Quiz Name</th>
            <th>Physics Mark</th>
            <th>Chemistry Mark</th>
            <th>Biology Mark</th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach($quizzes as $quiz): ?>
            <tr>
               <!-- <td><?= $quiz ['package_id']?></td>-->
                <td><?= $quiz['quiz_name'] ?></td>
                <td><?= $quiz['physicsmark'] ?? 0?></td>
                <td><?= $quiz['chemistrymark'] ?? 0 ?></td>
                <td><?= $quiz['biologymark'] ?? 0?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
</div>

<?php require_once APPPATH.'views/layout/footer.php'; ?>