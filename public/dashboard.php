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
    <title>Money Tracker - Dashboard</title>
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

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-pic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
        }

        .user-email {
            font-size: 12px;
            opacity: 0.9;
        }

        .logout-btn,
        .edit-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout-btn:hover,
        .edit-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .welcome-card p {
            color: #666;
            line-height: 1.6;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .profile-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .profile-item label {
            display: block;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .profile-item .value {
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card .icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .stat-card .label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .stat-card .value {
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>💰 Money Tracker</h1>
            <div class="user-menu">
                <div class="profile-pic">
                    <?php if (!empty($profilePicture)): ?>
                        <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">
                    <?php else: ?>
                        <span>👤</span>
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($fullName); ?></div>
                    <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
                </div>
                <a href="edit-profile.php" class="edit-btn">✏️ Edit Profile</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Welcome back, <?php echo htmlspecialchars(explode(' ', $fullName)[0]); ?>! 👋</h2>
            <p>You are successfully logged in to your Money Tracker dashboard.</p>
            
            <div class="profile-grid">
                <div class="profile-item">
                    <label>Full Name</label>
                    <div class="value"><?php echo htmlspecialchars($fullName); ?></div>
                </div>
                <div class="profile-item">
                    <label>Email</label>
                    <div class="value"><?php echo htmlspecialchars($email); ?></div>
                </div>
                <?php if (!empty($age)): ?>
                <div class="profile-item">
                    <label>Age</label>
                    <div class="value"><?php echo htmlspecialchars($age); ?> years</div>
                </div>
                <?php endif; ?>
                <?php if (!empty($gender) && $gender !== 'prefer-not-to-say'): ?>
                <div class="profile-item">
                    <label>Gender</label>
                    <div class="value"><?php echo htmlspecialchars(ucfirst($gender)); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">💵</div>
                <div class="label">Total Income</div>
                <div class="value">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="icon">💸</div>
                <div class="label">Total Expenses</div>
                <div class="value">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="icon">💰</div>
                <div class="label">Balance</div>
                <div class="value">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="icon">📊</div>
                <div class="label">Transactions</div>
                <div class="value">0</div>
            </div>
        </div>
    </div>
</body>
</html>
