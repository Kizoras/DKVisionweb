<?php
// Dummy/index.php
session_start();

// Lokasi file status maintenance
$statusFile = __DIR__ . '/config/maintenance_status.json';

// Cek apakah maintenance aktif
$maintenance = false;
if (file_exists($statusFile)) {
    $data = json_decode(file_get_contents($statusFile), true);
    $maintenance = $data['maintenance'] ?? false;
}

// Jika maintenance aktif DAN bukan admin yang sedang login
if ($maintenance && !isset($_SESSION['admin'])) {
    // Redirect ke halaman maintenance
    header('Location: admin/maintenance.php');
    exit;
}

// Jika tidak maintenance atau user adalah admin, lanjut ke website normal
// Ganti 'user/index.html' dengan file utama website Anda
if (file_exists('user/index.html')) {
    include 'user/index.html';
} else {
    echo "Website sedang dalam pengembangan. Silakan cek kembali nanti.";
}
?>