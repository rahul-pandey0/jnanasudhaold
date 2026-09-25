<?php
/**
 * Sidebar Menu Component
 * Usage: Set $active_menu before including this file
 */
$active_menu = isset($active_menu) ? $active_menu : '';
?>
<!-- Sidebar -->
<div class="col-md-2 sidebar d-none d-md-block">
    <div class="list-group list-group-flush">
        <a href="<?php echo base_url('dashboard'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'dashboard') ? 'active' : ''; ?> border-0">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="<?php echo base_url('users'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'users') ? 'active' : ''; ?> border-0">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="<?php echo base_url('questions'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'questions') ? 'active' : ''; ?> border-0">
            <i class="bi bi-question-circle"></i> Questions
        </a>
        <a href="<?php echo base_url('dashboard/db_test'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'db_test') ? 'active' : ''; ?> border-0">
            <i class="bi bi-database"></i> DB Test
        </a>
        <a href="<?php echo base_url('notifications'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'notifications') ? 'active' : ''; ?> border-0">
            <i class="bi bi-bell"></i> Notifications
        </a>
        <a href="<?php echo base_url('notifications/quiz_rank'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'quiz_rank_notify') ? 'active' : ''; ?> border-0 ps-4">
            <i class="bi bi-trophy"></i> Quiz Rank Notify
        </a>
        <a href="<?php echo base_url('notifications/user_notify'); ?>" class="list-group-item list-group-item-action <?php echo ($active_menu === 'user_notify') ? 'active' : ''; ?> border-0 ps-4">
            <i class="bi bi-person-lines-fill"></i> User Notify
        </a>
        <a href="#" class="list-group-item list-group-item-action border-0">
            <i class="bi bi-table"></i> Tables
        </a>
        <a href="#" class="list-group-item list-group-item-action border-0">
            <i class="bi bi-gear"></i> Settings
        </a>
    </div>
</div>
