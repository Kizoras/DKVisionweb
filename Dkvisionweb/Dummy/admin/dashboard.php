<?php
session_start();
if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard Admin - DKV</title>
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
      --sidebar-w: 230px;
      --header-h: 60px;
    }

    body {
      font-family: "DM Sans", system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text-primary);
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .admin-header {
      height: var(--header-h);
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      padding: 0 24px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: var(--shadow-sm);
    }

    .header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      max-width: 1400px;
      margin: 0 auto;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .brand-logo {
      width: 34px;
      height: 34px;
      background: var(--accent);
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      flex-shrink: 0;
      color: white;
    }

    .brand-name {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text-primary);
      letter-spacing: -0.2px;
    }

    .brand-sub {
      font-size: 0.72rem;
      color: var(--text-muted);
      font-weight: 400;
    }

    .dynamic-content .loading {
    text-align: center;
    padding: 40px;
    color: #999;
    font-style: italic;
}

.dynamic-content p {
    line-height: 1.8;
    margin-bottom: 16px;
}

    .header-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .user-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 5px 10px 5px 5px;
      border-radius: 40px;
      border: 1px solid var(--border);
      background: var(--surface);
    }

    .user-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--accent-light);
      color: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .user-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .logout-btn {
      padding: 7px 14px;
      background: var(--surface);
      border: 1px solid var(--border);
      color: var(--text-secondary);
      border-radius: var(--radius-sm);
      font-size: 0.82rem;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      transition: all 150ms ease;
      font-family: inherit;
    }

    .logout-btn:hover {
      border-color: var(--danger);
      color: var(--danger);
      background: var(--danger-light);
    }

    /* Checkbox & Bulk Delete Styles */
.bulk-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding: 12px 16px;
    background: var(--surface-2);
    border-radius: var(--radius);
    display: none;
}

.bulk-actions.show {
    display: flex;
}

.bulk-actions .selected-count {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.delete-selected-btn {
    padding: 6px 14px;
    background: var(--danger);
    border: none;
    color: white;
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 140ms ease;
}

.delete-selected-btn:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.checkbox-col {
    width: 40px;
    text-align: center;
}

.checkbox-col input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--accent);
}

.select-all-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--accent);
}

/* Pagination Styles */
.pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 20px;
    padding: 12px 16px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.pagination button {
    padding: 6px 12px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 140ms ease;
}

.pagination button:hover:not(:disabled) {
    background: var(--accent-light);
    border-color: var(--accent);
    color: var(--accent);
}

.pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination .page-info {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin: 0 8px;
}

.pagination .page-numbers {
    display: flex;
    gap: 4px;
}

.pagination .page-number {
    padding: 6px 10px;
    min-width: 34px;
    text-align: center;
}

.pagination .page-number.active {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.items-per-page {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 8px;
}

.items-per-page select {
    padding: 5px 8px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    font-family: inherit;
    font-size: 0.8rem;
    cursor: pointer;
}

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 7px 14px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      color: var(--text-secondary);
      text-decoration: none;
      font-size: 0.82rem;
      font-weight: 500;
      transition: all 150ms ease;
    }

    .back-link:hover {
      border-color: var(--accent);
      color: var(--accent);
      background: var(--accent-light);
    }

    .layout {
      display: flex;
      flex: 1;
      max-width: 1400px;
      margin: 0 auto;
      width: 100%;
    }

    .sidebar {
      width: var(--sidebar-w);
      flex-shrink: 0;
      padding: 20px 12px;
      position: sticky;
      top: var(--header-h);
      height: calc(100vh - var(--header-h));
      overflow-y: auto;
    }

    .nav-label {
      font-size: 0.68rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text-muted);
      padding: 0 10px;
      margin-bottom: 6px;
      margin-top: 16px;
    }

    .nav-label:first-child {
      margin-top: 0;
    }

    .tab-btn {
      display: flex;
      align-items: center;
      gap: 9px;
      width: 100%;
      padding: 8px 10px;
      border: none;
      background: transparent;
      color: var(--text-secondary);
      font-family: inherit;
      font-size: 0.865rem;
      font-weight: 500;
      border-radius: var(--radius-sm);
      cursor: pointer;
      text-align: left;
      text-decoration: none;
      transition: all 140ms ease;
      margin-bottom: 2px;
    }

    .tab-btn:hover {
      background: var(--surface-2);
      color: var(--text-primary);
    }

    .tab-btn.active {
      background: var(--accent-light);
      color: var(--accent);
      font-weight: 600;
    }

    .tab-btn .nav-icon {
      width: 18px;
      height: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      flex-shrink: 0;
      opacity: 0.75;
    }

    .tab-btn.active .nav-icon {
      opacity: 1;
    }

    .nav-divider {
      height: 1px;
      background: var(--border-light);
      margin: 12px 0;
    }

    .main {
      flex: 1;
      padding: 28px 28px 28px 20px;
      min-width: 0;
    }

    .tab-content {
      display: none;
      animation: fadeUp 220ms ease;
    }

    .tab-content.active {
      display: block;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 22px;
      gap: 16px;
      flex-wrap: wrap;
    }

    .page-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--text-primary);
      letter-spacing: -0.3px;
    }

    .page-sub {
      font-size: 0.82rem;
      color: var(--text-muted);
      margin-top: 2px;
      font-weight: 400;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 14px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 18px 20px;
      box-shadow: var(--shadow-sm);
      transition: box-shadow 150ms ease;
    }

    .stat-card:hover {
      box-shadow: var(--shadow-md);
    }

    .stat-number {
      font-size: 1.9rem;
      font-weight: 700;
      color: var(--accent);
      letter-spacing: -1px;
      font-family: "DM Mono", monospace;
      line-height: 1;
      margin-bottom: 6px;
    }

    .stat-label {
      font-size: 0.8rem;
      color: var(--text-secondary);
      font-weight: 500;
    }

    .table-container {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow-x: auto;
      box-shadow: var(--shadow-sm);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    

    thead {
      background: var(--bg);
    }

    th {
      padding: 11px 16px;
      text-align: left;
      font-size: 0.76rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }

    td {
      padding: 13px 16px;
      border-bottom: 1px solid var(--border-light);
      font-size: 0.865rem;
      color: var(--text-primary);
      vertical-align: middle;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tbody tr:hover {
      background: var(--bg);
    }

    .badge {
      display: inline-flex;
      align-items: center;
      padding: 3px 9px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      white-space: nowrap;
    }

    .badge-success {
      background: var(--success-light);
      color: var(--success);
    }

    .badge-danger {
      background: var(--danger-light);
      color: var(--danger);
    }

    .badge-warning {
      background: var(--warning-light);
      color: var(--warning);
    }

    .badge-neutral {
      background: var(--surface-2);
      color: var(--text-secondary);
    }

    .add-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: var(--accent);
      border: none;
      color: #fff;
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 0.845rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 150ms ease, box-shadow 150ms ease;
      box-shadow: 0 1px 4px rgba(59,91,219,0.25);
    }

    .add-btn:hover {
      background: var(--accent-hover);
      box-shadow: 0 3px 10px rgba(59,91,219,0.3);
    }

    .actions {
      display: flex;
      gap: 6px;
    }

    .btn-edit, .btn-delete, .btn-balas, .btn-hapus {
      padding: 5px 12px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 0.795rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 140ms ease;
      white-space: nowrap;
      text-decoration: none;
      display: inline-block;
    }

    .btn-edit, .btn-balas {
      background: var(--surface);
      color: var(--accent);
      border-color: var(--border);
    }

    .btn-edit:hover, .btn-balas:hover {
      background: var(--accent-light);
      border-color: var(--accent);
    }

    .btn-delete, .btn-hapus {
      background: var(--surface);
      color: var(--danger);
      border-color: var(--border);
    }

    .btn-delete:hover, .btn-hapus:hover {
      background: var(--danger-light);
      border-color: var(--danger);
    }

    .btn-balas {
    padding: 5px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.795rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 140ms ease;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--surface);
    color: var(--accent);
    border-color: var(--border);
}

.btn-balas:hover {
    background: var(--accent-light);
    border-color: var(--accent);
}

    .guru-foto-cell {
      width: 36px;
      height: 36px;
      border-radius: var(--radius-sm);
      background: var(--accent-light);
      color: var(--accent);
      font-size: 0.7rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }

    .guru-foto-cell img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .empty-state {
      text-align: center;
      padding: 56px 20px;
      color: var(--text-muted);
    }

    .empty-icon {
      font-size: 2.2rem;
      margin-bottom: 12px;
      opacity: 0.4;
    }

    .empty-state p {
      font-size: 0.875rem;
    }

    .info-table td:first-child {
      font-weight: 600;
      color: var(--text-secondary);
      width: 35%;
      font-size: 0.82rem;
    }

    .guru-modal {
      position: fixed;
      inset: 0;
      background: rgba(15, 17, 26, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
      animation: fadeIn 200ms ease;
      backdrop-filter: blur(2px);
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }

    .guru-modal-content {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 28px;
      max-width: 480px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: var(--shadow-lg);
      animation: slideUp 220ms ease;
    }

    @keyframes slideUp {
      from { transform: translateY(16px); opacity: 0; }
      to   { transform: translateY(0);    opacity: 1; }
    }

    .guru-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 22px;
    }

    .guru-modal-header h3 {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--text-primary);
    }

    .close-modal {
      background: none;
      border: none;
      color: var(--text-muted);
      font-size: 1.3rem;
      cursor: pointer;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--radius-sm);
      transition: all 140ms ease;
    }

    .close-modal:hover {
      background: var(--surface-2);
      color: var(--text-primary);
    }

    .guru-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group label {
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .form-group small {
      font-size: 0.76rem;
      color: var(--text-muted);
    }

    /* File Upload Styles */
.file-upload-wrapper {
    position: relative;
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.upload-btn {
    padding: 9px 18px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--accent);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.865rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 140ms ease;
}

.upload-btn:hover {
    background: var(--accent-light);
    border-color: var(--accent);
}

    .required-mark {
      color: var(--danger);
      margin-left: 2px;
    }

    .guru-form input,
    .guru-form select,
    .guru-form textarea {
      padding: 9px 12px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: var(--surface);
      color: var(--text-primary);
      font-family: inherit;
      font-size: 0.875rem;
      outline: none;
      transition: border-color 140ms ease, box-shadow 140ms ease;
    }

    .guru-form input::placeholder,
    .guru-form textarea::placeholder {
      color: var(--text-muted);
    }

    .guru-form input:focus,
    .guru-form select:focus,
    .guru-form textarea:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(59, 91, 219, 0.1);
    }

    .guru-form select {
      color: var(--text-primary);
      cursor: pointer;
    }

    .guru-form textarea {
      resize: vertical;
      min-height: 90px;
    }

    .foto-preview {
      height: 90px;
      border-radius: var(--radius-sm);
      border: 1px dashed var(--border);
      background: var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      color: var(--text-muted);
      font-size: 0.8rem;
      margin-top: 8px;
    }

    .foto-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .form-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      padding-top: 6px;
      border-top: 1px solid var(--border-light);
      margin-top: 4px;
    }

    .btn-cancel {
      padding: 9px 18px;
      background: var(--surface);
      border: 1px solid var(--border);
      color: var(--text-secondary);
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 0.865rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 140ms ease;
    }

    .btn-cancel:hover {
      background: var(--surface-2);
      color: var(--text-primary);
    }

    .btn-submit {
      padding: 9px 20px;
      background: var(--accent);
      border: none;
      color: #fff;
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 0.865rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 150ms ease;
      box-shadow: 0 1px 4px rgba(59,91,219,0.25);
    }

    .btn-submit:hover {
      background: var(--accent-hover);
    }

    @media (max-width: 860px) {
      :root { --sidebar-w: 200px; }
    }

    @media (max-width: 680px) {
      .layout { flex-direction: column; }

      .sidebar {
        width: 100%;
        height: auto;
        position: static;
        padding: 12px;
        display: flex;
        gap: 6px;
        overflow-x: auto;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
      }

      .nav-label, .nav-divider { display: none; }

      .tab-btn {
        white-space: nowrap;
        flex-shrink: 0;
        margin-bottom: 0;
      }

      .main { padding: 20px 16px; }

      .guru-modal-content { padding: 22px; }

      .form-actions { flex-direction: column; }

      .btn-cancel, .btn-submit { width: 100%; text-align: center; }

      .header-right .back-link { display: none; }
    }
  </style>
</head>
<body>
  <header class="admin-header">
    <div class="header-inner">
      <div class="brand">
        <div class="brand-logo"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
        <div>
          <div class="brand-name">DKV Admin</div>
          <div class="brand-sub">SMKN 1 Cibinong</div>
        </div>
      </div>
      <div class="header-right">
        <div class="user-badge">
          <div class="user-avatar">A</div>
          <span class="user-label"><?php echo $_SESSION['admin']; ?></span>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
      </div>
    </div>
  </header>

  <div class="layout">
    <aside class="sidebar">
  <div class="nav-label">Main</div>
  <button class="tab-btn tab-nav" data-tab="overview">
    <span class="nav-icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
    <span>Overview</span>
  </button>
  <button class="tab-btn tab-nav" data-tab="guru">
    <span class="nav-icon"><i class="fa fa-users" aria-hidden="true"></i></span>
    <span>Kelola Guru</span>
  </button>
  <button class="tab-btn tab-nav" data-tab="berita">
    <span class="nav-icon"><i class="fas fa-edit"></i></span>
    <span>Kelola Berita</span>
  </button>
  <button class="tab-btn tab-nav" data-tab="portfolio">
    <span class="nav-icon"><i class="fa fa-images" aria-hidden="true"></i></span>
    <span>Kelola Portofolio</span>
  </button>
  
  
  <div class="nav-divider"></div>
  <div class="nav-label">Lainnya</div>
  <button class="tab-btn tab-nav" data-tab="pesan">
    <span class="nav-icon"><i class="fa fa-comments" aria-hidden="true"></i></span>
    <span>Pesan Masuk</span>
  </button>
  <button class="tab-btn tab-nav" data-tab="settings">
    <span class="nav-icon"><i class="fa fa-cog" aria-hidden="true"></i></span>
    <span>Pengaturan</span>
  </button>

  <!-- Kelola Informasi -->
