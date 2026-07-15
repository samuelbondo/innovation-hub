<?php
require 'db.php';
json_headers();

$action = $_GET['action'] ?? (json_decode(file_get_contents('php://input'), true)['action'] ?? '');

// ── Public: list slides ───────────────────────────────────────────────────────
if ($action === 'list') {
    $rows = $pdo->query('SELECT id, src_type, src, caption FROM slideshow_images ORDER BY sort_order, id')->fetchAll();
    echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ── Admin-only actions below ──────────────────────────────────────────────────
$data  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim($data['email'] ?? '');

if ($action !== 'upload') { // upload sends multipart, email checked separately
    $u = $pdo->prepare('SELECT role FROM users WHERE email=?');
    $u->execute([$email]);
    $role = $u->fetchColumn();
    if (!in_array($role, ['admin','staff'])) {
        http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit;
    }
}

// ── Add by URL ────────────────────────────────────────────────────────────────
if ($action === 'add_url') {
    $src = trim($data['src'] ?? '');
    if (!$src) { http_response_code(400); echo json_encode(['error'=>'URL required']); exit; }
    $max = $pdo->query('SELECT COALESCE(MAX(sort_order),0)+1 FROM slideshow_images')->fetchColumn();
    $pdo->prepare('INSERT INTO slideshow_images (src_type, src, caption, sort_order) VALUES (?,?,?,?)')
        ->execute(['url', $src, trim($data['caption'] ?? ''), $max]);
    echo json_encode(['success'=>true, 'id'=>$pdo->lastInsertId()]);

// ── Upload image ──────────────────────────────────────────────────────────────
} elseif ($action === 'upload') {
    $email = trim($_POST['email'] ?? '');
    $u = $pdo->prepare('SELECT role FROM users WHERE email=?');
    $u->execute([$email]);
    $role = $u->fetchColumn();
    if (!in_array($role, ['admin','staff'])) {
        http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit;
    }
    $file = $_FILES['image'] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400); echo json_encode(['error'=>'Upload failed']); exit;
    }
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    if (!in_array($file['type'], $allowed)) {
        http_response_code(400); echo json_encode(['error'=>'Only JPG, PNG, GIF, WEBP allowed']); exit;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        http_response_code(400); echo json_encode(['error'=>'Max 5MB']); exit;
    }
    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = 'slide_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
    $dest = '../uploads/slides/' . $name;
    if (!is_dir('../uploads/slides')) mkdir('../uploads/slides', 0755, true);
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        http_response_code(500); echo json_encode(['error'=>'Could not save file']); exit;
    }
    $src = 'uploads/slides/' . $name;
    $max = $pdo->query('SELECT COALESCE(MAX(sort_order),0)+1 FROM slideshow_images')->fetchColumn();
    $pdo->prepare('INSERT INTO slideshow_images (src_type, src, caption, sort_order) VALUES (?,?,?,?)')
        ->execute(['upload', $src, trim($_POST['caption'] ?? ''), $max]);
    echo json_encode(['success'=>true, 'id'=>$pdo->lastInsertId(), 'src'=>$src]);

// ── Delete ────────────────────────────────────────────────────────────────────
} elseif ($action === 'delete') {
    $id = intval($data['id']);
    // Remove file if uploaded
    $row = $pdo->prepare('SELECT src_type, src FROM slideshow_images WHERE id=?');
    $row->execute([$id]);
    $slide = $row->fetch();
    if ($slide && $slide['src_type'] === 'upload' && file_exists('../' . $slide['src'])) {
        unlink('../' . $slide['src']);
    }
    $pdo->prepare('DELETE FROM slideshow_images WHERE id=?')->execute([$id]);
    echo json_encode(['success'=>true]);

// ── Reorder ───────────────────────────────────────────────────────────────────
} elseif ($action === 'reorder') {
    // $data['order'] = [id, id, id, ...] in new order
    $stmt = $pdo->prepare('UPDATE slideshow_images SET sort_order=? WHERE id=?');
    foreach (($data['order'] ?? []) as $i => $id) {
        $stmt->execute([$i, intval($id)]);
    }
    echo json_encode(['success'=>true]);

} else {
    http_response_code(400); echo json_encode(['error'=>'Unknown action']);
}
