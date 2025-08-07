<?php
include 'conDB/DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
    $message = isset($_POST['message']) ? $_POST['message'] : null;

    if ($name && $email && $message) {
        try {
            $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':subject' => $subject,
                ':message' => $message
            ]);

            echo "<script>
                alert('Your message has been sent successfully!');
                window.location.href = 'index.php';
            </script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<script>
            alert('Please fill in all required fields.');
            window.history.back();
        </script>";
    }
}
?>
