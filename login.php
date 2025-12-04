<?php
// Load dependencies if not already loaded
if (!function_exists('sanitize')) {
    require 'db.php';
    require 'functions.php';
    require 'mail.php';
}

$errors = [];
$captcha_text = '';
$step = 1; // Step 1: Email/Password, Step 2: CAPTCHA

// Check if we have credentials from step 1
if (isset($_SESSION['login_step2'])) {
    $step = 2;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Step 1: Validate email and password
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $errors[] = 'Please fill in both email and password.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // If user is admin, skip CAPTCHA and log in directly
                if (isset($user['role']) && $user['role'] === 'admin') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];

                    header('Location: admin_dashboard.php');
                    exit;
                }

                // For non-admin users, move to step 2 (CAPTCHA)
                $_SESSION['pending_user'] = [
                    'id' => $user['id'],
                    'role' => $user['role'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];

                // Generate CAPTCHA
                create_captcha();
                $_SESSION['login_step2'] = true;
                $step = 2;
            } else {
                $errors[] = 'Invalid email or password.';
            }
        }
    } elseif ($step === 2) {
        // Step 2: Validate CAPTCHA
        $captcha_input = trim($_POST['captcha'] ?? '');

        if (empty($captcha_input)) {
            $errors[] = 'Please enter the CAPTCHA text.';
        } elseif (!isset($_SESSION['pending_user'])) {
            $errors[] = 'Session expired. Please login again.';
            unset($_SESSION['login_step2']);
            $step = 1;
        } elseif (!verify_captcha($captcha_input)) {
            $errors[] = 'Invalid CAPTCHA. Please try again.';
            // Regenerate new CAPTCHA for retry
            create_captcha();
        } else {
            // CAPTCHA verified - complete login
            $pending = $_SESSION['pending_user'];
            $_SESSION['user_id'] = $pending['id'];
            $_SESSION['role'] = $pending['role'];
            $_SESSION['user_name'] = $pending['name'];

            // Cleanup session
            unset($_SESSION['pending_user']);
            unset($_SESSION['login_step2']);

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

<div class="container" role="main">
    <div class="card" role="region" aria-labelledby="loginTitle">
    <h1 id="loginTitle" class="title"><?= $step === 1 ? 'Log In' : 'Verify CAPTCHA' ?></h1>

    <?php if ($errors): ?>
        <div class="error-box" role="alert" aria-live="assertive">
            <?php foreach ($errors as $e): ?>
                &bull; <?= htmlspecialchars($e) ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <?php if ($step === 1): ?>
            <!-- Step 1: Email & Password -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" placeholder="you@example.com" required autocomplete="email" autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn">Login</button>

            <a href="register.php" class="small-link">Don't have an account? Register</a>
        <?php else: ?>
            <!-- Step 2: CAPTCHA Verification -->
            <div style="text-align: center; margin: 25px 0;">
                <p style="font-size: 0.95rem; color: #555; margin-bottom: 20px; letter-spacing: 0.3px;">
                    <strong>Security Verification</strong><br>
                    <span style="font-size: 0.85rem; color: #888; margin-top: 5px; display: block;">Please enter the text from the image below</span>
                </p>
            </div>

            <div style="text-align: center; margin: 25px 0; padding: 20px; background: linear-gradient(135deg, rgba(124, 58, 237, 0.08) 0%, rgba(59, 130, 246, 0.05) 100%); border-radius: 12px; border: 2px solid rgba(124, 58, 237, 0.25); box-shadow: inset 0 1px 3px rgba(124, 58, 237, 0.1);">
                <iframe src="captcha.php" style="border: none; width: 100%; height: 140px; border-radius: 8px; display: block; margin: 0; padding: 0; max-width: 360px; margin-left: auto; margin-right: auto; background: #0f172a;"></iframe>
            </div>

            <div class="form-group">
                <label for="captcha" style="font-weight: 600; color: #333; display: flex; align-items: center; gap: 6px;">
                    <span style="width: 20px; height: 20px; background: linear-gradient(135deg, #7C3AED, #3B82F6); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">✓</span>
                    Enter the verification text *
                </label>
                <input id="captcha" type="text" name="captcha" placeholder="E.g. ABC123" required autocomplete="off" autofocus maxlength="6" style="text-transform: uppercase; letter-spacing: 4px; font-size: 22px; font-weight: bold; text-align: center; font-family: 'Courier New', 'Monaco', monospace; border: 2px solid #7C3AED; padding: 14px; border-radius: 6px; background: #f9f9f9; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(124, 58, 237, 0.15);">
            </div>

            <style>
                input[type="text"]:focus {
                    outline: none;
                    border-color: #A78BFA !important;
                    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2), 0 2px 8px rgba(124, 58, 237, 0.25) !important;
                    background: #ffffff !important;
                }
            </style>

            <div style="text-align: center; margin: 18px 0; padding: 12px 16px; background: linear-gradient(135deg, rgba(124, 58, 237, 0.06), rgba(59, 130, 246, 0.04)); border-radius: 8px; border-left: 4px solid #7C3AED; border-right: 1px solid rgba(124, 58, 237, 0.2);">
                <p style="font-size: 0.82rem; color: #666; margin: 0;">
                    <a href="javascript: document.querySelector('iframe').src = document.querySelector('iframe').src;" style="color: #7C3AED; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                        <span style="font-size: 14px;">🔄</span> Refresh image
                    </a>
                    <span style="color: #ccc; margin: 0 8px;">•</span>
                    <span style="color: #999;">Can't read the text?</span>
                </p>
            </div>

            <button type="submit" class="btn" style="background: linear-gradient(135deg, #7C3AED 0%, #3B82F6 100%); margin-top: 10px;">Verify & Login</button>

            <a href="login.php" class="small-link">Start over</a>
        <?php endif; ?>
    </form>
    </div>
</div>

</body>
</html>
