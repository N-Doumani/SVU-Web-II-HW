document.addEventListener('DOMContentLoaded', function () {
    // 1. Validate password match on registration form
    const passwordInput = document.querySelector('#password');
    const confirmInput  = document.querySelector('#confirm_password');
    const registerForm  = document.querySelector('#registerForm');

    if (passwordInput && confirmInput) {
        const validatePasswordMatch = () => {
            if (confirmInput.value.length > 0 && passwordInput.value !== confirmInput.value) {
                if (window.UI && typeof UI.setFieldError === 'function') {
                    UI.setFieldError('#confirm_password', 'Passwords do not match');
                } else {
                    confirmInput.setCustomValidity('Passwords do not match');
                }
                return false;
            } else {
                if (window.UI && typeof UI.clearFieldError === 'function') {
                    UI.clearFieldError('#confirm_password', confirmInput.value.length >= 8);
                } else {
                    confirmInput.setCustomValidity('');
                }
                return true;
            }
        };

        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmInput.addEventListener('input', validatePasswordMatch);

        if (registerForm) {
            registerForm.addEventListener('submit', function (e) {
                if (!validatePasswordMatch()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }
    }

// 2. Real-time AJAX email validation for student forms
    const emailInput  = document.querySelector('input[name="email"]');
    const studentForm = document.querySelector('form[enctype="multipart/form-data"]');

    if (emailInput && studentForm) {
        let debounceTimer;

        emailInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const email = emailInput.value.trim();
            const urlParams = new URLSearchParams(window.location.search);
            const studentId = urlParams.get('id') || '';

            if (!email || !email.includes('@')) {
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`check_email.php?email=${encodeURIComponent(email)}&id=${encodeURIComponent(studentId)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            if (window.UI && typeof UI.setFieldError === 'function') {
                                UI.setFieldError('#email', 'This email is already used');
                            } else {
                                emailInput.setCustomValidity('This email is already used');
                            }
                        } else {
                            if (window.UI && typeof UI.clearFieldError === 'function') {
                                UI.clearFieldError('#email', true);
                            } else {
                                emailInput.setCustomValidity('');
                            }
                        }
                    })
                    .catch(err => console.error('AJAX validation error:', err));
            }, 400);
        });
    }
});