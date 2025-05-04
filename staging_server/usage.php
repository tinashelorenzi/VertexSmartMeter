<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "vertexcg_vsmxxps", "Arj1V54qWWSbChSN1zY*", "vertexcg_VSM_data");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    file_put_contents('debug.log', "KWusage - Database connection failed: " . $conn->connect_error . "\n", FILE_APPEND);
    die(json_encode(["error" => "Database connection failed"]));
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$device_id = $data['device_id'] ?? '';
$timestamp = $data['timestamp'] ?? 'unknown';
$usage = isset($data['usage']) ? floatval($data['usage']) : 0.0;

// Authenticate using custom header
$authToken = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
file_put_contents('debug.log', "KWusage - Received X-Auth-Token: '$authToken'\n", FILE_APPEND);
if ($authToken !== "hs=p@8Cr%*a5") {
    file_put_contents('debug.log', "KWusage - Authentication failed. Expected: 'hs=p@8Cr%*a5', Received: '$authToken'\n", FILE_APPEND);
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized"]));
}

// Log successful authentication
file_put_contents('debug.log', "KWusage - Authentication successful for device: $device_id\n", FILE_APPEND);

// Validate device_id
if (empty($device_id)) {
    file_put_contents('debug.log', "KWusage - Invalid device_id\n", FILE_APPEND);
    http_response_code(400);
    die(json_encode(["error" => "Invalid device_id"]));
}

// Insert usage data
$sql = "INSERT INTO KWusage (device_id, timestamp, KWusage) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents('debug.log', "KWusage - SQL prepare failed: " . $conn->error . "\n", FILE_APPEND);
    http_response_code(500);
    die(json_encode(["error" => "SQL prepare failed"]));
}
$stmt->bind_param("ssd", $device_id, $timestamp, $usage);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    file_put_contents('debug.log', "KWusage - Recorded for device: $device_id, timestamp: $timestamp, KWusage: " . number_format($usage, 3) . " kWh\n", FILE_APPEND);
    echo json_encode(["message" => "Usage recorded"]);
} else {
    file_put_contents('debug.log', "KWusage - Failed to record for device: $device_id\n", FILE_APPEND);
    http_response_code(500);
    die(json_encode(["error" => "Failed to record usage"]));
}

$stmt->close();
$conn->close();
?>

