<?php
session_start();

// Load database connection
require 'db.php';
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
