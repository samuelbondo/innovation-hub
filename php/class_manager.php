<?php
require 'db.php';
json_headers();

$data   = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $data['action'] ?? ($_GET['action'] ?? '');
$email  = trim($data['email'] ?? ($_GET['email'] ?? ''));

if (!$email) { http_response_code(401); echo json_encode(['error' => 'Unauthorized']); exit; }

// Auth — check users table first, then students table
$caller = $pdo->prepare('SELECT id, role FROM users WHERE email = ?');
$caller->execute([$email]);
$caller = $caller->fetch();
if (!$caller) {
    $sc = $pdo->prepare('SELECT student_id AS id, "student" AS role FROM students WHERE email = ?');
    $sc->execute([$email]);
    $caller = $sc->fetch();
}
if (!$caller) { http_response_code(403); echo json_encode(['error' => 'Forbidden']); exit; }

$uid       = $caller['id'];
$role      = $caller['role'];
$isTeacher = in_array($role, ['teacher','admin','staff']);

// ── GET announcements ────────────────────────────────────────────────────────
if ($action === 'get_announcements') {
    $stmt = $pdo->prepare('SELECT a.id, a.title, a.body, a.created_at, u.name AS teacher_name
        FROM course_announcements a JOIN users u ON u.id = a.teacher_id
        WHERE a.course_id = ? ORDER BY a.created_at DESC');
    $stmt->execute([$data['course_id'] ?? $_GET['course_id']]);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);

// ── POST announcement (teachers only) ───────────────────────────────────────
} elseif ($action === 'post_announcement') {
    if (!$isTeacher) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }
    $stmt = $pdo->prepare('INSERT INTO course_announcements (course_id, teacher_id, title, body) VALUES (?,?,?,?)');
    $stmt->execute([$data['course_id'], $uid, trim($data['title']), trim($data['body'])]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);

// ── DELETE announcement ──────────────────────────────────────────────────────
} elseif ($action === 'delete_announcement') {
    if (!$isTeacher) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }
    $stmt = $pdo->prepare('DELETE FROM course_announcements WHERE id = ? AND teacher_id = ?');
    $stmt->execute([$data['id'], $uid]);
    echo json_encode(['success' => true]);

// ── GET enrolled students + grades for a course ──────────────────────────────
} elseif ($action === 'get_grades') {
    $cid = $data['course_id'] ?? $_GET['course_id'];
    $stmt = $pdo->prepare('
        SELECT s.student_id, s.fname, s.lname, s.photo,
               g.id AS grade_id, g.score, g.grade, g.remark
        FROM student_enrollments se
        JOIN students s ON s.student_id = se.student_id
        LEFT JOIN course_grades g ON g.course_id = se.course_id AND g.student_id = se.student_id
        WHERE se.course_id = ?
        ORDER BY s.lname, s.fname');
    $stmt->execute([$cid]);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);

// ── SAVE grade ───────────────────────────────────────────────────────────────
} elseif ($action === 'save_grade') {
    if (!$isTeacher) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }
    $stmt = $pdo->prepare('INSERT INTO course_grades (course_id, student_id, score, grade, remark)
        VALUES (?,?,?,?,?)
        ON DUPLICATE KEY UPDATE score=VALUES(score), grade=VALUES(grade), remark=VALUES(remark)');
    $stmt->execute([
        $data['course_id'], $data['student_id'],
        $data['score'] !== '' ? $data['score'] : null,
        trim($data['grade'] ?? ''),
        trim($data['remark'] ?? '')
    ]);
    echo json_encode(['success' => true]);

} else {
    http_response_code(400); echo json_encode(['error' => 'Unknown action']);
}
