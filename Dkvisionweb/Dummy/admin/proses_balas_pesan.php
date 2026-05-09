<?php
// admin/proses_balas_pesan.php

require_once "../config/email_config.php";
require_once "db.php";  // atau koneksi.php, sesuaikan dengan file koneksi Anda

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_pesan = mysqli_real_escape_string($conn, $_POST['id_pesan']);
    $balasan = mysqli_real_escape_string($conn, $_POST['balasan']);
    $email_admin = $_SESSION['admin'];
    
    // Ambil data pesan asli
    $query = mysqli_query($conn, "SELECT * FROM pesan WHERE id = '$id_pesan'");
    $data = mysqli_fetch_assoc($query);
    
    if (!$data) {
        echo "<script>
            alert('Data pesan tidak ditemukan!');
            window.location.href = 'dashboard.php?tab=pesan';
        </script>";
        exit();
    }
    
    $email_pengirim = $data['email'];
    $nama_pengirim = $data['nama'];
    $pesan_asli = $data['pesan'];
    
    // Update status pesan
    mysqli_query($conn, "UPDATE pesan SET 
               status = 'Sudah Dibalas',
               balasan = '$balasan',
               balasan_dari = '$email_admin',
               waktu_balas = NOW() 
               WHERE id = '$id_pesan'");
    
    // Format email HTML
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Balasan Pesan Anda</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
        <h2 style='color: #2c3e50;'>📧 Balasan dari Admin DKV</h2>
        <p>Halo <strong>" . htmlspecialchars($nama_pengirim) . "</strong>,</p>
        <p>Admin telah membalas pesan Anda:</p>
        
        <div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0; border-radius: 5px;'>
            <strong>📝 Pesan Anda:</strong><br>
            <em>" . nl2br(htmlspecialchars($pesan_asli)) . "</em>
        </div>
        
        <div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #2ecc71; margin: 20px 0; border-radius: 5px;'>
            <strong>✅ Balasan Admin:</strong><br>
            " . nl2br(htmlspecialchars($balasan)) . "
        </div>
        
        <hr>
        <p style='color: #7f8c8d; font-size: 12px;'>
            Hormat kami,<br>
            <strong>Admin DKV SMKN 1 Cibinong</strong>
        </p>
    </body>
    </html>
    ";
    
    // Kirim email
    $result_email = kirimEmail(
        $email_pengirim,
        "Balasan Pesan Anda - DKV SMKN 1 Cibinong",
        $message
    );
    
    if ($result_email['success']) {
        echo "<script>
            alert('✅ Balasan berhasil dikirim ke $email_pengirim!');
            window.location.href = 'dashboard.php?tab=pesan';
        </script>";
    } else {
        echo "<script>
            alert('⚠️ Pesan tersimpan, tapi email gagal: " . addslashes($result_email['message']) . "');
            window.location.href = 'dashboard.php?tab=pesan';
        </script>";
    }
    
} else {
    header("Location: dashboard.php?tab=pesan");
    exit();
}
?>