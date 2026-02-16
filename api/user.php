<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Not authenticated'
        ]);
        exit;
    }
    
    $userId = $_SESSION['user'];
    $firebaseDbUrl = "https://moneytrackerweb-default-rtdb.firebaseio.com";
    
    // Handle GET request - Retrieve user profile
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $profileUrl = "$firebaseDbUrl/users/$userId.json";
        
        $profileData = @file_get_contents($profileUrl);
        
        if ($profileData === false) {
            throw new Exception('Failed to retrieve user profile');
        }
        
        $profile = json_decode($profileData, true);
        
        if (!$profile) {
            // Return session data if Firebase data not available
            $profile = [
                'email' => $_SESSION['email'] ?? '',
                'fullName' => $_SESSION['fullName'] ?? '',
                'age' => $_SESSION['age'] ?? '',
                'gender' => $_SESSION['gender'] ?? '',
                'profilePicture' => $_SESSION['profilePicture'] ?? ''
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $profile
        ]);
        exit;
    }
    
    // Handle PUT/POST request - Update user profile
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $action = $_POST['action'] ?? 'updateProfile';
        
        // Handle profile picture upload
        if ($action === 'updateProfilePicture') {
            $profilePicture = $_POST['profilePicture'] ?? '';
            
            if (empty($profilePicture)) {
                throw new Exception('No profile picture data provided');
            }
            
            // Validate base64 image data
            if (!preg_match('/^data:image\/(jpeg|jpg|png|gif);base64,/', $profilePicture)) {
                throw new Exception('Invalid image format');
            }
            
            // Check image size (approximate - base64 is ~33% larger)
            $imageSize = strlen($profilePicture) * 0.75;
            if ($imageSize > 2 * 1024 * 1024) {
                throw new Exception('Image size exceeds 2MB limit');
            }
            
            // Update Firebase
            $updateUrl = "$firebaseDbUrl/users/$userId.json";
            $updateData = [
                'profilePicture' => $profilePicture,
                'updatedAt' => date('Y-m-d H:i:s')
            ];
            
            $options = [
                "http" => [
                    "header"  => "Content-type: application/json\r\n",
                    "method"  => "PATCH",
                    "content" => json_encode($updateData),
                    "ignore_errors" => true
                ]
            ];
            
            $context = stream_context_create($options);
            $result = @file_get_contents($updateUrl, false, $context);
            
            if ($result === false) {
                throw new Exception('Failed to update profile picture');
            }
            
            // Update session
            $_SESSION['profilePicture'] = $profilePicture;
            
            echo json_encode([
                'success' => true,
                'message' => 'Profile picture updated successfully'
            ]);
            exit;
        }
        
        // Handle remove profile picture
        if ($action === 'removeProfilePicture') {
            $updateUrl = "$firebaseDbUrl/users/$userId.json";
            $updateData = [
                'profilePicture' => '',
                'updatedAt' => date('Y-m-d H:i:s')
            ];
            
            $options = [
                "http" => [
                    "header"  => "Content-type: application/json\r\n",
                    "method"  => "PATCH",
                    "content" => json_encode($updateData),
                    "ignore_errors" => true
                ]
            ];
            
            $context = stream_context_create($options);
            $result = @file_get_contents($updateUrl, false, $context);
            
            if ($result === false) {
                throw new Exception('Failed to remove profile picture');
            }
            
            // Update session
            $_SESSION['profilePicture'] = '';
            
            echo json_encode([
                'success' => true,
                'message' => 'Profile picture removed successfully'
            ]);
            exit;
        }
        
        // Handle regular profile update
        $fullName = $_POST['fullName'] ?? '';
        $age = $_POST['age'] ?? '';
        $gender = $_POST['gender'] ?? '';
        
        // Validate age if provided
        if (!empty($age)) {
            $age = intval($age);
            if ($age < 13 || $age > 120) {
                throw new Exception('Invalid age');
            }
        }
        
        // Prepare update data
        $updateData = [];
        if (!empty($fullName)) $updateData['fullName'] = $fullName;
        if (!empty($age)) $updateData['age'] = $age;
        if (!empty($gender)) $updateData['gender'] = $gender;
        $updateData['updatedAt'] = date('Y-m-d H:i:s');
        
        if (count($updateData) <= 1) {
            throw new Exception('No data to update');
        }
        
        // Update Firebase Realtime Database
        $updateUrl = "$firebaseDbUrl/users/$userId.json";
        
        $options = [
            "http" => [
                "header"  => "Content-type: application/json\r\n",
                "method"  => "PATCH",
                "content" => json_encode($updateData),
                "ignore_errors" => true
            ]
        ];
        
        $context = stream_context_create($options);
        $result = @file_get_contents($updateUrl, false, $context);
        
        if ($result === false) {
            throw new Exception('Failed to update user profile');
        }
        
        // Update session
        if (!empty($fullName)) $_SESSION['fullName'] = $fullName;
        if (!empty($age)) $_SESSION['age'] = $age;
        if (!empty($gender)) $_SESSION['gender'] = $gender;
        
        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $updateData
        ]);
        exit;
    }
    
    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
