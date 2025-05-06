<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $device_id = htmlspecialchars($_POST['device_id']);
    
    // Validate device ID exists in the database
    $stmt = $conn->prepare("SELECT * FROM smart_meters WHERE device_id = ?");
    $stmt->bind_param("s", $device_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Device exists, create session and redirect to dashboard
        $_SESSION['name'] = $name;
        $_SESSION['device_id'] = $device_id;
        header("Location: dashboard.php");
        exit();
    } else {
        // Device doesn't exist
        $_SESSION['login_error'] = "Device ID not found. Please check and try again.";
        header("Location: index.php");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>