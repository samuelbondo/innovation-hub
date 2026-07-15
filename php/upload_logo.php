<?php
require 'db.php';
json_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error' => 'Method not allowed']); exit;
}

$file = $_FILES['logo'] ?? null;
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400); echo json_encode(['error' => 'No file uploaded.']); exit;
}

$allowed = ['image/png','image/jpeg','image/gif','image/svg+xml','image/webp'];
if (!in_array($file['type'], $allowed)) {
    http_response_code(400); echo json_encode(['error' => 'Only PNG, JPG, GIF, SVG, WEBP allowed.']); exit;
}
if ($file['size'] > 2 * 1024 * 1024) {
    http_response_code(400); echo json_encode(['error' => 'Max file size is 2MB.']); exit;
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'logo.' . strtolower($ext);
$dest     = __DIR__ . '/../uploads/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500); echo json_encode(['error' => 'Failed to save file.']); exit;
}

$path = 'uploads/' . $filename;
$stmt = $pdo->prepare('INSERT INTO settings (k,v) VALUES (?,?) ON DUPLICATE KEY UPDATE v=VALUES(v)');
$stmt->execute(['system_logo', $path]);

echo json_encode(['success' => true, 'path' => $path]);
