<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "vertexcg_vsmxxps", "Arj1V54qWWSbChSN1zY*", "vertexcg_VSM_data");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$device_id = $data['device_id'] ?? '';
$update_id = $data['update_id'] ?? 0;

// Authenticate using custom header
$authToken = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
file_put_contents('debug.log', "Confirm - Received X-Auth-Token: '$authToken'\n", FILE_APPEND); // Debug log with quotes
if ($authToken !== "hs=p@8Cr%*a5") {
    file_put_contents('debug.log', "Confirm - Authentication failed. Expected: 'hs=p@8Cr%*a5', Received: '$authToken'\n", FILE_APPEND);
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized"]));
}

// Log successful authentication
file_put_contents('debug.log', "Confirm - Authentication successful for device: $device_id, update_id: $update_id\n", FILE_APPEND);

// Mark the update as completed
$sql = "UPDATE updates SET status = 'completed' WHERE device_id = ? AND update_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents('debug.log', "Confirm - SQL prepare failed: " . $conn->error . "\n", FILE_APPEND);
    http_response_code(500);
    die(json_encode(["error" => "SQL prepare failed"]));
}
$stmt->bind_param("si", $device_id, $update_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    file_put_contents('debug.log', "Confirm - Update $update_id marked as completed for device: $device_id\n", FILE_APPEND);
    echo json_encode(["message" => "Update confirmed"]);
} else {
    file_put_contents('debug.log', "Confirm - Update $update_id not found or already confirmed for device: $device_id\n", FILE_APPEND);
    http_response_code(404);
    echo json_encode(["error" => "Update not found or already confirmed"]);
}

$stmt->close();
$conn->close();
?>
