<?php
// hapus_pesan.php
session_start();
include "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "DELETE FROM pesan WHERE id='$id'");
    
    // Redirect back with success message
    header("Location: dashboard.php?tab=pesan&pesan=hapus_berhasil");
    exit;
} else {
    header("Location: dashboard.php?tab=pesan");
    exit;
}
?>