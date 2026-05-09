<?php
// get_pesan.php
session_start();
if(!isset($_SESSION['admin'])){
    http_response_code(403);
    exit('Unauthorized');
}

include 'db.php';

$query = mysqli_query($conn, "SELECT * FROM pesan ORDER BY dibuat_dari_tanggal DESC");
$pesan = [];

while ($row = mysqli_fetch_assoc($query)) {
    $pesan[] = [
        'id' => $row['id'],
        'nama' => $row['nama'],
        'email' => $row['email'],
        'telepon' => $row['telepon'],
        'pesan' => $row['pesan'],
        'status' => $row['status'],
        'dibuat_dari_tanggal' => $row['dibuat_dari_tanggal'],
        'balasan' => $row['balasan'] ?? '',  // TAMBAHKAN INI
        'balasan_dari' => $row['balasan_dari'] ?? '',  // TAMBAHKAN INI
        'waktu_balas' => $row['waktu_balas'] ?? ''  // TAMBAHKAN INI
    ];
}

header('Content-Type: application/json');
echo json_encode($pesan);
?>