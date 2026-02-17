<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Check if user is logged in
    if (!isset($_SESSION['user']) || !isset($_SESSION['email'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Not authenticated'
        ]);
        exit;
    }

    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    // Validate input
    if (empty($currentPassword) || empty($newPassword)) {
        throw new Exception('Current password and new password are required');
    }

    if (strlen($newPassword) < 6) {
        throw new Exception('New password must be at least 6 characters');
    }

    $email = $_SESSION['email'];
    $firebaseConfig = require __DIR__ . '/../firebase-config.php';
    $apiKey = $firebaseConfig['apiKey'];

    // Step 1: Verify current password by attempting to sign in
    $verifyUrl = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=$apiKey";
    
    $verifyData = [
        "email" => $email,
        "password" => $currentPassword,
        "returnSecureToken" => true
    ];

    $verifyOptions = [
        "http" => [
            "header"  => "Content-type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode($verifyData),
            "ignore_errors" => true
        ]
    ];

    $verifyContext = stream_context_create($verifyOptions);
    $verifyResult = @file_get_contents($verifyUrl, false, $verifyContext);

    if ($verifyResult === false) {
        throw new Exception('Failed to verify current password');
    }

    $verifyResponse = json_decode($verifyResult, true);

    if (!isset($verifyResponse['idToken'])) {
        // Current password is incorrect
        throw new Exception('Current password is incorrect');
    }

    // Step 2: Change password using Firebase REST API
    $changeUrl = "https://identitytoolkit.googleapis.com/v1/accounts:update?key=$apiKey";
    
    $changeData = [
        "idToken" => $verifyResponse['idToken'],
        "password" => $newPassword,
        "returnSecureToken" => true
    ];

    $changeOptions = [
        "http" => [
            "header"  => "Content-type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode($changeData),
            "ignore_errors" => true
        ]
    ];

    $changeContext = stream_context_create($changeOptions);
    $changeResult = @file_get_contents($changeUrl, false, $changeContext);

    if ($changeResult === false) {
        throw new Exception('Failed to change password');
    }

    $changeResponse = json_decode($changeResult, true);

    if (isset($changeResponse['idToken'])) {
        // Password changed successfully
        // Update session with new token
        $_SESSION['idToken'] = $changeResponse['idToken'];

        echo json_encode([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    } else {
        // Password change failed
        $errorMessage = $changeResponse['error']['message'] ?? 'Failed to change password';
        throw new Exception($errorMessage);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
