<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '0');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Get JSON data from request body
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : '';
    $otp = isset($input['otp']) ? trim($input['otp']) : '';

    if (!$email || !$otp) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing email or OTP']);
        exit;
    }

    // Read OTP from file
    $otp_file = __DIR__ . '/.otps/' . md5($email) . '.json';
    
    if (!file_exists($otp_file)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'OTP not found. Request a new one.']);
        exit;
    }
    
    $otp_content = @file_get_contents($otp_file);
    if ($otp_content === false) {
        throw new Exception('Unable to read OTP file');
    }
    
    $otp_data = json_decode($otp_content, true);
    if (!$otp_data) {
        throw new Exception('Invalid OTP data');
    }
    
    // Check if OTP is expired
    if ($otp_data['expires'] < time()) {
        @unlink($otp_file);
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'OTP has expired. Request a new one.']);
        exit;
    }
    
    // Check if OTP was already used
    if ($otp_data['verified'] === true) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'OTP already used. Request a new one.']);
        exit;
    }
    
    // Verify OTP code
    if ($otp_data['otp'] !== $otp) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid OTP code']);
        exit;
    }
    
    // Mark OTP as verified
    $otp_data['verified'] = true;
    $otp_data['verified_at'] = time();
    @file_put_contents($otp_file, json_encode($otp_data));
    
    echo json_encode([
        'success' => true,
        'message' => 'OTP verified successfully',
        'email' => $email
    ]);
    
} catch (Exception $error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $error->getMessage()
    ]);
}
?>
