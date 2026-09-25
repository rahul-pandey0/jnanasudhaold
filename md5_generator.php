<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5 Password Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 0.85rem; padding: 2rem; background-color: #f8f9fa; }
        .container { max-width: 600px; }
        .card { box-shadow: 0 0.05rem 0.1rem rgba(0, 0, 0, 0.075); }
        .form-control { font-size: 0.8rem; }
        .btn { font-size: 0.8rem; }
        code { background-color: #f4f4f4; padding: 0.2rem 0.4rem; border-radius: 3px; font-size: 0.75rem; }
        pre { background-color: #f4f4f4; padding: 0.5rem; border-radius: 3px; font-size: 0.7rem; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">MD5 Password Generator</h1>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Generate MD5 Hash</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Enter Password:</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter password to hash" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Hash</button>
                </form>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <?php
                    $password = isset($_POST['password']) ? $_POST['password'] : '';
                    $hash = md5($password);
                    ?>
                    <hr>
                    <h6>Result:</h6>
                    <p><strong>Password:</strong> <code><?php echo htmlspecialchars($password); ?></code></p>
                    <p><strong>MD5 Hash:</strong></p>
                    <pre><?php echo htmlspecialchars($hash); ?></pre>
                    <p class="text-muted small">Use this hash value when inserting user records into the <code>user_details</code> table.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Important Notes</h5>
                <ul class="small mb-0">
                    <li>Password is stored as MD5 in <code>user_details.password</code> column</li>
                    <li>User must have <code>role_id != 2</code> to login</li>
                    <li>User must have <code>user_status = 1</code> (active) to login</li>
                    <li>Login uses <code>email</code> and <code>password</code> fields</li>
                    <li>Example: role_id 1 = Admin, role_id 3 = User</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
