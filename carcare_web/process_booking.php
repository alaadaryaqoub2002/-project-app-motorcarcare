<?php
require 'conDB/DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $appointment_date = $_POST['appointment_date'];

    $query = "INSERT INTO appointment (service_id, name, phone, appointment_date) VALUES (:service_id, :name, :phone, :appointment_date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':service_id', $service_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':appointment_date', $appointment_date);

    if ($stmt->execute()) {
        echo "<script>alert('Your appointment has been booked successfully!'); window.location.href='services.php';</script>";
        header("Location: message/success_booking.php");
    } else {
        header("Location: message/error_page.php");
    }
}
?>
