<?php
// config/email_config.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

function kirimEmail($to, $subject, $message, $from_name = "Admin DKV SMKN 1 Cibinong") {
    $mail = new PHPMailer(true);
    
    try {
        // ============ KONFIGURASI SMTP BREVO ============
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a71da4001@smtp-brevo.com';
        $mail->Password   = 'xsmtpsib-f18f50e54cb49226f6aa47cfd3579da8684b511b76207f5703ab6daf206b1531-GRQcgmdUKbN0f3nB';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        // ===============================================
        
        // Gunakan GMAIL ANDA (yang sudah diverifikasi di Brevo)
        $mail->setFrom('dkv97263@gmail.com', $from_name);  // GANTI dengan Gmail Anda!
        
        $mail->addAddress($to);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);
        
        $mail->send();
        return ['success' => true, 'message' => 'Email berhasil dikirim via Brevo'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => "Email gagal dikirim: {$mail->ErrorInfo}"];
    }
}
?>