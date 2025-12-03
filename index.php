<?php
session_start();

// Database Configuration - Works with both localhost and InfinityFree
$host = 'sql113.infinityfree.com';
$db = 'ifo_40589293_hotel_appointments';
$user = 'ifo_40589293';
$pass = 'j2iHCIxMxYRbGA7';
$charset = 'utf8mb4';

// Try different connection methods
$connected = false;
$error_msg = '';

// Method 1: Try with port 3306
if (!$connected) {
    try {
        $dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
        $connected = true;
    } catch (PDOException $e) {
        $error_msg = $e->getMessage();
    }
}

// Method 2: Try without port
if (!$connected) {
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
        $connected = true;
    } catch (PDOException $e) {
        $error_msg = $e->getMessage();
    }
}

// If connection failed, show error
if (!$connected) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Database Connection Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f5f5;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .error-box {
                background: white;
                border: 2px solid #dc3545;
                border-radius: 8px;
                padding: 30px;
                max-width: 600px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #dc3545;
                margin-top: 0;
            }
            .error-details {
                background: #f8f9fa;
                border-left: 4px solid #dc3545;
                padding: 15px;
                margin: 20px 0;
                border-radius: 4px;
                font-family: monospace;
                font-size: 12px;
                word-break: break-all;
            }
            .checklist {
                margin: 20px 0;
                line-height: 1.8;
            }
            .checklist li {
                margin: 10px 0;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h1>🔴 Database Connection Failed</h1>
            
            <div class="error-details">
                <?php echo htmlspecialchars($error_msg); ?>
            </div>
            
            <h3>Troubleshooting Checklist:</h3>
            <ul class="checklist">
                <li>✓ Verify database credentials in index.php (line 5-8)</li>
                <li>✓ Check that host is: <code>sql113.infinityfree.com</code></li>
                <li>✓ Check that database is: <code>ifo_40589293_hotel_appointments</code></li>
                <li>✓ Check that username is: <code>ifo_40589293</code> (with letter O, not zero)</li>
                <li>✓ Verify password is correct in InfinityFree MySQL Databases page</li>
                <li>✓ Ensure all files are uploaded to /htdocs/ folder</li>
                <li>✓ Wait 5-10 minutes for DNS to propagate if site is newly created</li>
            </ul>
            
            <p><a href="javascript:location.reload()" style="color: #007bff; text-decoration: none;">↻ Retry Connection</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Connection successful! Now load the application
require 'functions.php';
require 'mail.php';

// Route to appropriate page based on login status
if (isset($_SESSION['user_id'])) {
    // User is logged in - show dashboard
    require 'dashboard.php';
} else {
    // User not logged in - show login page
    require 'login.php';
}
?>
