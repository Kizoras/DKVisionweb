<?php
/**
 * api_data.php — DKV SMKN 1 Cibinong
 * API penyimpanan data pengganti localStorage.
 * Letakkan di root folder (sejajar app.js, admin/, user/)
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Folder tempat data disimpan
$DATA_DIR = __DIR__ . '/data/';
if (!is_dir($DATA_DIR)) {
    mkdir($DATA_DIR, 0755, true);
}

// Sanitasi key: hanya huruf, angka, underscore, strip
function safeKey($key) {
    return preg_replace('/[^a-zA-Z0-9_\-]/', '_', substr((string)$key, 0, 64));
}

$key = safeKey($_GET['key'] ?? '');

if (empty($key)) {
    echo json_encode(['error' => 'key required']);
    exit;
}

$file = $DATA_DIR . $key . '.json';

// GET → baca nilai
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!file_exists($file)) {
        echo json_encode(['value' => null]);
    } else {
        $value = file_get_contents($file);
        echo json_encode(['value' => $value]);
    }
    exit;
}

// POST → simpan nilai
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data === null) {
        echo json_encode(['error' => 'invalid JSON body']);
        exit;
    }

    // Jika value null → hapus file (DELETE semantics)
    if (!isset($data['value']) || $data['value'] === null) {
        if (file_exists($file)) unlink($file);
        echo json_encode(['ok' => true]);
        exit;
    }

    file_put_contents($file, (string)$data['value']);
    echo json_encode(['ok' => true]);
    exit;
}

echo json_encode(['error' => 'method not allowed']);