
<?php
session_start();
if (isset($_SESSION['company_user'])) {
    header('Location: company_dashboard.php');
    exit();
}
include 'conDB/head.php';
?>

<div class="hero-wrap" style="display: flex; height: 100vh; overflow: hidden;">
    <div class="image-container" style="flex: 0.7; background-image: url('images/car-5.jpg'); background-size: cover; background-position: center; position: relative;">
        <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
    </div>
    <div class="login-container" style="flex: 1; padding: 30px; display: flex; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 400px; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); background-color: rgba(255, 255, 255, 0.95);">
            <div class="text-center mb-3">
                <img src="images/splash_screenlogo.png" alt="MotorCarCare Logo" style="width: 100px;">
            </div>
            <h2 style="color: #026dfe; text-align: center;">Company Login</h2>

            <div id="alertContainer"></div>
            <div id="loadingSpinner" class="loading-spinner" style="display: none;"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email" style="color: #026dfe;">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password" style="color: #026dfe;">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-login btn-block" style="background-color: #026dfe; border-color: #026dfe; color: white; border-radius: 5px; font-size: 16px;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div class="text-center mt-3">
                <span style="color: #7e7e7e;">Don't have an account? <a href="company_signup.php" class="signup-link" style="color: #026dfe;">Signup Now</a></span>
            </div>
        </div>
    </div>
</div>

<style>
.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #f3f3f3;
    border-radius: 50%;
    border-top: 3px solid #026dfe;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script type="module">
import { signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";
import { auth } from './js/firebase-config.js';

const loginForm = document.getElementById('loginForm');
const loadingSpinner = document.getElementById('loadingSpinner');
const alertContainer = document.getElementById('alertContainer');

function showAlert(message, type = 'success') {
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
}

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    loadingSpinner.style.display = 'block';

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        // Sign in with Firebase Auth
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        // Store user data in session via PHP
        const response = await fetch('handle_company_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                userId: user.uid,
                email: user.email,
                username: user.email.split('@')[0], // Use email prefix as username
                role: 'company'
            })
        });

        const result = await response.json();
        if (result.success) {
            showAlert('Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = 'company_dashboard.php';
            }, 1500);
        } else {
            throw new Error(result.error || 'Failed to create session');
        }
    } catch (error) {
        console.error('Login error:', error);
        showAlert(error.message || 'Login failed. Please check your credentials.', 'danger');
    } finally {
        loadingSpinner.style.display = 'none';
    }
});
</script>

<?php include 'conDB/footer.php'; ?>
