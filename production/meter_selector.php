<?php
$page_title = 'Select Meter';
$show_header = true;
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

// Check if user is logged in
require_login();

$user_id = $_SESSION['user_id'];

// Get user's meters
$meters = get_user_meters($conn, $user_id);

// If no meters, redirect to dashboard with a message
if (empty($meters)) {
    redirect_with_message('dashboard.php', 'No smart meters found in your account. Please add a meter.', 'warning');
}

// If only one meter, set it as active and redirect to dashboard
if (count($meters) == 1) {
    $_SESSION['active_meter'] = $meters[0]['device_id'];
    header("Location: dashboard.php");
    exit();
}
?>

<div class="meter-selector-container">
    <div class="section-header">
        <h2>Select a Smart Meter</h2>
        <p>Choose which meter you want to manage:</p>
    </div>

    <div class="meters-grid">
        <?php foreach ($meters as $meter): ?>
            <?php
                // Get meter info
                $meter_info = get_meter_info($conn, $meter['device_id']);
                $pending_units = get_pending_units($conn, $meter['device_id']);
            ?>
            <div class="meter-card" data-meter-id="<?php echo htmlspecialchars($meter['device_id']); ?>">
                <div class="meter-header">
                    <h3><?php echo htmlspecialchars($meter['nickname'] ?? 'My Meter'); ?></h3>
                    <span class="meter-id"><?php echo htmlspecialchars($meter['device_id']); ?></span>
                </div>

                <div class="meter-info">
                    <div class="info-item">
                        <span class="label">Units Left:</span>
                        <span class="value"><?php echo $meter_info['UnitsLeft']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Current Usage:</span>
                        <span class="value"><?php echo $meter_info['KWusage']; ?> KW</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Pending Units:</span>
                        <span class="value"><?php echo $pending_units; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Last Updated:</span>
                        <span class="value"><?php echo date("j M Y, g:i a", strtotime($meter_info['timestamp'])); ?></span>
                    </div>
                </div>

                <button class="btn btn-primary select-meter-btn">Select This Meter</button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="add-meter-section">
        <button id="addMeterBtn" class="btn btn-secondary">Add New Meter</button>
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
$extra_js = '<script src="assets/js/meter-selector.js"></script>';
require_once 'includes/footer.php';
?>
