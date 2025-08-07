<?php
session_start();

// Get JSON data from the request
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data && isset($data['username'])) {
    // Update username in session

//    die($data['username']);
    $_SESSION['user']['username'] = $data['username'];

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Session updated successfully'
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
