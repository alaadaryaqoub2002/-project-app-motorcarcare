<?php include 'conDB/head-company.php'; ?>
<div class="container my-5" style="max-width: 700px;">
    <h2 class="mb-4 text-center" style="color: #026dfe;">Company Settings</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <form id="companySettingsForm">
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
                <div class="mb-3">
                    <label class="form-label">Business Hours</label>
                    <div class="row g-2" id="businessHoursInputs">
                        <!-- Business hours fields will be generated here -->
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
            <div id="settingsSuccess" class="alert alert-success mt-3 d-none">Settings saved successfully!</div>
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

const COMPANY_DOC_ID = 'main_company_profile';
const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

const logoPreview = document.getElementById('logoPreview');
const companyLogo = document.getElementById('companyLogo');
const businessHoursInputs = document.getElementById('businessHoursInputs');
const settingsSuccess = document.getElementById('settingsSuccess');

// Generate business hours fields
function renderBusinessHoursFields(hours = {}) {
    businessHoursInputs.innerHTML = '';
    DAYS.forEach(day => {
        const open = hours[day]?.open || '';
        const close = hours[day]?.close || '';
        businessHoursInputs.innerHTML += `
        <div class="col-12 col-md-6 mb-2">
            <div class="input-group input-group-sm">
                <span class="input-group-text" style="min-width:80px;">${day}</span>
                <input type="time" class="form-control" id="open_${day}" value="${open}">
                <span class="input-group-text">to</span>
                <input type="time" class="form-control" id="close_${day}" value="${close}">
            </div>
        </div>`;
    });
}

// Load settings from Firestore
async function loadSettings() {
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
        renderBusinessHoursFields(data.business_hours || {});
    } else {
        renderBusinessHoursFields();
        logoPreview.src = '';
        logoPreview.style.display = 'none';
    }
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

// Save settings
$('#companySettingsForm').on('submit', async function(e) {
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
            const filename = `company_logos/${Date.now()}_${file.name}`;
            const storageRef = ref(storage, filename);
            await uploadBytes(storageRef, file);
            logo_url = await getDownloadURL(storageRef);
        } else if (logoPreview.src && logoPreview.style.display !== 'none') {
            logo_url = logoPreview.src;
        }
        // Collect business hours
        const business_hours = {};
        DAYS.forEach(day => {
            const open = $(`#open_${day}`).val();
            const close = $(`#close_${day}`).val();
            business_hours[day] = { open, close };
        });
        // Save to Firestore
        await setDoc(doc(db, 'company_profiles', COMPANY_DOC_ID), {
            name, email, phone, address, description, logo_url, business_hours
        });
        settingsSuccess.classList.remove('d-none');
        setTimeout(() => settingsSuccess.classList.add('d-none'), 2500);
    } catch (error) {
        alert('Error saving settings: ' + (error.message || error));
    }
});

// Initial load
loadSettings();
</script>
<?php include 'conDB/footer.php'; ?> 