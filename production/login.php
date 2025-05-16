<?php
$page_title = 'Login';
require_once 'includes/header.php';

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Login to Your Account</h2>

        <form action="process_login.php" method="post" class="auth-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
