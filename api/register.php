<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Get user data
    $userId = $_POST['userId'] ?? '';
    $email = $_POST['email'] ?? '';
    $fullName = $_POST['fullName'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $idToken = $_POST['idToken'] ?? '';
    
    // Validate required fields
    if (empty($userId) || empty($email) || empty($fullName) || empty($age)) {
        throw new Exception('Missing required fields');
    }
    
    // Validate age
    $age = intval($age);
    if ($age < 13 || $age > 120) {
        throw new Exception('Invalid age');
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Firebase Realtime Database URL (replace with your database URL)
    // Format: https://your-project-id.firebaseio.com/
    $firebaseDbUrl = "https://moneytrackerweb-default-rtdb.firebaseio.com";
    
    // Prepare user data
    $userData = [
        'email' => $email,
        'fullName' => $fullName,
        'age' => $age,
        'gender' => $gender,
        'createdAt' => date('Y-m-d H:i:s'),
        'lastLogin' => date('Y-m-d H:i:s')
    ];
    
    // Save to Firebase Realtime Database
    $url = "$firebaseDbUrl/users/$userId.json";
    
    // If you have idToken, you can authenticate the request
    if (!empty($idToken)) {
        $url .= "?auth=$idToken";
    }
    
    $options = [
        "http" => [
            "header"  => "Content-type: application/json\r\n",
            "method"  => "PUT",
            "content" => json_encode($userData),
            "ignore_errors" => true
        ]
    ];
    
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === false) {
        // If Firebase Database fails, we can still proceed with local session
        // Log the error but don't fail the registration
        error_log("Firebase Database write failed for user $userId");
    }
    
    // Create session for immediate login
    $_SESSION['user'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['fullName'] = $fullName;
    $_SESSION['age'] = $age;
    $_SESSION['gender'] = $gender;
    $_SESSION['idToken'] = $idToken;
    
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful',
        'userId' => $userId
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
