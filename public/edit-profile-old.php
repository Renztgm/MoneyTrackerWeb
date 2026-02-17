<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.html");
    exit;
}

$fullName = $_SESSION['fullName'] ?? 'User';
$email = $_SESSION['email'] ?? '';
$age = $_SESSION['age'] ?? '';
$gender = $_SESSION['gender'] ?? '';
$profilePicture = $_SESSION['profilePicture'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Money Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .profile-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-card h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .profile-picture-section {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }

        .profile-picture-container {
            position: relative;
            display: inline-block;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #667eea;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: #667eea;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .upload-btn-wrapper {
            position: absolute;
            bottom: 0;
            right: 0;
        }

        .upload-btn {
            background: #667eea;
            color: white;
            border: 3px solid white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: background 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .upload-btn:hover {
            background: #764ba2;
        }

        .upload-btn input[type=file] {
            display: none;
        }

        .picture-hint {
            margin-top: 15px;
            color: #666;
            font-size: 13px;
        }

        .remove-picture-btn {
            margin-top: 10px;
            background: none;
            border: none;
            color: #f44336;
            font-size: 13px;
            cursor: pointer;
            text-decoration: underline;
        }

        .form-section {
            margin-bottom: 40px;
        }

        .section-title {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group input:disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .message.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        .message.success {
            background: #efe;
            border: 1px solid #cfc;
            color: #3c3;
        }

        .message.error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .password-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>✏️ Edit Profile</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <h2>Update Your Profile</h2>

            <div id="messageDiv" class="message"></div>

            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
                <div class="profile-picture-container">
                    <div class="profile-picture" id="profilePicturePreview">
                        <?php if (!empty($profilePicture)): ?>
                            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <span>👤</span>
                        <?php endif; ?>
                    </div>
                    <div class="upload-btn-wrapper">
                        <label for="profilePictureInput" class="upload-btn">
                            📷
                            <input type="file" id="profilePictureInput" accept="image/*">
                        </label>
                    </div>
                </div>
                <p class="picture-hint">Click the camera icon to upload a profile picture (max 2MB)</p>
                <?php if (!empty($profilePicture)): ?>
                    <button class="remove-picture-btn" id="removePictureBtn">Remove picture</button>
                <?php endif; ?>
            </div>

            <!-- Personal Information Section -->
            <form id="profileForm">
                <div class="form-section">
                    <h3 class="section-title">Personal Information</h3>
                    
                    <div class="form-group">
                        <label for="fullName">Full Name *</label>
                        <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age *</label>
                            <input type="number" id="age" name="age" min="13" max="120" value="<?php echo htmlspecialchars($age); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select</option>
                                <option value="male" <?php echo $gender === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo $gender === 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo $gender === 'other' ? 'selected' : ''; ?>>Other</option>
                                <option value="prefer-not-to-say" <?php echo $gender === 'prefer-not-to-say' ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                        <p class="password-hint">Email cannot be changed</p>
                    </div>
                </div>

                <button type="submit" class="btn-primary" id="saveProfileBtn">Save Changes</button>
            </form>

            <!-- Password Change Section -->
            <form id="passwordForm" style="margin-top: 40px;">
                <div class="form-section">
                    <h3 class="section-title">Change Password</h3>
                    
                    <div class="form-group">
                        <label for="currentPassword">Current Password *</label>
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter current password">
                        <p class="password-hint">Leave blank if you don't want to change password</p>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password *</label>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password (min. 6 characters)">
                    </div>

                    <div class="form-group">
                        <label for="confirmNewPassword">Confirm New Password *</label>
                        <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm new password">
                    </div>
                </div>

                <button type="submit" class="btn-primary" id="changePasswordBtn">Change Password</button>
            </form>
        </div>
    </div>

    <script src="../firebase-config.php"></script>
    <script>
        const FIREBASE_API_KEY = window.FIREBASE_API_KEY;
        let uploadedImageData = null;

        // Profile Picture Upload Handler
        document.getElementById('profilePictureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showMessage('Image size must be less than 2MB', 'error');
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showMessage('Please select a valid image file', 'error');
                return;
            }

            // Read and preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgData = e.target.result;
                uploadedImageData = imgData;
                
                // Update preview
                document.getElementById('profilePicturePreview').innerHTML = 
                    `<img src="${imgData}" alt="Profile Picture">`;
                
                // Upload immediately
                uploadProfilePicture(imgData);
            };
            reader.readAsDataURL(file);
        });

        // Remove Picture Handler
        const removePictureBtn = document.getElementById('removePictureBtn');
        if (removePictureBtn) {
            removePictureBtn.addEventListener('click', async function() {
                if (!confirm('Are you sure you want to remove your profile picture?')) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('action', 'removeProfilePicture');

                    const response = await fetch('../api/user.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        document.getElementById('profilePicturePreview').innerHTML = '<span>👤</span>';
                        this.style.display = 'none';
                        showMessage('Profile picture removed successfully', 'success');
                    } else {
                        throw new Error(data.error || 'Failed to remove picture');
                    }
                } catch (error) {
                    showMessage(error.message, 'error');
                }
            });
        }

        async function uploadProfilePicture(imageData) {
            try {
                const formData = new FormData();
                formData.append('profilePicture', imageData);
                formData.append('action', 'updateProfilePicture');

                const response = await fetch('../api/user.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showMessage('Profile picture updated successfully!', 'success');
                    
                    // Show remove button if not visible
                    const removeBtn = document.getElementById('removePictureBtn');
                    if (removeBtn) {
                        removeBtn.style.display = 'inline-block';
                    }
                } else {
                    throw new Error(data.error || 'Failed to upload picture');
                }
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        // Profile Form Handler
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value.trim();
            const age = document.getElementById('age').value;
            const gender = document.getElementById('gender').value;

            if (!fullName || fullName.length < 2) {
                showMessage('Please enter a valid full name', 'error');
                return;
            }

            if (age < 13 || age > 120) {
                showMessage('Age must be between 13 and 120', 'error');
                return;
            }

            const saveBtn = document.getElementById('saveProfileBtn');
            const originalText = saveBtn.textContent;
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            try {
                const formData = new FormData();
                formData.append('fullName', fullName);
                formData.append('age', age);
                formData.append('gender', gender);
                formData.append('action', 'updateProfile');

                const response = await fetch('../api/user.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showMessage('Profile updated successfully!', 'success');
                } else {
                    throw new Error(data.error || 'Failed to update profile');
                }
            } catch (error) {
                showMessage(error.message, 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = originalText;
            }
        });

        // Password Form Handler
        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            if (!currentPassword) {
                showMessage('Please enter your current password', 'error');
                return;
            }

            if (!newPassword || newPassword.length < 6) {
                showMessage('New password must be at least 6 characters', 'error');
                return;
            }

            if (newPassword !== confirmNewPassword) {
                showMessage('New passwords do not match', 'error');
                return;
            }

            const changeBtn = document.getElementById('changePasswordBtn');
            const originalText = changeBtn.textContent;
            changeBtn.disabled = true;
            changeBtn.textContent = 'Changing Password...';

            try {
                const formData = new FormData();
                formData.append('currentPassword', currentPassword);
                formData.append('newPassword', newPassword);

                const response = await fetch('../api/change-password.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showMessage('Password changed successfully!', 'success');
                    document.getElementById('passwordForm').reset();
                } else {
                    throw new Error(data.error || 'Failed to change password');
                }
            } catch (error) {
                showMessage(error.message, 'error');
            } finally {
                changeBtn.disabled = false;
                changeBtn.textContent = originalText;
            }
        });

        function showMessage(message, type) {
            const messageDiv = document.getElementById('messageDiv');
            messageDiv.textContent = message;
            messageDiv.className = `message ${type} show`;

            setTimeout(() => {
                messageDiv.classList.remove('show');
            }, 5000);
        }
    </script>
</body>
</html>
