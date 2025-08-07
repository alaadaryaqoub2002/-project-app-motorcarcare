<?php
session_start();

// Get JSON data from the request
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data && isset($data['userId']) && isset($data['email']) && isset($data['username'])) {
    // Store company user data in session
    $_SESSION['company_user'] = [
        'id' => $data['userId'],
        'email' => $data['email'],
        'username' => $data['username'],
        'role' => $data['role'],
        'logged_in' => true
    ];

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful'
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
