document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const topupModal = document.getElementById('topupModal');
    const paymentModal = document.getElementById('paymentModal');
    const successModal = document.getElementById('successModal');
    const addMeterModal = document.getElementById('addMeterModal');

    // Buttons and form elements
    const topupBtn = document.getElementById('topupBtn');
    const closeTopupBtn = topupModal ? topupModal.querySelector('.close') : null;
    const topupForm = document.getElementById('topupForm');
    const closeSuccessBtn = document.getElementById('closeSuccessBtn');
    const addMeterBtn = document.getElementById('addMeterBtn');
    const addFirstMeterBtn = document.getElementById('addFirstMeterBtn');
    const closeAddMeterBtn = addMeterModal ? addMeterModal.querySelector('.close') : null;

    // Elements for displaying top-up info
    const creditedUnitsSpan = document.getElementById('creditedUnits');
    const currentBalanceSpan = document.getElementById('currentBalance');
    const pendingBalanceSpan = document.getElementById('pendingBalance');

    // Event Listeners

    // Top-up button
    if (topupBtn) {
        topupBtn.addEventListener('click', function() {
            topupModal.style.display = 'block';
        });
    }

    // Close top-up modal
    if (closeTopupBtn) {
        closeTopupBtn.addEventListener('click', function() {
            topupModal.style.display = 'none';
        });
    }

    // Process top-up form
    if (topupForm) {
        topupForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const unitsValue = document.getElementById('units').value;

            // Validate input
            if (unitsValue <= 0) {
                alert('Please enter a valid number of units.');
                return;
            }

            // Hide top-up modal and show payment processing
            topupModal.style.display = 'none';
            paymentModal.style.display = 'block';

            // Simulate payment processing (3 seconds)
            setTimeout(function() {
                processTopUp(unitsValue);
            }, 3000);
        });
    }

    // Close success modal
    if (closeSuccessBtn) {
        closeSuccessBtn.addEventListener('click', function() {
            successModal.style.display = 'none';
            location.reload(); // Refresh page to show updated balance
        });
    }

    // Add meter button
    if (addMeterBtn) {
        addMeterBtn.addEventListener('click', function() {
            addMeterModal.style.display = 'block';
        });
    }

    // Add first meter button (empty state)
    if (addFirstMeterBtn) {
        addFirstMeterBtn.addEventListener('click', function() {
            addMeterModal.style.display = 'block';
        });
    }

    // Close add meter modal
    if (closeAddMeterBtn) {
        closeAddMeterBtn.addEventListener('click', function() {
            addMeterModal.style.display = 'none';
        });
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === topupModal) {
            topupModal.style.display = 'none';
        }
        if (e.target === successModal) {
            successModal.style.display = 'none';
            location.reload();
        }
        if (e.target === addMeterModal) {
            addMeterModal.style.display = 'none';
        }
    });

    // Process top-up AJAX request
    function processTopUp(units) {
        fetch('process_topup.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                units: units
            })
        })
        .then(response => response.json())
        .then(data => {
            paymentModal.style.display = 'none';

            if (data.success) {
                creditedUnitsSpan.textContent = data.units_added;
                currentBalanceSpan.textContent = data.current_balance;
                pendingBalanceSpan.textContent = data.pending_balance;
                successModal.style.display = 'block';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            paymentModal.style.display = 'none';
            alert('Error processing top-up: ' + error);
        });
    }

    // Meter dropdown functionality
    const meterSelectorBtn = document.querySelector('.meter-selector-btn');
    const meterDropdown = document.querySelector('.meter-dropdown-content');

    if (meterSelectorBtn && meterDropdown) {
        meterSelectorBtn.addEventListener('click', function() {
            meterDropdown.style.display = meterDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!meterSelectorBtn.contains(e.target) && !meterDropdown.contains(e.target)) {
                meterDropdown.style.display = 'none';
            }
        });
    }
});
