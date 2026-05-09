<?php
// test_kirim_ke_siswa.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config/email_config.php";

// EMAIL SISWA (GANTI DENGAN EMAIL ASLI ANDA UNTUK TEST)
$email_siswa = "dkv97264@gmail.com"; // Ganti dengan email siswa beneran

$pertanyaan_siswa = "Apakah pendaftaran masih dibuka?";
$jawaban_admin = "Ya, pendaftaran masih dibuka hingga 30 Juni 2026. Silakan daftar segera!";

$message = "
<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='font-family: Arial, sans-serif;'>
    <h2 style='color: #2c3e50;'>Balasan Pertanyaan Anda</h2>
    
    <div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;'>
        <strong>📝 Pertanyaan Anda:</strong><br>
        {$pertanyaan_siswa}
    </div>
    
    <div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #2ecc71; margin: 20px 0;'>
        <strong>✅ Jawaban Admin:</strong><br>
        {$jawaban_admin}
    </div>
    
    <hr>
    <p style='color: #7f8c8d; font-size: 12px;'>
        Jika masih ada pertanyaan, silakan kirim pertanyaan baru melalui form di website.<br>
        Hormat kami,<br>
        <strong>Panitia PPDB DKV SMKN 1 Cibinong</strong>
    </p>
</body>
</html>
";

echo "<h3>📧 Mengirim email ke: {$email_siswa}</h3>";
echo "<h3>📝 Subjek: Balasan Pertanyaan Anda</h3>";
echo "<hr>";

$result = kirimEmail($email_siswa, "Balasan Pertanyaan Anda - PPDB DKV", $message);

echo "<pre>";
print_r($result);
echo "</pre>";

if($result['success']){
    echo "<h2 style='color:green'>✅ BERHASIL! Cek inbox {$email_siswa}</h2>";
    echo "<p>Email balasan dari admin sudah terkirim ke siswa.</p>";
} else {
    echo "<h2 style='color:red'>❌ GAGAL: " . $result['message'] . "</h2>";
}
?>