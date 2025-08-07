<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit();
}
include 'conDB/head.php';
?>


<div class="container">
    <h1 class="text-center mb-4">My Appointments</h1>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="btn-group mb-2">
                            <button class="btn btn-outline-primary active" data-filter="all">
                                <i class="fas fa-list"></i> All
                            </button>
                            <button class="btn btn-outline-warning" data-filter="pending">
                                <i class="fas fa-clock"></i> Pending
                            </button>
                            <button class="btn btn-outline-success" data-filter="approved">
                                <i class="fas fa-check"></i> Approved
                            </button>
                            <button class="btn btn-outline-danger" data-filter="rejected">
                                <i class="fas fa-times"></i> Rejected
                            </button>
                        </div>
                        <div class="form-group mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                </div>
                                <input type="date" id="dateFilter" class="form-control" placeholder="Filter by date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Container -->
    <div class="row" id="appointmentsContainer">
        <!-- Loading spinner will be inserted here -->
    </div>
</div>


<style>
    .appointment-card {
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .appointment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        z-index: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .card-body {
        padding: 1.5rem;
    }

    .appointment-details {
        background: #f8f9fa;
        padding: 1.2rem;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .appointment-details p {
        margin-bottom: 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .appointment-details p:last-child {
        margin-bottom: 0;
    }

    .payment-status {
        font-size: 0.85rem;
        padding: 5px 12px;
        border-radius: 15px;
        font-weight: 600;
    }

    .payment-paid {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .payment-unpaid {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .btn-group .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .btn-group .btn i {
        margin-right: 5px;
    }

    .loading-container {
        text-align: center;
        padding: 3rem 0;
        width: 100%;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #026dfe;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .btn-group {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .btn-group .btn {
            margin: 0;
            width: 100%;
        }

        .input-group {
            width: 100%;
        }
    }
</style>

<script type="module">
    import {db} from './js/firebase-config.js';
    import {
        collection,
        query,
        where,
        getDocs,
        orderBy
    } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

    const appointmentsContainer = document.getElementById('appointmentsContainer');
    const dateFilter = document.getElementById('dateFilter');
    const userId = '<?php echo $_SESSION['user']['id']; ?>';

    // Format date for display
    function formatDate(date) {
        if (!date) return 'N/A';

        if (date.seconds) {
            date = new Date(date.seconds * 1000);
        } else if (typeof date === 'string') {
            date = new Date(date);
        }

        if (!(date instanceof Date) || isNaN(date)) return 'N/A';

        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Service name mapping
    const serviceNames = {
        'oil_change': 'Oil Change',
        'brake_inspection': 'Brake Inspection',
        'tire_rotation': 'Tire Rotation',
        'air_conditioning': 'Air Conditioning Service',
        'engine_diagnostic': 'Engine Diagnostic',
        'wheel_alignment': 'Wheel Alignment',
        'battery_service': 'Battery Service',
        'transmission_service': 'Transmission Service'
    };

    // Function to get service display name
    function getServiceDisplayName(serviceId) {
        return serviceNames[serviceId] || serviceId;
    }

    // Show loading spinner
    function showLoading() {
        appointmentsContainer.innerHTML = `
            <div class="col-12">
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <p class="text-muted">Loading your appointments...</p>
                </div>
            </div>
        `;
    }

    // Show error message
    function showError(message) {
        appointmentsContainer.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Error</h5>
                    <p>${message}</p>
                    <button onclick="window.location.reload()" class="btn btn-outline-danger btn-sm mt-2">
                        <i class="fas fa-sync"></i> Try Again
                    </button>
                </div>
            </div>
        `;
    }

    // Filter appointments
    function filterAppointments(appointments, statusFilter, dateFilter) {
        return appointments.filter(appointment => {
            const matchesStatus = statusFilter === 'all' || appointment.approval_status === statusFilter;
            const matchesDate = !dateFilter || appointment.appointment_date === dateFilter;
            return matchesStatus && matchesDate;
        });
    }

    // Load appointments
    async function loadAppointments() {
        showLoading();

        try {
            const statusFilter = document.querySelector('[data-filter].active').dataset.filter;
            const dateFilterValue = dateFilter.value;

            const appointmentsRef = collection(db, 'appointments');
            const q = query(
                appointmentsRef,
                where('user_id', '==', userId)
            );

            const querySnapshot = await getDocs(q);

            if (querySnapshot.empty) {
                appointmentsContainer.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> No Appointments</h5>
                            <p>You haven't made any appointments yet.</p>
                            <a href="services.php" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-calendar-plus"></i> Book an Appointment
                            </a>
                        </div>
                    </div>
                `;
                return;
            }

            let appointments = [];
            querySnapshot.forEach((doc) => {
                appointments.push({id: doc.id, ...doc.data()});
            });

            // Sort by date
            appointments.sort((a, b) => {
                const dateA = a.created_at?.seconds || 0;
                const dateB = b.created_at?.seconds || 0;
                return dateB - dateA;
            });

            // Apply filters
            const filteredAppointments = filterAppointments(appointments, statusFilter, dateFilterValue);

            if (filteredAppointments.length === 0) {
                appointmentsContainer.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-filter"></i> No Matching Appointments</h5>
                            <p>No appointments found with the current filters.</p>
                            <button onclick="window.location.reload()" class="btn btn-outline-warning btn-sm mt-2">
                                <i class="fas fa-sync"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                `;
                return;
            }

            let appointmentsHTML = '';
            filteredAppointments.forEach((appointment) => {
                const statusClass = {
                    'pending': 'status-pending',
                    'approved': 'status-approved',
                    'rejected': 'status-rejected'
                }[appointment.approval_status || 'pending'];

                const paymentStatusClass = appointment.payment_status === 'Paid' ? 'payment-paid' : 'payment-unpaid';

                appointmentsHTML += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card appointment-card shadow-sm">
                            <div class="status-badge ${statusClass}">
                                <i class="fas fa-${appointment.approval_status === 'approved' ? 'check' :
                    appointment.approval_status === 'rejected' ? 'times' : 'clock'}"></i>
                                ${(appointment.approval_status || 'pending').charAt(0).toUpperCase() +
                (appointment.approval_status || 'pending').slice(1)}
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-calendar-check"></i> Appointment Details
                                </h5>
                                <div class="appointment-details">
                                    <p><strong><i class="fas fa-user"></i> Name:</strong> ${appointment.name || 'N/A'}</p>
                                    <p><strong><i class="fas fa-wrench"></i> Service:</strong> ${getServiceDisplayName(appointment.service_id)}</p>
                                    <p><strong><i class="fas fa-calendar"></i> Date:</strong> ${appointment.appointment_date || 'N/A'}</p>
                                    <p><strong><i class="fas fa-clock"></i> Time:</strong> ${appointment.appointment_time || 'N/A'}</p>
                                    <p><strong><i class="fas fa-phone"></i> Phone:</strong> ${appointment.phone || 'N/A'}</p>
                                    <p><strong><i class="fas fa-info-circle"></i> Status:</strong> ${appointment.service_status || 'N/A'}</p>
                                    <p>
                                        <strong><i class="fas fa-credit-card"></i> Payment:</strong>
                                        <span class="payment-status ${paymentStatusClass}">
                                            ${appointment.payment_status || 'Not Paid'}
                                        </span>
                                    </p>
                                </div>
                                ${appointment.rejection_reason ? `
                                    <div class="alert alert-danger mt-3 mb-0">
                                        <small>
                                            <i class="fas fa-exclamation-circle"></i>
                                            <strong>Rejection Reason:</strong> ${appointment.rejection_reason}
                                        </small>
                                    </div>
                                ` : ''}
                            </div>
                            <div class="card-footer bg-light">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-plus"></i> Created: ${formatDate(appointment.created_at)}
                                </small>
                                ${appointment.updated_at ? `
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-sync"></i> Updated: ${formatDate(appointment.updated_at)}
                                    </small>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            appointmentsContainer.innerHTML = appointmentsHTML;

        } catch (error) {
            console.error("Error loading appointments:", error);
            showError('Failed to load appointments. Please try again later.');
        }
    }

    // Event Listeners
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', (e) => {
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            e.target.classList.add('active');
            loadAppointments();
        });
    });

    dateFilter.addEventListener('change', loadAppointments);

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        loadAppointments();
    });
</script>

<?php include 'conDB/footer.php'; ?>
