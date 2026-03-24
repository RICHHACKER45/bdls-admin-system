<?php
// public/login.php
session_start();

// Kung naka-login na, wag na pabalikin sa login page, idiretso na sa dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BDLS</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 2.5rem; border-radius: 15px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        .login-card h2 { text-align: center; margin-bottom: 1.5rem; color: #4f46e5; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #374151; }
        .form-group input { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 0.75rem; background-color: #4f46e5; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 1rem; }
        .btn-login:hover { background-color: #4338ca; }
        .error-msg { color: #dc2626; background: #fee2e2; padding: 0.75rem; border-radius: 8px; font-size: 0.875rem; text-align: center; margin-bottom: 1rem; display: <?php echo isset($_GET['error']) ? 'block' : 'none'; ?>; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>BDLS Authentication</h2>
        
        <div class="error-msg">Invalid username or password.</div>

        <form action="../app/api/auth.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>