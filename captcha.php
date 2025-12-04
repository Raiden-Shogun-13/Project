<?php
/**
 * CAPTCHA HTML5 Canvas Generator
 * Premium gradient design with purple and blue theme
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
            border: 3px solid #7C3AED;
            border-radius: 12px;
            display: block;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 0 12px 40px rgba(124, 58, 237, 0.25), 
                        0 0 30px rgba(99, 102, 241, 0.15),
                        inset 0 1px 2px rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        canvas:hover {
            box-shadow: 0 16px 50px rgba(124, 58, 237, 0.35), 
                        0 0 40px rgba(99, 102, 241, 0.25),
                        inset 0 1px 2px rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            border-color: #A78BFA;
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
        
        // Premium gradient colors (Purple to Blue theme)
        const colors = {
            primary: '#7C3AED',           // Vibrant Purple
            primaryLight: '#A78BFA',      // Light Purple
            primaryDark: '#6D28D9',       // Dark Purple
            secondary: '#3B82F6',         // Bright Blue
            secondaryLight: '#60A5FA',    // Light Blue
            accent: '#EC4899',            // Pink accent
            text: '#FFFFFF',              // White
            textAlt: '#E9D5FF',           // Light purple text
            glow1: 'rgba(124, 58, 237, 0.3)',    // Purple glow
            glow2: 'rgba(59, 130, 246, 0.2)',    // Blue glow
            grid: 'rgba(167, 139, 250, 0.1)',    // Grid pattern
            dark: '#0f172a'               // Very dark blue
        };
        
        function drawCaptcha() {
            // Clear and create gradient background
            const bgGradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            bgGradient.addColorStop(0, '#1a1a2e');
            bgGradient.addColorStop(0.5, '#16213e');
            bgGradient.addColorStop(1, '#0f3460');
            ctx.fillStyle = bgGradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Add animated radial gradient overlay
            const radialGradient = ctx.createRadialGradient(
                canvas.width / 2, canvas.height / 2, 0,
                canvas.width / 2, canvas.height / 2, Math.max(canvas.width, canvas.height) / 2
            );
            radialGradient.addColorStop(0, 'rgba(124, 58, 237, 0.1)');
            radialGradient.addColorStop(0.7, 'rgba(59, 130, 246, 0.05)');
            radialGradient.addColorStop(1, 'rgba(15, 23, 42, 0)');
            ctx.fillStyle = radialGradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Add premium grid pattern
            ctx.strokeStyle = colors.grid;
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
            
            // Add decorative circles with gradient
            for (let i = 0; i < 3; i++) {
                const circleGrad = ctx.createRadialGradient(
                    Math.random() * canvas.width, Math.random() * canvas.height, 0,
                    Math.random() * canvas.width, Math.random() * canvas.height, Math.random() * 40 + 20
                );
                circleGrad.addColorStop(0, colors.glow1);
                circleGrad.addColorStop(1, colors.glow2);
                
                ctx.fillStyle = circleGrad;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(
                    Math.random() * canvas.width,
                    Math.random() * canvas.height,
                    Math.random() * 35 + 15,
                    0,
                    Math.PI * 2
                );
                ctx.fill();
                ctx.globalAlpha = 1.0;
            }
            
            // Add security dot pattern
            for (let i = 0; i < 100; i++) {
                ctx.fillStyle = Math.random() > 0.5 ? colors.primary : colors.secondary;
                ctx.globalAlpha = 0.4;
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
            
            // Add premium wave lines
            for (let i = 0; i < 2; i++) {
                ctx.strokeStyle = i === 0 ? colors.glow1 : colors.glow2;
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
            
            // Draw premium corner accents
            // Top-left corner - purple gradient
            const cornerGrad1 = ctx.createLinearGradient(0, 0, 60, 60);
            cornerGrad1.addColorStop(0, colors.primary);
            cornerGrad1.addColorStop(1, 'rgba(124, 58, 237, 0)');
            ctx.fillStyle = cornerGrad1;
            ctx.fillRect(0, 0, 60, 3);
            ctx.fillRect(0, 0, 3, 60);
            
            // Bottom-right corner - blue gradient
            const cornerGrad2 = ctx.createLinearGradient(canvas.width - 60, canvas.height - 60, canvas.width, canvas.height);
            cornerGrad2.addColorStop(0, 'rgba(59, 130, 246, 0)');
            cornerGrad2.addColorStop(1, colors.secondary);
            ctx.fillStyle = cornerGrad2;
            ctx.fillRect(canvas.width - 60, canvas.height - 3, 60, 3);
            ctx.fillRect(canvas.width - 3, canvas.height - 60, 3, 60);
            
            // Main CAPTCHA text rendering
            const textX = canvas.width / 2;
            const textY = canvas.height / 2;
            
            // Text styling
            ctx.font = 'bold 58px Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            // Layer 1: Deep shadow (black)
            ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
            ctx.fillText(captchaText, textX + 3, textY + 3);
            
            // Layer 2: Purple shadow
            ctx.fillStyle = colors.primaryDark;
            ctx.fillText(captchaText, textX + 1, textY + 1);
            
            // Layer 3: Purple outline/glow
            ctx.strokeStyle = colors.primaryLight;
            ctx.lineWidth = 2.5;
            ctx.strokeText(captchaText, textX, textY);
            
            // Layer 4: Blue outline
            ctx.strokeStyle = colors.secondaryLight;
            ctx.lineWidth = 1;
            ctx.globalAlpha = 0.6;
            ctx.strokeText(captchaText, textX, textY);
            ctx.globalAlpha = 1.0;
            
            // Layer 5: Main white text
            ctx.fillStyle = colors.text;
            ctx.fillText(captchaText, textX, textY);
            
            // Layer 6: Pink shimmer accent
            ctx.strokeStyle = colors.accent;
            ctx.lineWidth = 0.5;
            ctx.globalAlpha = 0.2;
            ctx.strokeText(captchaText, textX, textY);
            ctx.globalAlpha = 1.0;
            
            // Add premium label
            ctx.font = 'bold 10px Arial, sans-serif';
            ctx.fillStyle = colors.primaryLight;
            ctx.globalAlpha = 0.7;
            ctx.textAlign = 'right';
            ctx.fillText('PREMIUM SECURITY', canvas.width - 10, canvas.height - 8);
            ctx.globalAlpha = 1.0;
            
            // Professional double border with gradient
            // Outer border (primary purple)
            ctx.strokeStyle = colors.primary;
            ctx.lineWidth = 3;
            ctx.strokeRect(0, 0, canvas.width, canvas.height);
            
            // Inner accent border (dark purple)
            ctx.strokeStyle = colors.primaryDark;
            ctx.lineWidth = 1;
            ctx.strokeRect(3, 3, canvas.width - 6, canvas.height - 6);
            
            // Top accent line (gradient)
            const topGradient = ctx.createLinearGradient(0, 0, canvas.width, 0);
            topGradient.addColorStop(0, colors.primaryLight);
            topGradient.addColorStop(0.5, colors.secondaryLight);
            topGradient.addColorStop(1, colors.primaryLight);
            ctx.strokeStyle = topGradient;
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(canvas.width, 0);
            ctx.stroke();
        }
        
        // Initial draw
        drawCaptcha();
        
        // Click to refresh CAPTCHA with animation
        canvas.addEventListener('click', function() {
            canvas.style.opacity = '0.6';
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




