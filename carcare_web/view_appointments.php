<?php
session_start();
include 'conDB/head-company.php';
?>

<div class="container py-4" style="margin-bottom: 400px;">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0" style="color: #026dfe;">Appointments</h2>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2 justify-content-md-end">
                <!-- فلتر الحالة كأزرار -->
                <div class="btn-group me-2" role="group" aria-label="Status Filter">
                    <button type="button" class="btn btn-outline-secondary status-filter-btn active" data-status="all"><i class="fas fa-list"></i> All</button>
                    <button type="button" class="btn btn-warning status-filter-btn" data-status="Pending"><i class="fas fa-clock"></i> Pending</button>
                    <button type="button" class="btn btn-success status-filter-btn" data-status="Approved"><i class="fas fa-check"></i> Approved</button>
                    <button type="button" class="btn btn-outline-danger status-filter-btn" data-status="Rejected"><i class="fas fa-times"></i> Rejected</button>
                </div>
                <!-- باقي الفلاتر -->
                <select class="form-select w-auto" id="paymentStatusFilter">
                    <option value="all">All Payment Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Not Paid">Not Paid</option>
                </select>
                <select class="form-select w-auto" id="dateFilter">
                    <option value="all">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
                <div class="input-group w-auto">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search customer name...">
                    <button class="btn btn-primary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Car Details</th>
                            <th>Payment Status</th>
                            <th>Service Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div id="loadingIndicator" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="noAppointments" class="text-center py-4 d-none">
                <p class="text-muted mb-0">No appointments found.</p>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="appointmentId">
                <div class="mb-3">
                    <label class="form-label">Service Status</label>
                    <div id="statusButtonGroup" class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="serviceStatus" id="statusPending" value="Pending" autocomplete="off">
                        <label class="btn btn-warning" for="statusPending"><i class="fas fa-clock me-1"></i> Pending</label>

                        <input type="radio" class="btn-check" name="serviceStatus" id="statusApproved" value="Approved" autocomplete="off">
                        <label class="btn btn-success" for="statusApproved"><i class="fas fa-check me-1"></i> Approved</label>

                        <input type="radio" class="btn-check" name="serviceStatus" id="statusRejected" value="Rejected" autocomplete="off">
                        <label class="btn btn-outline-danger" for="statusRejected"><i class="fas fa-times me-1"></i> Rejected</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Status</label>
                    <select class="form-select" id="paymentStatus">
                        <option value="Not Paid">Not Paid</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount Paid</label>
                    <input type="number" class="form-control" id="amountPaid" min="0" step="0.01">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateStatusBtn">Update</button>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { collection, query, getDocs, orderBy, where, updateDoc, doc, Timestamp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
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

// Utility Functions
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

function getPaymentBadgeClass(status) {
    if (!status) return 'bg-warning';
    
    switch(status.toLowerCase()) {
        case 'paid':
            return 'bg-success';
        case 'not paid':
            return 'bg-danger';
        default:
            return 'bg-warning';
    }
}

// Filter Functions
function filterByDate(appointment, dateFilter) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const appointmentDate = appointment.appointment_date instanceof Timestamp 
        ? appointment.appointment_date.toDate() 
        : new Date(appointment.appointment_date);
    
    switch(dateFilter) {
        case 'today':
            return appointmentDate.toDateString() === today.toDateString();
        case 'week': {
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            return appointmentDate >= weekStart && appointmentDate <= weekEnd;
        }
        case 'month': {
            return appointmentDate.getMonth() === today.getMonth() && 
                   appointmentDate.getFullYear() === today.getFullYear();
        }
        default:
            return true;
    }
}

function filterByServiceStatus(appointment, status) {
    return status === 'all' || appointment.service_status?.toLowerCase() === status.toLowerCase();
}

function filterByPaymentStatus(appointment, status) {
    return status === 'all' || appointment.payment_status?.toLowerCase() === status.toLowerCase();
}

function filterBySearch(appointment, searchTerm) {
    if (!searchTerm) return true;
    const searchLower = searchTerm.toLowerCase();
    return appointment.name?.toLowerCase().includes(searchLower);
}

// في الجافاسكريبت: أضف متغير لتخزين الفلتر الحالي
let currentStatusFilter = 'all';
// عند الضغط على زر فلتر الحالة
$(document).on('click', '.status-filter-btn', function() {
    $('.status-filter-btn').removeClass('active');
    $(this).addClass('active');
    currentStatusFilter = $(this).data('status');
    loadAppointments();
});

// عدل دالة loadAppointments لتفلتر حسب currentStatusFilter
function filterByStatus(appointment) {
    if (currentStatusFilter === 'all') return true;
    return (appointment.service_status || '').toLowerCase() === currentStatusFilter.toLowerCase();
}

