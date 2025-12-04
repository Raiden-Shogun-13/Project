<?php
/**
 * CAPTCHA HTML5 Canvas Generator
 * Professional hotel-themed CAPTCHA with green (#38CE3C) and dark navy (#181824)
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .captcha-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            padding: 0;
        }
        
        canvas {
            border: 3px solid #38CE3C;
            border-radius: 10px;
            display: block;
            background: #181824;
            box-shadow: 0 8px 25px rgba(56, 206, 60, 0.15), 
                        inset 0 1px 2px rgba(255, 255, 255, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        canvas:hover {
            box-shadow: 0 12px 35px rgba(56, 206, 60, 0.25), 
                        inset 0 1px 2px rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }
        
        canvas:active {
            transform: translateY(0px);
        }
        
        .refresh-hint {
            font-size: 11px;
            color: #999;
            letter-spacing: 0.5px;
            opacity: 0.7;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="captcha-container">
        <canvas id="captcha" width="320" height="110"></canvas>
        <div class="refresh-hint">Click to refresh</div>
    </div>
    
    <script>
        const canvas = document.getElementById('captcha');
        const ctx = canvas.getContext('2d');
        const captchaText = '<?= htmlspecialchars($captcha_text, ENT_QUOTES) ?>';
        
        // Hotel theme colors
        const colors = {
            bgDark: '#181824',
            primary: '#38CE3C',
            primaryLight: '#64DC64',
            primaryDark: '#2B9530',
            text: '#FFFFFF',
            accent: '#8E32E9',
            border: '#287C28',
            line: 'rgba(56, 206, 60, 0.3)',
            highlight: 'rgba(56, 206, 60, 0.2)'
        };
        
        function drawCaptcha() {
            // Clear canvas
            ctx.fillStyle = colors.bgDark;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Add sophisticated gradient background
            const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            gradient.addColorStop(0, '#181824');
            gradient.addColorStop(0.5, '#1f1f2a');
            gradient.addColorStop(1, '#181824');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Add subtle grid pattern
            ctx.strokeStyle = 'rgba(56, 206, 60, 0.08)';
            ctx.lineWidth = 0.5;
            for (let i = 0; i < canvas.height; i += 15) {
                ctx.beginPath();
                ctx.moveTo(0, i);
                ctx.lineTo(canvas.width, i);
                ctx.stroke();
            }
            for (let i = 0; i < canvas.width; i += 20) {
                ctx.beginPath();
                ctx.moveTo(i, 0);
                ctx.lineTo(i, canvas.height);
                ctx.stroke();
            }
            
            // Add decorative circles
            for (let i = 0; i < 3; i++) {
                ctx.fillStyle = colors.line;
                ctx.globalAlpha = 0.4;
                ctx.beginPath();
                ctx.arc(
                    Math.random() * canvas.width,
                    Math.random() * canvas.height,
                    Math.random() * 30 + 10,
                    0,
                    Math.PI * 2
                );
                ctx.fill();
                ctx.globalAlpha = 1.0;
            }
            
            // Add security dots pattern
            for (let i = 0; i < 80; i++) {
                ctx.fillStyle = Math.random() > 0.5 ? colors.primaryDark : colors.primaryLight;
                ctx.globalAlpha = 0.5;
                ctx.beginPath();
                ctx.arc(
                    Math.random() * canvas.width,
                    Math.random() * canvas.height,
                    Math.random() * 1.5,
                    0,
                    Math.PI * 2
                );
                ctx.fill();
                ctx.globalAlpha = 1.0;
            }
            
            // Add distortion lines (green waves)
            for (let i = 0; i < 2; i++) {
                ctx.strokeStyle = colors.highlight;
                ctx.lineWidth = 2;
                ctx.beginPath();
                const startX = Math.random() * canvas.width / 2;
                const startY = Math.random() * canvas.height;
                const endX = canvas.width / 2 + Math.random() * canvas.width / 2;
                const endY = Math.random() * canvas.height;
                ctx.moveTo(startX, startY);
                ctx.lineTo(endX, endY);
                ctx.stroke();
            }
            
            // Draw decorative corner elements
            ctx.fillStyle = colors.primary;
            
            // Top-left corner accent
            ctx.fillRect(0, 0, 50, 3);
            ctx.fillRect(0, 0, 3, 50);
            
            // Bottom-right corner accent
            ctx.fillRect(canvas.width - 50, canvas.height - 3, 50, 3);
            ctx.fillRect(canvas.width - 3, canvas.height - 50, 3, 50);
            
            // Main CAPTCHA text rendering
            const textX = canvas.width / 2;
            const textY = canvas.height / 2;
            
            // Text styling
            ctx.font = 'bold 56px Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.letterSpacing = '8px';
            
            // Multi-layer text effect for security and visibility
            // Layer 1: Deep shadow
            ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
            ctx.fillText(captchaText, textX + 3, textY + 3);
            
            // Layer 2: Dark green shadow
            ctx.fillStyle = colors.primaryDark;
            ctx.fillText(captchaText, textX + 1, textY + 1);
            
            // Layer 3: Green outline/glow
            ctx.strokeStyle = colors.primaryLight;
            ctx.lineWidth = 2;
            ctx.strokeText(captchaText, textX, textY);
            
            // Layer 4: Main white text
            ctx.fillStyle = colors.text;
            ctx.fillText(captchaText, textX, textY);
            
            // Layer 5: Accent outline (purple shimmer)
            ctx.strokeStyle = colors.accent;
            ctx.lineWidth = 0.5;
            ctx.globalAlpha = 0.3;
            ctx.strokeText(captchaText, textX, textY);
            ctx.globalAlpha = 1.0;
            
            // Add bottom label
            ctx.font = 'bold 9px Arial, sans-serif';
            ctx.fillStyle = colors.primaryLight;
            ctx.globalAlpha = 0.6;
            ctx.textAlign = 'right';
            ctx.fillText('SECURITY VERIFICATION', canvas.width - 10, canvas.height - 8);
            ctx.globalAlpha = 1.0;
            
            // Professional double border
            // Outer border (primary green)
            ctx.strokeStyle = colors.primary;
            ctx.lineWidth = 3;
            ctx.strokeRect(0, 0, canvas.width, canvas.height);
            
            // Inner accent border (dark green)
            ctx.strokeStyle = colors.border;
            ctx.lineWidth = 1;
            ctx.strokeRect(3, 3, canvas.width - 6, canvas.height - 6);
            
            // Top accent line
            ctx.strokeStyle = colors.primaryLight;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(canvas.width, 0);
            ctx.stroke();
        }
        
        // Initial draw
        drawCaptcha();
        
        // Click to refresh CAPTCHA
        canvas.addEventListener('click', function() {
            // Add visual feedback
            canvas.style.opacity = '0.7';
            setTimeout(() => {
                drawCaptcha();
                canvas.style.opacity = '1';
            }, 100);
        });
        
        // Redraw on window resize
        window.addEventListener('resize', drawCaptcha);
    </script>
</body>
</html>



