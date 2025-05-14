<?php
session_start();
date_default_timezone_set('Africa/Johannesburg');
// Clear any existing session data
if(isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
} else {
    $login_error = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertex Smart Meters - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>Vertex Smart Meters</h1>
            <div class="logo">
                <svg width="80" height="80" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="#6a0dad" />
                    <path d="M50 20 L80 50 L50 80 L20 50 Z" fill="white" />
                </svg>
            </div>
            <form action="process_login.php" method="post">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label for="device_id">Device ID</label>
                    <input type="text" id="device_id" name="device_id" required placeholder="Enter your meter ID">
                </div>
                <?php if(!empty($login_error)) { ?>
                    <div class="error-message"><?php echo $login_error; ?></div>
                <?php } ?>
                <button type="submit" class="btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>