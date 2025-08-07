<?php include 'conDB/head.php'; ?>
<style>
    .about-section {
        padding: 100px 0;
        background: #f8f9fa;
    }

    .about-content {
        margin-top: 30px;
    }

    .about-image {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        max-width: 100%;
        height: auto;
    }

    .section-title {
        margin-bottom: 50px;
        text-align: center;
    }

    .section-title h2 {
        color: #026dfe;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .section-title p {
        color: #666;
        font-size: 1.1rem;
    }

    /* Testimonials Section */
    .testimonials {
        padding: 80px 0;
        background: #fff;
    }

    .testimonial-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        margin: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
    }

    .testimonial-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 20px;
        object-fit: cover;
    }

    .testimonial-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .testimonial-position {
        font-size: 0.9rem;
        color: #026dfe;
        margin-bottom: 15px;
    }

    .testimonial-text {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
    }

    .testimonial-date {
        font-size: 0.8rem;
        color: #999;
        margin-top: 15px;
    }

    .values-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .value-item {
        text-align: center;
        padding: 30px;
        margin: 15px 0;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .value-item:hover {
        transform: translateY(-5px);
    }

    .value-icon {
        font-size: 2.5rem;
        color: #026dfe;
        margin-bottom: 20px;
    }

    .value-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .value-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
    }
</style>
<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="section-title">
            <h2>About CarCare</h2>
            <p>Your Trusted Partner in Automotive Care</p>
        </div>
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="images/about.jpg" alt="About CarCare" class="about-image">
            </div>
            <div class="col-md-6">
                <div class="about-content">
                    <h3>Why Choose Us?</h3>
                    <p>At CarCare, we're dedicated to providing top-notch automotive services with a focus on quality,
                        reliability, and customer satisfaction. Our team of experienced professionals ensures that your
                        vehicle receives the best care possible.</p>
                    <ul class="mt-4">
                        <li>Expert Technicians</li>
                        <li>Quality Service</li>
                        <li>Modern Equipment</li>
                        <li>Customer Satisfaction</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Values</h2>
            <p>What drives us forward</p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="value-title">Passion</h4>
                    <p class="value-description">We're passionate about cars and providing the best service possible to
                        our clients.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="value-title">Integrity</h4>
                    <p class="value-description">We believe in honest, transparent service and fair pricing for all our
                        customers.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="value-title">Excellence</h4>
                    <p class="value-description">We strive for excellence in everything we do, from minor repairs to
                        major services.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <div class="section-title">
            <h2>What Our Clients Say</h2>
            <p>Testimonials from our valued customers</p>
        </div>
        <div class="row">
            <!-- Testimonial 1 -->
            <div class="col-md-4">
                <div class="testimonial-card">
                    <img src="images/person_1.jpg" alt="John Doe" class="testimonial-image">
                    <h4 class="testimonial-name">John Doe</h4>
                    <p class="testimonial-position">Customer</p>
                    <p class="testimonial-text">Great service and support! The team at CarCare went above and beyond to
                        ensure my vehicle was properly serviced.</p>
                    <p class="testimonial-date">December 7, 2024</p>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-md-4">
                <div class="testimonial-card">
                    <img src="images/person_2.jpg" alt="Jane Smith" class="testimonial-image">
                    <h4 class="testimonial-name">Jane Smith</h4>
                    <p class="testimonial-position">Customer</p>
                    <p class="testimonial-text">Highly recommended! Professional service, fair prices, and excellent
                        customer care. Will definitely come back!</p>
                    <p class="testimonial-date">December 7, 2024</p>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-md-4">
                <div class="testimonial-card">
                    <img src="images/person_3.jpg" alt="Alex Brown" class="testimonial-image">
                    <h4 class="testimonial-name">Alex Brown</h4>
                    <p class="testimonial-position">Client</p>
                    <p class="testimonial-text">Amazing experience! The staff was knowledgeable and friendly. They fixed
                        my car quickly and efficiently.</p>
                    <p class="testimonial-date">December 7, 2024</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'conDB/footer.php'; ?>


</body>
</html>
