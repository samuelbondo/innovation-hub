<?php
require 'db.php';
json_headers();

$student_id = trim($_POST['student_id'] ?? '');

if (!$student_id || empty($_FILES['photo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing data.']); exit;
}

$file     = $_FILES['photo'];
$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed  = ['jpg','jpeg','png','webp'];

if (!in_array($ext, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Only JPG, PNG or WEBP allowed.']); exit;
}

if ($file['size'] > 2 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'File must be under 2MB.']); exit;
}

$filename = 'uploads/' . preg_replace('/[^a-zA-Z0-9\-]/', '_', $student_id) . '.' . $ext;
$dest     = dirname(__DIR__) . '/' . $filename;

// Remove old photo if different extension
foreach ($allowed as $e) {
    $old = dirname(__DIR__) . '/uploads/' . preg_replace('/[^a-zA-Z0-9\-]/', '_', $student_id) . '.' . $e;
    if (file_exists($old)) unlink($old);
}

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Upload failed.']); exit;
}

$stmt = $pdo->prepare('UPDATE students SET photo = ? WHERE student_id = ?');
$stmt->execute([$filename, $student_id]);

echo json_encode(['success' => true, 'photo' => $filename]);
