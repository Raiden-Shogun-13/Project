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
            <p style="text-align: center; margin: 20px 0; color: #666;">
                Please verify you're human by entering the text from the security image below.
            </p>

            <div style="text-align: center; margin: 25px 0; padding: 15px; background: linear-gradient(135deg, rgba(56, 206, 60, 0.08), rgba(24, 24, 36, 0.05)); border-radius: 8px; border: 2px solid rgba(56, 206, 60, 0.3);">
                <iframe src="captcha.php" style="border: none; width: 330px; height: 130px; border-radius: 6px; display: inline-block; vertical-align: middle; margin: 0; padding: 0;"></iframe>
            </div>

            <div class="form-group">
                <label for="captcha" style="font-weight: 600; color: #181824;">Enter the text from the image *</label>
                <input id="captcha" type="text" name="captcha" placeholder="E.g. ABC123" required autocomplete="off" autofocus maxlength="6" style="text-transform: uppercase; letter-spacing: 3px; font-size: 20px; font-weight: bold; text-align: center; font-family: 'Courier New', monospace; border: 2px solid #38CE3C; padding: 12px;">
            </div>

            <p style="font-size: 0.85rem; color: #666; text-align: center; margin: 15px 0;">
                <span style="display: inline-block; padding: 8px 16px; background: rgba(56, 206, 60, 0.1); border-radius: 4px; border-left: 3px solid #38CE3C;">
                    Can't read it? <a href="javascript: location.reload();" style="color: #38CE3C; text-decoration: none; font-weight: 600;">🔄 Refresh image</a>
                </span>
            </p>

            <button type="submit" class="btn">Verify & Login</button>

            <a href="login.php" class="small-link">Start over</a>
        <?php endif; ?>
    </form>
    </div>
</div>

</body>
</html>
