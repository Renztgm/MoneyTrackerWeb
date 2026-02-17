<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Money Tracker</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#667eea">
    <link rel="icon" href="/assets/pwa-icon-192.svg">
    <link rel="apple-touch-icon" href="/assets/pwa-icon-192.svg">
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

        body.dark-mode {
            background: #0f1115;
            color: #e6e9ef;
        }

        .hidden {
            display: none;
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-screen.hidden {
            display: none;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: white;
            font-size: 18px;
            margin-top: 20px;
            font-weight: 500;
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
            cursor: pointer;
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

        body.dark-mode .profile-card {
            background: #1b1f26;
            color: #e6e9ef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }

        .profile-card h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        body.dark-mode .profile-card h2 {
            color: #e6e9ef;
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

        body.dark-mode .section-title {
            color: #e6e9ef;
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

        body.dark-mode .form-group label {
            color: #cfd6e4;
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

        body.dark-mode .form-group input,
        body.dark-mode .form-group select {
            background: #141820;
            color: #e6e9ef;
            border-color: #2a2f3a;
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

        body.dark-mode .form-group input:disabled {
            background: #171b22;
            color: #9aa3b2;
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
            background: #e8f5e9;
            border: 1px solid #4caf50;
            color: #2e7d32;
        }

        .message.error {
            background: #ffebee;
            border: 1px solid #f44336;
            color: #c62828;
        }

        .install-container {
            text-align: center;
            margin-top: 10px;
            padding-top: 24px;
            border-top: 1px solid #e6e8ef;
        }

        body.dark-mode .install-container {
            border-top-color: #2a2f3a;
        }

        .install-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        body.dark-mode .install-text {
            color: #9aa3b2;
        }

        .install-btn {
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .install-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.25);
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

        body.dark-mode .password-hint {
            color: #9aa3b2;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #cfd6e4;
            border-radius: 999px;
            transition: background 0.3s;
        }

        .toggle-slider::before {
            position: absolute;
            content: '';
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: #667eea;
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(24px);
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .profile-card {
                padding: 24px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .container {
                padding: 0 12px;
            }
        }

        @media (max-width: 420px) {
            .header h1 {
                font-size: 20px;
            }

            .back-btn {
                width: 100%;
            }

            .btn-primary {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
        <div class="loading-text">Loading profile...</div>
    </div>

    <div class="header">
        <div class="header-content">
            <h1>✏️ Edit Profile</h1>
            <button class="back-btn" onclick="window.location.href='dashboard.php'">← Back to Dashboard</button>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <h2>Edit Your Profile</h2>

            <div id="messageDiv" class="message"></div>

            <!-- Profile Information Form -->
            <form id="profileForm">
                <div class="form-section">
                    <div class="section-title">Personal Information</div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" disabled>
                    </div>

                    <div class="form-group">
                        <label for="fullName">Full Name *</label>
                        <input type="text" id="fullName" required placeholder="Enter your full name">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age *</label>
                            <input type="number" id="age" min="13" max="120" required placeholder="Enter your age">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" required>
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="prefer-not-to-say">Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">Settings</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <select id="currency">
                                <option value="USD">USD ($)</option>
                                <option value="PHP">PHP (PHP)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="darkModeToggle">Dark Mode</label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="darkModeToggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" id="saveProfileBtn">Save Changes</button>
                </div>
            </form>

            <!-- Change Password Form -->
            <form id="passwordForm">
                <div class="form-section">
                    <div class="section-title">Change Password</div>

                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" placeholder="Enter current password">
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" placeholder="Enter new password">
                        <div class="password-hint">Password must be at least 6 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="confirmNewPassword">Confirm New Password</label>
                        <input type="password" id="confirmNewPassword" placeholder="Confirm new password">
                    </div>

                    <button type="submit" class="btn-primary" id="changePasswordBtn">Change Password</button>
                </div>
            </form>

            <div class="install-container">
                <div class="install-text">Want the app?</div>
                <button id="installBtn" class="install-btn" type="button" hidden>Install App</button>
            </div>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
        import { getAuth, onAuthStateChanged, updatePassword, EmailAuthProvider, reauthenticateWithCredential } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-auth.js";
        import { getFirestore, doc, getDoc, setDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCxMoF0mTrZYej5K8h1_MkXQ3eKQ-4FZvE",
            authDomain: "moneytracker-c1dd1.firebaseapp.com",
            projectId: "moneytracker-c1dd1",
            storageBucket: "moneytracker-c1dd1.firebasestorage.app",
            messagingSenderId: "71356341269",
            appId: "1:71356341269:web:8ae54dbdfebe06acd3c21c",
            measurementId: "G-49036TSCHY"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const db = getFirestore(app);

        let currentUser = null;
        const defaultSettings = {
            currency: 'USD',
            darkMode: false
        };
        let currentSettings = { ...defaultSettings };

        onAuthStateChanged(auth, async (user) => {
            if (!user) {
                window.location.href = '../index.html';
                return;
            }

            currentUser = user;
            await loadProfile(user);
            document.getElementById('loadingScreen').classList.add('hidden');
        });

        async function loadProfile(user) {
            try {
                // Set email (disabled field)
                document.getElementById('email').value = user.email || '';

                // Load profile from Firestore
                const profileRef = doc(db, 'users', user.uid);
                const profileSnap = await getDoc(profileRef);

                if (profileSnap.exists()) {
                    const profile = profileSnap.data();
                    document.getElementById('fullName').value = profile.fullName || '';
                    document.getElementById('age').value = profile.age || '';
                    document.getElementById('gender').value = profile.gender || '';

                    currentSettings = { ...defaultSettings, ...(profile.settings || {}) };
                    document.getElementById('currency').value = currentSettings.currency;
                    document.getElementById('darkModeToggle').checked = !!currentSettings.darkMode;
                    applyTheme(!!currentSettings.darkMode);
                } else {
                    currentSettings = { ...defaultSettings };
                    document.getElementById('currency').value = currentSettings.currency;
                    document.getElementById('darkModeToggle').checked = !!currentSettings.darkMode;
                    applyTheme(!!currentSettings.darkMode);
                }
            } catch (error) {
                console.error('Error loading profile:', error);
                showMessage('Error loading profile data', 'error');
            }
        }

        document.getElementById('darkModeToggle').addEventListener('change', function() {
            applyTheme(this.checked);
        });

        // Profile Form Handler
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value.trim();
            const age = parseInt(document.getElementById('age').value);
            const gender = document.getElementById('gender').value;
            const currency = document.getElementById('currency').value;
            const darkMode = document.getElementById('darkModeToggle').checked;

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
                const profileRef = doc(db, 'users', currentUser.uid);
                await setDoc(profileRef, {
                    fullName: fullName,
                    age: age,
                    gender: gender,
                    email: currentUser.email,
                    settings: {
                        currency: currency,
                        darkMode: darkMode
                    },
                    updatedAt: serverTimestamp()
                }, { merge: true });

                showMessage('Profile updated successfully!', 'success');
            } catch (error) {
                console.error('Error updating profile:', error);
                showMessage('Failed to update profile: ' + error.message, 'error');
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
                // Re-authenticate user before changing password
                const credential = EmailAuthProvider.credential(
                    currentUser.email,
                    currentPassword
                );
                await reauthenticateWithCredential(currentUser, credential);

                // Update password
                await updatePassword(currentUser, newPassword);

                showMessage('Password changed successfully!', 'success');
                document.getElementById('passwordForm').reset();
            } catch (error) {
                console.error('Error changing password:', error);
                let errorMessage = 'Failed to change password';
                
                if (error.code === 'auth/wrong-password') {
                    errorMessage = 'Current password is incorrect';
                } else if (error.code === 'auth/weak-password') {
                    errorMessage = 'New password is too weak';
                } else if (error.code === 'auth/requires-recent-login') {
                    errorMessage = 'Please log out and log in again before changing password';
                }
                
                showMessage(errorMessage, 'error');
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

        function applyTheme(isDark) {
            document.body.classList.toggle('dark-mode', !!isDark);
        }
    </script>
    <script>
        let deferredInstallPrompt;
        const installBtn = document.getElementById("installBtn");

        window.addEventListener("beforeinstallprompt", (event) => {
            event.preventDefault();
            deferredInstallPrompt = event;
            installBtn.hidden = false;
        });

        installBtn.addEventListener("click", async () => {
            if (!deferredInstallPrompt) {
                return;
            }

            deferredInstallPrompt.prompt();
            await deferredInstallPrompt.userChoice;
            deferredInstallPrompt = null;
            installBtn.hidden = true;
        });

        window.addEventListener("appinstalled", () => {
            deferredInstallPrompt = null;
            installBtn.hidden = true;
        });
    </script>
    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("/sw.js").catch((error) => {
                    console.warn("Service worker registration failed:", error);
                });
            });
        }
    </script>
</body>
</html>
