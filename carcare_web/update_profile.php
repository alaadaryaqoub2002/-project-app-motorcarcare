<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: login_form.php');
    exit();
}
require 'conDB/head.php';

?>


<div class="content-container" style="margin-top:150px">
    <div class="container">
        <h1 class="mb-4" style="color: #026dfe;">Update Profile</h1>

        <div id="alertContainer"></div>
        <div id="loadingSpinner" class="loading-spinner" style="display: none;"></div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="updateProfileForm">
                    <div class="form-group mb-3">
                        <label for="username"><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" readonly>
                        <small class="form-text text-muted">Email cannot be changed for security reasons.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



<script type="module">
    import { db } from './js/firebase-config.js';
    import { doc, getDoc, updateDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

    const updateProfileForm = document.getElementById('updateProfileForm');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const alertContainer = document.getElementById('alertContainer');
    const userId = '<?php echo $_SESSION['user']['id']; ?>';

    // Show alert function
    function showAlert(message, type = 'success') {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // Load user data
    async function loadUserData() {
        loadingSpinner.style.display = 'block';
        try {
            const userDoc = await getDoc(doc(db, 'users', userId));
            if (userDoc.exists()) {
                const userData = userDoc.data();
                document.getElementById('username').value = userData.username || '';
                document.getElementById('email').value = userData.email || '';
                document.getElementById('phone').value = userData.phone || '';
            }
        } catch (error) {
            console.error("Error loading user data:", error);
            showAlert('Error loading user data: ' + error.message, 'danger');
        } finally {
            loadingSpinner.style.display = 'none';
        }
    }

    // Handle form submission
    updateProfileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        loadingSpinner.style.display = 'block';

        const updatedData = {
            username: document.getElementById('username').value,
            phone: document.getElementById('phone').value,
            updated_at: new Date().toISOString()
        };

        try {
            await updateDoc(doc(db, 'users', userId), updatedData);

            // Update PHP session
            const sessionResponse = await fetch('update_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: updatedData.username
                })
            });

            const sessionResult = await sessionResponse.json();
            if (!sessionResult.success) {
                throw new Error('Failed to update session');
            }

            showAlert('Profile updated successfully! Refreshing...');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } catch (error) {
            console.error("Error updating profile:", error);
            showAlert('Error updating profile: ' + error.message, 'danger');
        } finally {
            loadingSpinner.style.display = 'none';
        }
    });

    // Load user data when page loads
    document.addEventListener('DOMContentLoaded', loadUserData);
</script>
<?php include 'conDB/footer.php'; ?>
