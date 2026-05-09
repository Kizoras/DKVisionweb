<?php
// admin/save_guru.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

include 'db.php';

$id = $_POST['id'] ?? null;
$nama = $_POST['nama'] ?? '';
$mapel = $_POST['mapel'] ?? '';
$status = $_POST['status'] ?? '';
$foto = $_POST['foto'] ?? '';

if (empty($nama) || empty($mapel) || empty($status)) {
    echo json_encode(['success' => false, 'error' => 'Data tidak lengkap']);
    exit;
}

if ($id) {
    // Update
    $query = "UPDATE guru SET nama='$nama', mapel='$mapel', status='$status', foto='$foto' WHERE id=$id";
} else {
    // Insert
    $query = "INSERT INTO guru (nama, mapel, status, foto) VALUES ('$nama', '$mapel', '$status', '$foto')";
}

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>