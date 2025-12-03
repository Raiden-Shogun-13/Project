<?php
session_start();
require 'db.php';
require 'functions.php';

// If user is logged in, show dashboard
if (isset($_SESSION['user_id'])) {
    require 'dashboard.php';
} else {
    // If not logged in, show login page
    require 'login.php';
}
