<?php
// admin/get_guru_list.php
header('Content-Type: application/json');
include 'db.php';

$query = "SELECT * FROM guru ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$guruList = [];
while ($row = mysqli_fetch_assoc($result)) {
    $guruList[] = $row;
}

echo json_encode(['success' => true, 'data' => $guruList]);
?>