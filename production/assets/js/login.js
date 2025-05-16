document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    const registerForm = document.querySelector('form');

    if (passwordField && confirmPasswordField && registerForm) {
        // Add input event to confirm password field
        confirmPasswordField.addEventListener('input', function() {
            validatePasswordMatch();
        });

        // Add submit event to form
        registerForm.addEventListener('submit', function(e) {
            if (!validatePasswordMatch()) {
                e.preventDefault();
            }
        });

        // Validate password match
        function validatePasswordMatch() {
            if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordField.setCustomValidity('Passwords do not match');
                return false;
            } else {
                confirmPasswordField.setCustomValidity('');
                return true;
            }
        }
    }
});
