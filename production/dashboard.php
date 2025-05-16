<?php
$page_title = 'Dashboard';
$show_header = true;
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

// Check if user is logged in
require_login();

$user_id = $_SESSION['user_id'];

// Get user's meters
$meters = get_user_meters($conn, $user_id);

// If no meters, show a message
if (empty($meters)) {
    echo '<div class="no-meters-container">';
    echo '<div class="empty-state">';
    echo '<h2>No Smart Meters Found</h2>';
    echo '<p>You don\'t have any smart meters added to your account yet.</p>';
    echo '<button id="addFirstMeterBtn" class="btn btn-primary">Add Your First Meter</button>';
    echo '</div>';
    echo '</div>';

    // Add Meter Modal
    echo '<div id="addMeterModal" class="modal">';
    echo '<div class="modal-content">';
    echo '<span class="close">&times;</span>';
    echo '<h2>Add New Meter</h2>';
    echo '<form id="addMeterForm" action="process_add_meter.php" method="post">';
    echo '<div class="form-group">';
    echo '<label for="device_id">Meter ID</label>';
    echo '<input type="text" id="device_id" name="device_id" required placeholder="Enter meter ID">';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="nickname">Nickname (Optional)</label>';
    echo '<input type="text" id="nickname" name="nickname" placeholder="E.g. Home, Office, Rental Property">';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Add Meter</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';

    $extra_js = '<script src="assets/js/dashboard.js"></script>';
    require_once 'includes/footer.php';
    exit();
}

// Check if active meter is set, otherwise redirect to meter selector
if (!isset($_SESSION['active_meter'])) {
    if (count($meters) > 1) {
        header("Location: meter_selector.php");
        exit();
    } else {
        $_SESSION['active_meter'] = $meters[0]['device_id'];
    }
}

$device_id = $_SESSION['active_meter'];

// Check if user owns this meter
if (!user_owns_meter($conn, $user_id, $device_id)) {
    redirect_with_message('meter_selector.php', 'You do not have permission to access this meter.', 'error');
}

// Get meter information
$meter_info = get_meter_info($conn, $device_id);
$pending_units = get_pending_units($conn, $device_id);
$topup_history = get_topup_history($conn, $device_id, 5);

// Get meter nickname
$stmt = $conn->prepare("SELECT nickname FROM user_meters WHERE user_id = ? AND device_id = ?");
$stmt->bind_param("is", $user_id, $device_id);
$stmt->execute();
$result = $stmt->get_result();
$meter_nickname = ($result->num_rows > 0) ? $result->fetch_assoc()['nickname'] : 'My Meter';
$stmt->close();
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="meter-selector-dropdown">
            <button class="meter-selector-btn">
                <?php echo htmlspecialchars($meter_nickname); ?>
                <span class="meter-id">(<?php echo htmlspecialchars($device_id); ?>)</span>
                <span class="dropdown-icon">▼</span>
            </button>
            <div class="meter-dropdown-content">
                <?php foreach ($meters as $meter): ?>
                    <?php if ($meter['device_id'] != $device_id): ?>
                        <a href="switch_meter.php?meter=<?php echo urlencode($meter['device_id']); ?>">
                            <?php echo htmlspecialchars($meter['nickname'] ?? 'My Meter'); ?>
                            <span class="meter-id">(<?php echo htmlspecialchars($meter['device_id']); ?>)</span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <a href="meter_selector.php" class="view-all-meters">View All Meters</a>
            </div>
        </div>

        <div class="dashboard-actions">
            <button id="addMeterBtn" class="btn btn-outline">Add New Meter</button>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="main-card meter-status">
            <div class="card-header">
                <h2>Meter Status</h2>
                <span class="last-updated">Last updated: <?php echo date("j M Y, g:i a", strtotime($meter_info['timestamp'])); ?></span>
            </div>

            <div class="meter-status-content">
                <div class="meter-gauge">
                    <div class="gauge-value"><?php echo $meter_info['UnitsLeft']; ?></div>
                    <div class="gauge-label">Units Left</div>
                    <?php if ($pending_units > 0): ?>
                        <div class="pending-units">+<?php echo $pending_units; ?> units pending</div>
                    <?php endif; ?>
                </div>

                <div class="meter-details">
                    <div class="detail-item">
                        <span class="label">Current Usage:</span>
                        <span class="value"><?php echo $meter_info['KWusage']; ?> KW</span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Device ID:</span>
                        <span class="value"><?php echo htmlspecialchars($device_id); ?></span>
                    </div>

                    <button id="topupBtn" class="btn btn-primary">Top Up Units</button>
                </div>
            </div>
        </div>

        <div class="secondary-cards">
            <div class="card recent-history">
                <div class="card-header">
                    <h2>Recent Top-up History</h2>
                </div>

                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Units</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($topup_history) > 0): ?>
                            <?php foreach ($topup_history as $record): ?>
                                <tr>
                                    <td><?php echo date("j M Y, g:i a", strtotime($record['time_stamp'])); ?></td>
                                    <td class="<?php echo ($record['units'] < 0) ? 'negative' : 'positive'; ?>">
                                        <?php echo ($record['units'] >= 0) ? '+' : ''; ?><?php echo $record['units']; ?> units
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $record['status']; ?>">
                                            <?php echo ucfirst($record['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="no-data">No recent top-ups</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card usage-stats">
                <div class="card-header">
                    <h2>Usage Statistics</h2>
                </div>

                <div class="stats-content">
                    <p class="coming-soon">Detailed usage statistics coming soon!</p>
                </div>
            </div>
        </div>
    </div>
</div>

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

            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
    </div>
</div>

<!-- Payment Simulation Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content payment-processing">
        <h2>Processing Payment</h2>
        <div class="loader"></div>
        <p>Please wait while we process your payment...</p>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content success-content">
        <h2>Top-up Successful!</h2>
        <div class="success-icon">✓</div>
        <p>Your account has been credited with <span id="creditedUnits"></span> units.</p>
        <p>Current balance: <span id="currentBalance"></span> units</p>
        <p>Pending balance: <span id="pendingBalance"></span> units</p>
        <button id="closeSuccessBtn" class="btn btn-primary">Close</button>
    </div>
</div>

<!-- Add Meter Modal -->
<div id="addMeterModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Meter</h2>

        <form id="addMeterForm" action="process_add_meter.php" method="post">
            <div class="form-group">
                <label for="device_id">Meter ID</label>
                <input type="text" id="device_id" name="device_id" required placeholder="Enter meter ID">
            </div>

            <div class="form-group">
                <label for="nickname">Nickname (Optional)</label>
                <input type="text" id="nickname" name="nickname" placeholder="E.g. Home, Office, Rental Property">
            </div>

            <button type="submit" class="btn btn-primary">Add Meter</button>
        </form>
    </div>
</div>

<?php
// Include JavaScript for dashboard functionality
$extra_js = '<script src="assets/js/dashboard.js"></script>';
require_once 'includes/footer.php';
?>
