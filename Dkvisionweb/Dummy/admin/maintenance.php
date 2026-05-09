<?php
/**
 * maintenance.php
 * Letakkan di: Dummy/admin/maintenance.php
 */
session_start();
$isAdmin = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sedang Dalam Pemeliharaan</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    .admin-banner {
      position: fixed; top: 0; left: 0; right: 0; z-index: 999;
      background: #1a1d27;
      border-bottom: 2px solid #3b5bdb;
      padding: 10px 20px;
      display: flex; align-items: center;
      justify-content: space-between;
      gap: 12px; flex-wrap: wrap;
      font-family: 'DM Sans', sans-serif;
    }
    .admin-banner-text { font-size: .83rem; color: #a5b4fc; font-weight: 500; }
    .admin-banner-text strong { color: #fff; }
    .admin-banner-btns { display: flex; gap: 8px; }
    .abtn {
      padding: 6px 14px; border-radius: 6px;
      font-family: 'DM Sans', sans-serif; font-size: .8rem;
      font-weight: 600; cursor: pointer; text-decoration: none;
      border: none; display: inline-block;
    }
    .abtn-blue  { background: #3b5bdb; color: white; }
    .abtn-green { background: #2f9e44; color: white; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: linear-gradient(135deg, #1a1d27 0%, #2d3561 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      color: white;
    }
    .spacer { height: 52px; }
    .wrap { text-align: center; padding: 40px 24px; max-width: 480px; }
    .icon {
      font-size: 4rem; margin-bottom: 24px;
      display: inline-block;
      animation: wobble 3s ease-in-out infinite;
    }
    @keyframes wobble {
      0%,100%{transform:rotate(0)} 20%{transform:rotate(-10deg)}
      40%{transform:rotate(10deg)} 60%{transform:rotate(-6deg)} 80%{transform:rotate(6deg)}
    }
    h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: 12px; letter-spacing: -.5px; }
    p  { font-size: .95rem; color: rgba(255,255,255,.65); line-height: 1.7; margin-bottom: 8px; }
    .badge {
      display: inline-flex; align-items: center; gap: 8px;
      margin-top: 28px; padding: 10px 22px;
      background: rgba(59,91,219,.25);
      border: 1px solid rgba(59,91,219,.4);
      border-radius: 999px; font-size: .85rem; color: #a5b4fc; font-weight: 500;
    }
    .dot {
      width: 8px; height: 8px; border-radius: 50%; background: #a5b4fc;
      animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
      0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.8)}
    }
    .admin-link {
      display: block; margin-top: 48px;
      font-size: .72rem; color: rgba(255,255,255,.15);
      text-decoration: none; transition: color 200ms;
    }
    .admin-link:hover { color: rgba(255,255,255,.45); }
  </style>
</head>
<body>

  <?php if ($isAdmin): ?>
  <div class="admin-banner">
    <div class="admin-banner-text">
      Preview sebagai pengunjung — 
      Halo <strong><?php echo htmlspecialchars($_SESSION['admin']); ?></strong>,
      ini tampilan yang dilihat pengunjung saat maintenance aktif.
    </div>
    <div class="admin-banner-btns">
      <a href="dashboard.php" class="abtn abtn-blue">← Dashboard</a>
      <button class="abtn abtn-green" onclick="nonaktifkan()">✓ Nonaktifkan Maintenance</button> 
    </div>
  </div>
  <div class="spacer"></div>
  <script>
    function nonaktifkan() {
      fetch('toggle_maintenance.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'set', value: false })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          alert('Mode Maintenance dinonaktifkan! Website kembali normal.');
          window.location.href = 'dashboard.php';
        } else {
          alert('Gagal: ' + (data.error || 'Unknown error'));
        }
      });
    }
  </script>
  <?php endif; ?>

  <div class="wrap">
    <div class="icon"><i class="fas fa-tools"></i></div>
    <h1>Sedang Dalam Pemeliharaan</h1>
    <p>Website kami sedang dalam maintenance untuk memberikan pengalaman yang lebih baik.</p>
    <p>Mohon kunjungi kembali beberapa saat lagi.</p>
    <div class="badge"><div class="dot"></div>Under Maintenance</div>
    <?php if (!$isAdmin): ?>
    
    <?php endif; ?>
  </div>

</body>
</html>