<?php
session_start();
if(isset($_SESSION['admin'])){
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login - DKV</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg: #f5f6fa;
      --surface: #ffffff;
      --surface-2: #f0f1f5;
      --border: #e4e6ed;
      --border-light: #eef0f6;
      --text-primary: #1a1d27;
      --text-secondary: #6b7280;
      --text-muted: #9ca3af;
      --accent: #3b5bdb;
      --accent-light: #eef2ff;
      --accent-hover: #3451c7;
      --danger: #e03131;
      --danger-light: #fff5f5;
      --success: #2f9e44;
      --success-light: #ebfbee;
      --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
      --shadow-md: 0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
      --shadow-lg: 0 10px 30px rgba(0,0,0,0.10), 0 4px 8px rgba(0,0,0,0.06);
      --radius: 10px;
      --radius-sm: 6px;
      --radius-lg: 14px;
    }

    body {
      font-family: "DM Sans", system-ui, -apple-system, sans-serif;
      background: var(--bg);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Background decorative elements */
    body::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at 20% 30%, rgba(59, 91, 219, 0.03) 0%, transparent 50%),
                  radial-gradient(circle at 80% 70%, rgba(59, 91, 219, 0.02) 0%, transparent 50%);
      pointer-events: none;
      animation: rotate 60s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .login-container {
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 1;
      animation: fadeUp 0.4s ease;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 48px 40px;
      box-shadow: var(--shadow-lg);
      transition: box-shadow 0.2s ease;
    }

    .login-card:hover {
      box-shadow: var(--shadow-lg);
    }

    .login-header {
      text-align: center;
      margin-bottom: 36px;
    }

    .logo-icon {
      width: 70px;
      height: 70px;
      margin: 0 auto 20px;
      background: var(--accent-light);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.2rem;
      transition: transform 0.2s ease;
    }

    .logo-icon:hover {
      transform: scale(1.05);
    }

    .login-header h1 {
      color: var(--text-primary);
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: -0.3px;
    }

    .login-header p {
      color: var(--text-muted);
      font-size: 0.85rem;
      font-weight: 500;
      line-height: 1.4;
    }

    /* Alert Messages */
    .alert {
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      margin-bottom: 24px;
      font-size: 0.85rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: slideIn 0.2s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-10px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .alert-error {
      background: var(--danger-light);
      border-left: 3px solid var(--danger);
      color: var(--danger);
    }

    .alert-icon {
      font-size: 1rem;
    }

    .login-form {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group label {
      color: var(--text-primary);
      font-weight: 600;
      font-size: 0.82rem;
      letter-spacing: 0.2px;
    }

    .form-group label .required-mark {
      color: var(--danger);
      margin-left: 2px;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      color: var(--text-muted);
      font-size: 1rem;
      pointer-events: none;
    }

    .form-group input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border: 1px solid var(--border);
      background: var(--surface);
      border-radius: var(--radius-sm);
      color: var(--text-primary);
      font-family: inherit;
      font-size: 0.875rem;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-group input::placeholder {
      color: var(--text-muted);
    }

    .form-group input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(59, 91, 219, 0.1);
    }

    /* Show Password Toggle */
    .show-password {
      margin-top: -8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .show-password input[type="checkbox"] {
      width: 16px;
      height: 16px;
      margin: 0;
      cursor: pointer;
      accent-color: var(--accent);
    }

    .show-password label {
      font-size: 0.75rem;
      color: var(--text-muted);
      cursor: pointer;
      margin: 0;
      font-weight: 500;
    }

    .submit-btn {
      padding: 12px 24px;
      background: var(--accent);
      border: none;
      border-radius: var(--radius-sm);
      color: #fff;
      font-weight: 600;
      font-size: 0.875rem;
      letter-spacing: 0.3px;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 1px 4px rgba(59, 91, 219, 0.25);
      margin-top: 8px;
      font-family: inherit;
    }

    .submit-btn:hover {
      background: var(--accent-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 91, 219, 0.3);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .footer-text {
      text-align: center;
      color: var(--text-muted);
      font-size: 0.75rem;
      margin-top: 28px;
      padding-top: 20px;
      border-top: 1px solid var(--border-light);
    }

    .footer-text a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s ease;
    }

    .footer-text a:hover {
      color: var(--accent-hover);
      text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 520px) {
      .login-card {
        padding: 32px 24px;
      }

      .login-header h1 {
        font-size: 1.5rem;
      }

      .login-header p {
        font-size: 0.8rem;
      }

      .form-group input {
        padding: 11px 12px 11px 38px;
        font-size: 16px;
      }

      .input-icon {
        left: 12px;
        font-size: 0.9rem;
      }

      .submit-btn {
        padding: 11px 20px;
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="logo-icon">
          <i class="fa fa-user-circle" aria-hidden="true"></i>
        </div>
        <h1>Admin DKV</h1>
        <p>Portal Manajemen Jurusan Desain Komunikasi Visual<br>SMKN 1 Cibinong</p>
      </div>

      <?php if(isset($_GET['error'])): ?>
      <div class="alert alert-error">
        <span class="alert-icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
        <span>Username atau password salah!</span>
      </div>
      <?php endif; ?>

      <form action="auth.php" method="POST" class="login-form" enctype="application/x-www-form-urlencoded">
        <div class="form-group">
          <label>Username <span class="required-mark">*</span></label>
          <div class="input-wrapper">
            <span class="input-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
            <input 
              type="text" 
              name="username" 
              placeholder="Masukkan username Anda" 
              required 
              autocomplete="username"
            />
          </div>
        </div>

        <div class="form-group">
          <label>Password <span class="required-mark">*</span></label>
          <div class="input-wrapper">
            <span class="input-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
            <input 
              type="password" 
              name="password" 
              id="password"
              placeholder="Masukkan password Anda" 
              required 
              autocomplete="current-password"
            />
          </div>
        </div>

        <div class="show-password">
          <input type="checkbox" id="showPassword" onclick="togglePassword()">
          <label for="showPassword">Tampilkan password</label>
        </div>

        <button type="submit" class="submit-btn">Masuk ke Dashboard</button>
      </form>

      <div class="footer-text">
        &copy; <?php echo date('Y'); ?> SMKN 1 Cibinong - Jurusan DKV
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const showCheckbox = document.getElementById('showPassword');
      
      if (showCheckbox.checked) {
        passwordInput.type = 'text';
      } else {
        passwordInput.type = 'password';
      }
    }
  </script>
</body>
</html>