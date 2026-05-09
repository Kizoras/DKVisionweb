<?php
// test_email.php
require_once "config/email_config.php";

// Ganti dengan email Anda sendiri untuk test
$to = "dkv97264@gmail.com"; // Email sendiri untuk test
$subject = "Test Email dari DKV Admin";
$message = "<h1>✅ TEST BERHASIL!</h1><p>Email ini dikirim menggunakan PHPMailer dan Gmail SMTP.</p><p>Waktu: " . date('Y-m-d H:i:s') . "</p>";

$result = kirimEmail($to, $subject, $message);

echo "<pre>";
print_r($result);
echo "</pre>";

if($result['success']){
    echo "✅ Email berhasil dikirim ke $to";
} else {
    echo "❌ Gagal: " . $result['message'];
}
?>