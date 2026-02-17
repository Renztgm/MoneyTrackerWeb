<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Don't display errors directly, we'll handle them

try {
    require_once __DIR__ . '/../config/gmail.php';

    $gmail_config = require __DIR__ . '/../config/gmail.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Get JSON data from request body
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : '';

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
        exit;
    }

    // Generate 6-digit OTP
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $otp_expires = time() + (10 * 60); // 10 minutes expiry
    
    // Create OTP storage directory
    $otp_dir = __DIR__ . '/.otps';
    if (!is_dir($otp_dir)) {
        @mkdir($otp_dir, 0777, true);
    }
    
    // Store OTP (using email as filename for easy lookup)
    $otp_file = $otp_dir . '/' . md5($email) . '.json';
    $otp_data = [
        'email' => $email,
        'otp' => $otp,
        'expires' => $otp_expires,
        'verified' => false,
        'created' => time()
    ];
    
    $write_result = @file_put_contents($otp_file, json_encode($otp_data));
    if ($write_result === false) {
        throw new Exception('Unable to save OTP. Try again later.');
    }
    
    // Send OTP via Gmail SMTP
    if ($gmail_config['enabled']) {
        try {
            sendOtpEmail($email, $otp, $gmail_config);
        } catch (Exception $sendError) {
            // Email sending failed, but OTP is still stored - we'll return it in dev_info for testing
            error_log('Email send error: ' . $sendError->getMessage());
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'OTP sent to your email. Check browser console for dev_info if you don\'t see an email.',
        'dev_info' => ['otp' => $otp, 'stored_at' => date('Y-m-d H:i:s')] // For testing only
    ]);
    
} catch (Exception $error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send OTP: ' . $error->getMessage()
    ]);
}

function sendOtpEmail($email, $otp, $gmail_config) {
    $smtp_host = 'smtp.gmail.com';
    $smtp_port = 587;
    $smtp_user = $gmail_config['gmail_email'];
    $smtp_pass = $gmail_config['gmail_app_password'];
    $sender_name = $gmail_config['sender_name'];
    
    if ($smtp_user === 'your-email@gmail.com' || $smtp_pass === 'your-app-password') {
        throw new Exception('Gmail credentials not configured');
    }
    
    $subject = 'Email Verification Code - ' . $sender_name;
    
    $html_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
            .content { background: white; padding: 30px; border-radius: 8px; }
            .otp-box { 
                background: #f0f4ff; 
                border: 2px solid #667eea;
                padding: 20px; 
                text-align: center; 
                border-radius: 8px;
                margin: 20px 0;
            }
            .otp-code {
                font-size: 36px;
                font-weight: bold;
                color: #667eea;
                letter-spacing: 8px;
                font-family: 'Courier New', monospace;
            }
            .footer { font-size: 12px; color: #999; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='content'>
                <h2>Verify Your Email</h2>
                <p>Hi,</p>
                <p>Thank you for signing up! Please verify your email using the code below:</p>
                
                <div class='otp-box'>
                    <p style='color: #999; margin: 0 0 10px 0; font-size: 12px;'>VERIFICATION CODE</p>
                    <div class='otp-code'>{$otp}</div>
                </div>
                
                <p>This code will expire in 10 minutes.</p>
                <p>If you didn't create this account, you can safely ignore this email.</p>
                
                <div class='footer'>
                    <p>— {$sender_name} Team</p>
                </div>
            </div>
        </div>
    </body>
    </html>";
    
    sendSmtpEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $email, $subject, $html_body, $sender_name);
}

function sendSmtpEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $recipient, $subject, $html_body, $sender_name) {
    $socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);
    
    if (!$socket) {
        throw new Exception("Failed to connect to SMTP: $errstr");
    }
    
    stream_set_timeout($socket, 10);
    
    $response = fgets($socket, 515);
    if (strpos($response, '220') === false) {
        fclose($socket);
        throw new Exception('SMTP server error');
    }
    
    fputs($socket, "EHLO localhost\r\n");
    $response = '';
    do {
        $line = fgets($socket, 515);
        $response .= $line;
    } while (strpos($line, '250 ') === false && strpos($line, '250-') !== false);
    
    fputs($socket, "STARTTLS\r\n");
    $response = fgets($socket, 515);
    
    if (strpos($response, '220') === false) {
        fclose($socket);
        throw new Exception('STARTTLS failed');
    }
    
    $crypto_method = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
    if (!stream_socket_enable_crypto($socket, true, $crypto_method)) {
        fclose($socket);
        throw new Exception('TLS encryption failed');
    }
    
    fputs($socket, "EHLO localhost\r\n");
    $response = '';
    do {
        $line = fgets($socket, 515);
        $response .= $line;
    } while (strpos($line, '250 ') === false && strpos($line, '250-') !== false);
    
    fputs($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 515);
    
    fputs($socket, base64_encode($smtp_user) . "\r\n");
    $response = fgets($socket, 515);
    
    fputs($socket, base64_encode($smtp_pass) . "\r\n");
    $response = fgets($socket, 515);
    if (strpos($response, '235') === false) {
        fclose($socket);
        throw new Exception('Authentication failed');
    }
    
    fputs($socket, "MAIL FROM: <{$smtp_user}>\r\n");
    $response = fgets($socket, 515);
    
    fputs($socket, "RCPT TO: <{$recipient}>\r\n");
    $response = fgets($socket, 515);
    
    fputs($socket, "DATA\r\n");
    $response = fgets($socket, 515);
    
    $email_content = "From: {$sender_name} <{$smtp_user}>\r\n";
    $email_content .= "To: <{$recipient}>\r\n";
    $email_content .= "Subject: {$subject}\r\n";
    $email_content .= "MIME-Version: 1.0\r\n";
    $email_content .= "Content-Type: text/html; charset=UTF-8\r\n";
    $email_content .= "\r\n";
    $email_content .= $html_body . "\r\n";
    $email_content .= ".\r\n";
    
    fputs($socket, $email_content);
    $response = fgets($socket, 515);
    
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    if (strpos($response, '250') === false) {
        throw new Exception('Failed to send email');
    }
}
?>
