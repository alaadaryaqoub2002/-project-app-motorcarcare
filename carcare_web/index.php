<?php include 'conDB/head.php'; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold mb-3 text-primary">Welcome to <span class="text-dark">MotorCarCare</span></h1>
            <p class="lead mb-4">Your smart solution for car maintenance.</p>
            <div class="mb-4">
                <a href="services.php" class="btn btn-primary btn-lg me-2">Book Now</a>
                <a href="login.php" class="btn btn-outline-primary btn-lg">Login / Register</a>
            </div>
            <ul class="list-unstyled fs-5">
                <li class="mb-2"><i class="fas fa-calendar-check text-primary me-2"></i>Booking: Easily book appointments for your car service.</li>
                <li class="mb-2"><i class="fas fa-route text-primary me-2"></i>Tracking: Monitor your repair status and service history effortlessly.</li>
            </ul>
        </div>
        <div class="col-md-6 text-center">
            <img src="images/car-7.jpg" alt="Car" class="img-fluid" style="max-width:100%; height:auto; max-height:340px; object-fit:cover;">
        </div>
    </div>

    <!-- Why choose us -->
    <div class="mb-5">
        <h2 class="mb-4 text-center text-primary">Why choose us</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="p-4 border rounded-3 h-100">
                    <i class="fas fa-check-circle fa-2x text-primary mb-3"></i>
                    <h5>Simple and user-friendly</h5>
                    <p class="text-muted">Easy to use for everyone, no tech skills needed.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-4 border rounded-3 h-100">
                    <i class="fas fa-globe fa-2x text-primary mb-3"></i>
                    <h5>Accessible on web and mobile</h5>
                    <p class="text-muted">Book and track from anywhere, anytime.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-4 border rounded-3 h-100">
                    <i class="fas fa-bolt fa-2x text-primary mb-3"></i>
                    <h5>Real-time alerts & reminders</h5>
                    <p class="text-muted">Stay updated with instant notifications.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Links (optional, like in your screenshot) -->
    <div class="row text-center mt-5">
        <div class="col-md-4">
            <h6 class="fw-bold">About Us</h6>
            <p class="mb-1"><a href="#" class="text-decoration-none text-muted">Company info</a></p>
        </div>
        <div class="col-md-4">
            <h6 class="fw-bold">Contact</h6>
            <p class="mb-1"><a href="#" class="text-decoration-none text-muted">email</a></p>
        </div>
        <div class="col-md-4">
            <h6 class="fw-bold">Terms</h6>
            <p class="mb-1"><a href="#" class="text-decoration-none text-muted">Jobs</a></p>
        </div>
    </div>
</div>

<?php include 'conDB/footer.php'; ?>
