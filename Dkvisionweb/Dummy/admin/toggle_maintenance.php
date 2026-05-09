<?php
// admin/toggle_maintenance.php
session_start();
header('Content-Type: application/json');

// Cek login admin
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Baca request
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

// File penyimpanan status maintenance (di luar folder public agar aman)
$statusFile = __DIR__ . '/../config/maintenance_status.json';

if ($action === 'set') {
    $value = $input['value'] ?? false;
    file_put_contents($statusFile, json_encode([
        'maintenance' => $value,
        'updated_at' => date('Y-m-d H:i:s')
    ]));
    echo json_encode(['success' => true]);
} 
elseif ($action === 'get') {
    if (file_exists($statusFile)) {
        $data = json_decode(file_get_contents($statusFile), true);
        echo json_encode(['success' => true, 'maintenance' => $data['maintenance'] ?? false]);
    } else {
        echo json_encode(['success' => true, 'maintenance' => false]);
    }
}
?>