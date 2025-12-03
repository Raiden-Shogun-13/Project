<?php
require 'functions.php';
redirect_if_not_logged_in();

$raw = flash('booking_info');
$info = $raw ? json_decode($raw, true) : null;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Booking Success</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navigation */
    nav {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      padding: 1rem 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    nav .container {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    nav .logo {
      color: white;
      font-size: 1.3rem;
      font-weight: bold;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: opacity 0.3s;
    }

    nav a:hover {
      opacity: 0.8;
    }

    /* Main Container */
    main {
      max-width: 700px;
      margin: 3rem auto;
      padding: 2rem;
      flex: 1;
    }

    /* Success Card */
    .success-card {
      background: white;
      border-radius: 15px;
      padding: 3rem;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      text-align: center;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Success Icon */
    .success-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 3rem;
      box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    h1 {
      color: #1e293b;
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    .subtitle {
      color: #64748b;
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    /* Booking Details */
    .booking-details {
      background: #f1f5f9;
      border-radius: 10px;
      padding: 2rem;
      margin: 2rem 0;
      text-align: left;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid #e2e8f0;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      color: #64748b;
      font-weight: 500;
      font-size: 0.95rem;
    }

    .detail-value {
      color: #1e293b;
      font-weight: 600;
      font-size: 1rem;
    }

    .service-name {
      color: #2563eb;
      font-size: 1.2rem;
      font-weight: bold;
    }

    .booking-id {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      font-family: monospace;
      font-weight: bold;
    }

    /* Confirmation Message */
    .confirmation-message {
      background: #ecfdf5;
      border-left: 4px solid #10b981;
      padding: 1rem;
      border-radius: 5px;
      margin: 2rem 0;
      color: #065f46;
    }

    .confirmation-message strong {
      color: #047857;
    }

    /* Buttons */
    .button-group {
      display: flex;
      gap: 1rem;
      justify-content: center;
      margin-top: 2.5rem;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 28px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
      text-align: center;
    }

    .btn-primary {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: white;
      box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }

    .btn-secondary {
      background: white;
      color: #2563eb;
      border: 2px solid #2563eb;
    }

    .btn-secondary:hover {
      background: #f1f5f9;
      transform: translateY(-2px);
    }

    .btn-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    /* Error State */
    .error-state {
      text-align: center;
      padding: 2rem;
    }

    .error-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .error-state h1 {
      color: #dc2626;
    }

    .error-state p {
      color: #64748b;
      margin: 1rem 0;
    }

    /* Responsive */
    @media (max-width: 600px) {
      main {
        margin: 1rem;
        padding: 1rem;
      }

      .success-card {
        padding: 2rem 1.5rem;
      }

      h1 {
        font-size: 1.5rem;
      }

      .detail-row {
        flex-direction: column;
        align-items: flex-start;
      }

      .detail-value {
        margin-top: 0.5rem;
      }

      .button-group {
        flex-direction: column;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav>
    <div class="container">
      <div class="logo">🏨 Hotel Appointments</div>
      <a href="dashboard.php">← Back to Dashboard</a>
    </div>
  </nav>

  <!-- Main Content -->
  <main>
    <?php if (!$info): ?>
      <!-- Error State -->
      <div class="success-card error-state">
        <div class="error-icon">⚠️</div>
        <h1>Oops!</h1>
        <p>No booking information available.</p>
        <p style="font-size: 0.9rem; color: #94a3b8;">It seems you accessed this page directly. Please make a booking from your dashboard.</p>
        <div class="button-group">
          <a href="dashboard.php" class="btn btn-primary">← Return to Dashboard</a>
        </div>
      </div>
    <?php else: ?>
      <!-- Success State -->
      <div class="success-card">
        <!-- Success Icon -->
        <div class="success-icon">✓</div>

        <!-- Heading -->
        <h1>Booking Confirmed! 🎉</h1>
        <p class="subtitle">Your appointment has been successfully scheduled</p>

        <!-- Booking Details -->
        <div class="booking-details">
          <div class="detail-row">
            <span class="detail-label">Service:</span>
            <span class="detail-value service-name"><?= htmlspecialchars($info['service']) ?></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date & Time:</span>
            <span class="detail-value"><?= htmlspecialchars(date('F j, Y', strtotime($info['datetime']))) ?></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Time:</span>
            <span class="detail-value"><?= htmlspecialchars(date('g:i A', strtotime($info['datetime']))) ?></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Booking ID:</span>
            <span class="booking-id">#<?= htmlspecialchars($info['id']) ?></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Confirmation Email:</span>
            <span class="detail-value" style="color: <?= $info['email_sent'] ? '#10b981' : '#dc2626'; ?>">
              <?= $info['email_sent'] ? '✓ Sent' : '✗ Not Sent' ?>
            </span>
          </div>
        </div>

        <!-- Confirmation Message -->
        <div class="confirmation-message">
          📧 <strong>A confirmation email has been sent</strong> to your registered email address. 
          You can manage, reschedule, or cancel this appointment from your dashboard anytime.
        </div>

        <!-- Action Buttons -->
        <div class="button-group">
          <a href="dashboard.php" class="btn btn-primary">
            📊 View My Appointments
          </a>
          <a href="dashboard.php?tab=book" class="btn btn-secondary">
            + Book Another
          </a>
        </div>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
