<?php
/**
 * XAMPP Database Setup Script
 * Run this ONCE to create all database tables
 * Delete this file after running (for security)
 */

// Create database tables
$host = 'localhost';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // First, connect without database to create it
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `hotel_appointments`");
    
    // Now select the database
    $pdo = new PDO("mysql:host=$host;dbname=hotel_appointments;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Create tables
    $sql = <<<SQL
    
    -- Users table
    CREATE TABLE IF NOT EXISTS `users` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `contact` varchar(20),
        `room_number` varchar(50),
        `role` enum('user','staff','admin') DEFAULT 'user',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    -- Services table
    CREATE TABLE IF NOT EXISTS `services` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text,
        `price` decimal(10,2),
        `duration_minutes` int DEFAULT 60,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    -- Staff table
    CREATE TABLE IF NOT EXISTS `staff` (
        `id` int NOT NULL AUTO_INCREMENT,
        `user_id` int NOT NULL,
        `specialization` varchar(255),
        `availability_status` enum('available','busy','unavailable') DEFAULT 'available',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    -- Appointments table
    CREATE TABLE IF NOT EXISTS `appointments` (
        `id` int NOT NULL AUTO_INCREMENT,
        `user_id` int NOT NULL,
        `service_id` int NOT NULL,
        `staff_id` int,
        `appointment_datetime` datetime NOT NULL,
        `status` enum('pending','confirmed','completed','canceled') DEFAULT 'pending',
        `notes` text,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        KEY `service_id` (`service_id`),
        KEY `staff_id` (`staff_id`),
        KEY `appointment_datetime` (`appointment_datetime`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT,
        FOREIGN KEY (`staff_id`) REFERENCES `staff`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    SQL;
    
    // Execute each table creation separately
    foreach (explode(';', $sql) as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    // Insert sample services
    $services_exist = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    if ($services_exist == 0) {
        $pdo->exec("INSERT INTO `services` (`name`, `description`, `price`, `duration_minutes`) VALUES
        ('Spa Treatment', 'Relaxing full-body spa massage', 99.99, 60),
        ('Gym Session', 'Personal training and gym access', 50.00, 60),
        ('Restaurant Reservation', 'Fine dining experience', 150.00, 120),
        ('Massage Therapy', 'Professional therapeutic massage', 80.00, 50)");
    }
    
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Database Setup Complete</title>
        <style>
            body { font-family: Arial; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
            .box { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; }
            h1 { color: #28a745; margin-top: 0; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .steps { background: #e7f3ff; border-left: 4px solid #2196F3; padding: 20px; margin: 20px 0; }
            a { display: inline-block; background: #007bff; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none; margin-top: 20px; }
            a:hover { background: #0056b3; }
            code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>✅ Database Setup Complete!</h1>
            
            <div class="success">
                <strong>All tables created successfully!</strong><br>
                Database: <code>hotel_appointments</code>
            </div>
            
            <div class="steps">
                <strong>Next Steps:</strong>
                <ol>
                    <li>Go to <a href="/" target="_blank">Home Page</a></li>
                    <li>Click "Register" to create your account</li>
                    <li>Go to phpMyAdmin and find your user in the <code>users</code> table</li>
                    <li>Edit your user and change <code>role</code> from 'user' to 'admin'</li>
                    <li><strong>DELETE this file (setup_xampp.php) for security!</strong></li>
                </ol>
            </div>
            
            <p><strong>⚠️ IMPORTANT:</strong> Delete this setup file immediately after setup is complete. It's a security risk to leave it on your server.</p>
            
            <a href="/">Go to Home Page</a>
        </div>
    </body>
    </html>
    <?php
    
} catch (PDOException $e) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Setup Error</title>
        <style>
            body { font-family: Arial; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
            .box { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; }
            h1 { color: #dc3545; margin-top: 0; }
            .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; margin: 20px 0; font-family: monospace; font-size: 13px; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>❌ Setup Error</h1>
            <div class="error">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <p><strong>Make sure:</strong></p>
            <ul>
                <li>XAMPP MySQL server is running</li>
                <li>You're using the correct credentials (root/no password)</li>
                <li>MySQL is listening on localhost:3306</li>
            </ul>
        </div>
    </body>
    </html>
    <?php
}
?>
