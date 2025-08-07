<?php
session_start();
include 'conDB/DBConn.php';
include 'conDB/head.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger text-center mt-5'>No appointment ID provided.</div>";
    include 'conDB/footer.php';
    exit();
}

$appointment_id = intval($_GET['id']);

// Fetch the current payment status
$stmt = $conn->prepare("SELECT payment_status FROM appointments WHERE id = :id");
$stmt->bindParam(':id', $appointment_id, PDO::PARAM_INT);
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    echo "<div class='alert alert-danger text-center mt-5'>Appointment not found.</div>";
    include 'conDB/footer.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['payment_status'];

    $update_stmt = $conn->prepare("UPDATE appointments SET payment_status = :payment_status WHERE id = :id");
    $update_stmt->bindParam(':payment_status', $new_status);
    $update_stmt->bindParam(':id', $appointment_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Payment status updated successfully.";
        header("Location: view_appointments.php");
        exit();
    } else {
        $error_message = "Failed to update payment status. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Edit Payment Status</title>
    <style>
        body {
            margin-top: 80px;
            background-color: #f8f9fa;
        }
        .edit-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-container">
            <h3 class="text-center mb-4" style="color: #026dfe;">Edit Payment Status</h3>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="payment_status">Payment Status</label>
                    <select class="form-control" id="payment_status" name="payment_status" required>
                        <option value="Paid" <?php echo $appointment['payment_status'] === 'Paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="Not Paid" <?php echo $appointment['payment_status'] === 'Not Paid' ? 'selected' : ''; ?>>Not Paid</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update</button>
                <a href="view_appointments.php" class="btn btn-secondary btn-block">Cancel</a>
            </form>
        </div>
        <br><br><br>
    </div>
  
</body>
</html>
  <?php include 'conDB/footer.php'; ?>