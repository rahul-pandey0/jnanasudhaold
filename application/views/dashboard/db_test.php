<?php
// Set active menu for sidebar highlight
$active_menu = 'db_test';
$user_email = isset($user_email) ? $user_email : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin');
$connection_status = isset($connection_status) ? $connection_status : 'unknown';
$connection_message = isset($connection_message) ? $connection_message : 'Testing connection...';
$db_config = isset($db_config) ? $db_config : array();

// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo isset($title) ? $title : 'Database Test'; ?></li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-database"></i> Database Connection Test</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($connection_status === 'success'): ?>
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle"></i> <span class="status-success"><?php echo $connection_message; ?></span>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" style="background-color: #f8f9fa; color: #333; border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);">
                                            <h6 class="mb-0" style="font-size: 0.7rem;">Connection Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table" style="font-size: 0.7rem;">
                                                <tr>
                                                    <td><strong>Host:</strong></td>
                                                    <td><?php echo htmlspecialchars(isset($db_config['hostname']) ? $db_config['hostname'] : 'N/A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Username:</strong></td>
                                                    <td><?php echo htmlspecialchars(isset($db_config['username']) ? $db_config['username'] : 'N/A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Database:</strong></td>
                                                    <td><?php echo htmlspecialchars(isset($db_config['database']) ? $db_config['database'] : 'N/A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Driver:</strong></td>
                                                    <td><?php echo htmlspecialchars(isset($db_config['dbdriver']) ? $db_config['dbdriver'] : 'N/A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Charset:</strong></td>
                                                    <td><?php echo htmlspecialchars(isset($db_config['char_set']) ? $db_config['char_set'] : 'N/A'); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="alert alert-info" style="margin-top: 0.5rem;">
                                        <strong><i class="bi bi-info-circle"></i> Next Steps:</strong>
                                        <ul style="font-size: 0.65rem; margin: 0.3rem 0 0 1.2rem;">
                                            <li>Database is connected successfully!</li>
                                            <li>Ready to create tables and CRUD operations</li>
                                            <li>Provide table structure to get started</li>
                                        </ul>
                                    </div>

                                <?php else: ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-circle"></i> <span class="status-error">Connection Failed!</span>
                                    </div>

                                    <div class="alert alert-warning">
                                        <strong style="font-size: 0.7rem;"><i class="bi bi-exclamation-triangle"></i> Error:</strong>
                                        <div style="font-size: 0.65rem; margin-top: 0.3rem; word-break: break-word;">
                                            <?php echo htmlspecialchars($connection_message); ?>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" style="background-color: #f8f9fa; color: #333; border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);">
                                            <h6 class="mb-0" style="font-size: 0.7rem;">Attempted Connection</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="code-block">
Host: <?php echo htmlspecialchars(isset($db_config['hostname']) ? $db_config['hostname'] : 'N/A'); ?><br>
Username: <?php echo htmlspecialchars(isset($db_config['username']) ? $db_config['username'] : 'N/A'); ?><br>
Database: <?php echo htmlspecialchars(isset($db_config['database']) ? $db_config['database'] : 'N/A'); ?><br>
Driver: <?php echo htmlspecialchars(isset($db_config['dbdriver']) ? $db_config['dbdriver'] : 'N/A'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info" style="margin-top: 0.5rem;">
                                        <strong style="font-size: 0.7rem;"><i class="bi bi-info-circle"></i> Troubleshooting:</strong>
                                        <ul style="font-size: 0.65rem; margin: 0.3rem 0 0 1.2rem;">
                                            <li>Ensure MySQL/MariaDB is running</li>
                                            <li>Verify hostname, username, and password in config</li>
                                            <li>Check if database exists</li>
                                            <li>Edit <code>application/config/database.php</code> to fix credentials</li>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div style="margin-top: 1rem;">
                                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
</html>
