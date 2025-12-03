<?php
// test.php - Simple test to check if PHP is working
echo "PHP is working! ✓<br>";

// Try to connect to database
try {
    require 'db.php';
    echo "Database connection successful! ✓<br>";
} catch (Exception $e) {
    echo "Database connection FAILED: " . htmlspecialchars($e->getMessage()) . "<br>";
}

// Check if key files exist
$files = ['index.php', 'login.php', 'dashboard.php', 'functions.php', 'mail.php'];
echo "<br>Files check:<br>";
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ $file exists<br>";
    } else {
        echo "✗ $file MISSING!<br>";
    }
}

// Check vendor
if (is_dir('vendor')) {
    echo "✓ vendor/ folder exists<br>";
} else {
    echo "✗ vendor/ folder MISSING!<br>";
}

echo "<br><a href='index.php'>Go to index.php</a>";
?>
