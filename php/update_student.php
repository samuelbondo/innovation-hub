<?php
require 'db.php';
json_headers();

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'POST') {
    $sid = trim($data['student_id'] ?? '');
    if (!$sid) { http_response_code(400); echo json_encode(['error' => 'Missing student ID']); exit; }

    $dept = $pdo->prepare('SELECT id FROM departments WHERE name = ?');
    $dept->execute([$data['department']]);
    $dept_id = $dept->fetchColumn();
    if (!$dept_id) { http_response_code(400); echo json_encode(['error' => 'Invalid department']); exit; }

    $validStatus = ['Active', 'Inactive', 'Suspended'];
    $status = in_array($data['status'] ?? '', $validStatus) ? $data['status'] : 'Active';

    $pdo->prepare('UPDATE students SET fname=?, lname=?, gender=?, email=?, phone=?, department_id=?, year_of_study=?, dob=?, address=?, status=? WHERE student_id=?')
        ->execute([
            trim($data['fname']),
            trim($data['lname']),
            trim($data['gender'] ?? '') ?: null,
            trim($data['email']),
            trim($data['phone'] ?? ''),
            $dept_id,
            (int)$data['year'],
            $data['dob'] ?: null,
            trim($data['address'] ?? ''),
            $status,
            $sid
        ]);

    $pdo->prepare('UPDATE users SET name=? WHERE email=(SELECT email FROM students WHERE student_id=?) AND role="student"')
        ->execute([trim($data['fname']).' '.trim($data['lname']), $sid]);

    $newPass = trim($data['new_password'] ?? '');
    if ($newPass && strlen($newPass) >= 6) {
        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        $pdo->prepare('UPDATE users SET password=? WHERE email=(SELECT email FROM students WHERE student_id=?) AND role="student"')
            ->execute([$hash, $sid]);
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'DELETE') {
    $sid = trim($data['student_id'] ?? '');
    if (!$sid) { http_response_code(400); echo json_encode(['error' => 'Missing student ID']); exit; }

    $row = $pdo->prepare('SELECT email FROM students WHERE student_id=?');
    $row->execute([$sid]); $email = $row->fetchColumn();

    // Delete documents files
    $docs = $pdo->prepare('SELECT filename FROM student_documents WHERE student_id=?');
    $docs->execute([$sid]);
    foreach ($docs->fetchAll() as $doc) {
        $path = dirname(__DIR__) . '/' . $doc['filename'];
        if (file_exists($path)) unlink($path);
    }
    $pdo->prepare('DELETE FROM student_documents WHERE student_id=?')->execute([$sid]);
    $pdo->prepare('DELETE FROM students WHERE student_id=?')->execute([$sid]);
    if ($email) $pdo->prepare('DELETE FROM users WHERE email=? AND role="student"')->execute([$email]);

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405); echo json_encode(['error' => 'Method not allowed']);
