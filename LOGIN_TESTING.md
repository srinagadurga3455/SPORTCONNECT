# SportConnect Login Testing Guide

## Test Credentials

### Player Account
- **Phone**: `1234567890`
- **Password**: `password`
- **User Type**: Player
- **Name**: Test User

### Coach Account
- **Phone**: `+1234567891`
- **Password**: `password`
- **User Type**: Coach
- **Name**: Jane Smith

### Turf Account
- **Phone**: `+1234567892`
- **Password**: `password`
- **User Type**: Turf Admin
- **Name**: Mike Johnson

## Testing Steps

### 1. Database Setup
1. Import `database_schema.sql` into your MySQL database
2. Verify the `users` table is created with test data

### 2. Test Login Flow
1. Open `login.html` in your browser
2. Select "Player" as user type
3. Enter phone: `1234567890`
4. Enter password: `password`
5. Click "Allow" for location access (optional)
6. Click "Login"

### 3. Expected Results
- ✅ Success message: "Login successful!"
- ✅ Redirect to `player-dashboard.html` after 2 seconds
- ✅ Dashboard displays user information
- ✅ Session data stored in browser

### 4. Test Different User Types
- Try logging in as Coach and Turf Admin
- Verify user type switching works
- Check that appropriate data is displayed

## Troubleshooting

### Common Issues:
1. **"No account found"**: Check database connection and user data
2. **"Invalid password"**: Verify password hash in database
3. **Form not submitting**: Check browser console for JavaScript errors
4. **Dashboard not loading**: Verify sessionStorage has user data

### Debug Steps:
1. Check browser console for errors
2. Verify PHP error logs
3. Test database connection
4. Check form field names match PHP expectations

## Security Notes

⚠️ **Important**: The test passwords in this guide are for development only. In production:
- Use strong, unique passwords
- Implement proper password hashing
- Add rate limiting for login attempts
- Use HTTPS for all communications
- Implement proper session management

## Next Steps

After successful login testing:
1. Implement proper session management
2. Add password reset functionality
3. Create coach and turf dashboards
4. Add user profile management
5. Implement proper logout functionality