// Main Functions
async function loadAppointments() {
    const tableBody = document.getElementById('appointmentsTableBody');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const noAppointments = document.getElementById('noAppointments');

    try {
        console.log('Loading appointments...');
        loadingIndicator.classList.remove('d-none');
        tableBody.innerHTML = '';

        const appointmentsRef = collection(db, 'appointments');
        const q = query(appointmentsRef, orderBy('appointment_date', 'desc'));
        const querySnapshot = await getDocs(q);
        
        const appointments = [];
        querySnapshot.forEach((doc) => {
            appointments.push({ id: doc.id, ...doc.data() });
        });

        if (appointments.length === 0) {
            noAppointments.classList.remove('d-none');
            return;
        }

        const serviceStatusFilter = document.getElementById('serviceStatusFilter').value;
        const paymentStatusFilter = document.getElementById('paymentStatusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        const searchTerm = document.getElementById('searchInput').value.trim();

        const filteredAppointments = appointments.filter(appointment => 
            filterByStatus(appointment) &&
            filterByPaymentStatus(appointment, paymentStatusFilter) && 
            filterByDate(appointment, dateFilter) && 
            filterBySearch(appointment, searchTerm)
        );

        filteredAppointments.forEach(appointment => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="fw-bold">${appointment.name || ''}</div>
                    <div class="text-muted small">${appointment.phone_number || ''}</div>
                </td>
                <td>
                    <div>${appointment.service_name || ''}</div>
                    <div class="text-muted small">ID: ${appointment.service_id || ''}</div>
                </td>
                <td>
                    <div>${formatDate(appointment.appointment_date)}</div>
                    <div class="text-muted small">${formatTime(appointment.appointment_time)}</div>
                </td>
                <td>
                    <div>Type: ${appointment.car_type || ''}</div>
                    <div class="text-muted small">Chassis: ${appointment.chassis_number || ''}</div>
                </td>
                <td>
                    <span class="badge ${getPaymentBadgeClass(appointment.payment_status)}">
                        ${appointment.payment_status || ''}
                    </span>
                    ${appointment.amount_paid ? `<div class="text-muted small">Amount: $${appointment.amount_paid}</div>` : ''}
                </td>
                <td>
                    <span class="badge ${getStatusBadgeClass(appointment.service_status)}">
                        ${appointment.service_status || ''}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary update-status" data-id="${appointment.id}">
                        Update Status
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        // Add event listeners for update buttons
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', (e) => {
                const appointmentId = e.target.dataset.id;
                const appointment = appointments.find(a => a.id === appointmentId);
                openUpdateModal(appointment);
            });
        });

    } catch (error) {
        console.error('Error loading appointments:', error);
        alert('Error loading appointments. Please try again.');
    } finally {
        loadingIndicator.classList.add('d-none');
    }
}

function openUpdateModal(appointment) {
    $('#appointmentId').val(appointment.id);
    // اضبط الزر المناسب حسب الحالة
    const status = appointment.service_status || 'Pending';
    $("#statusButtonGroup input[name='serviceStatus']").prop('checked', false);
    if (status === 'Approved') {
        $('#statusApproved').prop('checked', true);
    } else if (status === 'Rejected') {
        $('#statusRejected').prop('checked', true);
    } else {
        $('#statusPending').prop('checked', true);
    }
    $('#paymentStatus').val(appointment.payment_status || 'Not Paid');
    $('#amountPaid').val(appointment.amount_paid || 0);
    $('#statusModal').modal('show');
}

async function updateAppointmentStatus() {
    const appointmentId = $('#appointmentId').val();
    const serviceStatus = $("#statusButtonGroup input[name='serviceStatus']:checked").val();
    const paymentStatus = $('#paymentStatus').val();
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;

    try {
        const appointmentRef = doc(db, 'appointments', appointmentId);
        await updateDoc(appointmentRef, {
            service_status: serviceStatus,
            payment_status: paymentStatus,
            amount_paid: amountPaid,
            updated_at: Timestamp.now()
        });

        // Hide modal using jQuery
        $('#statusModal').modal('hide');
        
        loadAppointments();
        alert('Appointment status updated successfully!');
    } catch (error) {
        console.error('Error updating appointment:', error);
        alert('Error updating appointment. Please try again.');
    }
}

// Event Listeners using jQuery
$(document).ready(function() {
    $('#updateStatusBtn').on('click', updateAppointmentStatus);
    
    // Close modal on escape key
    $(document).on('keydown', function(event) {
        if (event.key === 'Escape') {
            $('#statusModal').modal('hide');
        }
    });
    
    // Initialize modal with options
    $('#statusModal').modal({
        backdrop: 'static',
        keyboard: true,
        focus: true
    });
});

// Event Listeners
document.getElementById('serviceStatusFilter').addEventListener('change', loadAppointments);
document.getElementById('paymentStatusFilter').addEventListener('change', loadAppointments);
document.getElementById('dateFilter').addEventListener('change', loadAppointments);
document.getElementById('searchBtn').addEventListener('click', loadAppointments);
document.getElementById('searchInput').addEventListener('keyup', loadAppointments);

// Initial load
loadAppointments();
</script>

<?php include 'conDB/footer.php'; ?>
