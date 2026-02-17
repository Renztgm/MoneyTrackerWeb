<?php
/**
 * Gmail Configuration for Password Reset Emails
 * 
 * To get your Gmail app password:
 * 1. Enable 2-Step Verification on your Google Account
 * 2. Go to https://myaccount.google.com/apppasswords
 * 3. Select Mail and Windows PC
 * 4. Copy the 16-character password generated
 * 5. Paste it below
 */

return [    
    'enabled' => true,
    'gmail_email' => 'baconjhonlorenz@gmail.com',      // Your Gmail address
    'gmail_app_password' => 'narb yrhf wars qtzk',  // 16-character app password (NOT your Google password)
    'sender_name' => 'Money Tracker',             // Name shown in email sender
    'reset_link_base' => 'http://localhost:3000', // Change to your domain in production
];
?>
