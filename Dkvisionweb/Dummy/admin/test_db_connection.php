<?php
$conn = mysqli_connect("localhost", "root", "", "dkv");
if (!$conn) {
    echo "❌ Koneksi GAGAL: " . mysqli_connect_error();
} else {
    echo "✅ Koneksi berhasil!";
    $result = mysqli_query($conn, "SELECT * FROM admin");
    if ($result) {
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo "<br>Jumlah user: " . count($rows);
        foreach($rows as $r) {
            echo "<br>Username: " . $r['username'];
        }
    } else {
        echo "<br>❌ Tabel admin tidak ditemukan!";
    }
}
?>