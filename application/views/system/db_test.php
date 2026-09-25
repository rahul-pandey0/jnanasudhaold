<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 2rem;
        }
        .container {
            max-width: 600px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0 !important;
        }
        .status-ok {
            color: #28a745;
            font-weight: 600;
        }
        .status-error {
            color: #dc3545;
            font-weight: 600;
        }
        .code-block {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85rem;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-database"></i> Database Connection Test</h4>
            </div>
            <div class="card-body">
                <?php 
                // Test database connection
                $config = array();
                require_once APPPATH . 'config/database.php';
                
                $test_connection = @mysqli_connect(
                    $db['default']['hostname'],
                    $db['default']['username'],
                    $db['default']['password'],
                    $db['default']['database']
                );
                
                if ($test_connection) {
                    ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> <span class="status-ok">Database Connection Successful!</span>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Connection Details:</h6>
                        <div class="code-block">
                            Host: <?php echo $db['default']['hostname']; ?><br>
                            Database: <?php echo $db['default']['database']; ?><br>
                            Driver: <?php echo $db['default']['dbdriver']; ?><br>
                            Charset: <?php echo $db['default']['char_set']; ?>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong><i class="bi bi-info-circle"></i> Next Steps:</strong>
                        <ol class="mb-0" style="font-size: 0.85rem;">
                            <li>Create database tables for your application</li>
                            <li>Provide table structure and sample data</li>
                            <li>System will auto-generate CRUD operations</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <h6>Create Sample Database:</h6>
                        <p style="font-size: 0.85rem;">Run this SQL in phpMyAdmin or MySQL CLI:</p>
                        <div class="code-block">
CREATE DATABASE IF NOT EXISTS codeigniter_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                        </div>
                    </div>
                    <?php
                    $test_connection->close();
                } else {
                    ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <span class="status-error">Database Connection Failed!</span>
                    </div>

                    <div class="mb-3">
                        <h6>Connection Details Attempted:</h6>
                        <div class="code-block">
                            Host: <?php echo $db['default']['hostname']; ?><br>
                            Database: <?php echo $db['default']['database']; ?><br>
                            Username: <?php echo $db['default']['username']; ?>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <strong><i class="bi bi-exclamation-triangle"></i> Troubleshooting:</strong>
                        <ul class="mb-0" style="font-size: 0.85rem;">
                            <li>Ensure MySQL/MariaDB is running</li>
                            <li>Check hostname, username, and password in <code>application/config/database.php</code></li>
                            <li>Verify the database exists or update the database name</li>
                            <li>Check MySQL error: <?php echo mysqli_connect_error(); ?></li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6>Update Configuration:</h6>
                        <p style="font-size: 0.85rem;">Edit <code>application/config/database.php</code>:</p>
                        <div class="code-block">
$db['default']['hostname'] = 'your_host';<br>
$db['default']['username'] = 'your_username';<br>
$db['default']['password'] = 'your_password';<br>
$db['default']['database'] = 'your_database';
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="mt-3">
                    <a href="<?php echo base_url('auth/login'); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
