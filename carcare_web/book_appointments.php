<?php
include 'conDB/head.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit;
}
?>

    <div class="content-wrapper" style="margin-top: 100px;">
        <div class="container my-5">
            <div id="serviceDetails">
                <!-- Service details will be loaded here -->
            </div>

            <form id="bookingForm" class="mt-4">
                <input type="hidden" id="service_id" name="service_id" value="<?php echo htmlspecialchars($_GET['service_id'] ?? ''); ?>">

                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['username']); ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="form-group mb-3">
                    <label for="car_type">Car Type</label>
                    <select class="form-control" id="car_type" name="car_type" required>
                        <option value="">Select car type</option>
                        <option value="sedan">Sedan</option>
                        <option value="suv">SUV</option>
                        <option value="hatchback">Hatchback</option>
                        <option value="pickup">Pickup Truck</option>
                        <option value="van">Van</option>
                        <option value="luxury">Luxury Car</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="appointment_date">Preferred Appointment Date</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="appointment_time">Preferred Time</label>
                    <select class="form-control" id="appointment_time" name="appointment_time" required>
                        <option value="">Select a time</option>
                        <?php
                        // Generate time slots from 9 AM to 5 PM
                        for ($hour = 9; $hour <= 17; $hour++) {
                            $time = sprintf("%02d:00", $hour);
                            echo "<option value=\"$time\">$time</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </form>

            <div class="text-center mt-4">
                <a href="services.php" class="btn btn-secondary">Back to Services</a>
            </div>
        </div>
    </div>

    <!-- Add Firebase SDK -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getFirestore, collection, doc, getDoc, addDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
        import { db } from './js/firebase-config.js';

        // Function to format price
        function formatPrice(price) {
            const numPrice = parseFloat(price);
            return isNaN(numPrice) ? "0.00" : numPrice.toFixed(2);
        }

        // Load service details
        async function loadServiceDetails() {
            const serviceId = document.getElementById('service_id').value;
            const serviceDetails = document.getElementById('serviceDetails');

            try {
                const serviceDoc = await getDoc(doc(db, 'services', serviceId));

                if (serviceDoc.exists()) {
                    const service = serviceDoc.data();
                    serviceDetails.innerHTML = `
                <h1 class="text-center mb-4">Book Appointment for ${service.name}</h1>
                <p class="text-center">Duration: ${service.duration} | Price: $${formatPrice(service.price)}</p>
            `;
                } else {
                    serviceDetails.innerHTML = '<p class="text-center text-danger">Service not found.</p>';
                    document.getElementById('bookingForm').style.display = 'none';
                }
            } catch (error) {
                console.error("Error loading service:", error);
                serviceDetails.innerHTML = '<p class="text-center text-danger">Error loading service details. Please try again.</p>';
            }
        }

        // Handle form submission
        async function handleBooking(event) {
            event.preventDefault();

            const submitButton = event.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            try {
                const serviceId = document.getElementById('service_id').value;
                const name = document.getElementById('name').value;
                const phone = document.getElementById('phone').value;
                const carType = document.getElementById('car_type').value;
                const date = document.getElementById('appointment_date').value;
                const time = document.getElementById('appointment_time').value;

                // Create booking document
                const bookingData = {
                    service_id: serviceId,
                    user_id: '<?php echo $_SESSION['user']['id'] ?? ''; ?>',
                    name: name,
                    phone: phone,
                    car_type: carType,
                    appointment_date: date,
                    appointment_time: time,
                    status: 'pending',
                    created_at: new Date().toISOString()
                };

                await addDoc(collection(db, 'appointments'), bookingData);

                // Show success message and redirect
                alert('Appointment booked successfully!');
                window.location.href = 'user-appointment.php';

            } catch (error) {
                console.error("Error booking appointment:", error);
                alert('Error booking appointment. Please try again.');
                submitButton.disabled = false;
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadServiceDetails();
            document.getElementById('bookingForm').addEventListener('submit', handleBooking);

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('appointment_date').min = today;
        });
    </script>

    <!-- Add some custom styles -->
    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>

<?php include 'conDB/footer.php'; ?>
