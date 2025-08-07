<?php
session_start();
include 'conDB/head.php';
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light p-0">
    <div class="row w-100 flex-grow-1">
        <!-- Left image: hidden on screens <lg -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center p-0" style="background: url('images/car01.jpg') center center/cover no-repeat; min-height: 100vh;"><div class="w-100 h-100" style="background: rgba(0,0,0,0.5);"></div></div>
        <!-- Signup form: full width on mobile, centered on desktop -->
        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center bg-white" style="min-height: 100vh;">
            <div class="w-100 px-3 px-sm-4" style="max-width: 400px;">
                <div class="text-center mb-4">
                    <img src="images/splash_screenlogo.png" alt="MotorCarCare Logo" style="width: 100px;">
                    <h2 class="mt-3" style="color: #026dfe;">Create Account</h2>
                </div>

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
                    <div class="form-group mb-3">
                        <label for="username" class="form-label" style="color: #026dfe;">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" id="username" name="username" class="form-control" required value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label" style="color: #026dfe;">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone" class="form-label" style="color: #026dfe;">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" id="phone" name="phone" class="form-control" required pattern="^(05)[0-9]{8}$" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone'] ?? ''); ?>">
                        </div>
                        <small class="form-text text-muted">Format: 05XXXXXXXX (Saudi number)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label" style="color: #026dfe;">Password</label>
                        <div class="input-group" id="show_hide_password_signup">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" required minlength="6">
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePasswordSignup('password', 'toggleIconSignup1')"><i class="bi bi-eye" id="toggleIconSignup1"></i></span>
                        </div>
                        <small class="form-text text-muted">Minimum 6 characters</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="confirm_password" class="form-label" style="color: #026dfe;">Confirm Password</label>
                        <div class="input-group" id="show_hide_confirm_password_signup">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePasswordSignup('confirm_password', 'toggleIconSignup2')"><i class="bi bi-eye" id="toggleIconSignup2"></i></span>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="termsCheck">
                        <label class="form-check-label" for="termsCheck">
                            By signing up, you agree to our <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>.
                        </label>
                    </div>

                    <button type="submit" id="signupBtn" class="btn btn-primary w-100" style="background-color: #026dfe; border-color: #026dfe;" disabled>Sign Up</button>
                </form>
                <div class="text-center mt-3">
                    <span style="color: #7e7e7e;">Already have an account? <a href="Login_form.php" class="signup-link">Sign in</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms & Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms & Conditions & Site Instructions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="font-size: 0.98rem; text-align: left;">
        <strong>Important Instructions for Using MotorCarCare:</strong><br>
        <ul>
          <li><b>You cannot access the site or make any booking without logging in.</b></li>
          <li>All users must create an account and log in to use the platform's features.</li>
          <li>These instructions are provided to help you understand how to use the site efficiently and safely.</li>
        </ul>
        <hr>
        <strong>About MotorCarCare:</strong><br>
        Many car owners face difficulties when trying to book car maintenance services. Traditional methods, such as calling or visiting workshops, are time-consuming and often lead to confusion. Workshop managers also struggle with organizing appointments, tracking services, and managing customer information.<br><br>
        To solve this problem, we developed MotorCarCare, a web and mobile application that allows car owners to book services online, track their car's repair status, and receive automatic service reminders. Workshop managers can use the system to manage appointments, view service history, and organize daily operations more efficiently.<br><br>
        The system was built using PHP, HTML, CSS for the web interface, and Flutter with Firebase for the mobile version. These technologies ensured that the platform is fast, user-friendly, and accessible across devices.<br><br>
        Our testing showed that MotorCarCare improves service efficiency, reduces manual errors, and provides a better experience for both customers and workshop staff.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!-- Bootstrap JS (for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script type="module">
    import {checkUserExists, createUser} from './js/firebase-config.js';

    const form = document.getElementById('signupForm');
    const termsCheck = document.getElementById('termsCheck');
    const signupBtn = document.getElementById('signupBtn');

    // Enable/disable signup button based on checkbox
    termsCheck.addEventListener('change', function() {
        signupBtn.disabled = !this.checked;
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            username: form.username.value,
            email: form.email.value,
            phone: form.phone.value,
            password: form.password.value,
            confirm_password: form.confirm_password.value
        };

        try {
            // Basic validation
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
                    email: formData.email
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
<script>
function togglePasswordSignup(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

<?php include 'conDB/footer.php'; ?>
