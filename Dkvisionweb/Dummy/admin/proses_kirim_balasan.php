<?php
// admin/proses_kirim_balasan.php

require_once "../config/email_config.php";
require_once "../config/koneksi.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pertanyaan = $_POST['id_pertanyaan'];
    $jawaban = $_POST['jawaban'];
    $id_admin = $_SESSION['admin_id'];
    
    // Ambil data siswa dan pertanyaannya
    $query = "SELECT p.*, s.nama_siswa, s.email_siswa, p.pertanyaan 
              FROM tanya_jawab p 
              JOIN siswa s ON p.id_siswa = s.id 
              WHERE p.id = '$id_pertanyaan'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    
    $email_siswa = $data['email_siswa'];
    $nama_siswa = $data['nama_siswa'];
    $pertanyaan = $data['pertanyaan'];
    
    // Update jawaban di database
    $update = "UPDATE tanya_jawab SET 
               jawaban = '$jawaban', 
               status = 'dijawab',
               id_admin = '$id_admin',
               waktu_jawab = NOW() 
               WHERE id = '$id_pertanyaan'";
    mysqli_query($koneksi, $update);
    
    // Format email HTML
    $message = "
    <!DOCTYPE html>
    <html>
    <head><meta charset='UTF-8'></head>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Balasan Pertanyaan Anda</h2>
        <p>Halo <strong>{$nama_siswa}</strong>,</p>
        
        <div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;'>
            <strong>Pertanyaan Anda:</strong><br>
            {$pertanyaan}
        </div>
        
        <div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #2ecc71; margin: 20px 0;'>
            <strong>Jawaban Admin:</strong><br>
            {$jawaban}
        </div>
        
        <hr>
        <p>Terima kasih telah menghubungi kami.</p>
        <p>Hormat kami,<br>Panitia PPDB DKV SMKN 1 Cibinong</p>
    </body>
    </html>
    ";
    
    // Kirim email ke siswa
    $result_email = kirimEmail(
        $email_siswa, 
        "Balasan Pertanyaan Anda - PPDB DKV", 
        $message
    );
    
    if ($result_email['success']) {
        echo "<script>
            alert('Balasan berhasil dikirim ke email siswa!');
            window.location.href = 'dashboard_admin.php?page=pertanyaan';
        </script>";
    } else {
        echo "<script>
            alert('Email gagal dikirim: " . addslashes($result_email['message']) . "');
            window.location.href = 'dashboard_admin.php?page=pertanyaan';
        </script>";
    }
}
?>