<?php
// hapus_pesan_banyak.php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['admin'])){
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

include "db.php";

// Ambil data JSON dari request
$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];

if (empty($ids) || !is_array($ids)) {
    echo json_encode(['success' => false, 'error' => 'Tidak ada pesan yang dipilih']);
    exit;
}

// Escape semua ID untuk keamanan
$escapedIds = array_map(function($id) use ($conn) {
    return mysqli_real_escape_string($conn, $id);
}, $ids);

$ids_string = implode("','", $escapedIds);
$query = "DELETE FROM pesan WHERE id IN ('$ids_string')";

if (mysqli_query($conn, $query)) {
    $deleted = mysqli_affected_rows($conn);
    echo json_encode(['success' => true, 'deleted' => $deleted, 'message' => "$deleted pesan berhasil dihapus"]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>