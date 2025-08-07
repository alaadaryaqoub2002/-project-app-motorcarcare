<?php
session_start();
include 'conDB/DBConn.php';
include 'conDB/head-company.php';

try {
    // استعلام لجلب جميع الطلبات مع تحديثات بناءً على التعديلات
    $stmt = $conn->prepare("SELECT 
        a.id, 
        u.username AS user_name, 
        a.card_number, 
        a.chassis_number, 
        a.car_type, 
        a.service_status1,  -- تم تغيير الاسم هنا
        a.appointment_date, 
        a.appointment_time, 
        s.name AS service_name  
    FROM appointments a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN services s ON a.service_id = s.id
    ORDER BY a.appointment_date DESC
    LIMIT 10");
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching appointments: " . $e->getMessage();
    exit();
}

if (isset($_POST['action']) && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action']; // إما 'approve' أو 'disapprove'

    try {
        // تحديد حالة الخدمة بناءً على العمل الذي اختاره المسؤول
        if ($action == 'approve') {
            $new_status = 'Approved';
            $_SESSION['status_message'] = "Appointment approved successfully!";
            $_SESSION['status_message_type'] = "success";
        } elseif ($action == 'disapprove') {
            $new_status = 'Disapproved';
            $_SESSION['status_message'] = "Appointment disapproved.";
            $_SESSION['status_message_type'] = "danger";
        } else {
            throw new Exception('Invalid action.');
        }

        // استعلام لتحديث حالة الخدمة في قاعدة البيانات
        $stmt = $conn->prepare("UPDATE appointments SET service_status1 = ? WHERE id = ?");  // تم تغيير الاسم هنا
        $stmt->execute([$new_status, $appointment_id]);

        // إعادة توجيه المسؤول إلى نفس الصفحة
        header("Location: service_order.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Service Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-sm {
            margin: 5px;
        }
        .header-title {
            color: #026dfe;
            font-weight: bold;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <br><br><br>
        <h2 class="text-center mb-4 header-title">Service Orders</h2>

        <?php if (empty($appointments)): ?>
            <p class="text-center">No service orders found.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Card Number</th>
                        <th>Chassis Number</th>
                        <th>Car Type</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['user_name']); ?></td>
                            <td><?= htmlspecialchars($appointment['card_number']); ?></td>
                            <td><?= htmlspecialchars($appointment['chassis_number']); ?></td>
                            <td><?= htmlspecialchars($appointment['car_type']); ?></td>
                            <td><?= htmlspecialchars($appointment['service_name']); ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>
                            <td>
                                <?php if ($appointment['service_status1'] == 'Approved'): ?> <!-- تم تغيير الاسم هنا -->
                                    <span class="badge badge-success">
                                        Approved
                                    </span>
                                <?php elseif ($appointment['service_status1'] == 'Disapproved'): ?> <!-- تم تغيير الاسم هنا -->
                                    <span class="badge badge-danger">
                                        Disapproved
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        <?= htmlspecialchars($appointment['service_status1']); ?> <!-- تم تغيير الاسم هنا -->
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <form method="POST" action="service_order.php">
                                    <input type="hidden" name="appointment_id" value="<?= $appointment['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    <button type="submit" name="action" value="disapprove" class="btn btn-danger btn-sm">Disapprove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Display message when action performed -->
    <?php if (isset($_SESSION['status_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['status_message_type']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['status_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['status_message']); ?>
        <?php unset($_SESSION['status_message_type']); ?>
    <?php endif; ?>

    <!-- JS for Bootstrap alerts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php include 'conDB/footer.php'; ?>
