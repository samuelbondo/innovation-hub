<?php
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// ── GET profile ──────────────────────────────────────────────────────────────
if ($method === 'GET') {
    $id = intval($_GET['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
    $stmt = $pdo->prepare('SELECT id, name, email, phone, role, department_id, photo FROM users WHERE id=?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) { http_response_code(404); echo json_encode(['error'=>'Not found']); exit; }
    // Get department name
    if ($user['department_id']) {
        $d = $pdo->prepare('SELECT name FROM departments WHERE id=?');
        $d->execute([$user['department_id']]);
        $user['department'] = $d->fetchColumn() ?: null;
    } else {
        $user['department'] = null;
    }
    echo json_encode($user, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST update profile ───────────────────────────────────────────────────────
if ($method === 'POST' && empty($_FILES)) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = intval($data['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }

    $name  = trim($data['name']  ?? '');
    $phone = trim($data['phone'] ?? '');
    if (!$name) { http_response_code(400); echo json_encode(['error'=>'Name is required']); exit; }

    $pdo->prepare('UPDATE users SET name=?, phone=? WHERE id=?')->execute([$name, $phone, $id]);

    // Update localStorage name via response
    $stmt = $pdo->prepare('SELECT id, name, email, role, photo FROM users WHERE id=?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    echo json_encode(['success'=>true, 'user'=>$user], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST upload photo ─────────────────────────────────────────────────────────
if ($method === 'POST' && !empty($_FILES)) {
    $id   = intval($_POST['id'] ?? 0);
    $file = $_FILES['photo'] ?? null;
    if (!$id || !$file) { http_response_code(400); echo json_encode(['error'=>'Missing data']); exit; }

    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed)) { http_response_code(400); echo json_encode(['error'=>'Only JPG, PNG or WEBP allowed']); exit; }
    if ($file['size'] > 2 * 1024 * 1024) { http_response_code(400); echo json_encode(['error'=>'File must be under 2MB']); exit; }

    $filename = 'uploads/user_' . $id . '.' . $ext;
    $dest     = dirname(__DIR__) . '/' . $filename;

    // Remove old photos
    foreach ($allowed as $e) {
        $old = dirname(__DIR__) . '/uploads/user_' . $id . '.' . $e;
        if (file_exists($old)) unlink($old);
    }

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        http_response_code(500); echo json_encode(['error'=>'Upload failed']); exit;
    }

    $pdo->prepare('UPDATE users SET photo=? WHERE id=?')->execute([$filename, $id]);
    echo json_encode(['success'=>true, 'photo'=>$filename], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405); echo json_encode(['error'=>'Method not allowed']);
