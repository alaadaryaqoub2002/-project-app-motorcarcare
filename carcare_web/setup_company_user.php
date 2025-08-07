<?php include 'conDB/head.php'; ?>

<script type="module">
import { doc, setDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { db } from './js/firebase-config.js';

async function setupCompanyUser() {
    try {
        // User data from Authentication
        const userId = '56OqDVEAjpMhjXDC2npqSNBpQ1r1';
        const email = 'abdallah.maali@gmail.com';

        // Create user document in Firestore
        await setDoc(doc(db, 'users', userId), {
            email: email,
            username: 'CompanyAdmin',
            role: 'company',
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString()
        });

        console.log('Company user document created successfully');
        document.body.innerHTML += '<div class="alert alert-success">Company user document created successfully!</div>';
    } catch (error) {
        console.error('Error creating company user:', error);
        document.body.innerHTML += `<div class="alert alert-danger">Error: ${error.message}</div>`;
    }
}

// Run the setup
setupCompanyUser();
</script>

<div class="container mt-5">
    <h2>Setting up Company User</h2>
    <p>Creating Firestore document for company user...</p>
</div>
