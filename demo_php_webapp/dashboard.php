<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['device_id'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];
$device_id = $_SESSION['device_id'];

// Get the latest KWusage data for this device
$stmt = $conn->prepare("SELECT * FROM KWusage WHERE device_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $device_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $kw_usage = $row['KWusage'];
    $units_left = $row['UnitsLeft'];
    $last_update = date("F j, Y, g:i a", strtotime($row['timestamp']));
} else {
    $kw_usage = 0;
    $units_left = 0;
    $last_update = "N/A";
}

// Get last update ID from smart_meters
$stmt = $conn->prepare("SELECT last_update_id FROM smart_meters WHERE device_id = ?");
$stmt->bind_param("s", $device_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$last_update_id = $row['last_update_id'];

// Get recent top-up history
$stmt = $conn->prepare("SELECT * FROM updates WHERE device_id = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("s", $device_id);
$stmt->execute();
$topup_history = $stmt->get_result();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertex Smart Meters - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container dashboard">
        <header>
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="#6a0dad" />
                    <path d="M50 20 L80 50 L50 80 L20 50 Z" fill="white" />
                </svg>
                <h1>Vertex Smart Meters</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($name); ?></span>
                <a href="index.php" class="btn-logout">Logout</a>
            </div>
        </header>
        
        <main>
            <div class="meter-info">
                <h2>Meter Information</h2>
                <div class="info-card">
                    <div class="info-item">
                        <span class="label">Device ID:</span>
                        <span class="value"><?php echo htmlspecialchars($device_id); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Last Updated:</span>
                        <span class="value"><?php echo $last_update; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Current Usage:</span>
                        <span class="value"><?php echo $kw_usage; ?> KW</span>
                    </div>
                </div>
            </div>
            
            <div class="balance-section">
                <div class="balance-card">
                    <h3>Units Balance</h3>
                    <div class="balance-amount"><?php echo $units_left; ?></div>
                    <div class="units-label">Units</div>
                    <button id="topupBtn" class="btn-primary">Top Up Units</button>
                </div>
            </div>
            
            <div class="recent-history">
                <h2>Recent Top-up History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Units</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $topup_history->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['units']; ?> Units</td>
                                <td>
                                    <span class="status <?php echo $row['status']; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ($topup_history->num_rows == 0) { ?>
                            <tr>
                                <td colspan="3" class="no-data">No recent top-ups</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
        
        <!-- Top-up Modal -->
        <div id="topupModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Top Up Units</h2>
                <form id="topupForm">
                    <div class="form-group">
                        <label for="units">Number of Units</label>
                        <input type="number" id="units" name="units" min="1" required>
                    </div>
                    <button type="submit" class="btn-primary">Proceed to Payment</button>
                </form>
            </div>
        </div>
        
        <!-- Payment Simulation Modal -->
        <div id="paymentModal" class="modal">
            <div class="modal-content">
                <h2>Processing Payment</h2>
                <div class="loader"></div>
                <p>Please wait while we process your payment...</p>
            </div>
        </div>
        
        <!-- Success Modal -->
        <div id="successModal" class="modal">
            <div class="modal-content">
                <h2>Top-up Successful!</h2>
                <p>Your account has been credited with <span id="creditedUnits"></span> units.</p>
                <p>New balance: <span id="newBalance"></span> units</p>
                <button id="closeSuccessBtn" class="btn-primary">Close</button>
            </div>
        </div>
    </div>
    
    <script>
        // Store PHP variables for JavaScript use
        const deviceId = "<?php echo $device_id; ?>";
        const lastUpdateId = <?php echo $last_update_id; ?>;
        const currentUnits = <?php echo $units_left; ?>;
    </script>
    <script src="scripts.js"></script>
</body>
</html>