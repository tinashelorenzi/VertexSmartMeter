<?php
$page_title = 'Home';
require_once 'includes/header.php';

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}
?>

<div class="home-container">
    <div class="intro-card">
        <div class="logo-large">
            <svg width="100" height="100" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="#4E4699" />
                <path d="M50 20 L80 50 L50 80 L20 50 Z" fill="white" />
            </svg>
            <h1><?php echo APP_NAME; ?></h1>
        </div>

        <p class="intro-text">
            Manage your electricity usage efficiently with our smart metering solution.
            Track usage, top up your meters, and view your consumption history.
        </p>

        <div class="cta-buttons">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-secondary">Register</a>
        </div>
    </div>

    <div class="features">
        <div class="feature-item">
            <div class="feature-icon">📊</div>
            <h3>Real-time Monitoring</h3>
            <p>Track your electricity usage in real-time with our advanced smart meters.</p>
        </div>

        <div class="feature-item">
            <div class="feature-icon">💡</div>
            <h3>Multiple Meters</h3>
            <p>Manage multiple properties with a single account. All your meters in one place.</p>
        </div>

        <div class="feature-item">
            <div class="feature-icon">📱</div>
            <h3>Easy Top-ups</h3>
            <p>Add electricity units to your meters with just a few clicks.</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
