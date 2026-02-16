# Money Tracker Web Application

A complete web application for tracking personal finances with Firebase authentication and database integration.

## Features

✅ **User Authentication**
- Firebase Authentication integration
- Secure login/logout system
- Password strength validation
- User-friendly error messages

✅ **User Registration**
- Collects: Name, Age, Gender, Email, Password
- Real-time form validation
- Age validation (13-120 years)
- Password strength indicator
- Email format validation
- Password confirmation check

✅ **User Profile Management**
- Store user data in Firebase Realtime Database
- Display user information on dashboard
- **Edit profile** (name, age, gender)
- **Change password** (with current password verification)
- **Upload profile picture** (max 2MB, base64 storage)
- **Remove profile picture**
- API endpoints for profile CRUD operations

✅ **Modern UI/UX**
- Responsive design
- Gradient backgrounds
- Smooth animations
- Loading states
- Error/Success notifications
- Profile picture preview and upload

## Project Structure

```
MoneyTrackerWeb/
├── index.html              # Login page
├── signup.html             # Registration page
├── api/
│   ├── login.php          # Login API endpoint
│   ├── register.php       # Registration API endpoint
│   ├── change-password.php # Password change API endpoint
│   └── user.php           # User profile API (GET/UPDATE/PICTURE)
└── public/
    ├── dashboard.php      # User dashboard with profile display
    ├── edit-profile.php   # Profile editing page
    └── logout.php         # Logout handler
```

## Setup Instructions

### 1. Firebase Configuration

**Important:** Update the Firebase Database URL in the following files:
108
- `api/register.php` (Line 39)
- `api/login.php` (Lines 28, 63)
- `api/user.php` (Line 17)

Replace:
```php
$firebaseDbUrl = "https://moneytrackerweb-default-rtdb.firebaseio.com";
```

With your actual Firebase Realtime Database URL:
```php
$firebaseDbUrl = "https://YOUR-PROJECT-ID.firebaseio.com";
```

### 2. Firebase Project Setup

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project or create a new one
3. Enable **Authentication** → **Email/Password** sign-in method
4. Enable **Realtime Database**
5. Set database rules for development:

```json
{
  "rules": {
    "users": {
      "$uid": {
        ".read": "$uid === auth.uid",
        ".write": "$uid === auth.uid"
      }
    }
  }
}
```

### 3. Server Requirements

- PHP 7.0 or higher
- `allow_url_fopen` enabled in php.ini
- Web server (Apache/Nginx) or PHP built-in server

### 4. Running the Application

**Option 1: PHP Built-in Server**
```bash
cd e:\Codes\MoneyTrackerWeb
php -S localhost:8000
```

Then open: `http://localhost:8000`

**Option 2: XAMPP/WAMP**
- Place project in `htdocs` folder
- Access via `http://localhost/MoneyTrackerWeb`

## API Endpoints

### 1. Login
**POST** `/api/login.php`

**Parameters:**
- `email` (required)
- `password` (required)
- `idToken` (optional - from client-side Firebase auth)
- `localId` (optional - from client-side Firebase auth)

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "redirect": "public/dashboard.php"
}
```

### 2. Register
**POST** `/api/register.php`

**Parameters:**
- `userId` (required - Firebase UID)
- `email` (required)
- `fullName` (required)
- `age` (required)
- `gender` (optional)
- `idToken` (required - Firebase ID token)

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "userId": "firebase-user-id"
}
```

### 3. Get User Profile
**GET** `/api/user.php`

**Response:**
```json
{
  "success": true,
  "data": {
    "email": "user@example.com",
    "fullName": "John Doe",
    "age": 25,
    "gender": "male",
    "createdAt": "2026-02-16 10:30:00",
    "lastLogin": "2026-02-16 11:45:00"
  }
}
```

### 4. Update User Profile
**POST** `/api/user.php`
action` = "updateProfile" (default)
- `fullName` (optional)
- `age` (optional)
- `gender` (optional)

**Response:**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "fullName": "John Doe",
    "updatedAt": "2026-02-16 12:00:00"
  }
}
```

### 5. Upload Profile Picture
**POST** `/api/user.php`

**Parameters:**
- `action` = "updateProfilePicture"
- `profilePicture` (required - base64 encoded image)

**Response:**
```json
{profilePicture": "data:image/jpeg;base64,...",
      "
  "success": true,
  "message": "Profile picture updated successfully"
}
```

### 6. Remove Profile Picture
**POST** `/api/user.php`

**Parameters:**
- `action` = "removeProfilePicture"

