# Profile Management Guide

## Overview
The Money Tracker now includes complete profile management features:
- ✏️ Edit profile information (name, age, gender)
- 🔒 Change password securely
- 📷 Upload and manage profile picture

## Accessing Profile Settings

### From Dashboard
1. Login to your account
2. Click the **"✏️ Edit Profile"** button in the top navigation
3. You'll be redirected to the profile editing page

## Features

### 1. Profile Picture Management

#### Upload Profile Picture
1. Click the **📷 camera icon** on the profile picture
2. Select an image file (JPEG, PNG, or GIF)
3. Maximum file size: **2MB**
4. Image will be automatically uploaded and displayed

#### Remove Profile Picture
1. Click the **"Remove picture"** link below the profile picture
2. Confirm the action
3. Picture will be replaced with default avatar (👤)

**Note:** Profile pictures are stored as base64-encoded strings in Firebase Realtime Database.

### 2. Edit Personal Information

#### Editable Fields:
- **Full Name** - Your display name (minimum 2 characters)
- **Age** - Must be between 13-120 years
- **Gender** - Optional (Male, Female, Other, Prefer not to say)

#### Email Address
- **Cannot be changed** (Firebase authentication limitation)
- Displayed for reference only

#### How to Update:
1. Modify the fields you want to change
2. Click **"Save Changes"** button
3. Success message will appear when saved
4. Dashboard will reflect the changes immediately

### 3. Change Password

#### Requirements:
- Must know your **current password**
- New password must be at least **6 characters**
- Passwords must match

#### Steps:
1. Enter your **current password** (for verification)
2. Enter your **new password**
3. Confirm the **new password**
4. Click **"Change Password"** button
5. You'll remain logged in with the new password

#### Security Features:
- Current password is verified before allowing change
- Uses Firebase authentication for secure password updates
- Session token is automatically updated

## Technical Details

### Profile Picture Storage
- **Format:** Base64-encoded data URL
- **Storage:** Firebase Realtime Database
- **Path:** `/users/{userId}/profilePicture`
- **Size Limit:** 2MB (prevents database quota issues)

### Why Base64 Storage?
- ✅ Simple implementation (no separate storage service needed)
- ✅ Works with Firebase Realtime Database
- ✅ No additional Firebase Storage setup required
- ⚠️ Limited to smaller images (2MB)

### For Production
Consider using **Firebase Storage** for profile pictures:
- Supports larger files
- Better performance
- CDN delivery
- More cost-effective for many users

### API Endpoints Used

#### Update Profile
```
POST /api/user.php
Parameters: fullName, age, gender, action=updateProfile
```

#### Upload Picture
```
POST /api/user.php
Parameters: profilePicture, action=updateProfilePicture
```

#### Remove Picture
```
POST /api/user.php
Parameters: action=removeProfilePicture
```

#### Change Password
```
POST /api/change-password.php
Parameters: currentPassword, newPassword
```

## Firebase Database Structure

After editing profile, your user data looks like:

```json
{
  "users": {
    "your-user-id": {
      "email": "you@example.com",
      "fullName": "Your Name",
      "age": 25,
      "gender": "male",
      "profilePicture": "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
      "createdAt": "2026-02-16 10:30:00",
      "lastLogin": "2026-02-16 15:20:00",
      "updatedAt": "2026-02-16 15:25:00"
    }
  }
}
```

## Common Issues & Solutions

### Profile Picture Not Showing
**Problem:** Picture uploads but doesn't display
**Solutions:**
1. Clear browser cache (Ctrl+Shift+R)
2. Check if image is valid base64
3. Verify session has profilePicture value
4. Try re-uploading a smaller image

### "Image Too Large" Error
**Problem:** Can't upload large photos
**Solutions:**
1. Resize image before uploading
2. Use image compression tool
3. Convert to JPEG (usually smaller than PNG)
4. Keep images under 2MB

### Password Change Fails
**Problem:** "Current password is incorrect"
**Solutions:**
1. Verify you're entering the correct current password
2. Check for caps lock
3. Reset password if forgotten (use forgot password link on login)

### Changes Not Persisting
**Problem:** Profile updates don't save
**Solutions:**
1. Check Firebase Database rules allow writes
2. Verify you're logged in (session active)
3. Check browser console for errors
4. Ensure Firebase Database URL is correct in API files

## Tips & Best Practices

### Profile Pictures
- Use square images for best results (they're displayed in circles)
- Recommended size: 200x200 to 500x500 pixels
- Use JPEG for photos, PNG for logos/graphics
- Compress images before uploading

### Password Security
- Use strong passwords (mix of letters, numbers, symbols)
- Don't reuse passwords from other sites
- Change password periodically
- Never share your password

### Profile Information
- Keep your name current for personalized experience
- Age is used for analytics (optional to display)
- Gender helps with statistics (optional)

## Mobile Responsiveness

The profile editing page is fully responsive:
- ✅ Works on phones, tablets, and desktops
- ✅ Touch-friendly upload button
- ✅ Optimized form layout for small screens
- ✅ Single-column layout on mobile devices

## Next Steps

After setting up your profile:
1. Return to dashboard to view updated information
2. Start tracking your expenses
3. Set up budget goals
4. Review transaction history

## Support

If you encounter issues:
1. Check the main [README.md](README.md) documentation
2. Verify Firebase configuration
3. Check browser console for errors
4. Review Firebase Database rules
