<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
require_login();

// Process top-up request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Parse the JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($data['units']) || !is_numeric($data['units'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid units value.']);
        exit();
    }

    $units = intval($data['units']);

    // Check if active meter is set
    if (!isset($_SESSION['active_meter'])) {
        echo json_encode(['success' => false, 'message' => 'No active meter selected.']);
        exit();
    }

    $device_id = $_SESSION['active_meter'];
    $user_id = $_SESSION['user_id'];

    // Check if user owns this meter
    if (!user_owns_meter($conn, $user_id, $device_id)) {
        echo json_encode(['success' => false, 'message' => 'You do not have permission to top up this meter.']);
        exit();
    }

    // Get the current last_update_id
    $stmt = $conn->prepare("SELECT last_update_id FROM smart_meters WHERE device_id = ?");
    $stmt->bind_param("s", $device_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_update_id = $row['last_update_id'];
        $new_update_id = $last_update_id + 1;

        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert new update record with current timestamp
            $stmt = $conn->prepare("INSERT INTO updates (device_id, units, update_id, status, time_stamp) VALUES (?, ?, ?, 'pending', NOW())");
            $stmt->bind_param("sii", $device_id, $units, $new_update_id);
            $stmt->execute();

            // Update the last_update_id in smart_meters
            $stmt = $conn->prepare("UPDATE smart_meters SET last_update_id = ? WHERE device_id = ?");
            $stmt->bind_param("is", $new_update_id, $device_id);
            $stmt->execute();

            // Get the current units left
            $stmt = $conn->prepare("SELECT UnitsLeft FROM KWusage WHERE device_id = ? ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("s", $device_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $current_units = $row['UnitsLeft'];

            // Get total pending units
            $pending_units = get_pending_units($conn, $device_id);

            // Commit transaction
            $conn->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Top-up successful',
                'units_added' => $units,
                'current_balance' => $current_units,
                'pending_balance' => $pending_units
            ]);

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Meter not found.']);
    }

    $stmt->close();
} else {
    // Not a POST request
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>
