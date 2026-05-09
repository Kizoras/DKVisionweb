<?php
include "db.php";
$data = mysqli_query($conn,"SELECT * FROM guru");
?>

<h2>Data Guru DKV</h2>

<form method="POST">
<input type="text" name="nama" placeholder="Nama Guru">
<input type="text" name="mapel" placeholder="Mata Pelajaran">
<input type="text" name="status" placeholder="status">
<button name="simpan">Simpan</button>
</form>

<table border="1" cellpadding="10">
<tr>
<th>Nama</th><th>Mapel</th><th>Status</th><th>Aksi</th>
</tr>

<?php while($g = mysqli_fetch_array($data)){ ?>
<tr>
<td><?= $g['nama'] ?></td>
<td><?= $g['mapel'] ?></td>
<td><?= $g['status'] ?></td>
<td>
<a href="?hapus=<?= $g['id'] ?>">Hapus</a>
</td>
</tr>
<?php } ?>
</table>

<?php
if(isset($_POST['simpan'])){
  mysqli_query($conn,"INSERT INTO guru VALUES(
    NULL,
    '$_POST[nama]',
    '$_POST[mapel]',
    '$_POST[status]'
  )");
  header("Location: guru.php");
}

if(isset($_GET['hapus'])){
  mysqli_query($conn,"DELETE FROM guru WHERE id='$_GET[hapus]'");
  header("Location: guru.php");
}
?>