<button class="tab-btn tab-nav" data-tab="manage-content">
    <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
    <span>Kelola Konten</span>
</button>
  
  <!-- Link tidak perlu class tab-nav, cukup tab-btn untuk styling -->
  <a href="ganti_password.php" class="tab-btn">
    <span class="nav-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
    <span>Ganti Password</span>
  </a>
  
  <div class="nav-divider"></div>
  <a href="../user/index.html" class="tab-btn" style="margin-top: 8px;">
    <span class="nav-icon">←</span>
    <span>Kembali ke Website</span>
  </a>
</aside>

    <main class="main">
      <div id="overview" class="tab-content active">
        <div class="page-header">
          <div>
            <div class="page-title">Overview Dashboard</div>
            <div class="page-sub">Ringkasan data dan informasi sistem</div>
          </div>
        </div>
        
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number" id="totalGuruStat">0</div>
            <div class="stat-label">Total Guru</div>
          </div>
          
          <div class="stat-card">
            <div class="stat-number" id="totalBeritaStat">0</div>
            <div class="stat-label">Berita Terbaru</div>
          </div>
          <div class="stat-card">
            <div class="stat-number" id="totalPortfolioStat">0</div>
            <div class="stat-label">Portofolio</div>
          </div>
          
        </div>

        <div class="page-header" style="margin-bottom: 16px;">
          <div class="page-title">Informasi Sistem</div>
        </div>

        <div class="table-container">
          <table class="info-table">
            <tbody>
              <tr>
                <td style="font-weight: 600; width: 30%;">Nama Sistem</td>
                <td>Portal Admin Jurusan DKV</td>
              </tr>
              <tr>
                <td style="font-weight: 600;">Status</td>
                <td><span class="badge badge-success">✓ Aktif</span></td>
              </tr>
              <tr>
                <td style="font-weight: 600;">Versi</td>
                <td>1.0.0</td>
              </tr>
              <tr>
                <td style="font-weight: 600;">Last Update</td>
                <td><?php echo date('d/m/Y H:i'); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div id="manage-content" class="tab-content">
    <div class="page-header">
        <div>
            <div class="page-title">Kelola Konten Halaman About</div>
            <div class="page-sub">Edit konten visi misi dan struktur organisasi yang akan tampil di halaman user</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-eye"></i> Visi & Misi / Tentang DKV</div>
        </div>
        
        <form id="visiMisiForm" class="guru-form" style="padding: 20px;">
            <div class="form-group">
                <label for="visiMisiText">Konten Tentang DKV / Visi & Misi</label>
                <textarea id="visiMisiText" rows="12" placeholder="Masukkan konten tentang DKV, visi misi, dan informasi lainnya..."></textarea>
                <small>Konten ini akan tampil di halaman About (about.html) pada bagian Tentang DKV</small>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Visi & Misi</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 24px;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-sitemap"></i> Struktur Organisasi</div>
        </div>
        
        <form id="strukturForm" class="guru-form" style="padding: 20px;">
            <div class="form-group">
                <label for="strukturText">Konten Struktur Organisasi</label>
                <textarea id="strukturText" rows="8" placeholder="Masukkan konten struktur organisasi..."></textarea>
                <small>Konten ini akan tampil di halaman About (about.html) pada bagian Struktur Organisasi</small>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Struktur Organisasi</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 24px;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-palette"></i> Preview Konten</div>
        </div>
        <div style="padding: 20px;">
            <div style="margin-bottom: 24px;">
                <h4 style="margin-bottom: 12px; color: var(--accent);">Preview Visi & Misi:</h4>
                <div id="previewVisiMisi" style="padding: 16px; background: var(--surface-2); border-radius: var(--radius); line-height: 1.6;"></div>
            </div>
            <div>
                <h4 style="margin-bottom: 12px; color: var(--accent);">Preview Struktur Organisasi:</h4>
                <div id="previewStruktur" style="padding: 16px; background: var(--surface-2); border-radius: var(--radius); line-height: 1.6;"></div>
            </div>
        </div>
    </div>
</div>

      <div id="pesan" class="tab-content">
        <div class="page-header">
            <div>
                <div class="page-title">Pesan Masuk</div>
                <div class="page-sub">Daftar pesan dari pengunjung website</div>
            </div>
        </div>
        
        <div class="bulk-actions" id="pesanBulkActions">
            <span class="selected-count"><span id="pesanSelectedCount">0</span> item dipilih</span>
            <button class="delete-selected-btn" onclick="deleteSelectedPesan()">
                <i class="fas fa-trash"></i> Hapus yang dipilih
            </button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-col"><input type="checkbox" class="select-all-checkbox" id="selectAllPesan" onchange="selectAllPesan(this.checked)"></th>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pesanTableBody">
                    <!-- Data akan dimuat via JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="pagination" id="pesanPagination">
            <button onclick="changePesanPage('prev')" id="pesanPrevBtn"><i class="fas fa-chevron-left"></i> Prev</button>
            <div class="page-numbers" id="pesanPageNumbers"></div>
            <button onclick="changePesanPage('next')" id="pesanNextBtn">Next <i class="fas fa-chevron-right"></i></button>
            <div class="items-per-page">
                <span>Show:</span>
                <select id="pesanLimit" onchange="changePesanLimit()">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

      <div id="guru" class="tab-content">
    <div class="page-header">
        <div>
            <div class="page-title">Daftar Guru Pengajar</div>
            <div class="page-sub">Kelola data guru dan pengajar</div>
        </div>
        <button class="add-btn" onclick="openGuruModal('add')">+ Tambah Guru</button>
    </div>
    
    <div class="bulk-actions" id="guruBulkActions">
        <span class="selected-count"><span id="guruSelectedCount">0</span> item dipilih</span>
        <button class="delete-selected-btn" onclick="deleteSelectedGuru()">
            <i class="fas fa-trash"></i> Hapus yang dipilih
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-col"><input type="checkbox" class="select-all-checkbox" id="selectAllGuru" onchange="selectAllGuru(this.checked)"></th>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="guruTableBody"></tbody>
        </table>
    </div>
    
    <div class="pagination" id="guruPagination">
        <button onclick="changeGuruPage('prev')" id="guruPrevBtn"><i class="fas fa-chevron-left"></i> Prev</button>
        <div class="page-numbers" id="guruPageNumbers"></div>
        <button onclick="changeGuruPage('next')" id="guruNextBtn">Next <i class="fas fa-chevron-right"></i></button>
        <div class="items-per-page">
            <span>Show:</span>
            <select id="guruLimit" onchange="changeGuruLimit()">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
</div>

<div id="berita" class="tab-content">
    <div class="page-header">
        <div>
            <div class="page-title">Daftar Berita Terbaru</div>
            <div class="page-sub">Kelola berita dan informasi terbaru</div>
        </div>
        <button class="add-btn" onclick="openBeritaModal('add')">+ Tambah Berita</button>
    </div>
    
    <div class="bulk-actions" id="beritaBulkActions">
        <span class="selected-count"><span id="beritaSelectedCount">0</span> item dipilih</span>
        <button class="delete-selected-btn" onclick="deleteSelectedBerita()">
            <i class="fas fa-trash"></i> Hapus yang dipilih
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-col"><input type="checkbox" class="select-all-checkbox" id="selectAllBerita" onchange="selectAllBerita(this.checked)"></th>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>Tanggal</th>
                    <th>Views</th>
                    <th>Gambar</th>
                    <th>Video</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="beritaTableBody"></tbody>
        </table>
    </div>
    
    <div class="pagination" id="beritaPagination">
        <button onclick="changeBeritaPage('prev')" id="beritaPrevBtn"><i class="fas fa-chevron-left"></i> Prev</button>
        <div class="page-numbers" id="beritaPageNumbers"></div>
        <button onclick="changeBeritaPage('next')" id="beritaNextBtn">Next <i class="fas fa-chevron-right"></i></button>
        <div class="items-per-page">
            <span>Show:</span>
            <select id="beritaLimit" onchange="changeBeritaLimit()">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
</div>

<div id="portfolio" class="tab-content">
    <div class="page-header">
        <div>
            <div class="page-title">Daftar Portofolio</div>
            <div class="page-sub">Kelola portofolio karya siswa</div>
        </div>
        <button class="add-btn" onclick="openPortfolioModal('add')">+ Tambah Portofolio</button>
    </div>
    
    <div class="bulk-actions" id="portfolioBulkActions">
        <span class="selected-count"><span id="portfolioSelectedCount">0</span> item dipilih</span>
        <button class="delete-selected-btn" onclick="deleteSelectedPortfolio()">
            <i class="fas fa-trash"></i> Hapus yang dipilih
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-col"><input type="checkbox" class="select-all-checkbox" id="selectAllPortfolio" onchange="selectAllPortfolio(this.checked)"></th>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Video</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="portfolioTableBody"></tbody>
        </table>
    </div>
    
    <div class="pagination" id="portfolioPagination">
        <button onclick="changePortfolioPage('prev')" id="portfolioPrevBtn"><i class="fas fa-chevron-left"></i> Prev</button>
        <div class="page-numbers" id="portfolioPageNumbers"></div>
        <button onclick="changePortfolioPage('next')" id="portfolioNextBtn">Next <i class="fas fa-chevron-right"></i></button>
        <div class="items-per-page">
            <span>Show:</span>
            <select id="portfolioLimit" onchange="changePortfolioLimit()">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
</div>

<div id="produk" class="tab-content">
    <div class="page-header">
        <div>
            <div class="page-title">Daftar Produk</div>
            <div class="page-sub">Kelola produk unggulan</div>
        </div>
        <button class="add-btn" onclick="openProdukModal('add')">+ Tambah Produk</button>
    </div>
    
    <div class="bulk-actions" id="produkBulkActions">
        <span class="selected-count"><span id="produkSelectedCount">0</span> item dipilih</span>
        <button class="delete-selected-btn" onclick="deleteSelectedProduk()">
            <i class="fas fa-trash"></i> Hapus yang dipilih
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-col"><input type="checkbox" class="select-all-checkbox" id="selectAllProduk" onchange="selectAllProduk(this.checked)"></th>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Video</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="produkTableBody"></tbody>
        </table>
    </div>
    
    <div class="pagination" id="produkPagination">
        <button onclick="changeProdukPage('prev')" id="produkPrevBtn"><i class="fas fa-chevron-left"></i> Prev</button>
        <div class="page-numbers" id="produkPageNumbers"></div>
        <button onclick="changeProdukPage('next')" id="produkNextBtn">Next <i class="fas fa-chevron-right"></i></button>
        <div class="items-per-page">
            <span>Show:</span>
            <select id="produkLimit" onchange="changeProdukLimit()">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
