<?php
session_start();
require_once 'db_connect.php';
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['device_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $device_id = $_SESSION['device_id'];
    $units = intval($data['units']);
    
    // Validate input
    if ($units <= 0) {
        echo json_encode(['success' => false, 'message' => 'Units must be greater than 0']);
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
            // Insert new update record
            $stmt = $conn->prepare("INSERT INTO updates (device_id, units, update_id, status) VALUES (?, ?, ?, 'completed')");
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
            $new_units = $current_units + $units;
            
            // Insert new KWusage record with updated units
            $timestamp = date('Y-m-d\TH:i:sP');
            $kw_usage = 0.092; // Default value or can be retrieved from last record
            
            $stmt = $conn->prepare("INSERT INTO KWusage (device_id, timestamp, KWusage, UnitsLeft) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssdi", $device_id, $timestamp, $kw_usage, $new_units);
            $stmt->execute();
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Top-up successful', 
                'units_added' => $units,
                'new_balance' => $new_units
            ]);
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Device not found']);
    }
    
    $stmt->close();
}

$conn->close();
?>