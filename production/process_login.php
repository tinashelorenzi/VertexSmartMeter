<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize user input
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        redirect_with_message('login.php', 'Username and password are required.');
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, username, password, name FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];

            // Check if user has any meters
            $user_meters = get_user_meters($conn, $user['id']);

            if (count($user_meters) > 0) {
                // If user has multiple meters, redirect to meter selector
                if (count($user_meters) > 1) {
                    header("Location: meter_selector.php");
                    exit();
                } else {
                    // If user has only one meter, set it as active and redirect to dashboard
                    $_SESSION['active_meter'] = $user_meters[0]['device_id'];
                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                // User has no meters, redirect to dashboard with message
                redirect_with_message('dashboard.php', 'No smart meters found in your account. Please add a meter.', 'warning');
            }
        } else {
            // Invalid password
            redirect_with_message('login.php', 'Invalid username or password.');
        }
    } else {
        // User doesn't exist
        redirect_with_message('login.php', 'Invalid username or password.');
    }

    $stmt->close();
} else {
    // If not a POST request, redirect to login form
    header("Location: login.php");
    exit();
}

$conn->close();
?>