</div>

      <div id="settings" class="tab-content">
  <div class="page-header">
    <div>
      <div class="page-title">Pengaturan Sistem</div>
      <div class="page-sub">Konfigurasi tampilan website dan keamanan</div>
    </div>
  </div>

  <div class="table-container" style="margin-bottom: 24px;">
    <table>
      <thead>
        <tr>
          <th>Pengaturan</th>
          <th>Nilai Saat Ini</th>
          <th style="width:110px;">Aksi</th>
        </tr>
      </thead>
      <tbody>

        <!-- ═══ BARIS 1: Nama Website ═══ -->
        <tr>
          <td>
            <strong>Nama Website</strong>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:3px;">
              Teks logo di header — "Jurusan DKV" dan "SMKN 1 Cibinong"
            </div>
          </td>
          <td><span id="display-nama-website" style="font-weight:500;">Jurusan DKV · SMKN 1 Cibinong</span></td>
          <td>
            <button class="btn-edit" onclick="openSettingEdit('nama-website')">Edit</button>
          </td>
        </tr>
        <!-- Form inline edit Nama Website -->
        <tr id="form-nama-website" style="display:none; background:var(--accent-light);">
          <td colspan="3" style="padding:18px 20px;">
            <div style="display:flex;flex-direction:column;gap:14px;max-width:580px;">

              <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <div style="flex:1;min-width:180px;">
                  <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:5px;">
                    Nama Utama <span style="color:var(--danger)">*</span>
                  </label>
                  <input type="text" id="input-nama-utama"
                    placeholder="contoh: Jurusan DKV"
                    style="width:100%;padding:8px 12px;border:1px solid var(--border);
                           border-radius:var(--radius-sm);font-family:inherit;font-size:.875rem;outline:none;">
                </div>
                <div style="flex:1;min-width:180px;">
                  <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:5px;">
                    Sub-nama / Tagline <span style="color:var(--danger)">*</span>
                  </label>
                  <input type="text" id="input-nama-tagline"
                    placeholder="contoh: SMKN 1 Cibinong"
                    style="width:100%;padding:8px 12px;border:1px solid var(--border);
                           border-radius:var(--radius-sm);font-family:inherit;font-size:.875rem;outline:none;">
                </div>
              </div>

              <!-- Live Preview -->
              <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;
                          background:white;border-radius:var(--radius-sm);border:1px solid var(--border);">
                <div style="width:32px;height:32px;background:var(--accent);border-radius:6px;
                             display:flex;align-items:center;justify-content:center;
                             color:white;font-size:0.65rem;font-weight:700;flex-shrink:0;">DKV</div>
                <div>
                  <div id="preview-nama-utama"
                       style="font-size:.9rem;font-weight:700;color:var(--text-primary);line-height:1.2;">
                    Jurusan DKV
                  </div>
                  <div id="preview-nama-tagline"
                       style="font-size:.72rem;color:var(--text-muted);">
                    SMKN 1 Cibinong
                  </div>
                </div>
                <span style="margin-left:auto;font-size:.74rem;color:var(--text-muted);font-style:italic;">
                  Preview logo
                </span>
              </div>

              <div style="font-size:.76rem;color:var(--text-muted);">
                <i class="fas fa-info-circle"></i>
                Perubahan otomatis muncul di semua halaman user saat di-refresh.
              </div>

              <div style="display:flex;gap:8px;">
                <button class="btn-submit" onclick="saveNamaWebsite()" style="padding:8px 18px;">
                  <i class="fas fa-save"></i> Simpan
                </button>
                <button class="btn-cancel" onclick="closeSettingEdit('nama-website')" style="padding:8px 14px;">
                  Batal
                </button>
              </div>
            </div>
          </td>
        </tr>

        <!-- ═══ BARIS 2: Email Admin ═══ -->
        <tr>
          <td>
            <strong>Email Admin</strong>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:3px;">
              Tampil di halaman Contact dan footer website
            </div>
          </td>
          <td><span id="display-email-admin" style="font-weight:500;">admin@smkn1cibinong.sch.id</span></td>
          <td>
            <button class="btn-edit" onclick="openSettingEdit('email-admin')">Edit</button>
          </td>
        </tr>
        <!-- Form inline edit Email -->
        <tr id="form-email-admin" style="display:none; background:var(--accent-light);">
          <td colspan="3" style="padding:18px 20px;">
            <div style="display:flex;flex-direction:column;gap:10px;max-width:460px;">
              <label style="font-size:.8rem;font-weight:600;">
                Email Baru <span style="color:var(--danger)">*</span>
              </label>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <input type="email" id="input-email-admin"
                  placeholder="contoh: admindkv@gmail.com"
                  style="flex:1;min-width:220px;padding:8px 12px;border:1px solid var(--border);
                         border-radius:var(--radius-sm);font-family:inherit;font-size:.875rem;outline:none;"
                  onkeydown="if(event.key==='Enter') saveEmailAdmin()">
                <button class="btn-submit" onclick="saveEmailAdmin()" style="padding:8px 18px;white-space:nowrap;">
                  <i class="fas fa-save"></i> Simpan
                </button>
                <button class="btn-cancel" onclick="closeSettingEdit('email-admin')" style="padding:8px 14px;">
                  Batal
                </button>
              </div>
              <div style="font-size:.76rem;color:var(--text-muted);">
                <i class="fas fa-info-circle"></i>
                Email ini menggantikan semua link mailto: di halaman user.
              </div>
            </div>
          </td>
        </tr>

        <!-- ═══ BARIS 3: Mode Maintenance ═══ -->
        <tr>
          <td>
            <strong>Mode Maintenance</strong>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:3px;">
              Tutup website sementara untuk pengunjung
            </div>
          </td>
          <td><span id="badge-maintenance" class="badge badge-success">✓ Nonaktif</span></td>
          <td>
            <button class="btn-edit" id="btn-maintenance-toggle" onclick="openSettingEdit('maintenance')">
              Aktifkan
            </button>
          </td>
        </tr>
        <!-- Konfirmasi Maintenance -->
        <tr id="form-maintenance" style="display:none; background:#fff9db;">
          <td colspan="3" style="padding:18px 20px;">
            <div style="display:flex;gap:14px;align-items:flex-start;flex-wrap:wrap;max-width:560px;">
              <div style="flex:1;">
                <strong id="maintenance-confirm-title"
                        style="font-size:.9rem;color:var(--warning);display:block;margin-bottom:6px;">
                  Aktifkan Mode Maintenance?
                </strong>
                <p id="maintenance-confirm-desc"
                   style="font-size:.8rem;color:var(--text-secondary);line-height:1.5;">
                  Pengunjung tidak bisa membuka website dan akan diarahkan ke halaman pemeliharaan.
                  Admin yang sudah login tetap bisa akses semua halaman.
                </p>
              </div>
              <div style="display:flex;gap:8px;padding-top:2px;">
                <button id="btn-confirm-maintenance"
                  class="btn-submit"
                  onclick="saveMaintenance()"
                  style="padding:8px 16px;background:var(--warning);box-shadow:none;white-space:nowrap;">
                  <i class="fas fa-tools"></i> Ya, Aktifkan
                </button>
                <button class="btn-cancel" onclick="closeSettingEdit('maintenance')" style="padding:8px 14px;">
                  Batal
                </button>
              </div>
            </div>
          </td>
        </tr>

      </tbody>
    </table>
  </div>

  <!-- Keamanan Akun -->
  <div class="page-header" style="margin-top:4px;">
    <div class="page-title">Keamanan Akun</div>
  </div>
  <div class="table-container">
    <table class="info-table">
      <tbody>
        <tr>
          <td style="font-weight:600;width:30%;">Username</td>
          <td><?php echo $_SESSION['admin']; ?></td>
        </tr>
        <tr>
          <td style="font-weight:600;">Password</td>
          <td>••••••••</td>
        </tr>
        <tr>
          <td style="font-weight:600;">Last Login</td>
          <td><?php echo date('d/m/Y H:i'); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

  <!-- MODALS -->
  <div id="guruModal" class="guru-modal" style="display: none;">
    <div class="guru-modal-content">
      <div class="guru-modal-header">
        <h3 id="guruModalTitle">Tambah Guru</h3>
        <button class="close-modal" onclick="closeGuruModal()">&times;</button>
      </div>

      <form id="guruForm" class="guru-form">
        <div class="form-group">
          <label for="guruNama">Nama Guru <span class="required-mark">*</span></label>
          <input type="text" id="guruNama" placeholder="Misal: Drs. Ahmad Wijaya" required />
        </div>

        <div class="form-group">
    <label for="guruFoto">Foto Guru</label>
    <div class="file-upload-wrapper">
        <button type="button" class="upload-btn" onclick="document.getElementById('guruFotoFile').click()">Pilih File</button>
        <input type="file" id="guruFotoFile" accept="image/*" style="display: none;">
        <input type="hidden" id="guruFoto" name="guruFoto">
        <small style="color: var(--text-muted); display: block; margin-top: 5px;">Format: JPG, PNG, GIF, WEBP</small>
    </div>
    <div id="guruFotoPreview" class="foto-preview" style="margin-top: 12px;"></div>
</div>

        <div class="form-group">
          <label for="guruMapel">Mata Pelajaran <span class="required-mark">*</span></label>
          <input type="text" id="guruMapel" placeholder="Misal: Desain Grafis, Typography" required />
        </div>

        <div class="form-group">
          <label for="guruStatus">Status <span class="required-mark">*</span></label>
          <select id="guruStatus" required>
            <option value="">-- Pilih Status --</option>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
            <option value="Cuti">Cuti</option>
            <option value="Pensiun">Pensiun</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" onclick="closeGuruModal()">Batal</button>
          <button type="submit" class="btn-submit">Simpan Data Guru</button>
        </div>
      </form>
    </div>
  </div>

  <div id="beritaModal" class="guru-modal" style="display: none;">
    <div class="guru-modal-content">
      <div class="guru-modal-header">
        <h3 id="beritaModalTitle">Tambah Berita</h3>
        <button class="close-modal" onclick="closeBeritaModal()">&times;</button>
      </div>

      <form id="beritaForm" class="guru-form">
        <div class="form-group">
          <label for="beritaTitle">Judul Berita <span class="required-mark">*</span></label>
          <input type="text" id="beritaTitle" placeholder="Judul berita" required />
        </div>

        <div class="form-group">
          <label for="beritaContent">Konten Berita <span class="required-mark">*</span></label>
          <textarea id="beritaContent" rows="4" placeholder="Isi berita" required></textarea>
        </div>

        <div class="form-group">
    <label for="beritaImage">Gambar Berita</label>
    <div class="file-upload-wrapper">
        <button type="button" class="upload-btn" onclick="document.getElementById('beritaImageFile').click()">Pilih File</button>
        <input type="file" id="beritaImageFile" accept="image/*" style="display: none;">
        <input type="hidden" id="beritaImage" name="beritaImage">
        <small style="color: var(--text-muted); display: block; margin-top: 5px;">Format: JPG, PNG, GIF, WEBP</small>
    </div>
    <div id="beritaImagePreview" class="foto-preview" style="margin-top: 12px;"></div>
</div>

        <div class="form-group">
          <label for="beritaVideo">URL Video (YouTube)</label>
          <input type="url" id="beritaVideo" placeholder="https://www.youtube.com/watch?v=..." />
          <small>Opsional</small>
        </div>

        <div class="form-group">
          <label for="beritaDate">Tanggal Berita</label>
          <input type="datetime-local" id="beritaDate" />
          <small style="color: var(--text-muted);">Akan otomatis diisi dengan tanggal sekarang jika kosong</small>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" onclick="closeBeritaModal()">Batal</button>
          <button type="submit" class="btn-submit">Simpan Berita</button>
        </div>
      </form>
    </div>
  </div>

  <div id="portfolioModal" class="guru-modal" style="display: none;">
    <div class="guru-modal-content">
      <div class="guru-modal-header">
        <h3 id="portfolioModalTitle">Tambah Portofolio</h3>
        <button class="close-modal" onclick="closePortfolioModal()">&times;</button>
      </div>

      <form id="portfolioForm" class="guru-form">
        <div class="form-group">
          <label for="portfolioTitle">Judul Portofolio <span class="required-mark">*</span></label>
          <input type="text" id="portfolioTitle" placeholder="Judul karya" required />
          
          <div class="form-group">
    <label for="portfolioCategory">Kategori <span class="required-mark">*</span></label>
    <select id="portfolioCategory" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="Desain Grafis">Desain Grafis</option>
        <option value="Fotografi">Fotografi</option>
    </select>
        </div>
        </div>

        <div class="form-group">
          <label for="portfolioDesc">Deskripsi <span class="required-mark">*</span></label>
          <textarea id="portfolioDesc" rows="4" placeholder="Deskripsi karya" required></textarea>
        </div>

        <div class="form-group">
    <label for="portfolioImage">Gambar Portofolio</label>
    <div class="file-upload-wrapper">
        <button type="button" class="upload-btn" onclick="document.getElementById('portfolioImageFile').click()">Pilih File</button>
        <input type="file" id="portfolioImageFile" accept="image/*" style="display: none;">
        <input type="hidden" id="portfolioImage" name="portfolioImage">
        <small style="color: var(--text-muted); display: block; margin-top: 5px;">Format: JPG, PNG, GIF, WEBP</small>
    </div>
    <div id="portfolioImagePreview" class="foto-preview" style="margin-top: 12px;"></div>
</div>

        <div class="form-group">
          <label for="portfolioVideo">URL Video (YouTube)</label>
          <input type="url" id="portfolioVideo" placeholder="https://www.youtube.com/watch?v=..." />
          <small>Opsional</small>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" onclick="closePortfolioModal()">Batal</button>
          <button type="submit" class="btn-submit">Simpan Portofolio</button>
        </div>
      </form>
    </div>
  </div>

  <div id="produkModal" class="guru-modal" style="display: none;">
    <div class="guru-modal-content">
      <div class="guru-modal-header">
        <h3 id="produkModalTitle">Tambah Produk</h3>
        <button class="close-modal" onclick="closeProdukModal()">&times;</button>
      </div>

      <form id="produkForm" class="guru-form">
        <div class="form-group">
          <label for="produkName">Nama Produk <span class="required-mark">*</span></label>
          <input type="text" id="produkName" placeholder="Nama produk" required />
        </div>

        <div class="form-group">
          <label for="produkDesc">Deskripsi <span class="required-mark">*</span></label>
          <textarea id="produkDesc" rows="4" placeholder="Deskripsi produk" required></textarea>
        </div>

        <div class="form-group">
    <label for="produkImage">Gambar Produk</label>
    <div class="file-upload-wrapper">
        <button type="button" class="upload-btn" onclick="document.getElementById('produkImageFile').click()">Pilih File</button>
        <input type="file" id="produkImageFile" accept="image/*" style="display: none;">
        <input type="hidden" id="produkImage" name="produkImage">
        <small style="color: var(--text-muted); display: block; margin-top: 5px;">Format: JPG, PNG, GIF, WEBP</small>
    </div>
    <div id="produkImagePreview" class="foto-preview" style="margin-top: 12px;"></div>
