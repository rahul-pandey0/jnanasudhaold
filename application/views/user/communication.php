<?php
$active_menu = 'users';
$user_email = isset($user_email) ? $user_email : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin');
$title = isset($title) ? $title : 'Communication';
$user = isset($user) ? $user : array();
$communications = isset($communications) ? $communications : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Users</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('users/view/' . $user['user_id']); ?>"><?php echo htmlspecialchars($user['first_name']); ?></a></li>
                        <li class="breadcrumb-item active">Communication</li>
                    </ol>
                </nav>

                <!-- User Info Bar -->
                <div class="card" style="background-color: #f0f2f7; border: 1px solid #e0e4f0; margin-bottom: 1rem;">
                    <div class="card-body" style="padding: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h6 style="margin: 0; color: #667eea;"><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                                <small style="color: #666;"><?php echo htmlspecialchars($user['email']); ?></small>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?php echo base_url('users/view/' . $user['user_id']); ?>" class="btn btn-primary btn-sm" title="View Profile">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-outline-primary btn-sm" title="Dashboard">
                                    <i class="bi bi-house-door"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Communications Table -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Communication History (<?php echo count($communications); ?>)</h5>
                            <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="overflow-x: auto;">
                            <?php if (!empty($communications)): ?>
                                <table class="table table-hover" style="margin-bottom: 0;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">Date</th>
                                            <th style="width: 15%;">Type</th>
                                            <th style="width: 50%;">Subject</th>
                                            <th style="width: 20%;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($communications as $comm): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($comm['date']); ?></td>
                                                <td>
                                                    <?php if ($comm['type'] === 'Email'): ?>
                                                        <span class="badge" style="background-color: #667eea;"><i class="bi bi-envelope"></i> Email</span>
                                                    <?php elseif ($comm['type'] === 'SMS'): ?>
                                                        <span class="badge" style="background-color: #28a745;"><i class="bi bi-chat-left-text"></i> SMS</span>
                                                    <?php else: ?>
                                                        <span class="badge" style="background-color: #6c757d;"><i class="bi bi-chat-dots"></i> <?php echo htmlspecialchars($comm['type']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($comm['subject']); ?></td>
                                                <td>
                                                    <?php if ($comm['status'] === 'Delivered'): ?>
                                                        <span class="badge-active"><i class="bi bi-check-circle"></i> Delivered</span>
                                                    <?php elseif ($comm['status'] === 'Pending'): ?>
                                                        <span class="badge" style="background-color: #ffc107; color: #333;"><i class="bi bi-hourglass-split"></i> Pending</span>
                                                    <?php elseif ($comm['status'] === 'Failed'): ?>
                                                        <span class="badge-inactive"><i class="bi bi-x-circle"></i> Failed</span>
                                                    <?php else: ?>
                                                        <span class="badge" style="background-color: #6c757d;"><?php echo htmlspecialchars($comm['status']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="text-center text-muted" style="padding: 2rem;">
                                    <i class="bi bi-chat-dots" style="font-size: 2rem; color: #ccc;"></i>
                                    <p style="margin-top: 0.5rem;">No communication records found for this user.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                    <a href="<?php echo base_url('users/view/' . $user['user_id']); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to User
                    </a>
                    <a href="<?php echo base_url('users'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-list"></i> User List
                    </a>
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
