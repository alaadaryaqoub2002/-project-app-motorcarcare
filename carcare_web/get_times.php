<?php
include 'conDB/DBConn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];

    $query = "SELECT appointment_time FROM appointments WHERE appointment_date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookedTimes = [];
    while ($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row['appointment_time'];
    }

    $allTimes = ["10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM"];
    $availableTimes = array_diff($allTimes, $bookedTimes);

    echo json_encode($availableTimes);
}
?>
