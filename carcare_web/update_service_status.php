<?php
session_start();
include 'conDB/DBConn.php';

// تحقق من إرسال بيانات النموذج عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['action'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];

    // تحديد الحالة الجديدة بناءً على الإجراء
    if ($action == 'approve') {
        $new_status = 'Approved';
    } elseif ($action == 'reject') {
        $new_status = 'Rejected';
    } else {
        // في حال كانت القيمة غير معروفة
        $_SESSION['status_message'] = 'Invalid action.';
        $_SESSION['status_message_type'] = 'danger';
        header("Location: service_orders.php");
        exit();
    }

    try {
        // تحديث حالة الخدمة في قاعدة البيانات
        $stmt = $conn->prepare("UPDATE appointments SET service_status = :service_status WHERE id = :appointment_id");
        $stmt->bindParam(':service_status', $new_status);
        $stmt->bindParam(':appointment_id', $appointment_id);
        $stmt->execute();

        // التحقق من نجاح العملية
        if ($stmt->rowCount() > 0) {
            $_SESSION['status_message'] = 'Service status updated successfully!';
            $_SESSION['status_message_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'Failed to update the service status.';
            $_SESSION['status_message_type'] = 'danger';
        }
    } catch (PDOException $e) {
        // في حال حدوث خطأ في الاستعلام
        $_SESSION['status_message'] = 'Error: ' . $e->getMessage();
        $_SESSION['status_message_type'] = 'danger';
    }

    // إعادة توجيه المستخدم إلى صفحة الطلبات
    header("Location: service_order.php");
    exit();
} else {
    // إذا لم يتم إرسال البيانات عبر POST
    $_SESSION['status_message'] = 'Invalid request.';
    $_SESSION['status_message_type'] = 'danger';
    header("Location: service_order.php");
    exit();
}
?>
