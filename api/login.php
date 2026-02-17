<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    $firebaseConfig = require __DIR__ . '/../firebase-config.php';
    $apiKey = $firebaseConfig['apiKey'];
    $firebaseDbUrl = $firebaseConfig['databaseURL'];
    
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Get credentials
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        throw new Exception('Email and password are required');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Check if token is already provided (from client-side Firebase auth)
    if (isset($_POST['idToken']) && isset($_POST['localId'])) {
        // Token already validated by client
        $userId = $_POST['localId'];
        $_SESSION['user'] = $userId;
        $_SESSION['idToken'] = $_POST['idToken'];
        $_SESSION['email'] = $email;
        
        // Fetch user profile from Firebase Realtime Database
        $profileUrl = "$firebaseDbUrl/users/$userId.json";
        
        $profileData = @file_get_contents($profileUrl);
        if ($profileData !== false) {
            $profile = json_decode($profileData, true);
            if ($profile) {
                $_SESSION['fullName'] = $profile['fullName'] ?? '';
                $_SESSION['age'] = $profile['age'] ?? '';
                $_SESSION['gender'] = $profile['gender'] ?? '';
                
                // Update last login
                $updateUrl = "$firebaseDbUrl/users/$userId/lastLogin.json";
                $updateOptions = [
                    "http" => [
                        "header"  => "Content-type: application/json\r\n",
                        "method"  => "PUT",
                        "content" => json_encode(date('Y-m-d H:i:s'))
                    ]
                ];
                $updateContext = stream_context_create($updateOptions);
                @file_get_contents($updateUrl, false, $updateContext);
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => 'public/dashboard.php'
        ]);
        exit;
    }
    
    // Otherwise, authenticate with Firebase
    $url = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=$apiKey";
    
    $data = [
        "email" => $email,
        "password" => $password,
        "returnSecureToken" => true
    ];
    
    $options = [
        "http" => [
            "header"  => "Content-type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode($data),
            "ignore_errors" => true
        ]
    ];
    
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === false) {
        throw new Exception('Failed to connect to authentication service');
    }
    
    $response = json_decode($result, true);
    
    if (isset($response['idToken'])) {
        // Login successful
        $userId = $response['localId'];
        $_SESSION['user'] = $userId;
        $_SESSION['idToken'] = $response['idToken'];
        $_SESSION['email'] = $response['email'];
        
        // Fetch user profile from Firebase Realtime Database
        $profileUrl = "$firebaseDbUrl/users/$userId.json";
        
        $profileData = @file_get_contents($profileUrl);
        if ($profileData !== false) {
            $profile = json_decode($profileData, true);
            if ($profile) {
                $_SESSION['fullName'] = $profile['fullName'] ?? '';
                $_SESSION['age'] = $profile['age'] ?? '';
                $_SESSION['gender'] = $profile['gender'] ?? '';
                $_SESSION['profilePicture'] = $profile['profilePicture'] ?? '';
                
                // Update last login
                $updateUrl = "$firebaseDbUrl/users/$userId/lastLogin.json";
                $updateOptions = [
                    "http" => [
                        "header"  => "Content-type: application/json\r\n",
                        "method"  => "PUT",
                        "content" => json_encode(date('Y-m-d H:i:s'))
                    ]
                ];
                $updateContext = stream_context_create($updateOptions);
                @file_get_contents($updateUrl, false, $updateContext);
            }
        }
        
        // For direct form POST (not AJAX)
        if (!isset($_POST['idToken'])) {
            header("Location: ../public/dashboard.php");
            exit;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => 'public/dashboard.php'
        ]);
    } else {
        // Login failed
        $errorMessage = $response['error']['message'] ?? 'Unknown error';
        
        // Map Firebase errors to user-friendly messages
        $friendlyMessages = [
            'INVALID_PASSWORD' => 'Invalid email or password',
            'EMAIL_NOT_FOUND' => 'No account found with this email',
            'USER_DISABLED' => 'This account has been disabled',
            'TOO_MANY_ATTEMPTS_TRY_LATER' => 'Too many failed attempts. Please try again later',
            'INVALID_LOGIN_CREDENTIALS' => 'Invalid email or password'
        ];
        
        $userMessage = $friendlyMessages[$errorMessage] ?? 'Login failed. Please try again.';
        
        // For direct form POST
        if (!isset($_POST['idToken'])) {
            header("Location: ../index.html?error=" . urlencode($userMessage));
            exit;
        }
        
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => $userMessage
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
