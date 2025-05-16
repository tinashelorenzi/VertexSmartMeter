<?php
$page_title = 'Register';
require_once 'includes/header.php';

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Create a New Account</h2>

        <form action="process_register.php" method="post" class="auth-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Choose a username"
                       pattern="[a-zA-Z0-9_]{3,20}" title="3-20 characters, alphanumeric with underscore">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Choose a password"
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="At least 8 characters with 1 uppercase, 1 lowercase and 1 number">
                <small>At least 8 characters with uppercase, lowercase and number</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="email">Email (optional)</label>
                <input type="email" id="email" name="email" placeholder="Enter your email address">
            </div>

            <div class="form-group">
                <label for="device_id">Meter ID (optional)</label>
                <input type="text" id="device_id" name="device_id" placeholder="Enter your first meter ID">
                <small>You can add more meters later</small>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
