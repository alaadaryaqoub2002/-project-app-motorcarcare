<?php
session_start();
include 'conDB/head.php';
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light p-0">
    <div class="row w-100">
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center p-0" style="background: url('images/car01.jpg') center center/cover no-repeat; min-height: 100vh;">
            <div class="w-100 h-100" style="background: rgba(0,0,0,0.5);"></div>
        </div>
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white" style="min-height: 100vh;">
            <div class="w-100" style="max-width: 400px;">
                <div class="text-center mb-4">
                    <img src="images/splash_screenlogo.png" alt="MotorCarCare Logo" style="width: 100px;">
                    <h2 class="mt-3" style="color: #026dfe;">Login</h2>
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

                <form id="loginForm" class="form">
                    <div class="form-group mb-3">
                        <label for="email" class="form-label" style="color: #026dfe;">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" required placeholder="example@email.com">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label" style="color: #026dfe;">Password</label>
                        <div class="input-group" id="show_hide_password">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" required placeholder="********">
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()"><i class="bi bi-eye" id="toggleIcon"></i></span>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="termsCheck">
                        <label class="form-check-label" for="termsCheck">
                            By logging in, you agree to our <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>.
                        </label>
                    </div>

                    <button type="submit" id="loginBtn" class="btn btn-primary w-100" style="background-color: #026dfe; border-color: #026dfe;" disabled>Login</button>
                </form>
                <div class="text-center mt-3">
                    <span style="color: #7e7e7e;">Don't have an account? <a href="Signup_form.php" class="signup-link">Sign up</a></span>
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
    import {loginUser} from './js/firebase-config.js';

    const form = document.getElementById('loginForm');
    const termsCheck = document.getElementById('termsCheck');
    const loginBtn = document.getElementById('loginBtn');

    // Enable/disable login button based on checkbox
    termsCheck.addEventListener('change', function() {
        loginBtn.disabled = !this.checked;
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Remove previous alerts
        form.querySelectorAll('.alert').forEach(el => el.remove());

        try {
            const userData = await loginUser(form.email.value, form.password.value);

            // Send data to PHP for session handling
            const response = await fetch('handle_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    userId: userData.userId,
                    username: userData.username,
                    email: userData.email
                })
            });

            if (!response.ok) {
                throw new Error('Failed to create session');
            }

            const result = await response.json();
            if (result.success) {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success';
                successDiv.textContent = 'Login successful! Redirecting...';
                form.insertBefore(successDiv, form.firstChild);

                // Disable form
                form.querySelectorAll('input, button').forEach(element => {
                    element.disabled = true;
                });

                // Redirect after showing message
                setTimeout(() => {
                    window.location.href = 'user-appointment.php';
                }, 2000);
            } else {
                throw new Error(result.error || 'Login failed');
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
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
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