</div>

        <div class="form-group">
          <label for="produkVideo">URL Video (YouTube)</label>
          <input type="url" id="produkVideo" placeholder="https://www.youtube.com/watch?v=..." />
          <small>Opsional</small>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" onclick="closeProdukModal()">Batal</button>
          <button type="submit" class="btn-submit">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // ============ DATA MANAGEMENT FUNCTIONS ============
    const GURU_KEY = 'dkv_guru_data';
    const NEWS_KEY = 'dkv_news';
    const PORTFOLIO_KEY = 'dkv_portfolio';
    const PRODUCTS_KEY = 'dkv_products';
    const CONTENT_KEY = 'dkv_about_content';

    // ============ PAGINATION & BULK DELETE VARIABLES ============
    let guruCurrentPage = 1;
    let guruLimit = 10;
    let guruSelectedItems = new Set();

    let beritaCurrentPage = 1;
    let beritaLimit = 10;
    let beritaSelectedItems = new Set();

    let portfolioCurrentPage = 1;
    let portfolioLimit = 10;
    let portfolioSelectedItems = new Set();

    let produkCurrentPage = 1;
    let produkLimit = 10;
    let produkSelectedItems = new Set();

        // ============ PESAN MASUK VARIABLES ============
    let pesanCurrentPage = 1;
    let pesanLimit = 10;
    let pesanSelectedItems = new Set();
    let allPesanData = []; // Store all pesan data

    // Load pesan from database via AJAX
    function loadPesanFromDatabase() {
        return fetch('get_pesan.php')
            .then(response => response.json())
            .then(data => {
                allPesanData = data;
                renderPesanTable();
                return data;
            })
            .catch(error => {
                console.error('Error loading pesan:', error);
                allPesanData = [];
                renderPesanTable();
            });
    }

    // Render Pesan Table with Pagination
    function renderPesanTable() {
    const tbody = document.getElementById('pesanTableBody');
    if (!tbody) return;
    
    const start = (pesanCurrentPage - 1) * pesanLimit;
    const end = start + pesanLimit;
    const paginatedPesan = allPesanData.slice(start, end);
    const totalPages = Math.ceil(allPesanData.length / pesanLimit);
    
    tbody.innerHTML = '';

    if (allPesanData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 40px;"><div class="empty-state"><div class="empty-icon"><i class="fas fa-envelope-open"></i></div><p>Belum ada pesan masuk</p></div></td></tr>';
        document.getElementById('pesanPagination').style.display = 'none';
        return;
    }
    
    document.getElementById('pesanPagination').style.display = 'flex';
    
    paginatedPesan.forEach((p, idx) => {
        const globalIdx = start + idx;
        const isChecked = pesanSelectedItems.has(p.id) ? 'checked' : '';
        const nomor = globalIdx + 1;
        const statusClass = p.status === 'Sudah Dibaca' ? 'badge-neutral' : 'badge-warning';
        
        // Potong pesan untuk tampilan singkat
        const shortMessage = p.pesan.length > 50 ? p.pesan.substring(0, 50) + '...' : p.pesan;
        
        const row = `
            <tr>
                <td class="checkbox-col"><input type="checkbox" class="pesan-checkbox" data-id="${p.id}" ${isChecked} onchange="togglePesanSelect(${p.id})"></td>
                <td>${nomor}</td>
                <td>${escapeHtml(p.nama)}</td>
                <td>${escapeHtml(p.email)}</td>
                <td>${escapeHtml(p.telepon)}</td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHtml(p.pesan)}">
                    ${escapeHtml(shortMessage)}
                </td>
                <td><span class="badge ${statusClass}">${p.status === 'Sudah Dibaca' ? '✓ Dibaca' : '⏳ Baru'}</span></td>
                <td>${p.dibuat_dari_tanggal}</td>
                <td class="actions">
                    <a href="baca_pesan.php?id=${p.id}" class="btn-balas">
                        <i class="fas fa-book-open"></i> Baca
                    </a>
                    <button class="btn-delete" onclick="deleteSinglePesan(${p.id})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    updatePesanPaginationControls(totalPages);
    updatePesanBulkActions();
}

    function updatePesanPaginationControls(totalPages) {
        const pageNumbersDiv = document.getElementById('pesanPageNumbers');
        const prevBtn = document.getElementById('pesanPrevBtn');
        const nextBtn = document.getElementById('pesanNextBtn');
        
        if (prevBtn) prevBtn.disabled = pesanCurrentPage === 1;
        if (nextBtn) nextBtn.disabled = pesanCurrentPage === totalPages;
        
        if (pageNumbersDiv) {
            pageNumbersDiv.innerHTML = '';
            let startPage = Math.max(1, pesanCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = `page-number ${i === pesanCurrentPage ? 'active' : ''}`;
                pageBtn.onclick = () => goToPesanPage(i);
                pageNumbersDiv.appendChild(pageBtn);
            }
        }
    }

    function goToPesanPage(page) {
        const totalPages = Math.ceil(allPesanData.length / pesanLimit);
        if (page >= 1 && page <= totalPages) {
            pesanCurrentPage = page;
            renderPesanTable();
        }
    }

    function changePesanPage(direction) {
        const totalPages = Math.ceil(allPesanData.length / pesanLimit);
        if (direction === 'prev' && pesanCurrentPage > 1) {
            pesanCurrentPage--;
        } else if (direction === 'next' && pesanCurrentPage < totalPages) {
            pesanCurrentPage++;
        }
        renderPesanTable();
    }

    function changePesanLimit() {
        pesanLimit = parseInt(document.getElementById('pesanLimit').value);
        pesanCurrentPage = 1;
        pesanSelectedItems.clear();
        renderPesanTable();
    }

    function togglePesanSelect(id) {
        if (pesanSelectedItems.has(id)) {
            pesanSelectedItems.delete(id);
        } else {
            pesanSelectedItems.add(id);
        }
        updatePesanBulkActions();
        updatePesanSelectAllCheckbox();
    }

    function updatePesanBulkActions() {
        const bulkActions = document.getElementById('pesanBulkActions');
        const selectedCount = document.getElementById('pesanSelectedCount');
        const count = pesanSelectedItems.size;
        
        if (bulkActions) {
            if (count > 0) {
                bulkActions.classList.add('show');
                if (selectedCount) selectedCount.textContent = count;
            } else {
                bulkActions.classList.remove('show');
            }
        }
    }

    function updatePesanSelectAllCheckbox() {
        const start = (pesanCurrentPage - 1) * pesanLimit;
        const end = Math.min(start + pesanLimit, allPesanData.length);
        let allChecked = true;
        
        for (let i = start; i < end; i++) {
            if (!pesanSelectedItems.has(allPesanData[i].id)) {
                allChecked = false;
                break;
            }
        }
        
        const selectAllCheckbox = document.getElementById('selectAllPesan');
        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked && end > start;
    }

    function selectAllPesan(checked) {
        const start = (pesanCurrentPage - 1) * pesanLimit;
        const end = Math.min(start + pesanLimit, allPesanData.length);
        
        for (let i = start; i < end; i++) {
            const pesan = allPesanData[i];
            if (checked) {
                pesanSelectedItems.add(pesan.id);
            } else {
                pesanSelectedItems.delete(pesan.id);
            }
        }
        renderPesanTable();
    }

    function deleteSelectedPesan() {
    if (pesanSelectedItems.size === 0) {
        alert('Tidak ada item yang dipilih');
        return;
    }
    
    if (confirm(`Yakin ingin menghapus ${pesanSelectedItems.size} pesan?`)) {
        const ids = Array.from(pesanSelectedItems);
        
        // Kirim request ke hapus_pesan_banyak.php
        fetch('hapus_pesan_banyak.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                pesanSelectedItems.clear();
                loadPesanFromDatabase(); // Reload data
                alert(result.message || 'Pesan berhasil dihapus');
            } else {
                alert('Gagal menghapus pesan: ' + (result.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pesan. Cek koneksi atau file hapus_pesan_banyak.php');
        });
    }
}

    function deleteSinglePesan(id) {
    if (confirm('Yakin ingin menghapus pesan ini?')) {
        // Gunakan fetch API ke file hapus_pesan.php
        fetch(`hapus_pesan_ajax.php?id=${id}`, {
            method: 'GET',
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                if (pesanSelectedItems.has(id)) pesanSelectedItems.delete(id);
                loadPesanFromDatabase(); // Reload data
                alert('Pesan berhasil dihapus');
            } else {
                alert('Gagal menghapus pesan: ' + (result.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pesan');
        });
    }
}

    // ============ CORE DATA FUNCTIONS ============
    function updateStats() {
        const guru = loadGuruData();
        const berita = loadNews();
        const portfolio = loadPortfolio();
        const produk = loadProducts();

        const totalGuruEl = document.getElementById('totalGuruStat');
        const totalBeritaEl = document.getElementById('totalBeritaStat');
        const totalPortfolioEl = document.getElementById('totalPortfolioStat');
        const totalProdukEl = document.getElementById('totalProdukStat');
        
        if (totalGuruEl) totalGuruEl.textContent = guru.length;
        if (totalBeritaEl) totalBeritaEl.textContent = berita.length;
        if (totalPortfolioEl) totalPortfolioEl.textContent = portfolio.length;
        if (totalProdukEl) totalProdukEl.textContent = produk.length;
    }

    function loadGuruData() {
        const data = localStorage.getItem(GURU_KEY);
        if (!data) {
            // Jangan buat data default, kembalikan array kosong
            return [];
        }
        return JSON.parse(data);
    }

    function saveGuruData(data) {
        localStorage.setItem(GURU_KEY, JSON.stringify(data));
    }

    // Perbaiki fungsi loadNews dan saveNews
function loadNews() {
    const data = localStorage.getItem(NEWS_KEY);
    let news = data ? JSON.parse(data) : [];
    
    // Migrasi dan normalisasi tanggal untuk semua berita yang ada
    news = news.map(item => {
        if (!item.id) {
            item.id = Date.now() + Math.random();
        }
        if (!item.desc) {
            item.desc = item.content || '';
        }
        if (!item.excerpt) {
            item.excerpt = (item.desc || '').substring(0, 150) + '...';
        }
        // NORMALISASI TANGGAL - pastikan format ISO 8601
        if (!item.date) {
            item.date = new Date().toISOString();
        } else {
            // Pastikan tanggal dalam format ISO
            try {
                const parsedDate = new Date(item.date);
                if (!isNaN(parsedDate.getTime())) {
                    item.date = parsedDate.toISOString();
                } else {
                    item.date = new Date().toISOString();
                }
            } catch(e) {
                item.date = new Date().toISOString();
            }
        }
        if (!item.category) {
            item.category = 'info';
        }
        // INISIALISASI VIEWS - pastikan setiap berita memiliki views
        if (!item.views) {
            item.views = 1;
        }
        return item;
    });
    
    // Simpan hasil migrasi
    if (data !== JSON.stringify(news)) {
        saveNews(news);
    }
    return news;
}

    function saveNews(data) {
        localStorage.setItem(NEWS_KEY, JSON.stringify(data));
    }

    function loadPortfolio() {
    const data = localStorage.getItem(PORTFOLIO_KEY);
    let portfolio = data ? JSON.parse(data) : [];
    
    // Tambahkan default kategori dan ID untuk data lama
    let modified = false;
    portfolio = portfolio.map(item => {
        if (!item.category) {
            item.category = 'Desain Grafis';  // default
            modified = true;
        }
        if (!item.id) {
            item.id = Date.now() + Math.random();
            modified = true;
        }
        return item;
    });
    
    // Simpan kembali jika ada perubahan
    if (modified && data !== JSON.stringify(portfolio)) {
        savePortfolio(portfolio);
    }
    return portfolio;
}

    function savePortfolio(data) {
        localStorage.setItem(PORTFOLIO_KEY, JSON.stringify(data));
    }

    function loadProducts() {
        const data = localStorage.getItem(PRODUCTS_KEY);
        return data ? JSON.parse(data) : [];
    }

    function saveProducts(data) {
        localStorage.setItem(PRODUCTS_KEY, JSON.stringify(data));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ============ GURU TABLE WITH PAGINATION ============
    function renderGuruTable() {
        const tbody = document.getElementById('guruTableBody');
        if (!tbody) return;
        
        const allGuru = loadGuruData();
        const start = (guruCurrentPage - 1) * guruLimit;
        const end = start + guruLimit;
        const paginatedGuru = allGuru.slice(start, end);
        const totalPages = Math.ceil(allGuru.length / guruLimit);
        
        tbody.innerHTML = '';

        if (allGuru.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;"><div class="empty-state"><div class="empty-icon"><i class="fas fa-users"></i></div><p>Belum ada data guru</p></div></td></tr>';
            document.getElementById('guruPagination').style.display = 'none';
            return;
        }
        
        document.getElementById('guruPagination').style.display = 'flex';
        
        paginatedGuru.forEach((g, idx) => {
            const fotoHtml = g.foto 
                ? `<img src="${g.foto}" alt="${g.nama}" style="width: 100%; height: 100%; object-fit: cover;">` 
                : '<i class="fas fa-user-graduate" style="font-size: 1.2rem; color: var(--text-muted);"></i>';
            
            const statusClass = g.status === 'Aktif' ? 'badge-success' : (g.status === 'Cuti' ? 'badge-warning' : 'badge-danger');
            const isChecked = guruSelectedItems.has(g.id) ? 'checked' : '';
            const nomor = start + idx + 1;
            
            const row = `
                <tr>
                    <td class="checkbox-col"><input type="checkbox" class="guru-checkbox" data-id="${g.id}" ${isChecked} onchange="toggleGuruSelect(${g.id})"></td>
                    <td>${nomor}</td>
                    <td><div class="guru-foto-cell">${fotoHtml}</div></td>
                    <td>${escapeHtml(g.nama)}</td>
                    <td>${escapeHtml(g.mapel)}</td>
                    <td><span class="badge ${statusClass}">${g.status}</span></td>
                    <td class="actions">
                        <button class="btn-edit" onclick="openGuruModal('edit', ${g.id})">Edit</button>
                        <button class="btn-delete" onclick="deleteGuru(${g.id})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
        
        updateGuruPaginationControls(totalPages);
        updateGuruBulkActions();
    }

    function updateGuruPaginationControls(totalPages) {
        const pageNumbersDiv = document.getElementById('guruPageNumbers');
        const prevBtn = document.getElementById('guruPrevBtn');
        const nextBtn = document.getElementById('guruNextBtn');
        
        if (prevBtn) prevBtn.disabled = guruCurrentPage === 1;
        if (nextBtn) nextBtn.disabled = guruCurrentPage === totalPages;
        
        if (pageNumbersDiv) {
            pageNumbersDiv.innerHTML = '';
            let startPage = Math.max(1, guruCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = `page-number ${i === guruCurrentPage ? 'active' : ''}`;
                pageBtn.onclick = () => goToGuruPage(i);
                pageNumbersDiv.appendChild(pageBtn);
            }
        }
    }

    function goToGuruPage(page) {
        const allGuru = loadGuruData();
        const totalPages = Math.ceil(allGuru.length / guruLimit);
        if (page >= 1 && page <= totalPages) {
            guruCurrentPage = page;
            renderGuruTable();
        }
    }

    function changeGuruPage(direction) {
        const allGuru = loadGuruData();
        const totalPages = Math.ceil(allGuru.length / guruLimit);
        if (direction === 'prev' && guruCurrentPage > 1) {
            guruCurrentPage--;
        } else if (direction === 'next' && guruCurrentPage < totalPages) {
            guruCurrentPage++;
        }
        renderGuruTable();
    }

    function changeGuruLimit() {
        guruLimit = parseInt(document.getElementById('guruLimit').value);
        guruCurrentPage = 1;
        guruSelectedItems.clear();
        renderGuruTable();
    }

    function toggleGuruSelect(id) {
        if (guruSelectedItems.has(id)) {
            guruSelectedItems.delete(id);
        } else {
            guruSelectedItems.add(id);
        }
        updateGuruBulkActions();
        updateSelectAllCheckbox('guru');
    }

    function updateGuruBulkActions() {
        const bulkActions = document.getElementById('guruBulkActions');
        const selectedCount = document.getElementById('guruSelectedCount');
        const count = guruSelectedItems.size;
        
        if (bulkActions) {
            if (count > 0) {
                bulkActions.classList.add('show');
                if (selectedCount) selectedCount.textContent = count;
            } else {
                bulkActions.classList.remove('show');
            }
        }
    }

    function updateSelectAllCheckbox(type) {
        const allGuru = loadGuruData();
        const start = (guruCurrentPage - 1) * guruLimit;
        const end = Math.min(start + guruLimit, allGuru.length);
        let allChecked = true;
        
        for (let i = start; i < end; i++) {
            if (!guruSelectedItems.has(allGuru[i].id)) {
                allChecked = false;
                break;
            }
        }
        
        const selectAllCheckbox = document.getElementById('selectAllGuru');
        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked && end > start;
    }

    function selectAllGuru(checked) {
        const allGuru = loadGuruData();
        const start = (guruCurrentPage - 1) * guruLimit;
        const end = Math.min(start + guruLimit, allGuru.length);
        
        for (let i = start; i < end; i++) {
            const guru = allGuru[i];
            if (checked) {
                guruSelectedItems.add(guru.id);
            } else {
                guruSelectedItems.delete(guru.id);
            }
        }
        renderGuruTable();
    }

    function deleteSelectedGuru() {
        if (guruSelectedItems.size === 0) {
            alert('Tidak ada item yang dipilih');
            return;
        }
        
        if (confirm(`Yakin ingin menghapus ${guruSelectedItems.size} data guru?`)) {
            let allGuru = loadGuruData();
            allGuru = allGuru.filter(g => !guruSelectedItems.has(g.id));
            saveGuruData(allGuru);
            guruSelectedItems.clear();
            guruCurrentPage = 1;
            renderGuruTable();
            updateStats();
            alert('Data guru berhasil dihapus');
        }
    }

    // ============ BERITA TABLE WITH PAGINATION ============
    function renderBeritaTable() {
    const tbody = document.getElementById('beritaTableBody');
    if (!tbody) return;
    
    const allBerita = loadNews();
    const start = (beritaCurrentPage - 1) * beritaLimit;
    const end = start + beritaLimit;
    const paginatedBerita = allBerita.slice(start, end);
    const totalPages = Math.ceil(allBerita.length / beritaLimit);
    
    tbody.innerHTML = '';

    if (allBerita.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 40px;"><div class="empty-state"><div class="empty-icon"><i class="fas fa-newspaper"></i></div><p>Belum ada data berita</p></div></td></tr>';
        const pagination = document.getElementById('beritaPagination');
        if (pagination) pagination.style.display = 'none';
        return;
    }
    
    const pagination = document.getElementById('beritaPagination');
    if (pagination) pagination.style.display = 'flex';
    
    paginatedBerita.forEach((b, idx) => {
        const title = b.title || 'Tanpa Judul';
        const content = b.content || b.desc || '';
        const image = b.image || '';
        const video = b.video || '';
        const date = b.date ? new Date(b.date).toLocaleDateString('id-ID', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '-';
        const views = b.views || 1;
        
        const imageHtml = image 
            ? `<img src="${image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;" onerror="this.style.display='none'">` 
            : '<i class="fas fa-image" style="font-size: 1.2rem; color: var(--text-muted);"></i>';
        
        const videoHtml = video 
            ? '<i class="fas fa-video" style="font-size: 1rem; color: var(--accent);"></i>' 
            : '<span style="color: var(--text-muted);">-</span>';
        
        const globalIdx = start + idx;
        const isChecked = beritaSelectedItems.has(globalIdx) ? 'checked' : '';
        const nomor = globalIdx + 1;
        
        // AMAN: cek apakah content ada sebelum substring
        let contentPreview = '-';
        if (content && content.length > 0) {
            contentPreview = content.length > 50 ? content.substring(0, 50) + '...' : content;
        }
        
        const row = `
            <tr>
                <td class="checkbox-col"><input type="checkbox" class="berita-checkbox" data-index="${globalIdx}" ${isChecked} onchange="toggleBeritaSelect(${globalIdx})"></td>
                <td>${nomor}</td>
                <td>${escapeHtml(title)}</td>
                <td>${escapeHtml(contentPreview)}</td>
                <td><small>${date}</small></td>
                <td><span style="background: rgba(59, 91, 219, 0.1); padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">${views}</span></td>
                <td><div class="table-image-cell">${imageHtml}</div></td>
                <td><div class="table-icon-cell">${videoHtml}</div></td>
                <td class="actions">
                    <button class="btn-edit" onclick="openBeritaModal('edit', ${globalIdx})">Edit</button>
                    <button class="btn-delete" onclick="deleteBerita(${globalIdx})">Hapus</button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    updateBeritaPaginationControls(totalPages);
    updateBeritaBulkActions();
}

    function updateBeritaPaginationControls(totalPages) {
        const pageNumbersDiv = document.getElementById('beritaPageNumbers');
        const prevBtn = document.getElementById('beritaPrevBtn');
        const nextBtn = document.getElementById('beritaNextBtn');
        
        if (prevBtn) prevBtn.disabled = beritaCurrentPage === 1;
        if (nextBtn) nextBtn.disabled = beritaCurrentPage === totalPages;
        
        if (pageNumbersDiv) {
            pageNumbersDiv.innerHTML = '';
            let startPage = Math.max(1, beritaCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = `page-number ${i === beritaCurrentPage ? 'active' : ''}`;
                pageBtn.onclick = () => goToBeritaPage(i);
                pageNumbersDiv.appendChild(pageBtn);
            }
        }
    }

    function goToBeritaPage(page) {
        const allBerita = loadNews();
        const totalPages = Math.ceil(allBerita.length / beritaLimit);
        if (page >= 1 && page <= totalPages) {
            beritaCurrentPage = page;
            renderBeritaTable();
        }
    }

    function changeBeritaPage(direction) {
        const allBerita = loadNews();
        const totalPages = Math.ceil(allBerita.length / beritaLimit);
        if (direction === 'prev' && beritaCurrentPage > 1) {
            beritaCurrentPage--;
        } else if (direction === 'next' && beritaCurrentPage < totalPages) {
            beritaCurrentPage++;
        }
        renderBeritaTable();
    }

    function changeBeritaLimit() {
        beritaLimit = parseInt(document.getElementById('beritaLimit').value);
        beritaCurrentPage = 1;
        beritaSelectedItems.clear();
        renderBeritaTable();
    }

    function toggleBeritaSelect(index) {
        if (beritaSelectedItems.has(index)) {
            beritaSelectedItems.delete(index);
        } else {
            beritaSelectedItems.add(index);
        }
        updateBeritaBulkActions();
    }

    function updateBeritaBulkActions() {
        const bulkActions = document.getElementById('beritaBulkActions');
        const selectedCount = document.getElementById('beritaSelectedCount');
        const count = beritaSelectedItems.size;
        
        if (bulkActions) {
            if (count > 0) {
                bulkActions.classList.add('show');
                if (selectedCount) selectedCount.textContent = count;
            } else {
                bulkActions.classList.remove('show');
            }
        }
    }

    function selectAllBerita(checked) {
        const allBerita = loadNews();
        const start = (beritaCurrentPage - 1) * beritaLimit;
        const end = Math.min(start + beritaLimit, allBerita.length);
        
        for (let i = start; i < end; i++) {
            if (checked) {
                beritaSelectedItems.add(i);
            } else {
                beritaSelectedItems.delete(i);
            }
        }
        renderBeritaTable();
    }

    function deleteSelectedBerita() {
        if (beritaSelectedItems.size === 0) {
            alert('Tidak ada item yang dipilih');
            return;
        }
        
        if (confirm(`Yakin ingin menghapus ${beritaSelectedItems.size} data berita?`)) {
            let allBerita = loadNews();
            const sortedIndices = Array.from(beritaSelectedItems).sort((a, b) => b - a);
            sortedIndices.forEach(idx => {
                allBerita.splice(idx, 1);
            });
            saveNews(allBerita);
            beritaSelectedItems.clear();
            beritaCurrentPage = 1;
            renderBeritaTable();
            updateStats();
            alert('Data berita berhasil dihapus');
        }
    }

    // ============ PORTFOLIO TABLE WITH PAGINATION ============
    function renderPortfolioTable() {
        const tbody = document.getElementById('portfolioTableBody');
        if (!tbody) return;
        
        const allPortfolio = loadPortfolio();
        const start = (portfolioCurrentPage - 1) * portfolioLimit;
        const end = start + portfolioLimit;
        const paginatedPortfolio = allPortfolio.slice(start, end);
        const totalPages = Math.ceil(allPortfolio.length / portfolioLimit);
        
        tbody.innerHTML = '';

        if (allPortfolio.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px;"><div class="empty-state"><div class="empty-icon"><i class="fas fa-images"></i></div><p>Belum ada data portofolio</p></div></td></tr>';
            document.getElementById('portfolioPagination').style.display = 'none';
            return;
        }
        
        document.getElementById('portfolioPagination').style.display = 'flex';
        
        paginatedPortfolio.forEach((p, idx) => {
            
        
            const imageHtml = p.image 
                ? `<img src="${p.image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">` 
                : '<i class="fas fa-image" style="font-size: 1.2rem; color: var(--text-muted);"></i>';
            
            const videoHtml = p.video 
                ? '<i class="fas fa-video" style="font-size: 1rem; color: var(--accent);"></i>' 
                : '<span style="color: var(--text-muted);">-</span>';
            
            const globalIdx = start + idx;
            const isChecked = portfolioSelectedItems.has(globalIdx) ? 'checked' : '';
            const nomor = globalIdx + 1;
            
            // Tentukan badge untuk kategori
            const categoryBadge = p.category === 'Fotografi' ? 'badge-warning' : 'badge-success';
            
            const row = `
                <tr>
                    <td class="checkbox-col"><input type="checkbox" class="portfolio-checkbox" data-index="${globalIdx}" ${isChecked} onchange="togglePortfolioSelect(${globalIdx})"></td>
                    <td>${nomor}</td>
                    <td>${escapeHtml(p.title)}</td>
                    <td><span class="badge ${categoryBadge}">${escapeHtml(p.category || 'Desain Grafis')}</span></td>
                    <td>${escapeHtml(p.description.substring(0, 50))}...</td>
                    <td><div class="table-image-cell">${imageHtml}</div></td>
                    <td><div class="table-icon-cell">${videoHtml}</div></td>
                    <td class="actions">
                        <button class="btn-edit" onclick="openPortfolioModal('edit', ${globalIdx})">Edit</button>
                        <button class="btn-delete" onclick="deletePortfolio(${globalIdx})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
        
        updatePortfolioPaginationControls(totalPages);
        updatePortfolioBulkActions();
    }

    function updatePortfolioPaginationControls(totalPages) {
        const pageNumbersDiv = document.getElementById('portfolioPageNumbers');
        const prevBtn = document.getElementById('portfolioPrevBtn');
        const nextBtn = document.getElementById('portfolioNextBtn');
        
        if (prevBtn) prevBtn.disabled = portfolioCurrentPage === 1;
        if (nextBtn) nextBtn.disabled = portfolioCurrentPage === totalPages;
        
        if (pageNumbersDiv) {
            pageNumbersDiv.innerHTML = '';
            let startPage = Math.max(1, portfolioCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = `page-number ${i === portfolioCurrentPage ? 'active' : ''}`;
                pageBtn.onclick = () => goToPortfolioPage(i);
                pageNumbersDiv.appendChild(pageBtn);
            }
        }
    }

    function goToPortfolioPage(page) {
        const allPortfolio = loadPortfolio();
        const totalPages = Math.ceil(allPortfolio.length / portfolioLimit);
        if (page >= 1 && page <= totalPages) {
            portfolioCurrentPage = page;
            renderPortfolioTable();
        }
    }

    function changePortfolioPage(direction) {
        const allPortfolio = loadPortfolio();
        const totalPages = Math.ceil(allPortfolio.length / portfolioLimit);
        if (direction === 'prev' && portfolioCurrentPage > 1) {
            portfolioCurrentPage--;
        } else if (direction === 'next' && portfolioCurrentPage < totalPages) {
            portfolioCurrentPage++;
        }
        renderPortfolioTable();
    }

    function changePortfolioLimit() {
        portfolioLimit = parseInt(document.getElementById('portfolioLimit').value);
        portfolioCurrentPage = 1;
        portfolioSelectedItems.clear();
        renderPortfolioTable();
    }

    function togglePortfolioSelect(index) {
        if (portfolioSelectedItems.has(index)) {
            portfolioSelectedItems.delete(index);
        } else {
            portfolioSelectedItems.add(index);
        }
        updatePortfolioBulkActions();
    }

    function updatePortfolioBulkActions() {
        const bulkActions = document.getElementById('portfolioBulkActions');
        const selectedCount = document.getElementById('portfolioSelectedCount');
        const count = portfolioSelectedItems.size;
        
        if (bulkActions) {
            if (count > 0) {
                bulkActions.classList.add('show');
                if (selectedCount) selectedCount.textContent = count;
            } else {
                bulkActions.classList.remove('show');
            }
        }
    }

    function selectAllPortfolio(checked) {
        const allPortfolio = loadPortfolio();
        const start = (portfolioCurrentPage - 1) * portfolioLimit;
        const end = Math.min(start + portfolioLimit, allPortfolio.length);
        
        for (let i = start; i < end; i++) {
            if (checked) {
                portfolioSelectedItems.add(i);
            } else {
                portfolioSelectedItems.delete(i);
            }
        }
        renderPortfolioTable();
    }

    function deleteSelectedPortfolio() {
        if (portfolioSelectedItems.size === 0) {
            alert('Tidak ada item yang dipilih');
            return;
        }
        
        if (confirm(`Yakin ingin menghapus ${portfolioSelectedItems.size} data portofolio?`)) {
            let allPortfolio = loadPortfolio();
            const sortedIndices = Array.from(portfolioSelectedItems).sort((a, b) => b - a);
            sortedIndices.forEach(idx => {
                allPortfolio.splice(idx, 1);
            });
            savePortfolio(allPortfolio);
            portfolioSelectedItems.clear();
            portfolioCurrentPage = 1;
            renderPortfolioTable();
            updateStats();
            alert('Data portofolio berhasil dihapus');
        }
    }

    // ============ PRODUK TABLE WITH PAGINATION ============
    function renderProdukTable() {
        const tbody = document.getElementById('produkTableBody');
        if (!tbody) return;
        
        const allProduk = loadProducts();
        const start = (produkCurrentPage - 1) * produkLimit;
        const end = start + produkLimit;
        const paginatedProduk = allProduk.slice(start, end);
        const totalPages = Math.ceil(allProduk.length / produkLimit);
        
        tbody.innerHTML = '';

        if (allProduk.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;"><div class="empty-state"><div class="empty-icon"><i class="fas fa-box"></i></div><p>Belum ada data produk</p></div></td></tr>';
            document.getElementById('produkPagination').style.display = 'none';
            return;
        }
        
        document.getElementById('produkPagination').style.display = 'flex';
        
        paginatedProduk.forEach((p, idx) => {
            const imageHtml = p.image 
                ? `<img src="${p.image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">` 
                : '<i class="fas fa-image" style="font-size: 1.2rem; color: var(--text-muted);"></i>';
            
            const videoHtml = p.video 
                ? '<i class="fas fa-video" style="font-size: 1rem; color: var(--accent);"></i>' 
                : '<span style="color: var(--text-muted);">-</span>';
            
            const globalIdx = start + idx;
            const isChecked = produkSelectedItems.has(globalIdx) ? 'checked' : '';
            const nomor = globalIdx + 1;
            
            const row = `
                <tr>
                    <td class="checkbox-col"><input type="checkbox" class="produk-checkbox" data-index="${globalIdx}" ${isChecked} onchange="toggleProdukSelect(${globalIdx})"></td>
                    <td>${nomor}</td>
                    <td>${escapeHtml(p.name)}</td>
                    <td>${escapeHtml(p.desc.substring(0, 50))}...</td>
                    <td><div class="table-image-cell">${imageHtml}</div></td>
                    <td><div class="table-icon-cell">${videoHtml}</div></td>
                    <td class="actions">
                        <button class="btn-edit" onclick="openProdukModal('edit', ${globalIdx})">Edit</button>
                        <button class="btn-delete" onclick="deleteProduk(${globalIdx})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
        
        updateProdukPaginationControls(totalPages);
        updateProdukBulkActions();
    }

    function updateProdukPaginationControls(totalPages) {
        const pageNumbersDiv = document.getElementById('produkPageNumbers');
        const prevBtn = document.getElementById('produkPrevBtn');
        const nextBtn = document.getElementById('produkNextBtn');
        
        if (prevBtn) prevBtn.disabled = produkCurrentPage === 1;
        if (nextBtn) nextBtn.disabled = produkCurrentPage === totalPages;
        
        if (pageNumbersDiv) {
            pageNumbersDiv.innerHTML = '';
            let startPage = Math.max(1, produkCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = `page-number ${i === produkCurrentPage ? 'active' : ''}`;
                pageBtn.onclick = () => goToProdukPage(i);
                pageNumbersDiv.appendChild(pageBtn);
            }
        }
    }

    function goToProdukPage(page) {
        const allProduk = loadProducts();
        const totalPages = Math.ceil(allProduk.length / produkLimit);
        if (page >= 1 && page <= totalPages) {
            produkCurrentPage = page;
            renderProdukTable();
        }
    }

    function changeProdukPage(direction) {
        const allProduk = loadProducts();
        const totalPages = Math.ceil(allProduk.length / produkLimit);
        if (direction === 'prev' && produkCurrentPage > 1) {
            produkCurrentPage--;
        } else if (direction === 'next' && produkCurrentPage < totalPages) {
            produkCurrentPage++;
        }
        renderProdukTable();
    }

    function changeProdukLimit() {
        produkLimit = parseInt(document.getElementById('produkLimit').value);
        produkCurrentPage = 1;
        produkSelectedItems.clear();
        renderProdukTable();
    }

    function toggleProdukSelect(index) {
        if (produkSelectedItems.has(index)) {
            produkSelectedItems.delete(index);
        } else {
            produkSelectedItems.add(index);
        }
        updateProdukBulkActions();
    }

    function updateProdukBulkActions() {
        const bulkActions = document.getElementById('produkBulkActions');
        const selectedCount = document.getElementById('produkSelectedCount');
        const count = produkSelectedItems.size;
        
        if (bulkActions) {
            if (count > 0) {
                bulkActions.classList.add('show');
                if (selectedCount) selectedCount.textContent = count;
            } else {
                bulkActions.classList.remove('show');
            }
        }
    }

    function selectAllProduk(checked) {
        const allProduk = loadProducts();
        const start = (produkCurrentPage - 1) * produkLimit;
        const end = Math.min(start + produkLimit, allProduk.length);
        
        for (let i = start; i < end; i++) {
            if (checked) {
                produkSelectedItems.add(i);
            } else {
                produkSelectedItems.delete(i);
            }
        }
        renderProdukTable();
    }

    function deleteSelectedProduk() {
        if (produkSelectedItems.size === 0) {
            alert('Tidak ada item yang dipilih');
            return;
        }
        
        if (confirm(`Yakin ingin menghapus ${produkSelectedItems.size} data produk?`)) {
            let allProduk = loadProducts();
            const sortedIndices = Array.from(produkSelectedItems).sort((a, b) => b - a);
            sortedIndices.forEach(idx => {
                allProduk.splice(idx, 1);
            });
            saveProducts(allProduk);
            produkSelectedItems.clear();
            produkCurrentPage = 1;
            renderProdukTable();
            updateStats();
            alert('Data produk berhasil dihapus');
        }
    }

    // ============ MODAL FUNCTIONS (Guru, Berita, Portfolio, Produk) ============
    let editingGuruId = null;
    let editingBeritaIdx = null;
    let editingPortfolioIdx = null;
    let editingProdukIdx = null;

    function openGuruModal(mode, id = null) {
        const modal = document.getElementById('guruModal');
        const title = document.getElementById('guruModalTitle');
        const form = document.getElementById('guruForm');
        
        editingGuruId = null;
        form.reset();
        document.getElementById('guruFotoPreview').innerHTML = '';
        document.getElementById('guruFoto').value = '';
        document.getElementById('guruFotoFile').value = '';

        if (mode === 'edit' && id) {
            const guru = loadGuruData().find(g => g.id === id);
            if (guru) {
                editingGuruId = id;
                title.textContent = 'Edit Data Guru';
                document.getElementById('guruNama').value = guru.nama;
                document.getElementById('guruMapel').value = guru.mapel;
                document.getElementById('guruStatus').value = guru.status;
                
                if (guru.foto) {
                    document.getElementById('guruFotoPreview').innerHTML = `<img src="${guru.foto}" style="max-width: 100%; max-height: 100%; object-fit: cover;">`;
                    document.getElementById('guruFoto').value = guru.foto;
                }
            }
        } else {
            title.textContent = 'Tambah Guru';
        }

        modal.style.display = 'flex';
    }

    function closeGuruModal() {
        document.getElementById('guruModal').style.display = 'none';
        editingGuruId = null;
    }

    function deleteGuru(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data guru ini?')) {
            const guru = loadGuruData().filter(g => g.id !== id);
            saveGuruData(guru);
            if (guruSelectedItems.has(id)) guruSelectedItems.delete(id);
            guruCurrentPage = 1;
            renderGuruTable();
            updateStats();
            alert('Data guru berhasil dihapus');
        }
    }

    function openBeritaModal(mode, idx = null) {
        const modal = document.getElementById('beritaModal');
        const title = document.getElementById('beritaModalTitle');
        const form = document.getElementById('beritaForm');
        
        editingBeritaIdx = null;
        form.reset();
        document.getElementById('beritaImagePreview').innerHTML = '';
        document.getElementById('beritaImage').value = '';
        document.getElementById('beritaImageFile').value = '';
        // Set tanggal default ke sekarang untuk form baru
        document.getElementById('beritaDate').value = new Date().toISOString().slice(0, 16);

        if (mode === 'edit' && idx !== null) {
            const berita = loadNews()[idx];
            if (berita) {
                editingBeritaIdx = idx;
                title.textContent = 'Edit Berita';
                document.getElementById('beritaTitle').value = berita.title;
                document.getElementById('beritaContent').value = berita.content;
                document.getElementById('beritaVideo').value = berita.video || '';
                // Tampilkan tanggal yang ada
                if (berita.date) {
                    const dateObj = new Date(berita.date);
                    document.getElementById('beritaDate').value = dateObj.toISOString().slice(0, 16);
                } else {
                    document.getElementById('beritaDate').value = new Date().toISOString().slice(0, 16);
                }

                if (berita.image) {
                    document.getElementById('beritaImagePreview').innerHTML = `<img src="${berita.image}" style="max-width: 100%; max-height: 100%; object-fit: cover;">`;
                    document.getElementById('beritaImage').value = berita.image;
                }
            }
        } else {
            title.textContent = 'Tambah Berita';
        }

        modal.style.display = 'flex';
    }

    function closeBeritaModal() {
        document.getElementById('beritaModal').style.display = 'none';
        editingBeritaIdx = null;
    }

    function deleteBerita(idx) {
        if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
            const berita = loadNews();
            berita.splice(idx, 1);
            saveNews(berita);
            if (beritaSelectedItems.has(idx)) beritaSelectedItems.delete(idx);
            beritaCurrentPage = 1;
            renderBeritaTable();
            updateStats();
            alert('Berita berhasil dihapus');
        }
    }

    function openPortfolioModal(mode, idx = null) {
    const modal = document.getElementById('portfolioModal');
    const title = document.getElementById('portfolioModalTitle');
    
    editingPortfolioIdx = null;
    document.getElementById('portfolioForm').reset();
    document.getElementById('portfolioImagePreview').innerHTML = '';
    document.getElementById('portfolioImage').value = '';
    document.getElementById('portfolioImageFile').value = '';

    if (mode === 'edit' && idx !== null) {
        const portfolio = loadPortfolio()[idx];
        if (portfolio) {
            editingPortfolioIdx = idx;
            title.textContent = 'Edit Portofolio';
            document.getElementById('portfolioTitle').value = portfolio.title;
            // Ambil nilai kategori
            document.getElementById('portfolioCategory').value = portfolio.category || 'Desain Grafis';
            document.getElementById('portfolioDesc').value = portfolio.description;
            document.getElementById('portfolioVideo').value = portfolio.video || '';

            if (portfolio.image) {
                document.getElementById('portfolioImagePreview').innerHTML = `<img src="${portfolio.image}" style="max-width: 100%; max-height: 100%; object-fit: cover;">`;
                document.getElementById('portfolioImage').value = portfolio.image;
            }
        }
    } else {
        title.textContent = 'Tambah Portofolio';
        document.getElementById('portfolioCategory').value = ''; // reset pilihan
    }

    modal.style.display = 'flex';
}

    function closePortfolioModal() {
        document.getElementById('portfolioModal').style.display = 'none';
        editingPortfolioIdx = null;
    }

    function deletePortfolio(idx) {
        if (confirm('Apakah Anda yakin ingin menghapus portofolio ini?')) {
            const portfolio = loadPortfolio();
            portfolio.splice(idx, 1);
            savePortfolio(portfolio);
            if (portfolioSelectedItems.has(idx)) portfolioSelectedItems.delete(idx);
            portfolioCurrentPage = 1;
            renderPortfolioTable();
            updateStats();
            alert('Portofolio berhasil dihapus');
        }
    }

    function openProdukModal(mode, idx = null) {
        const modal = document.getElementById('produkModal');
        const title = document.getElementById('produkModalTitle');
        const form = document.getElementById('produkForm');
        
        editingProdukIdx = null;
        form.reset();
        document.getElementById('produkImagePreview').innerHTML = '';
        document.getElementById('produkImage').value = '';
        document.getElementById('produkImageFile').value = '';

        if (mode === 'edit' && idx !== null) {
            const produk = loadProducts()[idx];
            if (produk) {
                editingProdukIdx = idx;
                title.textContent = 'Edit Produk';
                document.getElementById('produkName').value = produk.name;
                document.getElementById('produkDesc').value = produk.desc;
                document.getElementById('produkVideo').value = produk.video || '';

                if (produk.image) {
                    document.getElementById('produkImagePreview').innerHTML = `<img src="${produk.image}" style="max-width: 100%; max-height: 100%; object-fit: cover;">`;
                    document.getElementById('produkImage').value = produk.image;
                }
            }
        } else {
            title.textContent = 'Tambah Produk';
        }

        modal.style.display = 'flex';
    }

    function closeProdukModal() {
        document.getElementById('produkModal').style.display = 'none';
        editingProdukIdx = null;
    }

    function deleteProduk(idx) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            const produk = loadProducts();
            produk.splice(idx, 1);
            saveProducts(produk);
            if (produkSelectedItems.has(idx)) produkSelectedItems.delete(idx);
            produkCurrentPage = 1;
            renderProdukTable();
            updateStats();
            alert('Produk berhasil dihapus');
        }
    }

    // ============ PREVIEW GAMBAR ============
    function setupImagePreview(fileInputId, previewId, hiddenInputId) {
        const fileInput = document.getElementById(fileInputId);
        const preview = document.getElementById(previewId);
        const hiddenInput = document.getElementById(hiddenInputId);
        
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%; object-fit: cover;" alt="Preview">`;
                        hiddenInput.value = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = '';
                    hiddenInput.value = '';
                }
            });
        }
    }

    // ============ FORM SUBMIT HANDLERS ============
    function setupFormHandlers() {
        const guruForm = document.getElementById('guruForm');
        if (guruForm) {
            guruForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const nama = document.getElementById('guruNama').value.trim();
                const foto = document.getElementById('guruFoto').value;
                const mapel = document.getElementById('guruMapel').value.trim();
                const status = document.getElementById('guruStatus').value;

                if (!nama || !mapel || !status) {
                    alert('Mohon isi semua field yang diperlukan');
                    return;
                }

                let guru = loadGuruData();
                if (editingGuruId) {
                    const idx = guru.findIndex(g => g.id === editingGuruId);
                    if (idx !== -1) {
                        guru[idx] = { id: editingGuruId, nama, foto, mapel, status };
                        alert('Data guru berhasil diperbarui');
                    }
                } else {
                    const newId = guru.length > 0 ? Math.max(...guru.map(g => g.id)) + 1 : 1;
                    guru.unshift({ id: newId, nama, foto, mapel, status });
                    alert('Data guru berhasil ditambahkan');
                }

                saveGuruData(guru);
                guruCurrentPage = 1;
                renderGuruTable();
                closeGuruModal();
                updateStats();
            });
        }

        const beritaForm = document.getElementById('beritaForm');
if (beritaForm) {
    beritaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const title = document.getElementById('beritaTitle').value.trim();
        const content = document.getElementById('beritaContent').value.trim();
        const image = document.getElementById('beritaImage').value;
        const video = document.getElementById('beritaVideo').value.trim();
        const dateInput = document.getElementById('beritaDate').value;

        if (!title || !content) {
            alert('Mohon isi judul dan konten berita');
            return;
        }

        let berita = loadNews();
        
        // ==== PERBAIKAN TANGGAL - PASTIKAN SELALU ADA ====
        let beritaDate;
        if (dateInput) {
            beritaDate = new Date(dateInput).toISOString();
        } else {
            beritaDate = new Date().toISOString();
        }
        
        if (editingBeritaIdx !== null) {
            // Edit berita - update dengan tanggal yang dipilih
            const existingBerita = berita[editingBeritaIdx];
            berita[editingBeritaIdx] = { 
                ...existingBerita,
                id: existingBerita.id || Date.now(),
                title: title, 
                desc: content,
                content: content,
                excerpt: content.substring(0, 150) + (content.length > 150 ? '...' : ''),
                image: image || existingBerita.image || '', 
                video: video || existingBerita.video || '',
                date: beritaDate,
                category: 'info',
                views: existingBerita.views || 1
            };
            console.log('Berita di-update dengan tanggal:', beritaDate, 'Views:', berita[editingBeritaIdx].views);
            alert('Berita berhasil diperbarui');
        } else {
            // Tambah berita baru
            const newId = Date.now();
            const newBerita = {
                id: newId,
                title: title,
                desc: content,
                content: content,
                excerpt: content.substring(0, 150) + (content.length > 150 ? '...' : ''),
                image: image || '',
                video: video || '',
                date: beritaDate,
                category: 'info',
                views: 1
            };
            berita.unshift(newBerita);
            console.log('Berita baru dibuat dengan ID:', newId, 'Tanggal:', beritaDate, 'Views: 1');
            alert('Berita berhasil ditambahkan');
        }

        saveNews(berita);
        beritaCurrentPage = 1;
        renderBeritaTable();
        closeBeritaModal();
        updateStats();
        
        // ==== SINKRONISASI KE SESSION DAN LOCAL STORAGE ====
        syncDataToSession();
    });
}

        const portfolioForm = document.getElementById('portfolioForm');
