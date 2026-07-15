<?php
require 'db.php';
json_headers();

$data   = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $data['action'] ?? ($_GET['action'] ?? '');

// ── Admin: save a window ──────────────────────────────────────────────────────
if ($action === 'save') {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE email=?");
    $stmt->execute([$data['email'] ?? '']);
    $user = $stmt->fetch();
    if (!$user || !in_array($user['role'], ['admin','staff'])) {
        http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit;
    }
    $stmt2 = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt2->execute([$data['email']]);
    $uid      = $stmt2->fetchColumn();
    $courseId = !empty($data['course_id']) ? intval($data['course_id']) : null;
    $from     = $data['open_from']  ?? '';
    $until    = $data['open_until'] ?? '';
    if (!$from || !$until) { http_response_code(400); echo json_encode(['error'=>'Dates required']); exit; }
    $pdo->prepare("
        INSERT INTO enrollment_windows (course_id, open_from, open_until, created_by)
        VALUES (?,?,?,?)
        ON DUPLICATE KEY UPDATE open_from=VALUES(open_from), open_until=VALUES(open_until), created_by=VALUES(created_by)
    ")->execute([$courseId, $from, $until, $uid]);
    echo json_encode(['success' => true]);

// ── Admin: delete a window ────────────────────────────────────────────────────
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE email=?");
    $stmt->execute([$data['email'] ?? '']);
    $user = $stmt->fetch();
    if (!$user || !in_array($user['role'], ['admin','staff'])) {
        http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit;
    }
    $courseId = !empty($data['course_id']) ? intval($data['course_id']) : null;
    if ($courseId) {
        $pdo->prepare("DELETE FROM enrollment_windows WHERE course_id=?")->execute([$courseId]);
    } else {
        $pdo->prepare("DELETE FROM enrollment_windows WHERE course_id IS NULL")->execute();
    }
    echo json_encode(['success' => true]);

// ── Admin: list all windows ───────────────────────────────────────────────────
} elseif ($action === 'list') {
    $rows = $pdo->query("
        SELECT ew.*, c.code, c.title
        FROM enrollment_windows ew
        LEFT JOIN courses c ON c.id = ew.course_id
        ORDER BY ew.course_id IS NULL DESC, c.code
    ")->fetchAll();
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// ── Check: is enrollment open? ────────────────────────────────────────────────
} elseif ($action === 'check') {
    $courseId = intval($_GET['course_id'] ?? 0);
    $now      = date('Y-m-d H:i:s');
    if ($courseId) {
        $stmt = $pdo->prepare('SELECT id, open_from, open_until FROM enrollment_windows WHERE course_id=?');
        $stmt->execute([$courseId]);
        $w = $stmt->fetch();
        if ($w) {
            echo json_encode(['open' => ($now >= $w['open_from'] && $now <= $w['open_until']), 'window' => $w]);
            exit;
        }
    }
    $w = $pdo->query('SELECT id, open_from, open_until FROM enrollment_windows WHERE course_id IS NULL')->fetch();
    if ($w) {
        echo json_encode(['open' => ($now >= $w['open_from'] && $now <= $w['open_until']), 'window' => $w, 'global' => true]);
    } else {
        echo json_encode(['open' => false, 'window' => null]);
    }

// ── Check: has student already self-enrolled in the current window? ───────────
} elseif ($action === 'self_check') {
    $studentId = trim($_GET['student_id'] ?? '');
    $w = $pdo->query('SELECT id FROM enrollment_windows WHERE course_id IS NULL AND open_from <= NOW() AND open_until >= NOW()')->fetch();
    if (!$w || !$studentId) { echo json_encode(['enrolled' => false]); exit; }
    $chk = $pdo->prepare('SELECT COUNT(*) FROM student_enrollments WHERE student_id=? AND window_id=? AND enrolled_by="self"');
    $chk->execute([$studentId, $w['id']]);
    echo json_encode(['enrolled' => (int)$chk->fetchColumn() > 0]);

} else {
    http_response_code(400); echo json_encode(['error' => 'Unknown action']);
}
