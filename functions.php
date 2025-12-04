<?php
// functions.php

// Start session if not started already (safe to call multiple times)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if logged in user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect to login if not logged in
function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Redirect to dashboard if not admin
function redirect_if_not_admin() {
    if (!is_admin()) {
        header('Location: dashboard.php');
        exit;
    }
}

// Flash messaging helper
function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
    } elseif (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

// Sanitize input (for output safety)
function sanitize($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Generate a numeric verification code of given length (default 6)
function generate_verification_code($length = 6) {
    $min = (int) str_repeat('1', $length - 1);
    $max = (int) str_repeat('9', $length);
    // fallback if length == 1
    if ($length === 1) {
        $min = 0;
        $max = 9;
    }
    return (string) random_int($min, $max);
}

// Optional: function to get current user info from DB, if needed
function current_user($pdo) {
    if (!is_logged_in()) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Generate random CAPTCHA text (6 characters)
function generate_captcha_text($length = 6) {
    $chars = 'ACDEFGHJKLMNPQRTWXYZ23456789';
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $captcha;
}

// Store CAPTCHA in session and return text
function create_captcha() {
    $captcha_text = generate_captcha_text(6);
    $_SESSION['captcha'] = [
        'text' => $captcha_text,
        'created' => time(),
        'expires' => time() + 600 // 10 minutes
    ];
    return $captcha_text;
}

// Verify user's CAPTCHA input
function verify_captcha($user_input) {
    if (!isset($_SESSION['captcha'])) {
        return false;
    }
    
    $captcha = $_SESSION['captcha'];
    
    // Check if expired
    if (time() > $captcha['expires']) {
        unset($_SESSION['captcha']);
        return false;
    }
    
    // Case-insensitive comparison
    $matches = hash_equals(
        strtoupper(trim($captcha['text'])),
        strtoupper(trim($user_input))
    );
    
    if ($matches) {
        unset($_SESSION['captcha']); // Clear after successful verification
    }
    
    return $matches;
}

