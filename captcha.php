<?php
/**
 * CAPTCHA Image Generator
 * Generates a dynamic CAPTCHA image with distortion and noise
 */

session_start();

// Ensure CAPTCHA exists in session
if (!isset($_SESSION['captcha']) || empty($_SESSION['captcha']['text'])) {
    http_response_code(400);
    exit;
}

$captcha_text = $_SESSION['captcha']['text'];

// Image dimensions
$width = 280;
$height = 80;

// Create image
$image = imagecreatetruecolor($width, $height);

// Define colors
$bg_color = imagecolorallocate($image, 245, 245, 245);        // Light gray background
$line_color = imagecolorallocate($image, 200, 200, 200);      // Medium gray lines
$text_color = imagecolorallocate($image, 0, 0, 0);            // Black text
$noise_color = imagecolorallocate($image, 220, 220, 220);     // Light noise

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Add noise/dots for security
for ($i = 0; $i < 200; $i++) {
    imagefilledellipse(
        $image,
        random_int(0, $width),
        random_int(0, $height),
        random_int(1, 3),
        random_int(1, 3),
        $noise_color
    );
}

// Add wavy lines for distortion
for ($i = 0; $i < 3; $i++) {
    $start_x = random_int(0, $width / 2);
    $start_y = random_int(0, $height);
    $end_x = random_int($width / 2, $width);
    $end_y = random_int(0, $height);
    
    imageline($image, $start_x, $start_y, $end_x, $end_y, $line_color);
}

// Add grid lines for additional distortion
for ($i = 0; $i < 4; $i++) {
    $x = random_int(0, $width);
    $y = random_int(0, $height);
    imagerectangle($image, $x, $y, $x + random_int(10, 40), $y + random_int(10, 40), $line_color);
}

// Calculate font size and position
$font_size = 28;
$text_box = imagettfbbox($font_size, 0, __DIR__ . '/arial.ttf', $captcha_text);

// Fallback if Arial font not available
if ($text_box === false || !file_exists(__DIR__ . '/arial.ttf')) {
    // Use built-in font as fallback
    $char_width = imagefontwidth(5);
    $x = ($width - (strlen($captcha_text) * $char_width)) / 2;
    $y = ($height - imagefontheight(5)) / 2;
    
    // Add each character with slight rotation simulation
    $char_x = $x;
    foreach (str_split($captcha_text) as $char) {
        $angle = random_int(-15, 15);
        // Simulate rotation by varying position slightly
        imagestring($image, 5, $char_x, $y + random_int(-3, 3), $char, $text_color);
        $char_x += $char_width + 2;
    }
} else {
    // Use TrueType font if available
    $text_width = $text_box[2] - $text_box[0];
    $text_height = $text_box[1] - $text_box[7];
    
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2 + $font_size;
    
    // Add text with slight rotation
    $angle = random_int(-5, 5);
    imagettftext($image, $font_size, $angle, $x, $y, $text_color, __DIR__ . '/arial.ttf', $captcha_text);
}

// Add border
imagerectangle($image, 0, 0, $width - 1, $height - 1, imagecolorallocate($image, 100, 100, 100));

// Output image
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

imagepng($image);
imagedestroy($image);
?>
