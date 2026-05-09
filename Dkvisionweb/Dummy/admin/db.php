<?php
$conn = mysqli_connect("localhost", "root", "", "dkv");

if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}
?>