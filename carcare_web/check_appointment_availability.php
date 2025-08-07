<?php
include 'conDB/DBConn.php';

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if (!$data['appointment_date'] || !$data['appointment_time']) {
    $response['error'] = 'Date and Time are required.';
    echo json_encode($response);
    exit();
}

$query = "SELECT * FROM appointments WHERE appointment_date = :appointment_date AND appointment_time = :appointment_time";
$stmt = $conn->prepare($query);
$stmt->bindParam(':appointment_date', $data['appointment_date']);
$stmt->bindParam(':appointment_time', $data['appointment_time']);
$stmt->execute();
$conflict = $stmt->fetch(PDO::FETCH_ASSOC);

if ($conflict) {
    $response['error'] = 'This time slot is already booked.';
    $response['suggested_time'] = date('H:i', strtotime($data['appointment_time'] . ' +1 hour'));
} else {
    $insert = "INSERT INTO appointments (name, phone_number, card_number, chassis_number, car_type, appointment_date, appointment_time, service_type) 
               VALUES (:name, :phone_number, :card_number, :chassis_number, :car_type, :appointment_date, :appointment_time, :service_type)";
    $stmt = $conn->prepare($insert);

    foreach ($data as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }

    if ($stmt->execute()) {
        $response['message'] = 'Appointment booked successfully.';
    } else {
        $response['error'] = 'Failed to book the appointment.';
    }
}

echo json_encode($response);
?>
