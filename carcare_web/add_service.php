<?php
// Remove any PHP includes related to MySQL
include 'conDB/head-company.php'; ?>
<div class="content-wrapper" style="margin-top: 50px;">
    <div class="container my-5" style="max-width: 600px;">
        <br><br><br>
        <h1 class="text-center mb-4" style="font-size: 24px;">Add a New Service</h1>
        <div class="card">
            <div class="card-body" style="padding: 20px;">
                <form id="addServiceForm">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="name" style="font-size: 14px;">Service Name</label>
                        <input type="text" class="form-control form-control-sm" id="name" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="description" style="font-size: 14px;">Description</label>
                        <textarea class="form-control form-control-sm" id="description" rows="2" required></textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="duration" style="font-size: 14px;">Duration (in minutes)</label>
                        <input type="number" class="form-control form-control-sm" id="duration" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="price" style="font-size: 14px;">Price</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" id="price" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 30px;">
                            <i class="fas fa-plus-circle me-2"></i>Add Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyC2KfKctrAoVzCBQBjAqXQw1Uc_1_V8i5U",
    authDomain: "carcare-app-52eb1.firebaseapp.com",
    databaseURL: "https://carcare-app-52eb1-default-rtdb.firebaseio.com",
    projectId: "carcare-app-52eb1",
    storageBucket: "carcare-app-52eb1.firebasestorage.app",
    messagingSenderId: "732379485235",
    appId: "1:732379485235:web:37818daef5f175db0ebf46",
    measurementId: "G-MFRCW8L835"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

// Handle form submission
$('#addServiceForm').on('submit', async function(e) {
    e.preventDefault();
    try {
        // Get form values
        const name = $('#name').val().trim();
        const description = $('#description').val().trim();
        const duration = parseInt($('#duration').val());
        const price = parseFloat($('#price').val());

        // Add service to Firestore
        const serviceData = {
            name,
            description,
            duration,
            price,
            created_at: serverTimestamp(),
            updated_at: serverTimestamp(),
            status: 'active'
        };

        await addDoc(collection(db, 'services'), serviceData);

        alert('Service added successfully!');
        window.location.href = 'manage_services.php';
    } catch (error) {
        console.error('Error adding service:', error);
        alert(error.message || 'Error adding service. Please try again.');
    }
});
</script>

<?php include 'conDB/footer.php'; ?>
