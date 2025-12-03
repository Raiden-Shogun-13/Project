<?php
session_start();
require 'index.php'; // This will load the database connection

// Check if we're supposed to run setup
$setup_token = $_GET['token'] ?? '';
$correct_token = 'setup_' . md5('hotel_system_2024');

if ($setup_token !== $correct_token) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Invalid Setup Token</title>
        <style>
            body { font-family: Arial; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
            .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; }
            h1 { color: #dc3545; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>⚠️ Setup Not Authorized</h1>
            <p>Database setup is not available. If you need to set up the database:</p>
            <ol>
                <li>Download <code>database.sql</code> from your project</li>
                <li>Go to InfinityFree phpMyAdmin</li>
                <li>Import the SQL file</li>
                <li>Update the admin password</li>
            </ol>
            <p><a href="/">← Go to Login</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Run setup
try {
    // Create tables
    $queries = [
        "CREATE TABLE IF NOT EXISTS `users` (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `email` varchar(255) NOT NULL UNIQUE,
          `password` varchar(255) NOT NULL,
          `contact` varchar(20),
          `room_number` varchar(50),
          `role` enum('user','staff','admin') DEFAULT 'user',
          `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `services` (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `description` text,
          `price` decimal(10,2),
          `duration_minutes` int DEFAULT 60,
          `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `staff` (
          `id` int NOT NULL AUTO_INCREMENT,
          `user_id` int NOT NULL,
          `specialization` varchar(255),
          `availability_status` enum('available','busy','unavailable') DEFAULT 'available',
          `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `appointments` (
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
          FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`staff_id`) REFERENCES `staff`(`id`) ON DELETE SET NULL,
          KEY `appointment_datetime` (`appointment_datetime`),
          KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];

    foreach ($queries as $query) {
        $pdo->exec($query);
    }

    // Insert sample services if they don't exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
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
            .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; }
            h1 { color: #28a745; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>✅ Database Setup Complete!</h1>
            <div class="success">
                <strong>All tables created successfully!</strong>
            </div>
            
            <h3>Next Steps:</h3>
            <ol>
                <li>Create your admin account by registering at <a href="/">the login page</a></li>
                <li>Go to phpMyAdmin and change your user's role to 'admin'</li>
                <li>Delete this setup_db.php file for security</li>
                <li>Start using your hotel system!</li>
            </ol>
            
            <p><a href="/" style="display: inline-block; background: #007bff; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;">Go to Login</a></p>
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
            .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; }
            h1 { color: #dc3545; }
            .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; margin: 20px 0; font-family: monospace; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>❌ Setup Error</h1>
            <div class="error">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <p><a href="/">← Go to Login</a></p>
        </div>
    </body>
    </html>
    <?php
}
?>
