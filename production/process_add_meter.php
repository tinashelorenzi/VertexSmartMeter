<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    require_login();

    $user_id = $_SESSION['user_id'];
    $device_id = sanitize_input($_POST['device_id']);
    $nickname = isset($_POST['nickname']) && !empty($_POST['nickname'])
                ? sanitize_input($_POST['nickname'])
                : 'My Meter';

    // Check if the meter exists in the database
    $stmt = $conn->prepare("SELECT id FROM smart_meters WHERE device_id = ?");
    $stmt->bind_param("s", $device_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Meter doesn't exist
        redirect_with_message((isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php'),
                            'Meter ID not found. Please check and try again.', 'error');
    }

    // Check if the user already has this meter
    $stmt = $conn->prepare("SELECT id FROM user_meters WHERE user_id = ? AND device_id = ?");
    $stmt->bind_param("is", $user_id, $device_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already has this meter
        redirect_with_message((isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php'),
                            'This meter is already added to your account.', 'warning');
    }

    // Add the meter to the user's account
    $stmt = $conn->prepare("INSERT INTO user_meters (user_id, device_id, nickname) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $device_id, $nickname);

    if ($stmt->execute()) {
        // Success
        $_SESSION['active_meter'] = $device_id;
        redirect_with_message('dashboard.php', 'Smart meter added successfully!', 'success');
    } else {
        // Error
        redirect_with_message((isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php'),
                            'Failed to add meter. Please try again later.', 'error');
    }

    $stmt->close();
} else {
    // Not a POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>
