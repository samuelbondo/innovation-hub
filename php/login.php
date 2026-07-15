<?php
require 'db.php';
json_headers();

$data       = json_decode(file_get_contents('php://input'), true);
$identifier = trim($data['email'] ?? '');
$password   = $data['password'] ?? '';

if (!$identifier || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email/Registration number and password are required.']);
    exit;
}

// Try login by email first, then by student_id
$stmt = $pdo->prepare('SELECT u.id, u.name, u.email, u.password, u.role, u.photo FROM users u WHERE u.email = ?');
$stmt->execute([$identifier]);
$user = $stmt->fetch();

if (!$user) {
    $s = $pdo->prepare('SELECT email FROM students WHERE student_id = ?');
    $s->execute([$identifier]);
    $row = $s->fetch();
    if ($row) {
        $stmt = $pdo->prepare('SELECT id, name, email, password, role, photo FROM users WHERE email = ?');
        $stmt->execute([$row['email']]);
        $user = $stmt->fetch();
    }
}

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid email or password.']);
    exit;
}

// Block student login if admission not approved or account suspended/inactive
if ($user['role'] === 'student') {
    $adm = $pdo->prepare('SELECT admission_status, student_id, status FROM students WHERE email=?');
    $adm->execute([$user['email']]);
    $adm = $adm->fetch();
    if ($adm) {
        // Must be approved first before anything else
        if ($adm['admission_status'] !== 'Approved') {
            http_response_code(403);
            echo json_encode([
                'error'  => 'Your admission is still ' . $adm['admission_status'] . '. You cannot log in until your application is approved by the administration.',
                'status' => $adm['admission_status'],
                'sid'    => $adm['student_id'],
                'blocked'=> true
            ]);
            exit;
        }
        if ($adm['status'] === 'Suspended') {
            http_response_code(403);
            echo json_encode(['error' => 'Your account has been suspended. Please contact the administration.', 'blocked' => true]);
            exit;
        }
        if ($adm['status'] === 'Inactive') {
            http_response_code(403);
            echo json_encode(['error' => 'Your account is inactive. Please contact the administration.', 'blocked' => true]);
            exit;
        }
    }
}

$photo = null;
if ($user['role'] === 'student') {
    $ps = $pdo->prepare('SELECT photo FROM students WHERE email = ?');
    $ps->execute([$user['email']]);
    $photo = $ps->fetchColumn() ?: null;
} else {
    // admin, staff, teacher — photo stored in users table
    $photo = $user['photo'] ?? null;
}

$dept_id = null;
if ($user['role'] === 'teacher') {
    $dq = $pdo->prepare('SELECT department_id FROM users WHERE id = ?');
    $dq->execute([$user['id']]);
    $dept_id = $dq->fetchColumn() ?: null;
}

echo json_encode([
    'success' => true,
    'user'    => [
        'id'            => $user['id'],
        'name'          => $user['name'],
        'email'         => $user['email'],
        'role'          => $user['role'],
        'photo'         => $photo,
        'department_id' => $dept_id
    ]
]);
