<?php
include 'conDB/DBConn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    try {
        $stmt = $conn->prepare("
            SELECT id, username, password 
            FROM users 
            WHERE username = :username OR email = :username
        ");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            header("Location: message/error.php?message=Invalid username or password");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: message/error.php?message=An unexpected error occurred. Please try again.");
        exit;
    }
}
?>
