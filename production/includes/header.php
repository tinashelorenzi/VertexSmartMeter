<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <?php if (isset($extra_css)) echo $extra_css; ?>
</head>
<body>
    <?php if (isset($show_header) && $show_header): ?>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="#4E4699" />
                    <path d="M50 20 L80 50 L50 80 L20 50 Z" fill="white" />
                </svg>
                <a href="dashboard.php"><h1><?php echo APP_NAME; ?></h1></a>
            </div>
            <?php if (is_logged_in()): ?>
            <div class="user-controls">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
            <?php endif; ?>
        </div>
    </header>
    <?php endif; ?>

    <div class="container main-content">
        <?php display_message(); ?>
