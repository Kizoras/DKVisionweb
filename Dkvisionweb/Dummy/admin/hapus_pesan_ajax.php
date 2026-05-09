<?php
// hapus_pesan_ajax.php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['admin'])){
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

include "db.php";

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    if(mysqli_query($conn, "DELETE FROM pesan WHERE id='$id'")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}
?>