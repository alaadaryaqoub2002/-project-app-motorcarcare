<?php
session_start();
include 'conDB/DBConn.php';
include 'conDB/head.php'; // Include header

if (!isset($_GET['id'])) {
    die("No appointment ID provided.");
}

$appointment_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['service_status'];

    $stmt = $conn->prepare("UPDATE appointments SET service_status = :service_status WHERE id = :id");
    $stmt->bindParam(':service_status', $new_status);
    $stmt->bindParam(':id', $appointment_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: view_appointments.php");
        exit();
    } else {
        $error = "Failed to update service status.";
    }
}

$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = :id");
$stmt->bindParam(':id', $appointment_id, PDO::PARAM_INT);
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    die("Appointment not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service Status</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin-top: 80px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #026dfe;
            border-color: #026dfe;
        }
        .btn-primary:hover {
            background-color: #0056cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4" style="color: #026dfe;">Edit Service Status</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="service_status" style="color: #026dfe;">Service Status</label>
                    <select name="service_status" id="service_status" class="form-control">
                        <option value="Booked" <?php echo $appointment['service_status'] === 'Booked' ? 'selected' : ''; ?>>Booked</option>
                        <option value="In Progress" <?php echo $appointment['service_status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo $appointment['service_status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="Cancelled" <?php echo $appointment['service_status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-4">Save Changes</button>
                <a href="view_appointments.php" class="btn btn-secondary btn-block mt-2">Cancel</a>
            </form>
        </div>
        <br><br><br>
    </div>

    <?php include 'conDB/footer.php'; ?>
</body>
</html>
