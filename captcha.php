<?php
/**
 * CAPTCHA HTML5 Canvas Generator
 * Uses client-side Canvas rendering instead of GD library
 * No server-side image generation needed
 */

session_start();

// Ensure CAPTCHA exists in session
if (!isset($_SESSION['captcha']) || empty($_SESSION['captcha']['text'])) {
    http_response_code(400);
    exit('Invalid CAPTCHA session');
}

$captcha_text = $_SESSION['captcha']['text'];

// Return HTML with Canvas-based CAPTCHA
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        canvas {
            border: 3px solid #38CE3C;
            border-radius: 6px;
            display: inline-block;
            background: #181824;
            box-shadow: 0 4px 12px rgba(56, 206, 60, 0.2);
            cursor: pointer;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background: transparent;">
    <canvas id="captcha" width="300" height="100"></canvas>
    
    <script>
        const canvas = document.getElementById('captcha');
        const ctx = canvas.getContext('2d');
        const captchaText = '<?= htmlspecialchars($captcha_text, ENT_QUOTES) ?>';
        
        // Colors
        const bgColor = '#181824';
        const primaryColor = '#38CE3C';
        const textColor = '#FFFFFF';
        const accentColor = '#64DC64';
        const darkGreen = '#287C28';
        
        function drawCaptcha() {
            // Clear canvas
            ctx.fillStyle = bgColor;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Add background pattern
            ctx.strokeStyle = 'rgba(56, 206, 60, 0.15)';
            ctx.lineWidth = 1;
            for (let i = 0; i < canvas.height; i += 10) {
                ctx.beginPath();
                ctx.moveTo(0, i);
                ctx.lineTo(canvas.width, i);
                ctx.stroke();
            }
            
            // Add noise dots
            for (let i = 0; i < 100; i++) {
                ctx.fillStyle = Math.random() > 0.5 ? accentColor : darkGreen;
                ctx.globalAlpha = 0.6;
                ctx.fillRect(
                    Math.random() * canvas.width,
                    Math.random() * canvas.height,
                    2, 2
                );
                ctx.globalAlpha = 1.0;
            }
            
            // Add distortion lines
            ctx.strokeStyle = primaryColor;
            ctx.lineWidth = 2;
            for (let i = 0; i < 3; i++) {
                ctx.beginPath();
                ctx.moveTo(Math.random() * canvas.width / 2, Math.random() * canvas.height);
                ctx.lineTo(canvas.width / 2 + Math.random() * canvas.width / 2, Math.random() * canvas.height);
                ctx.stroke();
            }
            
            // Add corner decorations
            ctx.fillStyle = primaryColor;
            ctx.fillRect(0, 0, 40, 40);
            ctx.fillRect(canvas.width - 40, canvas.height - 40, 40, 40);
            
            // Draw text with effects
            ctx.font = 'bold 42px Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            const textX = canvas.width / 2;
            const textY = canvas.height / 2;
            
            // Shadow
            ctx.fillStyle = darkGreen;
            ctx.fillText(captchaText, textX + 2, textY + 2);
            
            // Outline
            ctx.strokeStyle = accentColor;
            ctx.lineWidth = 2;
            ctx.strokeText(captchaText, textX, textY);
            
            // Main text
            ctx.fillStyle = textColor;
            ctx.fillText(captchaText, textX, textY);
            
            // Add security text
            ctx.font = '10px Arial';
            ctx.fillStyle = accentColor;
            ctx.globalAlpha = 0.7;
            ctx.fillText('SECURITY', 20, canvas.height - 10);
            ctx.globalAlpha = 1.0;
            
            // Draw borders
            ctx.strokeStyle = primaryColor;
            ctx.lineWidth = 3;
            ctx.strokeRect(0, 0, canvas.width, canvas.height);
            
            ctx.strokeStyle = darkGreen;
            ctx.lineWidth = 1;
            ctx.strokeRect(2, 2, canvas.width - 4, canvas.height - 4);
        }
        
        // Draw CAPTCHA
        drawCaptcha();
        
        // Allow refresh on click
        canvas.addEventListener('click', function() {
            drawCaptcha();
        });
        
        // Store text for verification (optional visual feedback)
        window.captchaText = captchaText;
    </script>
</body>
</html>


