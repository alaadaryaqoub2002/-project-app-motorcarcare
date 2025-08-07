<?php
session_start();
include 'conDB/head.php';
?>

<div class="hero-wrap" style="display: flex; height: 1050px; margin: 100px 0; overflow: hidden;">
    <div class="image-container"
         style="flex: 0.7; background-image: url('images/car-5.jpg'); background-size: cover; background-position: center; position: relative;">
        <div class="overlay"
             style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
    </div>
    <div class="login-container"
         style="flex: 1; padding: 30px; display: flex; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 400px; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); background-color: rgba(255, 255, 255, 0.9);">
            <h2 style="color: #026dfe;">Create Company Account</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <form id="signupForm" class="form">
                <div class="form-group">
                    <label for="company_name" style="color: #026dfe;">Company Name</label>
                    <input type="text" id="company_name" name="company_name" class="form-control" required
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['company_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="username" style="color: #026dfe;">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email" style="color: #026dfe;">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="phone" style="color: #026dfe;">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required pattern="^(05)[0-9]{8}$"
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['phone'] ?? ''); ?>">
                    <small class="form-text text-muted">Format: 05XXXXXXXX (Saudi number)</small>
                </div>

                <div class="form-group">
                    <label for="password" style="color: #026dfe;">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    <small class="form-text text-muted">Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" style="color: #026dfe;">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms" style="color: #026dfe;">
                        I agree with the <a href="terms.php" style="color: #026dfe;">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block"
                        style="background-color: #026dfe; border-color: #026dfe;">Sign Up
                </button>
            </form>
            <div class="text-center mt-3">
                <span style="color: #7e7e7e;">Already have an account? <a href="Login_form.php" class="signup-link">Sign in</a></span>
            </div>
        </div>
    </div>
</div>

<?php include 'conDB/footer.php'; ?>

<script type="module">
    import {checkUserExists, createUser} from './js/firebase-config.js';

    const form = document.getElementById('signupForm');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            company_name: form.company_name.value,
            username: form.username.value,
            email: form.email.value,
            phone: form.phone.value,
            password: form.password.value,
            confirm_password: form.confirm_password.value,
            role: 'company' // Set the role as company
        };

        try {
            // Basic validation
            if (!form.terms.checked) {
                throw new Error('You must agree to the Terms & Conditions');
            }

            if (formData.password !== formData.confirm_password) {
                throw new Error('Passwords do not match');
            }

            if (formData.password.length < 6) {
                throw new Error('Password must be at least 6 characters');
            }

            if (!formData.phone.match(/^(05)[0-9]{8}$/)) {
                throw new Error('Invalid phone number format');
            }

            // Check if user exists
            await checkUserExists(formData.email, formData.username);

            // Create user in Firebase
            const {confirm_password, ...userData} = formData;
            const userRef = await createUser(userData);

            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'alert alert-success';
            successDiv.textContent = 'Registration successful! Redirecting to dashboard...';
            form.insertBefore(successDiv, form.firstChild);

            // Send data to PHP for session handling
            const response = await fetch('handle_signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    userId: userRef.id,
                    username: formData.username,
                    email: formData.email,
                    role: 'company',
                    company_name: formData.company_name
                })
            });

            if (!response.ok) {
                throw new Error('Failed to create session');
            }

            const result = await response.json();
            if (result.success) {
                // Disable form
                form.querySelectorAll('input, button').forEach(element => {
                    element.disabled = true;
                });

                // Redirect after showing message
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 2000);
            } else {
                throw new Error(result.error || 'Failed to create session');
            }

        } catch (error) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = error.message;
            form.insertBefore(errorDiv, form.firstChild);

            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }
    });
</script>
