<style>

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 30px;
        }
        .card {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 8px;
            border: 2px solid #007bff; /* حدود ملونة */
        }
        .card-body {
            flex: 1;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 10px;
        }
        .card-text {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
        .btn-primary {
            font-size: 0.95rem;
            font-weight: bold;
            padding: 10px 15px;
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>

    <div class="container">
        <h1 class="text-center">Services We Can Help You With</h1>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="images/Full_car_wash.PNG" alt="Car Wash">
                    <div class="card-body">
                        <h5 class="card-title">Full Car Wash</h5>
                        <p class="card-text">There are many variations of passages available for car wash services.</p>
                        <a href="#" class="btn btn-primary">View More &gt;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="images/full_synthetic_oil_change.PNG" alt="Oil Change">
                    <div class="card-body">
                        <h5 class="card-title">Full Synthetic Oil Change</h5>
                        <p class="card-text">Ensure your car runs smoothly with our synthetic oil change services.</p>
                        <a href="#" class="btn btn-primary">View More &gt;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="images/car_battery_change.PNG" alt="Car Battery">
                    <div class="card-body">
                        <h5 class="card-title">Car Battery Change</h5>
                        <p class="card-text">Get reliable battery replacement services for all car models.</p>
                        <a href="#" class="btn btn-primary">View More &gt;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="images/full_car_service.PNG" alt="Full Car Service">
                    <div class="card-body">
                        <h5 class="card-title">Full Car Service</h5>
                        <p class="card-text">Comprehensive car service packages for optimal performance.</p>
                        <a href="#" class="btn btn-primary">View More &gt;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="images/car_tyer_change.PNG" alt="Tire Change">
                    <div class="card-body">
                        <h5 class="card-title">Car Tire Change</h5>
                        <p class="card-text">Efficient and quick tire replacement services.</p>
                        <a href="#" class="btn btn-primary">View More &gt;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="images/other_service.PNG" alt="Other Service">
                    <div class="card-body">
                        <h5 class="card-title">Other Service</h5>
                        <p class="card-text">Explore a variety of additional car services to meet your needs.</p>
                        <a href="#" class="btn btn-primary">View More &gt;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
