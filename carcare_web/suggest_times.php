<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define("HOST", "localhost");
define("DBNAME", "CarCare");
define("USER", "root");
define("PASS", "");

try {
    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Database connection failed.");
}

// Debugging: Output POST data
error_log(print_r($_POST, true));

$serviceType = $_POST['service_type'] ?? null;
$appointmentDate = $_POST['appointment_date'] ?? null;

if (!$serviceType || !$appointmentDate) {
    die("Invalid input.");
}

// ... (rest of the script)
