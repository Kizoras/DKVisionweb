<?php
session_start();
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../config/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../config/PHPMailer/src/SMTP.php';

$id     = $_POST['id'];
$email  = $_POST['email'];
$subjek = $_POST['subjek'];
$pesan  = $_POST['pesan'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dkv97263@gmail.com';
    $mail->Password   = 'dwglpdqhyrtnlgiv';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('dkv97263@gmail.com', 'dkv');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $subjek;
    $mail->Body    = $pesan;


    $mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';
    $mail->AuthType = 'LOGIN';
    $mail->send();

    // Update status jadi read atau dibalas
    mysqli_query($conn, "UPDATE kontak SET status='read' WHERE id='$id'");

    header("Location: dashboard.php?pesan=balas_berhasil");
    exit;

} catch (Exception $e) {
    echo "Gagal mengirim email. Error: {$mail->ErrorInfo}";
}
?>