**Response:**
```json
{
  "success": true,
  "message": "Profile picture removed succe

### Profile Edit
- **Name**: Minimum 2 characters
- **Age**: Between 13-120 years
- **Profile Picture**: Max 2MB, formats: JPEG, PNG, GIF
- **Email**: Cannot be changed (Firebase limitation)

### Password Change
- **Current Password**: Required for verification
- **New Password**: Minimum 6 characters
- **Confirm Password**: Must match new passwordssfully"
}
```

### 7. Change Password
**POST** `/api/change-password.php`

**Parameters:**
- `currentPassword` (required)

### Profile Picture Upload Errors
- File size > 2MB → "Image size must be less than 2MB"
- Invalid file type → "Please select a valid image file"
- Invalid format → "Invalid image format"
- `newPassword` (required - min 6 characters)

**Response:**
```json
{
  "success": true,
  "message": "Password changed successfully" "fullName": "John Doe",
    "updatedAt": "2026-02-16 12:00:00"
  }
}
```

## Firebase Database Structure

```json
{
  "users": {
    "user-uid-here": {
      "email": "user@example.com",
      "fullName": "John Doe",
      "age": 25,
      "gender": "male",
      "createdAt": "2026-02-16 10:30:00",
      "lastLogin": "2026-02-16 11:45:00",
      "updatedAt": "2026-02-16 12:00:00"
    }
  }
}
```

## Validation Rules

### Registration
- **Name**: Minimum 2 characters
- **Age**: Between 13-120 years
- **Email**: Valid email format
- **Password**: Minimum 6 characters (Firebase requirement)
- **Confirm Password**: Must match password
Password change not working
1. Verify current password is correct
2. Ensure new password meets minimum requirements
3. Check Firebase API key is valid
4. Verify user is logged in (session exists)

### Profile picture not uploading
1. x] Password reset via email
- [x] Profile picture upload
- [x] Profile editing
- [x] Password change
- [ ] Email verification
- [ ] OAuth providers (Google, Facebook)
- [ ] Transaction tracking features
- [ ] Budget management
- [ ] Expense categories
- [ ] Reports and analytics
- [ ] Export data (CSV, PDF)
- [ ] Multi-currency support
- [ ] Dark mode
- [ ] Profile picture crop/resize tool
- [ ] Cloud storage for profile pictures (Firebase Storage)0+ characters with uppercase, lowercase, numbers, and symbols

## Error Handling

All Firebase authentication errors are mapped to user-friendly messages:

| Firebase Error | User Message |
|----------------|--------------|
| `INVALID_PASSWORD` | Invalid email or password |
| `EMAIL_NOT_FOUND` | No account found with this email |
| `EMAIL_EXISTS` | This email is already registered |
| `USER_DISABLED` | This account has been disabled |
| `TOO_MANY_ATTEMPTS` | Too many failed attempts. Try again later |
| `WEAK_PASSWORD` | Password is too weak |
| `INVALID_EMAIL` | Invalid email address format |

## Security Notes

### Production Checklist
- [ ] Move Firebase API key to environment variable
- [ ] Set `display_errors = 0` in php.ini
- [ ] Implement rate limiting
- [ ] Add CSRF protection
- [ ] Use HTTPS only
- [ ] Update Firebase Database rules for production
- [ ] Add input sanitization
- [ ] Implement password reset functionality
- [ ] Add email verification

### Firebase Security Rules (Production)
```json
{
  "rules": {
    "users": {
      "$uid": {
        ".read": "$uid === auth.uid",
        ".write": "$uid === auth.uid",
        ".validate": "newData.hasChildren(['email', 'fullName', 'age'])"
      }
    }
  }
}
```

## Troubleshooting

### Login not working
1. Check Firebase API key is correct
2. Verify `allow_url_fopen` is enabled
3. Check browser console for errors
4. Verify Firebase Authentication is enabled

### Registration fails
1. Verify Firebase Realtime Database URL is correct
2. Check Firebase Database rules allow writes
3. Ensure all required fields are provided
4. Check age is within valid range (13-120)

### Session issues
1. Ensure PHP sessions are enabled
2. Check session save path permissions
3. Clear browser cookies and try again

## Future Enhancements

- [ ] Password reset via email
- [ ] Email verification
- [ ] Profile picture upload
- [ ] Transaction tracking features
- [ ] Budget management
- [ ] Expense categories
- [ ] Reports and analytics
- [ ] Export data (CSV, PDF)
- [ ] Multi-currency support
- [ ] Dark mode

## License

This project is for educational purposes.

## Support

For issues or questions, please check the Firebase documentation:
- [Firebase Authentication](https://firebase.google.com/docs/auth)
- [Firebase Realtime Database](https://firebase.google.com/docs/database)
