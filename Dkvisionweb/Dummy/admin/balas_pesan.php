<?php
session_start();
include "db.php";
require_once "config/email_config.php"; // Tambahkan ini

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Ambil data pesan
$query = mysqli_query($conn, "SELECT * FROM pesan WHERE id='$id'");
$pesan = mysqli_fetch_assoc($query);

if(!$pesan){
    header("Location: dashboard.php?tab=pesan");
    exit;
}

$error = '';
$success = '';

// Proses pengiriman email
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $to = $_POST['email'];
    $subject = $_POST['subjek'];
    $message = $_POST['pesan'];
    
    // Format pesan HTML
    $html_message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>$subject</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #3b5bdb; color: white; padding: 15px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f5f6fa; padding: 20px; border-radius: 0 0 10px 10px; }
            .footer { text-align: center; padding: 15px; font-size: 12px; color: #999; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Balasan dari Admin DKV</h2>
            </div>
            <div class='content'>
                <p>Halo,</p>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
                <br>
                <p>Hormat kami,<br><strong>Admin DKV SMKN 1 Cibinong</strong></p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " Jurusan DKV SMKN 1 Cibinong</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Kirim email menggunakan PHPMailer
    $result = kirimEmail($to, $subject, $html_message);
    
    if($result['success']){
        // Update status pesan asli menjadi sudah dibalas
        mysqli_query($conn, "UPDATE pesan SET status='Sudah Dibalas' WHERE id='$id'");
        $success = "Balasan berhasil dikirim ke " . $to;
        
        // Redirect setelah 2 detik
        echo "<script>
            setTimeout(function() {
                window.location.href = 'dashboard.php?tab=pesan';
            }, 2000);
        </script>";
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balas Pesan - DKV Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #f5f6fa;
            --surface: #ffffff;
            --border: #e4e6ed;
            --text-primary: #1a1d27;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --accent: #3b5bdb;
            --accent-light: #eef2ff;
            --accent-hover: #3451c7;
            --danger: #e03131;
            --success: #2f9e44;
            --warning: #e67700;
            --radius: 10px;
            --radius-sm: 6px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        }

        body {
            font-family: "DM Sans", system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, var(--accent-light) 0%, transparent 100%);
        }

        .card-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h1 i {
            color: var(--accent);
            font-size: 1.6rem;
        }

        .card-header .subtitle {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 6px;
        }

        .card-content {
            padding: 28px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .info-value {
            color: var(--text-primary);
            font-size: 0.9rem;
            word-break: break-word;
        }

        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #ebfbee;
            border-left: 3px solid var(--success);
            color: var(--success);
        }

        .alert-error {
            background: #fff5f5;
            border-left: 3px solid var(--danger);
            color: var(--danger);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-group label .required {
            color: var(--danger);
            margin-left: 2px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 91, 219, 0.1);
        }

        .form-group input[readonly] {
            background: var(--bg);
            color: var(--text-secondary);
            cursor: not-allowed;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-secondary:hover {
            background: var(--bg);
            border-color: var(--accent);
            color: var(--accent);
        }

        @media (max-width: 600px) {
            .card-header {
                padding: 18px 20px;
            }
            .card-content {
                padding: 20px;
            }
            .info-grid {
                grid-template-columns: 80px 1fr;
                gap: 12px;
            }
            .button-group {
                flex-direction: column;
            }
            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php?tab=pesan" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <div class="card">
            <div class="card-header">
                <h1>
                    <i class="fas fa-reply-all"></i> 
                    Balas Pesan
                </h1>
                <div class="subtitle">Kirim balasan ke pengirim pesan</div>
            </div>

            <div class="card-content">
                <?php if($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    <span><?php echo $success; ?></span>
                </div>
                <?php endif; ?>

                <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <span><?php echo $error; ?></span>
                </div>
                <?php endif; ?>

                <div class="info-grid">
                    <div class="info-label">Dari:</div>
                    <div class="info-value">
                        <strong><?php echo htmlspecialchars($pesan['nama']); ?></strong>
                    </div>

                    <div class="info-label">Email:</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($pesan['email']); ?>
                    </div>

                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">
                        <?php echo date('d F Y H:i', strtotime($pesan['dibuat_dari_tanggal'])); ?>
                    </div>

                    <div class="info-label">Pesan:</div>
                    <div class="info-value" style="background: var(--bg); padding: 10px; border-radius: var(--radius-sm);">
                        <?php echo nl2br(htmlspecialchars($pesan['pesan'])); ?>
                    </div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label>Email Tujuan <span class="required">*</span></label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($pesan['email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Subjek <span class="required">*</span></label>
                        <input type="text" name="subjek" placeholder="Masukkan subjek email" required>
                    </div>

                    <div class="form-group">
                        <label>Isi Pesan <span class="required">*</span></label>
                        <textarea name="pesan" placeholder="Tulis balasan Anda di sini..." required></textarea>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Balasan
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>