if (portfolioForm) {
    portfolioForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const title = document.getElementById('portfolioTitle').value.trim();
        const category = document.getElementById('portfolioCategory').value; // ambil kategori
        const description = document.getElementById('portfolioDesc').value.trim();
        const image = document.getElementById('portfolioImage').value;
        const video = document.getElementById('portfolioVideo').value.trim();

        // Validasi termasuk kategori
        if (!title || !category || !description) {
            alert('Mohon isi judul, kategori, dan deskripsi portofolio');
            return;
        }

        let portfolio = loadPortfolio();
        if (editingPortfolioIdx !== null) {
            // Edit data yang sudah ada
            portfolio[editingPortfolioIdx] = { 
                ...portfolio[editingPortfolioIdx],
                title, 
                category,      // simpan kategori
                description, 
                image, 
                video 
            };
            alert('Portofolio berhasil diperbarui');
        } else {
            // Tambah data baru
            portfolio.unshift({ 
                id: Date.now(),
                title, 
                category,      // simpan kategori
                description, 
                image, 
                video 
            });
            alert('Portofolio berhasil ditambahkan');
        }

        savePortfolio(portfolio);
        portfolioCurrentPage = 1;
        renderPortfolioTable();  // refresh tabel admin
        closePortfolioModal();
        updateStats();
    });
}

        const produkForm = document.getElementById('produkForm');
        if (produkForm) {
            produkForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = document.getElementById('produkName').value.trim();
                const desc = document.getElementById('produkDesc').value.trim();
                const image = document.getElementById('produkImage').value;
                const video = document.getElementById('produkVideo').value.trim();

                if (!name || !desc) {
                    alert('Mohon isi nama dan deskripsi produk');
                    return;
                }

                let produk = loadProducts();
                if (editingProdukIdx !== null) {
                    produk[editingProdukIdx] = { name, desc, image, video };
                    alert('Produk berhasil diperbarui');
                } else {
                    produk.unshift({ name, desc, image, video });
                    alert('Produk berhasil ditambahkan');
                }

                saveProducts(produk);
                produkCurrentPage = 1;
                renderProdukTable();
                closeProdukModal();
                updateStats();
            });
        }
    }

    // ============ TAB NAVIGATION ============
    function setupTabNavigation() {
        const tabButtons = document.querySelectorAll('.tab-nav');
        const tabContents = document.querySelectorAll('.tab-content');

        function switchTab(tabId) {
            tabContents.forEach(content => content.classList.remove('active'));
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            const activeTab = document.getElementById(tabId);
            if (activeTab) activeTab.classList.add('active');
            
            const activeButton = Array.from(tabButtons).find(btn => btn.dataset.tab === tabId);
            if (activeButton) activeButton.classList.add('active');
            
            if (tabId === 'guru') renderGuruTable();
            else if (tabId === 'berita') renderBeritaTable();
            else if (tabId === 'portfolio') renderPortfolioTable();
            else if (tabId === 'produk') renderProdukTable();
            else if (tabId === 'settings') initSettingsUI();
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.dataset.tab;
                if (tabId) switchTab(tabId);
            });
        });
    }

    // ============ AMBIL DATA GURU DARI DATABASE ============
