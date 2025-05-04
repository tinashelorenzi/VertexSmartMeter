<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "vertexcg_vsmxxps", "Arj1V54qWWSbChSN1zY*", "vertexcg_VSM_data");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    file_put_contents('debug.log', "Database connection failed: " . $conn->connect_error . "\n", FILE_APPEND);
    die(json_encode(["error" => "Database connection failed"]));
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$device_id = $data['device_id'] ?? '';
$last_update_id = $data['last_update_id'] ?? 0;

// Authenticate using custom header
$authToken = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
file_put_contents('debug.log', "Received X-Auth-Token: '$authToken'\n", FILE_APPEND);
if ($authToken !== "hs=p@8Cr%*a5") {
    file_put_contents('debug.log', "Authentication failed. Expected: 'hs=p@8Cr%*a5', Received: '$authToken'\n", FILE_APPEND);
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized"]));
}

// Log successful authentication
file_put_contents('debug.log', "Authentication successful for device: $device_id\n", FILE_APPEND);

// Find the next pending update
$sql = "SELECT * FROM updates WHERE device_id = ? AND update_id > ? AND status = 'pending' ORDER BY update_id ASC LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents('debug.log', "SQL prepare failed: " . $conn->error . "\n", FILE_APPEND);
    http_response_code(500);
    die(json_encode(["error" => "SQL prepare failed"]));
}
$stmt->bind_param("si", $device_id, $last_update_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $update = $result->fetch_assoc();
    $response = [
        "update_id" => $update['update_id'],
        "units" => $update['units'],
        "checksum" => md5(json_encode($update)) // Simplified checksum
    ];

    // Update last_update_id in smart_meters table
    $update_sql = "UPDATE smart_meters SET last_update_id = ? WHERE device_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        file_put_contents('debug.log', "SQL prepare failed for last_update_id update: " . $conn->error . "\n", FILE_APPEND);
        http_response_code(500);
        die(json_encode(["error" => "SQL prepare failed for last_update_id update"]));
    }
    $update_stmt->bind_param("is", $update['update_id'], $device_id);
    $update_stmt->execute();
    if ($update_stmt->affected_rows > 0) {
        file_put_contents('debug.log', "Updated last_update_id to " . $update['update_id'] . " for device: $device_id\n", FILE_APPEND);
    } else {
        file_put_contents('debug.log', "Failed to update last_update_id for device: $device_id\n", FILE_APPEND);
    }
    $update_stmt->close();

    file_put_contents('debug.log', "Sending update: " . json_encode($response) . "\n", FILE_APPEND);
    echo json_encode($response);
} else {
    file_put_contents('debug.log', "No updates available for device: $device_id\n", FILE_APPEND);
    echo json_encode(["message" => "No updates available"]);
}

$stmt->close();
$conn->close();
?>