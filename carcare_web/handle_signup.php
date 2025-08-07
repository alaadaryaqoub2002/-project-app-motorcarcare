<?php
session_start();

// Get JSON data from the request
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data) {
    // Store user data in session
    $_SESSION['user'] = [
        'id' => $data['userId'],
        'username' => $data['username'],
        'email' => $data['email'],
        'logged_in' => true
    ];

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Session created successfully'
    ]);
} else {
    // Send error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid data received'
    ]);
}
?>