function loadGuruFromDatabase() {
    fetch('get_guru_list.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderGuruTableFromDB(data.data);
                updateStatsFromDB(data.data);
            }
        })
        .catch(error => console.error('Error loading guru:', error));
}

function renderGuruTableFromDB(guruList) {
    const tbody = document.getElementById('guruTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    if (guruList.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Belum ada data guru</td></tr>';
        return;
    }
    
    guruList.forEach((guru, index) => {
        const row = `
            <tr>
                <td class="checkbox-col"><input type="checkbox" class="guru-checkbox" data-id="${guru.id}"></td>
                <td>${index + 1}</td>
                <td><div class="guru-foto-cell">${guru.foto ? `<img src="${guru.foto}" style="width:100%;height:100%;object-fit:cover;">` : '<i class="fas fa-user-graduate"></i>'}</div></td>
                <td>${escapeHtml(guru.nama)}</td>
                <td>${escapeHtml(guru.mapel)}</td>
                <td><span class="badge ${guru.status === 'Aktif' ? 'badge-success' : 'badge-danger'}">${guru.status}</span></td>
                <td class="actions">
                    <button class="btn-edit" onclick="editGuru(${guru.id})">Edit</button>
                    <button class="btn-delete" onclick="deleteGuru(${guru.id})">Hapus</button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

    // ============ KELOLA KONTEN DINAMIS ============
    function loadAboutContent() {
        const defaultContent = {
            visiMisi: "Jurusan DKV berkomitmen untuk membentuk peserta didik yang tidak hanya terampil dalam mengolah elemen visual, tetapi juga mampu berpikir kritis, komunikatif, dan adaptif terhadap perkembangan zaman. Jurusan ini menjadi program keahlian yang unggul, kreatif, dan inovatif dalam mencetak generasi muda yang mampu berkompetisi di dunia industri kreatif, serta berwawasan teknologi dan globalisasi.\n\nMelalui pembelajaran berbasis proyek, teknologi, dan seni, jurusan DKV bertujuan menghasilkan lulusan yang siap berkarya secara profesional di bidang desain grafis, ilustrasi, animasi, fotografi, maupun media digital lainnya.\n\nDengan semangat kreativitas dan inovasi, DKV terus berupaya menjadi wadah bagi siswa untuk menyalurkan ide, menciptakan karya yang inspiratif, serta memberikan kontribusi nyata bagi masyarakat dan industri kreatif di masa depan.",
            
            struktur: "Struktur organisasi Program Keahlian Desain Komunikasi Visual (DKV) terdiri dari beberapa unsur yang saling berperan dalam mendukung kegiatan belajar mengajar.\n\nTingkat tertinggi terdapat Kepala Sekolah yang memimpin seluruh kegiatan pendidikan di sekolah, dibantu oleh Wakil Kepala Sekolah bidang Kurikulum yang mengatur proses pembelajaran.\n\nDi bawahnya, terdapat Kepala Kompetensi Keahlian DKV yang bertanggung jawab terhadap pengelolaan jurusan, perencanaan kegiatan, serta pembinaan peserta didik di bidang desain komunikasi visual. Kepala kompetensi dibantu oleh guru produktif DKV yang mengajar mata pelajaran kejuruan seperti desain grafis, fotografi, ilustrasi, animasi, dan multimedia."
        };
        
        const data = localStorage.getItem(CONTENT_KEY);
        if (data) {
            try {
                return JSON.parse(data);
            } catch (e) {
                return defaultContent;
            }
        }
        return defaultContent;
    }

    function saveAboutContent(content) {
        localStorage.setItem(CONTENT_KEY, JSON.stringify(content));
        window.dispatchEvent(new StorageEvent('storage', {
            key: CONTENT_KEY,
            newValue: JSON.stringify(content)
        }));
    }

    function loadContentToForms() {
        const content = loadAboutContent();
        const visiMisiTextarea = document.getElementById('visiMisiText');
        const strukturTextarea = document.getElementById('strukturText');
        
        if (visiMisiTextarea) visiMisiTextarea.value = content.visiMisi;
        if (strukturTextarea) strukturTextarea.value = content.struktur;
        
        const previewVisiMisi = document.getElementById('previewVisiMisi');
        const previewStruktur = document.getElementById('previewStruktur');
        
        if (previewVisiMisi) previewVisiMisi.innerHTML = content.visiMisi.replace(/\n/g, '<br>');
        if (previewStruktur) previewStruktur.innerHTML = content.struktur.replace(/\n/g, '<br>');
    }

    function setupContentForms() {
        const visiMisiForm = document.getElementById('visiMisiForm');
        if (visiMisiForm) {
            visiMisiForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const visiMisi = document.getElementById('visiMisiText').value.trim();
                if (!visiMisi) {
                    alert('Mohon isi konten Visi & Misi');
                    return;
                }
                const currentContent = loadAboutContent();
                currentContent.visiMisi = visiMisi;
                saveAboutContent(currentContent);
                loadContentToForms();
                alert('✓ Konten Visi & Misi berhasil disimpan!');
            });
        }
        
        const strukturForm = document.getElementById('strukturForm');
        if (strukturForm) {
            strukturForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const struktur = document.getElementById('strukturText').value.trim();
                if (!struktur) {
                    alert('Mohon isi konten Struktur Organisasi');
                    return;
                }
                const currentContent = loadAboutContent();
                currentContent.struktur = struktur;
                saveAboutContent(currentContent);
                loadContentToForms();
                alert('✓ Konten Struktur Organisasi berhasil disimpan!');
            });
        }
    }

        // ============ INITIALIZATION ============
    document.addEventListener('DOMContentLoaded', function() {
        setupImagePreview('guruFotoFile', 'guruFotoPreview', 'guruFoto');
        setupImagePreview('beritaImageFile', 'beritaImagePreview', 'beritaImage');
        setupImagePreview('portfolioImageFile', 'portfolioImagePreview', 'portfolioImage');
        setupImagePreview('produkImageFile', 'produkImagePreview', 'produkImage');
        
        setupFormHandlers();
        setupTabNavigation();
        loadContentToForms();
        setupContentForms();
        
        renderGuruTable();
        renderBeritaTable();
        renderPortfolioTable();
        renderProdukTable();
setTimeout(function() {
    updateStats();
    console.log('Stats updated on load');
}, 100);
        
        // Load Pesan data from database
        loadPesanFromDatabase();
    });


    // ============ SINKRONISASI DATA UNTUK WEBSITE ============
function syncDataToSession() {
    // Simpan semua data ke sessionStorage agar bisa diakses halaman user
    const allNews = loadNews();
    const allPortfolio = loadPortfolio();
    const allProducts = loadProducts();
    
    sessionStorage.setItem('dkv_news_data', JSON.stringify(allNews));
    sessionStorage.setItem('dkv_portfolio_data', JSON.stringify(allPortfolio));
    sessionStorage.setItem('dkv_products_data', JSON.stringify(allProducts));
    
    // Juga simpan ke localStorage untuk redundansi
    localStorage.setItem('dkv_news', JSON.stringify(allNews));
    localStorage.setItem('dkv_portfolio', JSON.stringify(allPortfolio));
    localStorage.setItem('dkv_products', JSON.stringify(allProducts));
    
    console.log('Data sinkronisasi ke sessionStorage selesai');
}

// Panggil syncDataToSession setiap kali data berubah
// Override save functions
const originalSaveNews = saveNews;
window.saveNews = function(data) {
    originalSaveNews(data);
    syncDataToSession();
};

const originalSavePortfolio = savePortfolio;
window.savePortfolio = function(data) {
    originalSavePortfolio(data);
    syncDataToSession();
};

const originalSaveProducts = saveProducts;
window.saveProducts = function(data) {
    originalSaveProducts(data);
    syncDataToSession();
};

// Panggil pertama kali saat load
document.addEventListener('DOMContentLoaded', function() {
    // ... kode yang sudah ada ...
    
    // Sinkronisasi data
    setTimeout(syncDataToSession, 500);
});

// ================================================================

const BRANDING_KEY = 'dkv_site_branding';
const EMAIL_KEY    = 'dkv_email_admin';
const MAINT_KEY    = 'dkv_maintenance';

// ── INIT: tampilkan nilai saat ini ke tabel settings ─────────────
function initSettingsUI() {
  // Branding (nama website + tagline)
  let branding = { nama: 'Jurusan DKV', tagline: 'SMKN 1 Cibinong' };
  try {
    const raw = localStorage.getItem(BRANDING_KEY);
    if (raw) branding = Object.assign(branding, JSON.parse(raw));
  } catch(e) {}

  const elNama = document.getElementById('display-nama-website');
  if (elNama) elNama.textContent = branding.nama + ' · ' + branding.tagline;

  // Email admin
  const email = localStorage.getItem(EMAIL_KEY) || 'admin@smkn1cibinong.sch.id';
  const elEmail = document.getElementById('display-email-admin');
  if (elEmail) elEmail.textContent = email;

  // Maintenance
  let isMaint = false;
  try { isMaint = JSON.parse(localStorage.getItem(MAINT_KEY)) === true; } catch(e) {}
  applyMaintenanceUI(isMaint);
}

// ── BUKA / TUTUP FORM INLINE ─────────────────────────────────────
function openSettingEdit(field) {
  // Tutup semua form dulu
  ['nama-website', 'email-admin', 'maintenance'].forEach(function(f) {
    var row = document.getElementById('form-' + f);
    if (row) row.style.display = 'none';
  });

  var formRow = document.getElementById('form-' + field);
  if (!formRow) return;
  formRow.style.display = 'table-row';

  if (field === 'nama-website') {
    var branding = { nama: 'Jurusan DKV', tagline: 'SMKN 1 Cibinong' };
    try {
      var raw = localStorage.getItem(BRANDING_KEY);
      if (raw) branding = Object.assign(branding, JSON.parse(raw));
    } catch(e) {}

    var inputNama    = document.getElementById('input-nama-utama');
    var inputTagline = document.getElementById('input-nama-tagline');
    if (inputNama)    inputNama.value    = branding.nama;
    if (inputTagline) inputTagline.value = branding.tagline;
    setupBrandingPreview();
    setTimeout(function() { if (inputNama) inputNama.focus(); }, 60);

  } else if (field === 'email-admin') {
    var inputEmail = document.getElementById('input-email-admin');
    if (inputEmail) {
      inputEmail.value = localStorage.getItem(EMAIL_KEY) || 'admin@smkn1cibinong.sch.id';
      setTimeout(function() { inputEmail.focus(); }, 60);
    }

  } else if (field === 'maintenance') {
    var isActive = false;
    try { isActive = JSON.parse(localStorage.getItem(MAINT_KEY)) === true; } catch(e) {}

    var title = document.getElementById('maintenance-confirm-title');
    var desc  = document.getElementById('maintenance-confirm-desc');
    var btn   = document.getElementById('btn-confirm-maintenance');

    if (isActive) {
      if (title) title.textContent = 'Nonaktifkan Mode Maintenance?';
      if (desc)  desc.textContent  = 'Website akan kembali bisa diakses oleh pengunjung secara normal.';
      if (btn)   { btn.innerHTML = '<i class="fas fa-check"></i> Ya, Nonaktifkan'; btn.style.background = 'var(--success)'; }
    } else {
      if (title) title.textContent = 'Aktifkan Mode Maintenance?';
      if (desc)  desc.textContent  = 'Pengunjung tidak bisa membuka website dan akan diarahkan ke halaman pemeliharaan. Admin yang sudah login tetap bisa akses.';
      if (btn)   { btn.innerHTML = '<i class="fas fa-tools"></i> Ya, Aktifkan'; btn.style.background = 'var(--warning)'; }
    }
  }
}

function closeSettingEdit(field) {
  var formRow = document.getElementById('form-' + field);
  if (formRow) formRow.style.display = 'none';
}

// ── LIVE PREVIEW NAMA WEBSITE ────────────────────────────────────
function setupBrandingPreview() {
  var inputNama    = document.getElementById('input-nama-utama');
  var inputTagline = document.getElementById('input-nama-tagline');
  var prevNama     = document.getElementById('preview-nama-utama');
  var prevTagline  = document.getElementById('preview-nama-tagline');

  function update() {
    if (prevNama)    prevNama.textContent    = inputNama    ? inputNama.value    || 'Jurusan DKV'    : '';
    if (prevTagline) prevTagline.textContent = inputTagline ? inputTagline.value || 'SMKN 1 Cibinong' : '';
  }

  if (inputNama)    inputNama.oninput    = update;
  if (inputTagline) inputTagline.oninput = update;
  update();
}

// ── SIMPAN NAMA WEBSITE ──────────────────────────────────────────
function saveNamaWebsite() {
  var nama    = (document.getElementById('input-nama-utama')?.value    || '').trim();
  var tagline = (document.getElementById('input-nama-tagline')?.value || '').trim();

  if (!nama)    { showSettingsToast('Nama utama tidak boleh kosong', 'error');    return; }
  if (!tagline) { showSettingsToast('Sub-nama tidak boleh kosong', 'error'); return; }

  localStorage.setItem(BRANDING_KEY, JSON.stringify({ nama: nama, tagline: tagline }));

  var el = document.getElementById('display-nama-website');
  if (el) el.textContent = nama + ' · ' + tagline;

  // Update header dashboard itu sendiri juga
  var brandName = document.querySelector('.brand-name');
  if (brandName) brandName.textContent = nama;

  closeSettingEdit('nama-website');
  showSettingsToast('Nama website berhasil disimpan! Halaman user akan update otomatis saat di-refresh.', 'success');
}

// ── SIMPAN EMAIL ADMIN ───────────────────────────────────────────
function saveEmailAdmin() {
  var email = (document.getElementById('input-email-admin')?.value || '').trim();

  if (!email) { showSettingsToast('Email tidak boleh kosong', 'error'); return; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    showSettingsToast('Format email tidak valid', 'error'); return;
  }

  localStorage.setItem(EMAIL_KEY, email);

  var el = document.getElementById('display-email-admin');
  if (el) el.textContent = email;

  closeSettingEdit('email-admin');
  showSettingsToast('Email admin berhasil disimpan! Akan tampil di halaman Contact & footer.', 'success');
}

// ── SIMPAN MODE MAINTENANCE ──────────────────────────────────────
function saveMaintenance() {
  var current = false;
  try { current = JSON.parse(localStorage.getItem(MAINT_KEY)) === true; } catch(e) {}
  var newStatus = !current;

  localStorage.setItem(MAINT_KEY, JSON.stringify(newStatus));
  applyMaintenanceUI(newStatus);
  closeSettingEdit('maintenance');

  if (newStatus) {
    showSettingsToast('Maintenance AKTIF — pengunjung tidak bisa membuka website.', 'warning');
  } else {
    showSettingsToast('Maintenance dinonaktifkan — website kembali normal.', 'success');
  }
}

function applyMaintenanceUI(isActive) {
  var badge     = document.getElementById('badge-maintenance');
  var btnToggle = document.getElementById('btn-maintenance-toggle');

  if (badge) {
    badge.className   = isActive ? 'badge badge-danger' : 'badge badge-success';
    badge.textContent = isActive ? 'Aktif' : '✓ Nonaktif';
  }
  if (btnToggle) {
    btnToggle.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
  }
}

// ── TOAST NOTIFIKASI ─────────────────────────────────────────────
function showSettingsToast(message, type) {
  var old = document.getElementById('settings-toast');
  if (old) old.remove();

  if (!document.getElementById('toast-style')) {
    var s = document.createElement('style');
    s.id = 'toast-style';
    s.textContent = '@keyframes toastIn{from{opacity:0;transform:translateX(16px)}to{opacity:1;transform:translateX(0)}}';
    document.head.appendChild(s);
  }

  var colors = {
    success: { bg:'#ebfbee', color:'#2f9e44', border:'#2f9e44' },
    error:   { bg:'#fff5f5', color:'#e03131', border:'#e03131' },
    warning: { bg:'#fff9db', color:'#e67700', border:'#e67700' }
  };
  var c = colors[type] || colors.success;

  var toast = document.createElement('div');
  toast.id = 'settings-toast';
  toast.style.cssText = [
    'position:fixed', 'top:72px', 'right:24px', 'z-index:9999',
    'padding:12px 18px', 'border-radius:8px', 'font-size:.855rem',
    'font-weight:600', 'box-shadow:0 4px 16px rgba(0,0,0,.12)',
    'animation:toastIn 240ms ease', 'max-width:360px', 'line-height:1.4',
    'background:' + c.bg, 'color:' + c.color, 'border:1px solid ' + c.border
  ].join(';');
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(function() { if (toast.parentNode) toast.remove(); }, 4000);
}

// ── bypass maintenance di user side ─
sessionStorage.setItem('dkv_admin_logged_in', '1');



(function() {
  const API = '../api_data.php';
 
  // Key yang perlu disync
  const SYNC_KEYS = [
    'dkv_guru_data',
    'dkv_news',
    'dkv_portfolio',
    'dkv_products',
    'dkv_about_content',
    'dkv_site_branding',
    'dkv_email_admin',
    'dkv_maintenance',
  ];
 
  // Fungsi kirim ke server
  function pushToServer(key, value) {
    fetch(API + '?key=' + encodeURIComponent(key), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ value: value }),
    }).catch(() => {});
  }
 
  // Override localStorage.setItem agar setiap simpan otomatis sync ke server
  const _origSetItem = localStorage.setItem.bind(localStorage);
  localStorage.setItem = function(key, value) {
    _origSetItem(key, value);
    if (SYNC_KEYS.includes(key)) {
      pushToServer(key, value);
    }
  };
 
  // Upload semua data yang sudah ada di localStorage ke server sekarang
  SYNC_KEYS.forEach(function(key) {
    const val = localStorage.getItem(key);
    if (val) {
      pushToServer(key, val);
    }
  });
 
  console.log('[sync] Dashboard → api_data.php aktif');
})();
</script>
</body>
</html>