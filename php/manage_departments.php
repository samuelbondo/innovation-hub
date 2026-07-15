<?php
require 'db.php';
json_headers();

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents('php://input'), true) ?? [];

// GET single department detail
if ($method === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $dept = $pdo->prepare('SELECT d.*, f.name AS faculty FROM departments d LEFT JOIN faculties f ON f.id=d.faculty_id WHERE d.id=?');
    $dept->execute([$id]); $dept = $dept->fetch();
    if (!$dept) { http_response_code(404); echo json_encode(['error'=>'Not found']); exit; }

    $students = $pdo->prepare('SELECT student_id, fname, lname, email, year_of_study, status, admission_status, photo FROM students WHERE department_id=? ORDER BY fname');
    $students->execute([$id]); $students = $students->fetchAll();

    $courses = $pdo->prepare('SELECT c.*, u.name AS teacher FROM courses c LEFT JOIN teacher_courses tc ON tc.course_id=c.id LEFT JOIN users u ON u.id=tc.teacher_id WHERE c.department_id=? ORDER BY c.year_level, c.semester, c.code');
    $courses->execute([$id]); $courses = $courses->fetchAll();

    $teachers = $pdo->prepare('SELECT id, name, email FROM users WHERE role="teacher" AND department_id=?');
    $teachers->execute([$id]); $teachers = $teachers->fetchAll();

    echo json_encode(compact('dept','students','courses','teachers'), JSON_UNESCAPED_UNICODE);
    exit;
}

// GET all faculties for dropdown
if ($method === 'GET' && isset($_GET['faculties'])) {
    echo json_encode($pdo->query('SELECT id, name FROM faculties ORDER BY name')->fetchAll(), JSON_UNESCAPED_UNICODE);
    exit;
}

// POST — create department
if ($method === 'POST') {
    $name   = trim($data['name'] ?? '');
    $head   = trim($data['head'] ?? '');
    $desc   = trim($data['description'] ?? '');
    $fid    = (int)($data['faculty_id'] ?? 0);
    $icon   = trim($data['icon'] ?? '🏫');
    if (!$name || !$head) { http_response_code(400); echo json_encode(['error'=>'Name and head are required']); exit; }
    $stmt = $pdo->prepare('INSERT INTO departments (name,head,description,faculty_id,icon) VALUES (?,?,?,?,?)');
    $stmt->execute([$name,$head,$desc,$fid ?: null,$icon]);
    echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]);
    exit;
}

// PUT — update department
if ($method === 'PUT') {
    $id   = (int)($data['id'] ?? 0);
    $name = trim($data['name'] ?? '');
    $head = trim($data['head'] ?? '');
    $desc = trim($data['description'] ?? '');
    $fid  = (int)($data['faculty_id'] ?? 0);
    $icon = trim($data['icon'] ?? '🏫');
    if (!$id || !$name || !$head) { http_response_code(400); echo json_encode(['error'=>'Missing fields']); exit; }
    $pdo->prepare('UPDATE departments SET name=?,head=?,description=?,faculty_id=?,icon=? WHERE id=?')
        ->execute([$name,$head,$desc,$fid ?: null,$icon,$id]);
    echo json_encode(['success'=>true]);
    exit;
}

// DELETE
if ($method === 'DELETE') {
    $id = (int)($data['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
    // Check if students exist
    $count = $pdo->prepare('SELECT COUNT(*) FROM students WHERE department_id=?');
    $count->execute([$id]);
    if ($count->fetchColumn() > 0) { http_response_code(409); echo json_encode(['error'=>'Cannot delete: department has enrolled students']); exit; }
    $pdo->prepare('DELETE FROM departments WHERE id=?')->execute([$id]);
    echo json_encode(['success'=>true]);
    exit;
}

http_response_code(405); echo json_encode(['error'=>'Method not allowed']);
