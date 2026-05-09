<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Akses tidak valid');
}

$nama     = $_POST['nama'] ?? '';
$email    = $_POST['email'] ?? '';
$telepon  = $_POST['telepon'] ?? '';
$pesan    = $_POST['pesan'] ?? '';

if ($nama === '' || $email === '' || $pesan === '') {
    die('Data wajib belum diisi');
}

$status = 'unread';

$stmt = $conn->prepare(
    "INSERT INTO pesan (nama, email, telepon, pesan, status, dibuat_dari_tanggal)
     VALUES (?, ?, ?, ?, ?, NOW())"
);

$stmt->bind_param("sssss", $nama, $email, $telepon, $pesan, $status);
$stmt->execute();

header("Location: ../user/contact.html");
exit();

