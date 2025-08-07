<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
include 'conDB/head.php';

?>

<div class="content-wrapper" style="margin-top: 100px;">
    <div class="container my-5">
        <?php if ($isLoggedIn): ?>
            <div class="alert alert-success text-center mb-4">
                Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center mb-4">
                Please <a href="login_form.php" class="alert-link">login</a> to book services
            </div>
        <?php endif; ?>

        <h1 class="text-center mb-4">Our Services</h1>

        <!-- Search form -->


        <div class="row" id="servicesContainer">
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading our services...</div>
            </div>
        </div>
    </div>
</div>

<!-- Add Firebase SDK -->
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, query, where, getDocs } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { db } from './js/firebase-config.js';

// Function to format price
function formatPrice(price) {
    // Convert string to number and handle any potential formatting issues
    const numPrice = parseFloat(price);
    return isNaN(numPrice) ? "0.00" : numPrice.toFixed(2);
}

// Function to load and display services
async function loadServices(searchTerm = '') {
    const servicesContainer = document.getElementById('servicesContainer');
    servicesContainer.innerHTML = `
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading our services...</div>
        </div>
    `; // Show loading state

    try {
        const servicesRef = collection(db, 'services');
        let q = servicesRef;

        if (searchTerm) {
            q = query(servicesRef,
                where('name', '>=', searchTerm),
                where('name', '<=', searchTerm + '\uf8ff')
            );
        }

        const snapshot = await getDocs(q);

        if (snapshot.empty) {
            servicesContainer.innerHTML = `
                <div class="col-12">
                    <div class="no-services-message">
                        <h4>No Services Found</h4>
                        <p>${searchTerm ? 'We couldn\'t find any services matching your search criteria. Please try a different search term.' : 'We\'re currently updating our service catalog. Please check back soon!'}</p>
                    </div>
                </div>
            `;
            return;
        }

        servicesContainer.innerHTML = ''; // Clear loading spinner

        snapshot.forEach(doc => {
            const service = doc.data();
            const serviceCard = `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">${service.name}</h5>
                            <p class="card-text">${service.description}</p>
                            <p class="card-text"><strong>Duration:</strong> ${service.duration}</p>
                            <p class="card-text"><strong>Price:</strong> $${formatPrice(service.price)}</p>
                            ${<?php echo $isLoggedIn ? 'true' : 'false'; ?> ?
                                `<a href="book_appointments.php?service_id=${doc.id}" class="btn btn-primary">Book Now</a>` :
                                `<a href="login_form.php" class="btn btn-outline-primary">Login to Book</a>`
                            }
                        </div>
                    </div>
                </div>
            `;
            servicesContainer.innerHTML += serviceCard;
        });
    } catch (error) {
        console.error("Error loading services:", error);
        servicesContainer.innerHTML = '<p class="text-center w-100">Error loading services. Please try again later.</p>';
    }
}

// Function to handle search
function searchServices() {
    const searchTerm = document.getElementById('searchInput').value.trim();
    loadServices(searchTerm);
}

// Make functions available globally
window.loadServices = loadServices;
window.searchServices = searchServices;

// Load services when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadServices();
});
</script>

<!-- Add some custom styles -->
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
}

.card-body {
    padding: 2rem 1.5rem;
}

.btn-outline-primary {
    border-color: #026dfe;
    color: #026dfe;
}

.btn-outline-primary:hover {
    background-color: #026dfe;
    border-color: #026dfe;
    color: white;
}

.alert-link {
    text-decoration: none;
}

.alert-link:hover {
    text-decoration: underline;
}
</style>

<?php include 'conDB/footer.php'; ?>
