document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const topupBtn = document.getElementById('topupBtn');
    const topupModal = document.getElementById('topupModal');
    const paymentModal = document.getElementById('paymentModal');
    const successModal = document.getElementById('successModal');
    const closeBtn = document.querySelector('.close');
    const topupForm = document.getElementById('topupForm');
    const closeSuccessBtn = document.getElementById('closeSuccessBtn');
    const creditedUnitsSpan = document.getElementById('creditedUnits');
    const newBalanceSpan = document.getElementById('newBalance');
    
    // Open top-up modal
    topupBtn.addEventListener('click', function() {
        topupModal.style.display = 'block';
    });
    
    // Close modal when clicking the × button
    closeBtn.addEventListener('click', function() {
        topupModal.style.display = 'none';
    });
    
    // Close modal when clicking outside the modal
    window.addEventListener('click', function(event) {
        if (event.target === topupModal) {
            topupModal.style.display = 'none';
        }
        if (event.target === successModal) {
            successModal.style.display = 'none';
            location.reload(); // Refresh page to show updated balance
        }
    });
    
    // Handle top-up form submission
    topupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const unitsValue = document.getElementById('units').value;
        
        // Hide top-up modal and show payment simulation
        topupModal.style.display = 'none';
        paymentModal.style.display = 'block';
        
        // Simulate payment processing (3 seconds)
        setTimeout(function() {
            processTopUp(unitsValue);
        }, 3000);
    });
    
    // Close success modal
    closeSuccessBtn.addEventListener('click', function() {
        successModal.style.display = 'none';
        location.reload(); // Refresh page to show updated balance
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
                newBalanceSpan.textContent = data.new_balance;
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
});