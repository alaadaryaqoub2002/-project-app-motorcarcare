<?php


//include 'conDB/DBConn.php';
//include 'conDB/head-company.php';

if (!isset($_SESSION['username'])) {
    header('Location: login_company.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CarCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #026dfe;
            color: #fff;
        }
        .card-body {
            background-color: #f8f9fa;
        }
        .icon {
            font-size: 50px;
            color: #026dfe;
        }
    </style>
</head>
<body>
    <main class="container my-5">
        <br><br><br>
        <h1 class="text-center text-primary">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">Total Appointments</div>
                    <div class="card-body">
                        <h3>
                            <?php
                            $query = "SELECT COUNT(*) AS total_appointments FROM appointments";
                            $result = $conn->query($query);
                            $data = $result->fetch(PDO::FETCH_ASSOC);
                            echo $data['total_appointments'] ?? 0;
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- إجمالي الخدمات -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">Services Offered</div>
                    <div class="card-body">
                        <h3>
                            <?php
                            $query = "SELECT COUNT(*) AS total_services FROM services";
                            $result = $conn->query($query);
                            $data = $result->fetch(PDO::FETCH_ASSOC);
                            echo $data['total_services'] ?? 0;
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- إجمالي العملاء -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">Total Clients</div>
                    <div class="card-body">
                        <h3>
                            <?php
                            $query = "SELECT COUNT(DISTINCT phone_number) AS total_clients FROM appointments";
                            try {
                                $result = $conn->query($query);
                                $data = $result->fetch(PDO::FETCH_ASSOC);
                                echo $data['total_clients'] ?? 0;
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="view_appointments.php" class="btn btn-primary btn-lg">View Appointments</a>
            <a href="add_service.php" class="btn btn-secondary btn-lg">Add Service</a>
            <a href="Service_order.php" class="btn btn-primary btn-lg">Service order</a>
        </div>
    </main>

    <?php include 'conDB/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
