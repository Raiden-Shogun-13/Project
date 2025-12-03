-- Hotel Appointment System Database
-- This SQL file creates all necessary tables for the hotel booking system
-- Import this file into InfinityFree phpMyAdmin to set up your database

-- Create database (if not exists)
-- Note: InfinityFree creates the database for you, so just run the tables below

-- Table: users
CREATE TABLE IF NOT EXISTS `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: services
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2),
  `duration_minutes` int DEFAULT 60,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: staff
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `specialization` varchar(255),
  `availability_status` enum('available','busy','unavailable') DEFAULT 'available',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: appointments
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
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`staff_id`) REFERENCES `staff`(`id`) ON DELETE SET NULL,
  KEY `appointment_datetime` (`appointment_datetime`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample services
INSERT INTO `services` (`name`, `description`, `price`, `duration_minutes`) VALUES
('Spa Treatment', 'Relaxing full-body spa massage', 99.99, 60),
('Gym Session', 'Personal training and gym access', 50.00, 60),
('Restaurant Reservation', 'Fine dining experience', 150.00, 120),
('Massage Therapy', 'Professional therapeutic massage', 80.00, 50);

-- Create default admin account
-- Email: admin@hotel.com
-- Password: Admin@123 (hashed with password_hash('Admin@123', PASSWORD_BCRYPT))
INSERT INTO `users` (`name`, `email`, `password`, `contact`, `room_number`, `role`) VALUES
('Admin', 'admin@hotel.com', '$2y$10$YourHashedPasswordHere', '123-456-7890', 'Admin', 'admin');

-- Note: To set your own password, update the users table after importing
-- Use phpMyAdmin to update the password field with a hashed version
