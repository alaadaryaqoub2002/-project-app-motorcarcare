<?php include 'conDB/head.php'; ?>

<div class="hero-wrap" style="display: flex; height: 100vh; overflow: hidden;">
    <div class="image-container"
         style="flex: 0.7; background-image: url('images/car-5.jpg'); background-size: cover; background-position: center; position: relative;">
        <div class="overlay"
             style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
    </div>
    <div class="selection-container"
         style="flex: 1; padding: 30px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
        <h1 style="color: #026dfe; text-align: center; margin-bottom: 20px;">Welcome to Motor Car Care</h1>
        <div style="width: 100%; max-width: 500px; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); background-color: rgba(255, 255, 255, 0.95);">
            <h2 style="color: #026dfe; text-align: center;">Who Are You?</h2>

            <div style="display: flex; justify-content: space-around; margin-top: 30px;">
                <!-- Customer Card -->
                <a href="Login_form.php" style="text-decoration: none; color: inherit;">
                    <div style="text-align: center; cursor: pointer;">
                        <img src="images/customer_icon.png" alt="Customer"
                             style="width: 120px; height: auto; border-radius: 50%; border: 2px solid #026dfe; padding: 10px;">
                        <h3 style="margin-top: 10px; color: #026dfe;">Customer</h3>
                    </div>
                </a>
                <!-- Company Card -->
                <a href="login_company.php" style="text-decoration: none; color: inherit;">
                    <div style="text-align: center; cursor: pointer;">
                        <img src="images/company_icon.png" alt="Company"
                             style="width: 120px; height: auto; border-radius: 50%; border: 2px solid #026dfe; padding: 10px;">
                        <h3 style="margin-top: 10px; color: #026dfe;">Company</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'conDB/footer.php'; ?>

