document.addEventListener('DOMContentLoaded', function() {
    // Get all meter cards
    const meterCards = document.querySelectorAll('.meter-card');

    // Add meter modal elements
    const addMeterModal = document.getElementById('addMeterModal');
    const addMeterBtn = document.getElementById('addMeterBtn');
    const closeAddMeterBtn = addMeterModal ? addMeterModal.querySelector('.close') : null;

    // Add click event to each meter card
    meterCards.forEach(card => {
        const selectBtn = card.querySelector('.select-meter-btn');
        const meterId = card.dataset.meterId;

        // If select button exists, add click event
        if (selectBtn) {
            selectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                selectMeter(meterId);
            });
        }

        // Add click event to the entire card
        card.addEventListener('click', function(e) {
            // Only trigger if the click wasn't on the button
            if (!e.target.closest('.select-meter-btn')) {
                selectMeter(meterId);
            }
        });
    });

    // Add new meter button
    if (addMeterBtn) {
        addMeterBtn.addEventListener('click', function() {
            addMeterModal.style.display = 'block';
        });
    }

    // Close add meter modal
    if (closeAddMeterBtn) {
        closeAddMeterBtn.addEventListener('click', function() {
            addMeterModal.style.display = 'none';
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === addMeterModal) {
            addMeterModal.style.display = 'none';
        }
    });

    // Function to redirect to meter page
    function selectMeter(meterId) {
        window.location.href = 'switch_meter.php?meter=' + encodeURIComponent(meterId);
    }
});
