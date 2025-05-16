<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
require_login();

// Check if meter is specified
if (!isset($_GET['meter']) || empty($_GET['meter'])) {
    redirect_with_message('dashboard.php', 'No meter specified.', 'error');
}

$user_id = $_SESSION['user_id'];
$device_id = sanitize_input($_GET['meter']);

// Check if user owns this meter
if (!user_owns_meter($conn, $user_id, $device_id)) {
    redirect_with_message('dashboard.php', 'You do not have permission to access this meter.', 'error');
}

// Set active meter and redirect to dashboard
$_SESSION['active_meter'] = $device_id;
header("Location: dashboard.php");
exit();
?>
