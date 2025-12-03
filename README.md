# Hotel Appointment Management System

A professional hotel appointment booking and management system built with PHP, MySQL, and modern responsive design.

## Features

- **User Registration & Authentication**: Secure user registration and login system
- **Appointment Booking**: Easy-to-use appointment booking interface
- **Admin Dashboard**: Comprehensive dashboard for managing appointments and services
- **Service Management**: Add, edit, and delete hotel services
- **Staff Assignment**: Assign staff to appointments
- **Email Notifications**: Automated email reminders and status updates
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Dark Theme Admin Interface**: Professional dark-themed admin panel with blue accents

## Project Structure

```
hotel_system-1/
├── index.php                    # Home page
├── login.php                    # User login
├── register.php                 # User registration
├── dashboard.php                # User dashboard
├── admin_dashboard.php          # Admin management panel
├── appointments.php             # Appointment booking
├── admin_calendar.php           # Calendar view
├── db.php                       # Database configuration
├── functions.php                # Helper functions
├── mail.php                     # Email functionality
├── style.css                    # Main styles
├── admin-style.css              # Admin panel styles
├── setup_db.php                 # Database setup script
├── vendor/                      # Composer dependencies (PHPMailer)
└── images/                      # Image assets
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (for dependencies)

## Installation

### Local Setup (Development)

1. **Clone the repository**
   ```bash
   git clone https://github.com/Raiden-Shogun-13/Project.git
   cd Project
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure database**
   - Create a new MySQL database named `hotel_appointments`
   - Update `db.php` with your database credentials:
     ```php
     $host = 'localhost';
     $db = 'hotel_appointments';
     $user = 'root';
     $pass = '';
     ```

4. **Setup database tables**
   - Open `setup_db.php` in your browser or run the included SQL file
   - This will create all necessary tables

5. **Start development server**
   ```bash
   php -S localhost:8000
   ```

6. **Access the application**
   - Visit: http://localhost:8000
   - Admin login: Use registered admin account

---

## Deployment to InfinityFree

Follow these steps to deploy your application to InfinityFree's free hosting:

### Step 1: Prepare Your Files

1. **Create a ZIP file** of your entire project:
   - Select all files in `hotel_system-1/` folder
   - Right-click → Send to → Compressed (zipped) folder
   - Name it `hotel-system.zip`

2. **Alternatively, use Git** (recommended):
   ```bash
   git clone https://github.com/Raiden-Shogun-13/Project.git
   ```

### Step 2: Upload Files to InfinityFree

1. **Log in** to your InfinityFree account
2. **Go to File Manager**
3. **Navigate** to the `htdocs` or `public_html` folder (depends on your hosting)
4. **Upload method (choose one)**:
   
   **Option A: Upload ZIP file**
   - Click "Upload"
   - Select `hotel-system.zip`
   - Right-click the ZIP → Extract
   - Delete the ZIP file after extracting
   
   **Option B: Use Git (if available)**
   - Open terminal/console in File Manager
   - Run: `git clone https://github.com/Raiden-Shogun-13/Project.git`

### Step 3: Create MySQL Database

1. **Go to MySQL Databases** in your InfinityFree control panel
2. **Create new database**:
   - Database name: `hotel_appointments` (or similar)
   - Username: `[your_username]_hotel`
   - Password: Choose a strong password
   - Click Create

3. **Note your credentials**:
   ```
   Host: localhost
   Database: [account]_[your_db_name]
   Username: [account]_[your_username]
   Password: [your_password]
   ```

### Step 4: Update Database Configuration

1. **Open File Manager** and navigate to your project
2. **Edit `db.php`**:
   ```php
   <?php
   // db.php
   $host = 'localhost';
   $db = '[account]_hotel_appointments';  // Your actual database name from InfinityFree
   $user = '[account]_hotel_user';        // Your actual username
   $pass = 'your_strong_password';        // Your actual password
   $charset = 'utf8mb4';

   $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
   $options = [
       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   ];

   try {
       $pdo = new PDO($dsn, $user, $pass, $options);
   } catch (PDOException $e) {
       exit('DB Connection failed: ' . $e->getMessage());
   }
   ?>
   ```

3. **Save the file**

### Step 5: Initialize Database

1. **Open your browser** and navigate to:
   ```
   https://yourdomain.infinityfree.app/setup_db.php
   ```
   (Replace `yourdomain` with your actual domain)

2. **Wait for the database tables to be created**

3. **Verify success** - You should see confirmation message

4. **Delete `setup_db.php`** from File Manager (for security)

### Step 6: Access Your Application

1. **Visit your site**:
   ```
   https://yourdomain.infinityfree.app
   ```

2. **Register as a user**:
   - Click "Register"
   - Create your account
   - Verify your email if required

3. **Create admin account**:
   - Register a new account
   - Contact support or use phpMyAdmin to set the role to 'admin'
   - Or manually update in database:
     ```sql
     UPDATE users SET role = 'admin' WHERE id = 1;
     ```

4. **Access admin panel**:
   ```
   https://yourdomain.infinityfree.app/admin_dashboard.php
   ```

---

## Accessing Your Site on Mobile

### Share Your Link

Your site URL is:
```
https://yourdomain.infinityfree.app
```

**Share this link with others:**
- Copy the URL
- Send via email, WhatsApp, Telegram, etc.
- They can access it on their phone browser

### Test on Your Phone

1. **Connect to same network** (WiFi)
2. **Open browser on phone**
3. **Enter your domain URL**
4. **Test all features**:
   - User registration
   - Appointment booking
   - Admin dashboard (if using admin account)

### Troubleshooting

**Problem**: Database connection failed
- **Solution**: Check credentials in `db.php` match InfinityFree exactly
- Go back to Step 3 and verify all details

**Problem**: "File not found" error
- **Solution**: Make sure files are in correct folder (htdocs or public_html)
- Check file paths in upload

**Problem**: Email not sending
- **Solution**: Update SMTP settings in `mail.php`
- InfinityFree usually requires SMTP configuration

**Problem**: Can't access admin panel
- **Solution**: Verify your account role is set to 'admin' in database

---

## Email Configuration (InfinityFree)

1. **Go to Email Accounts** in control panel
2. **Create email** for notifications (e.g., `noreply@yourdomain.com`)
3. **Update `mail.php`** with SMTP details:
   ```php
   $mail->Host = 'smtp.infinityfree.app';  // or your email host
   $mail->Username = 'your-email@yourdomain.com';
   $mail->Password = 'email-password';
   ```

---

## File Upload in InfinityFree

**Images folder** (`images/`) can store appointment and service images:
- Maximum file size: Check InfinityFree limits
- Supported formats: JPG, PNG, GIF, WebP

---

## Database Backup

**Regular backups on InfinityFree**:
1. Go to MySQL Databases
2. Click "Backup" next to your database
3. Download the SQL file

---

## Security Notes

⚠️ **Important**: Before going live:
1. Delete `setup_db.php` - It should only be used once
2. Change default credentials
3. Update `config.php` with secure settings
4. Use HTTPS (usually automatic on InfinityFree)
5. Set strong database passwords

---

## Support & Issues

- **GitHub Issues**: https://github.com/Raiden-Shogun-13/Project/issues
- **Email**: Contact your InfinityFree support for hosting issues

---

## License

This project is open source and available for personal and commercial use.

---

## Version Info

- **Latest Version**: 1.0
- **Last Updated**: December 2025
- **PHP Version**: 7.4+
- **MySQL Version**: 5.7+
