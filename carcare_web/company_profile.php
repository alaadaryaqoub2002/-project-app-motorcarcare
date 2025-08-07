<?php include 'conDB/head-company.php'; ?>
<div class="container my-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center" style="color: #026dfe;">Company Profile</h2>
    <div id="profileCard" class="card shadow-sm mb-4 d-none">
        <div class="card-body text-center">
            <div id="companyLogoContainer" class="mb-3"></div>
            <h4 id="companyName"></h4>
            <p class="mb-1"><i class="bi bi-envelope"></i> <span id="companyEmail"></span></p>
            <p class="mb-1"><i class="bi bi-telephone"></i> <span id="companyPhone"></span></p>
            <p class="mb-1"><i class="bi bi-geo-alt"></i> <span id="companyAddress"></span></p>
            <p class="mb-2"><i class="bi bi-info-circle"></i> <span id="companyDescription"></span></p>
            <button class="btn btn-outline-primary btn-sm" id="editProfileBtn"><i class="bi bi-pencil"></i> Edit</button>
        </div>
    </div>
    <div id="noProfileMsg" class="alert alert-info text-center d-none">No company profile data found. Please add your company information.</div>
    <div id="editProfileCard" class="card shadow-sm d-none">
        <div class="card-body">
            <form id="companyProfileForm">
                <div class="mb-3 text-center">
                    <img id="logoPreview" src="" alt="Logo Preview" class="rounded mb-2" style="max-width: 120px; max-height: 120px; display: none;">
                    <input type="file" class="form-control form-control-sm" id="companyLogo" accept="image/*">
                    <small class="form-text text-muted">Optional. JPG/PNG, max 2MB.</small>
                </div>
                <div class="mb-3">
                    <label for="companyNameInput" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="companyNameInput" required>
                </div>
                <div class="mb-3">
                    <label for="companyEmailInput" class="form-label">Email</label>
                    <input type="email" class="form-control" id="companyEmailInput" required>
                </div>
                <div class="mb-3">
                    <label for="companyPhoneInput" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="companyPhoneInput" required>
                </div>
                <div class="mb-3">
                    <label for="companyAddressInput" class="form-label">Address</label>
                    <input type="text" class="form-control" id="companyAddressInput">
                </div>
                <div class="mb-3">
                    <label for="companyDescriptionInput" class="form-label">Description</label>
                    <textarea class="form-control" id="companyDescriptionInput" rows="2"></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, doc, getDoc, setDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { getStorage, ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-storage.js";

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
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const storage = getStorage(app);

const COMPANY_DOC_ID = 'main_company_profile'; // You can change this if you want multi-company support

const profileCard = document.getElementById('profileCard');
const noProfileMsg = document.getElementById('noProfileMsg');
const editProfileCard = document.getElementById('editProfileCard');
const editProfileBtn = document.getElementById('editProfileBtn');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const companyProfileForm = document.getElementById('companyProfileForm');
const logoPreview = document.getElementById('logoPreview');
const companyLogo = document.getElementById('companyLogo');

// Load company profile from Firestore
async function loadProfile() {
    const docRef = doc(db, 'company_profiles', COMPANY_DOC_ID);
    const docSnap = await getDoc(docRef);
    if (docSnap.exists()) {
        const data = docSnap.data();
        $('#companyName').text(data.name || '');
        $('#companyEmail').text(data.email || '');
        $('#companyPhone').text(data.phone || '');
        $('#companyAddress').text(data.address || '');
        $('#companyDescription').text(data.description || '');
        if (data.logo_url) {
            $('#companyLogoContainer').html(`<img src="${data.logo_url}" class="rounded mb-2" style="max-width: 120px; max-height: 120px;">`);
        } else {
            $('#companyLogoContainer').html('');
        }
        profileCard.classList.remove('d-none');
        noProfileMsg.classList.add('d-none');
        editProfileCard.classList.add('d-none');
    } else {
        profileCard.classList.add('d-none');
        noProfileMsg.classList.remove('d-none');
        editProfileCard.classList.add('d-none');
    }
}

// Show edit form with current data
if (editProfileBtn) {
    editProfileBtn.addEventListener('click', async function() {
        const docRef = doc(db, 'company_profiles', COMPANY_DOC_ID);
        const docSnap = await getDoc(docRef);
        if (docSnap.exists()) {
            const data = docSnap.data();
            $('#companyNameInput').val(data.name || '');
            $('#companyEmailInput').val(data.email || '');
            $('#companyPhoneInput').val(data.phone || '');
            $('#companyAddressInput').val(data.address || '');
            $('#companyDescriptionInput').val(data.description || '');
            if (data.logo_url) {
                logoPreview.src = data.logo_url;
                logoPreview.style.display = 'block';
            } else {
                logoPreview.src = '';
                logoPreview.style.display = 'none';
            }
        } else {
            companyProfileForm.reset();
            logoPreview.src = '';
            logoPreview.style.display = 'none';
        }
        profileCard.classList.add('d-none');
        noProfileMsg.classList.add('d-none');
        editProfileCard.classList.remove('d-none');
    });
}

if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', function() {
        editProfileCard.classList.add('d-none');
        loadProfile();
    });
}

// Preview logo image
if (companyLogo) {
    companyLogo.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024) {
                alert('Only JPG/PNG images up to 2MB are allowed.');
                companyLogo.value = '';
                logoPreview.src = '';
                logoPreview.style.display = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            logoPreview.src = '';
            logoPreview.style.display = 'none';
        }
    });
}

// Save profile
if (companyProfileForm) {
    companyProfileForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        try {
            const name = $('#companyNameInput').val().trim();
            const email = $('#companyEmailInput').val().trim();
            const phone = $('#companyPhoneInput').val().trim();
            const address = $('#companyAddressInput').val().trim();
            const description = $('#companyDescriptionInput').val().trim();
            let logo_url = null;
            const file = companyLogo.files[0];
            if (file) {
                // Upload logo to Firebase Storage
                const filename = `company_logos/${Date.now()}_${file.name}`;
                const storageRef = ref(storage, filename);
                await uploadBytes(storageRef, file);
                logo_url = await getDownloadURL(storageRef);
            } else if (logoPreview.src && logoPreview.style.display !== 'none') {
                logo_url = logoPreview.src;
            }
            // Save to Firestore
            await setDoc(doc(db, 'company_profiles', COMPANY_DOC_ID), {
                name, email, phone, address, description, logo_url
            });
            alert('Profile saved successfully!');
            loadProfile();
        } catch (error) {
            alert('Error saving profile: ' + (error.message || error));
        }
    });
}

// Initial load
loadProfile();
</script>
<?php include 'conDB/footer.php'; ?> 