# SportConnect Setup Instructions

## Prerequisites
- XAMPP, WAMP, or similar local server environment
- PHP 7.4 or higher
- MySQL 5.7 or higher

## Setup Steps

### 1. Database Setup
1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Create a new database called `sportconnect`
3. Import the `database_schema.sql` file or run the SQL commands manually

### 2. File Configuration
1. Ensure all HTML and PHP files are in your web server directory (e.g., `htdocs` for XAMPP)
2. Verify the database connection settings in `signup.php`:
   ```php
   $host = 'localhost';
   $dbname = 'sportconnect';
   $username = 'root';
   $password = ''; // Default XAMPP password is empty
   ```

### 3. Testing the Connection
1. Start your web server (Apache) and MySQL
2. Open `signup.html` in your browser
3. Fill out the form and submit
4. Check the browser console for any JavaScript errors
5. Check the PHP error logs if there are issues

### 4. Troubleshooting

#### Common Issues:
- **Database Connection Error**: Verify MySQL is running and credentials are correct
- **Form Not Submitting**: Check browser console for JavaScript errors
- **PHP Errors**: Check Apache error logs and ensure PHP is enabled

#### File Permissions:
- Ensure PHP files have execute permissions
- Check that the web server can read all files

### 5. Features Implemented

#### Frontend (signup.html):
- ✅ Responsive design with Bootstrap
- ✅ User type switching (Player/Coach/Turf)
- ✅ High-accuracy location detection
- ✅ Form validation
- ✅ AJAX form submission
- ✅ Toast notifications

#### Backend (signup.php):
- ✅ Database connection with PDO
- ✅ User type-specific data handling
- ✅ Password hashing
- ✅ Input validation
- ✅ JSON response handling
- ✅ Error handling

#### Database:
- ✅ Single table design for all user types
- ✅ Proper indexing
- ✅ Sample data included

### 6. Security Features
- Password hashing using PHP's built-in `password_hash()`
- Prepared statements to prevent SQL injection
- Input validation and sanitization
- CSRF protection through form tokens (can be enhanced)

### 7. Next Steps
After successful setup, consider implementing:
- Email verification
- Login system
- Password reset functionality
- User profile management
- Admin panel

## Support
If you encounter issues:
1. Check browser console for JavaScript errors
2. Check PHP error logs
3. Verify database connection
4. Ensure all files are in the correct directory
