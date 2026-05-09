<?php
// baca_pesan.php
session_start();
include "db.php";

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

// Update status menjadi sudah dibaca jika belum
if($pesan['status'] != 'Sudah Dibaca'){
    mysqli_query($conn, "UPDATE pesan SET status='Sudah Dibaca' WHERE id='$id'");
    $pesan['status'] = 'Sudah Dibaca';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Pesan - DKV Admin</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        /* Header */
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

        /* Content */
        .card-content {
            padding: 28px;
        }

        /* Info Grid */
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

        .info-value a {
            color: var(--accent);
            text-decoration: none;
        }

        .info-value a:hover {
            text-decoration: underline;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-read {
            background: #ebfbee;
            color: #2f9e44;
        }

        .status-unread {
            background: #fff9db;
            color: #e67700;
        }

        /* Pesan Box */
        .pesan-box {
            background: var(--bg);
            padding: 20px;
            border-radius: var(--radius);
            margin-bottom: 28px;
            line-height: 1.7;
            font-size: 0.95rem;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
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

        .btn-danger {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--danger);
        }

        .btn-danger:hover {
            background: #fff5f5;
            border-color: var(--danger);
        }

        /* Back link */
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
                    <i class="fas fa-envelope-open-text"></i> 
                    Detail Pesan
                </h1>
                <div class="subtitle">Pesan dari pengunjung website</div>
            </div>

            <div class="card-content">
                <div class="info-grid">
                    <div class="info-label">Dari:</div>
                    <div class="info-value">
                        <strong><?php echo htmlspecialchars($pesan['nama']); ?></strong>
                    </div>

                    <div class="info-label">Email:</div>
                    <div class="info-value">
                        <a href="mailto:<?php echo htmlspecialchars($pesan['email']); ?>">
                            <?php echo htmlspecialchars($pesan['email']); ?>
                        </a>
                    </div>

                    <div class="info-label">Telepon:</div>
                    <div class="info-value">
                        <a href="tel:<?php echo htmlspecialchars($pesan['telepon']); ?>">
                            <?php echo htmlspecialchars($pesan['telepon']); ?>
                        </a>
                    </div>

                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">
                        <?php echo date('d F Y H:i', strtotime($pesan['dibuat_dari_tanggal'])); ?>
                    </div>

                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge <?php echo $pesan['status'] == 'Sudah Dibaca' ? 'status-read' : 'status-unread'; ?>">
                            <i class="fas <?php echo $pesan['status'] == 'Sudah Dibaca' ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                            <?php echo $pesan['status']; ?>
                        </span>
                    </div>
                </div>

                <div class="pesan-box">
                    <?php echo nl2br(htmlspecialchars($pesan['pesan'])); ?>
                </div>

                

                <div class="button-group">
                    <a href="balas_pesan.php?id=<?php echo $pesan['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Balas Pesan
                    </a>
                    <a href="hapus_pesan.php?id=<?php echo $pesan['id']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                        <i class="fas fa-trash"></i> Hapus Pesan
                    </a>
                    <a href="dashboard.php?tab=pesan" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i> Lihat Semua Pesan
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>