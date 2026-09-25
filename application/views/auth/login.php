<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        html, body {
            height: 100%;
            font-size: 0.875rem;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 380px;
            padding: 0.5rem;
        }
        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 1rem;
            text-align: center;
        }
        .login-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        .login-header p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        .login-header i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .login-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
            color: #333;
        }
        .form-control {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-control::placeholder {
            font-size: 0.8rem;
        }
        .input-group-text {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.6rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .login-footer {
            text-align: center;
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            font-size: 0.8rem;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .remember-check {
            font-size: 0.8rem;
        }
        .remember-check input {
            margin-right: 0.3rem;
        }
        .alert {
            padding: 0.6rem 0.75rem;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .demo-info {
            background-color: #e7f3ff;
            border-left: 3px solid #667eea;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-size: 0.75rem;
            line-height: 1.4;
        }
        .demo-info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <i class="bi bi-shield-lock"></i>
                <h1>Admin Panel</h1>
                <p>Secure Login</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Demo Credentials Info -->
                <div class="demo-info">
                    <strong>Demo Credentials:</strong><br>
                    <small>User ID/Email: admin or admin@test.com<br>Password: admin123</small>
                </div>

                <!-- Login Form -->
                <form method="POST" action="<?php echo base_url('auth/do_login'); ?>">
                    <input type="hidden" id="device_token" name="device_token" value="">

                    <!-- User ID / Email Field -->
                    <div class="form-group">
                        <label for="login"><i class="bi bi-person"></i> User ID / Email Address</label>
                        <input type="text" class="form-control" id="login" name="login" 
                               placeholder="Enter user ID or email" required autofocus>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password"><i class="bi bi-lock"></i> Password</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter your password" required>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group">
                        <label class="remember-check">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <small>&copy; 2025 Admin Dashboard. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const tokenInput = document.getElementById('device_token');
            const savedToken = localStorage.getItem('fcm_device_token') || '';
            if (savedToken) {
                tokenInput.value = savedToken;
            }

            window.addEventListener('message', function (event) {
                if (event.data && event.data.type === 'fcm-token' && event.data.token) {
                    localStorage.setItem('fcm_device_token', event.data.token);
                    tokenInput.value = event.data.token;
                }
            });
        })();
    </script>
</body>
</html>
