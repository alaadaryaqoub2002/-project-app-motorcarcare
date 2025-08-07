<?php
require 'conDB/DBConn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM services WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: view_services.php");
        exit();
    } else {
        echo "Error deleting service.";
    }
}
?>
