<?php
// This file is now deprecated - CAPTCHA verification happens in login.php
// Redirect to login if someone tries to access it directly
session_start();

if (!isset($_SESSION['pending_user'])) {
    header('Location: login.php');
    exit;
}

// If they somehow have a pending user but reached here, redirect to login
header('Location: login.php');
exit;
?>

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<div class="container" role="main">
    <div class="card" role="region" aria-labelledby="verifyTitle">
        <h1 id="verifyTitle" class="title">Enter Verification Code</h1>
        <p class="note">A 6-digit code was sent to <strong><?php echo htmlspecialchars($pending['email']); ?></strong>. It expires in 5 minutes.</p>

        <?php if ($errors): ?>
            <div class="error-box" role="alert">
                <?php foreach ($errors as $e): ?>
                    &bull; <?php echo htmlspecialchars($e); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($messages): ?>
            <div class="message-box">
                <?php foreach ($messages as $m): ?>
                    <?php echo htmlspecialchars($m); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group">
                <label for="code">Verification Code</label>
                <input id="code" name="code" placeholder="Enter 6-digit code" autofocus>
            </div>
            <button class="btn" type="submit">Verify</button>
        </form>

        <div class="resend-row">
            <a href="verify.php?resend=1">Resend code</a>
            <a class="cancel-link" href="logout.php">Cancel</a>
        </div>
    </div>
</div>
</body>
</html>
