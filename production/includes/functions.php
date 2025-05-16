<?php
/**
 * Helper functions for the Vertex Smart Meters application
 */

/**
 * Sanitize user input data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate username (alphanumeric with underscore, 3-20 chars)
 */
function is_valid_username($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

/**
 * Validate password strength
 * At least 8 characters, contains at least one uppercase, one lowercase and one number
 */
function is_valid_password($password) {
    return (strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password));
}

/**
 * Get all meters for a user
 */
function get_user_meters($conn, $user_id) {
    $meters = array();

    $stmt = $conn->prepare("
        SELECT um.id, um.device_id, um.nickname, sm.last_update_id
        FROM user_meters um
        JOIN smart_meters sm ON um.device_id = sm.device_id
        WHERE um.user_id = ?
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $meters[] = $row;
    }

    $stmt->close();
    return $meters;
}

/**
 * Get meter info (last reading, units left, etc.)
 */
function get_meter_info($conn, $device_id) {
    $meter_info = array();

    // Get the latest KWusage data
    $stmt = $conn->prepare("
        SELECT * FROM KWusage
        WHERE device_id = ?
        ORDER BY id DESC LIMIT 1
    ");

    $stmt->bind_param("s", $device_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $meter_info = $result->fetch_assoc();
    } else {
        $meter_info = [
            'KWusage' => 0,
            'UnitsLeft' => 0,
            'timestamp' => 'N/A'
        ];
    }

    $stmt->close();
    return $meter_info;
}

/**
 * Get pending units for a meter
 */
function get_pending_units($conn, $device_id) {
    $stmt = $conn->prepare("
        SELECT SUM(units) as pending_units
        FROM updates
        WHERE device_id = ? AND status = 'pending'
    ");

    $stmt->bind_param("s", $device_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $pending_units = $row['pending_units'] ? $row['pending_units'] : 0;

    $stmt->close();
    return $pending_units;
}

/**
 * Get top-up history for a meter
 */
function get_topup_history($conn, $device_id, $limit = 5) {
    $history = array();

    $stmt = $conn->prepare("
        SELECT id, units, update_id, status, time_stamp
        FROM updates
        WHERE device_id = ?
        ORDER BY id DESC LIMIT ?
    ");

    $stmt->bind_param("si", $device_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }

    $stmt->close();
    return $history;
}

/**
 * Check if user owns a particular meter
 */
function user_owns_meter($conn, $user_id, $device_id) {
    $stmt = $conn->prepare("
        SELECT id FROM user_meters
        WHERE user_id = ? AND device_id = ?
    ");

    $stmt->bind_param("is", $user_id, $device_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $owns = ($result->num_rows > 0);

    $stmt->close();
    return $owns;
}

/**
 * Redirect with a message
 */
function redirect_with_message($url, $message, $type = 'error') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}

/**
 * Display flash message
 */
function display_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'error';

        echo '<div class="alert alert-' . $type . '">' . $message . '</div>';

        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login to access page
 */
function require_login() {
    if (!is_logged_in()) {
        redirect_with_message('login.php', 'Please login to access this page.');
    }
}
?>
