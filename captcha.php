<?php
/**
 * CAPTCHA Image Generator
 * Generates a dynamic CAPTCHA image with professional styling
 * Color scheme: Green (#38CE3C), Dark Navy (#181824)
 */

session_start();

// Ensure CAPTCHA exists in session
if (!isset($_SESSION['captcha']) || empty($_SESSION['captcha']['text'])) {
    http_response_code(400);
    exit;
}

$captcha_text = $_SESSION['captcha']['text'];

// Image dimensions
$width = 300;
$height = 100;

// Create image
$image = imagecreatetruecolor($width, $height);

// Define colors (matching hotel theme)
$bg_color = imagecolorallocate($image, 24, 24, 36);           // Dark Navy background (#181824)
$primary_color = imagecolorallocate($image, 56, 206, 60);     // Green (#38CE3C)
$accent_color = imagecolorallocate($image, 100, 220, 100);    // Light Green
$text_color = imagecolorallocate($image, 255, 255, 255);      // White text
$line_color = imagecolorallocate($image, 56, 206, 60);        // Green lines
$noise_color = imagecolorallocate($image, 80, 130, 80);       // Dark green noise

// Fill background with gradient effect
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Add subtle gradient-like effect with horizontal stripes
for ($i = 0; $i < $height; $i += 3) {
    $shade = imagecolorallocate($image, 30 + ($i % 10), 30 + ($i % 10), 42 + ($i % 10));
    imageline($image, 0, $i, $width, $i, $shade);
}

// Add decorative corner elements
imagefilledrectangle($image, 0, 0, 30, 30, $primary_color);
imagefilledrectangle($image, $width - 30, $height - 30, $width, $height, $primary_color);

// Add corner triangles (green accent)
$triangle_x = array($width, $width - 30, $width);
$triangle_y = array(0, 30, 30);
imagefilledpolygon($image, array_merge($triangle_x, $triangle_y), 3, $line_color);

// Add animated-looking circles for decoration
for ($i = 0; $i < 4; $i++) {
    $cx = random_int(20, $width - 20);
    $cy = random_int(10, $height - 10);
    $radius = random_int(3, 8);
    imageellipse($image, $cx, $cy, $radius * 2, $radius * 2, $noise_color);
}

// Add security dots/noise pattern
for ($i = 0; $i < 150; $i++) {
    $dot_color = (random_int(0, 1) === 0) ? $noise_color : $accent_color;
    imagefilledellipse(
        $image,
        random_int(50, $width - 50),
        random_int(15, $height - 15),
        random_int(1, 2),
        random_int(1, 2),
        $dot_color
    );
}

// Add protective lines (green grid pattern)
for ($i = 0; $i < 5; $i++) {
    $y = ($height / 5) * $i;
    imageline($image, 0, $y, $width, $y, imagecolorallocate($image, 56, 206, 60, 20));
}

// Add anti-bot wavy distortion lines
for ($i = 0; $i < 4; $i++) {
    $x1 = random_int(0, $width / 2);
    $y1 = random_int(0, $height);
    $x2 = random_int($width / 2, $width);
    $y2 = random_int(0, $height);
    imageline($image, $x1, $y1, $x2, $y2, imagecolorallocate($image, 56, 206, 60, 30));
}

// Render CAPTCHA text with shadow effect and styling
$char_positions = [];
$char_width = imagefontwidth(5);
$total_width = strlen($captcha_text) * ($char_width + 5);
$start_x = ($width - $total_width) / 2;
$base_y = ($height - 20) / 2 + 10;

// Add text shadow for depth
foreach (str_split($captcha_text) as $index => $char) {
    $char_x = $start_x + ($index * ($char_width + 5));
    $char_y = $base_y + random_int(-5, 5);
    
    // Shadow layer (dark green)
    imagestring($image, 5, $char_x + 1, $char_y + 1, $char, $noise_color);
    
    // Main text layer (white with green outline)
    imagestring($image, 5, $char_x - 1, $char_y - 1, $char, $accent_color);
    imagestring($image, 5, $char_x, $char_y, $char, $text_color);
    imagestring($image, 5, $char_x + 1, $char_y, $char, $line_color);
}

// Add professional border with green accent
// Outer border (dark)
imagerectangle($image, 0, 0, $width - 1, $height - 1, imagecolorallocate($image, 40, 40, 50));
// Inner accent border (green)
imagerectangle($image, 2, 2, $width - 3, $height - 3, $primary_color);

// Add watermark-style text at bottom (very subtle)
imagestring($image, 1, $width - 65, $height - 8, 'SECURITY', imagecolorallocate($image, 56, 100, 60));

// Output image
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('X-Content-Type-Options: nosniff');

imagepng($image);
imagedestroy($image);
?>
