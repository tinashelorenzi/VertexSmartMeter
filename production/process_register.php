<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize user input
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $name = sanitize_input($_POST['name']);
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : null;

    // Validate input
    $errors = array();

    // Validate username
    if (!is_valid_username($username)) {
        $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores.";
    }

    // Validate password
    if (!is_valid_password($password)) {
        $errors[] = "Password must be at least 8 characters and include uppercase, lowercase, and numbers.";
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Username already exists. Please choose another one.";
    }
    $stmt->close();

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hashed_password, $name, $email);
            $stmt->execute();
            $user_id = $conn->insert_id;
            $stmt->close();

            // If device_id was provided, check if it exists and add it to user_meters
            if (isset($_POST['device_id']) && !empty($_POST['device_id'])) {
                $device_id = sanitize_input($_POST['device_id']);

                // Check if device exists
                $stmt = $conn->prepare("SELECT id FROM smart_meters WHERE device_id = ?");
                $stmt->bind_param("s", $device_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Device exists, add to user_meters
                    $stmt->close();

                    $stmt = $conn->prepare("INSERT INTO user_meters (user_id, device_id, nickname) VALUES (?, ?, ?)");
                    $nickname = "My Meter"; // Default nickname
                    $stmt->bind_param("iss", $user_id, $device_id, $nickname);
                    $stmt->execute();
                    $stmt->close();
                }
                else {
                    $stmt->close();
                }
            }

            // Commit transaction
            $conn->commit();

            // Set session variables and redirect to dashboard
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $name;

            // Success message
            redirect_with_message('dashboard.php', 'Registration successful! Welcome to Vertex Smart Meters.', 'success');

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            redirect_with_message('register.php', 'Registration failed: ' . $e->getMessage());
        }
    } else {
        // Store errors in session and redirect back to register form
        $_SESSION['register_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: register.php");
        exit();
    }
} else {
    // If not a POST request, redirect to register form
    header("Location: register.php");
    exit();
}

$conn->close();
?>
