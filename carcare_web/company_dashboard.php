<?php
session_start();
if (!isset($_SESSION['company_user'])) {
    header('Location: login_company.php');
    exit();
}

include 'conDB/head-company.php';
?>

<div class="container py-4">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Appointments</h6>
                            <h4 class="mb-0" id="totalAppointments">Loading...</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-calendar fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Today's Appointments</h6>
                            <h4 class="mb-0" id="todayAppointments">Loading...</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-clock fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Active Services</h6>
                            <h4 class="mb-0" id="activeServices">Loading...</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-tools fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Customers</h6>
                            <h4 class="mb-0" id="totalCustomers">Loading...</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-users fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Appointments</h5>
            <a href="view_appointments.php" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { collection, query, where, getDocs, orderBy, limit, Timestamp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

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

function formatDate(timestamp) {
    if (!timestamp) return 'N/A';

    try {
        let date;
        if (timestamp instanceof Timestamp) {
            date = timestamp.toDate();
        } else if (timestamp.seconds) {
            date = new Date(timestamp.seconds * 1000);
        } else if (typeof timestamp === 'string') {
            date = new Date(timestamp);
        } else {
            return 'N/A';
        }

        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    } catch (error) {
        console.error('Error formatting date:', error);
        return 'N/A';
    }
}

function formatTime(timeStr) {
    if (!timeStr) return 'N/A';
    return timeStr;
}

function getStatusBadgeClass(status) {
    if (!status) return 'bg-warning';

    switch(status.toLowerCase()) {
        case 'completed':
            return 'bg-success';
        case 'in progress':
            return 'bg-info';
        case 'cancelled':
            return 'bg-danger';
        case 'booked':
            return 'bg-primary';
        default:
            return 'bg-warning';
    }
}

// Add this function at the start
async function getServiceName(serviceId) {
    if (!serviceId) return 'N/A';
    try {
        const servicesRef = collection(db, 'services');
        const q = query(servicesRef, where('id', '==', serviceId));
        const querySnapshot = await getDocs(q);

        if (!querySnapshot.empty) {
            return querySnapshot.docs[0].data().name || 'N/A';
        }
        return 'N/A';
    } catch (error) {
        console.error('Error getting service name:', error);
        return 'N/A';
    }
}

async function loadDashboardData() {
    try {
        const appointmentsRef = collection(db, 'appointments');

        // Load total appointments
        const totalAppointmentsSnap = await getDocs(appointmentsRef);
        document.getElementById('totalAppointments').textContent = totalAppointmentsSnap.size;

        // Load today's appointments
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        const todayQuery = query(appointmentsRef,
            where('appointment_date', '>=', today),
            where('appointment_date', '<', tomorrow)
        );
        const todayAppointmentsSnap = await getDocs(todayQuery);
        document.getElementById('todayAppointments').textContent = todayAppointmentsSnap.size;

        // Load active services (In Progress)
        const activeServicesQuery = query(appointmentsRef,
            where('service_status', '==', 'In Progress')
        );
        const activeServicesSnap = await getDocs(activeServicesQuery);
        document.getElementById('activeServices').textContent = activeServicesSnap.size;

        // Load total unique customers
        const uniqueCustomers = new Set();
        totalAppointmentsSnap.forEach(doc => {
            const data = doc.data();
            if (data.user_id) {
                uniqueCustomers.add(data.user_id);
            }
        });
        document.getElementById('totalCustomers').textContent = uniqueCustomers.size;

        // Load recent appointments
        const recentQuery = query(appointmentsRef,
            orderBy('appointment_date', 'desc'),
            limit(5)
        );
        const recentSnap = await getDocs(recentQuery);

        const tableBody = document.getElementById('appointmentsTableBody');
        tableBody.innerHTML = '';

        if (recentSnap.empty) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">No appointments found</td>
                </tr>
            `;
            return;
        }

        recentSnap.forEach(doc => {
            const data = doc.data();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="fw-bold">${data.name || 'N/A'}</div>
                            <div class="small text-muted">${data.phone || data.phone_number}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div>${data.service_name ||data.service_id}</div>
                    <div class="small text-muted">Car: ${data.car_type || 'N/A'}</div>
                </td>
                <td>${formatDate(data.appointment_date)}</td>
                <td>${formatTime(data.appointment_time)}</td>
                <td>
                    <span class="badge ${getStatusBadgeClass(data.service_status)}">
                        ${data.service_status || 'Pending'}
                    </span>
                </td>
                <td>
                    <a href="view_appointments.php" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            `;
            tableBody.appendChild(row);
        });

    } catch (error) {
        console.error('Error loading dashboard data:', error);
        // Show error messages in the UI
        document.getElementById('totalAppointments').textContent = 'Error';
        document.getElementById('todayAppointments').textContent = 'Error';
        document.getElementById('activeServices').textContent = 'Error';
        document.getElementById('totalCustomers').textContent = 'Error';

        const tableBody = document.getElementById('appointmentsTableBody');
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-danger">
                    Error loading appointments. Please refresh the page.
                </td>
            </tr>
        `;
    }
}

// Load dashboard data when the page loads
document.addEventListener('DOMContentLoaded', loadDashboardData);

// Refresh data every 5 minutes
setInterval(loadDashboardData, 5 * 60 * 1000);
</script>

<?php include 'conDB/footer.php'; ?>
