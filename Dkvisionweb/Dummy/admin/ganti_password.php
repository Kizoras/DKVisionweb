<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['admin'];
$error = "";
$success = "";

if(isset($_POST['old_password'])){

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $query = mysqli_query($conn,"SELECT * FROM admin WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    // cek password lama (support md5 + bcrypt)
    if(
        !password_verify($old,$data['password']) &&
        md5($old) !== $data['password']
    ){
        $error = "Password lama salah!";
    }
    elseif($new != $confirm){
        $error = "Konfirmasi password tidak sama!";
    }
    else{
        $hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn,"UPDATE admin SET password='$hash' WHERE username='$username'");
        $success = "Password berhasil diganti!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganti Password - DKV Admin</title>
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
        --warning: #e67700;
        --warning-light: #fff9db;
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
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Container */
    .change-password-container {
        max-width: 480px;
        width: 100%;
        animation: fadeUp 0.3s ease;
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

    /* Card */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px;
        box-shadow: var(--shadow-lg);
        transition: box-shadow 0.2s ease;
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
    }

    /* Header */
    .card-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        background: var(--accent-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .icon-wrapper span {
        font-size: 28px;
    }

    .card-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.3px;
        margin-bottom: 6px;
    }

    .card-header p {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Alert Messages */
    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        margin-bottom: 20px;
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

    .alert-success {
        background: var(--success-light);
        border-left: 3px solid var(--success);
        color: var(--success);
    }

    .alert-icon {
        font-size: 1.1rem;
    }

    /* Form */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .form-group label .required-mark {
        color: var(--danger);
        margin-left: 2px;
    }

    .form-group input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--surface);
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-group input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(59, 91, 219, 0.1);
    }

    .form-group input::placeholder {
        color: var(--text-muted);
    }

    /* Password Hint */
    .password-hint {
        margin-top: 8px;
        font-size: 0.75rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .password-hint::before {
        content: "💡";
        font-size: 0.8rem;
    }

    /* Show Password */
    .show-password {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .show-password input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
        accent-color: var(--accent);
    }

    .show-password label {
        font-size: 0.8rem;
        color: var(--text-secondary);
        cursor: pointer;
        margin: 0;
    }

    /* Buttons */
    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border-radius: var(--radius-sm);
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--accent);
        border: none;
        color: #fff;
        box-shadow: 0 1px 4px rgba(59, 91, 219, 0.25);
    }

    .btn-primary:hover {
        background: var(--accent-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 91, 219, 0.3);
    }

    .btn-secondary {
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--text-secondary);
    }

    .btn-secondary:hover {
        background: var(--surface-2);
        border-color: var(--border);
        color: var(--text-primary);
    }

    .btn-back {
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        margin-top: 16px;
        width: 100%;
    }

    .btn-back:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    /* Warning */
    .warning-note {
        background: var(--warning-light);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.75rem;
        color: var(--warning);
    }

    .warning-note span:first-child {
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 520px) {
        .card {
            padding: 24px;
        }

        .button-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
</head>

<body>
<div class="change-password-container">
    <div class="card">
        <div class="card-header">
            <div class="icon-wrapper">
                <span><i class="fa fa-lock" aria-hidden="true"></i></span>
            </div>
            <h2>Ganti Password</h2>
            <p>Perbarui password Anda untuk keamanan akun</p>
        </div>

        <?php if($error): ?>
        <div class="alert alert-error">
            <span class="alert-icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
            <span><?php echo $error ?></span>
        </div>
        <?php endif; ?>

        <?php if($success): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span><?php echo $success ?></span>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Password Lama <span class="required-mark">*</span></label>
                <input type="password" name="old_password" id="old" placeholder="Masukkan password lama" required>
            </div>

            <div class="form-group">
                <label>Password Baru <span class="required-mark">*</span></label>
                <input type="password" name="new_password" id="new" placeholder="Masukkan password baru" required>
                <div class="password-hint">
                    Minimal 6 karakter, kombinasi huruf dan angka
                </div>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru <span class="required-mark">*</span></label>
                <input type="password" name="confirm_password" id="confirm" placeholder="Ulangi password baru" required>
            </div>

            <div class="show-password">
                <input type="checkbox" onclick="toggle()" id="showPassword">
                <label for="showPassword">Tampilkan password</label>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <span>✓</span> Ganti Password
                </button>
                <button type="reset" class="btn btn-secondary">
                    <span>⟳</span> Reset
                </button>
            </div>
        </form>

        <div class="warning-note">
            <span><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
            <span>Pastikan untuk mengingat password baru Anda atau screenshot halaman ini sebagai bukti perubahan</span>
        </div>

        <a href="dashboard.php" class="btn btn-back">
            <span>←</span> Kembali ke Dashboard
        </a>
    </div>
</div>

<script>
function toggle() {
    let old = document.getElementById("old");
    let newPass = document.getElementById("new");
    let confirm = document.getElementById("confirm");
    let showCheckbox = document.getElementById("showPassword");
    
    let type = showCheckbox.checked ? "text" : "password";
    old.type = type;
    newPass.type = type;
    confirm.type = type;
}
</script>

</body>
</html>