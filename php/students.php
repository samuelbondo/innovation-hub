<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $count = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $next  = 'STU-' . date('Y') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    echo json_encode(['student_id' => $next]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['dob']) || $data['dob'] >= date('Y-m-d')) {
        http_response_code(400);
        echo json_encode(['error' => 'Date of birth must be before today.']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id FROM departments WHERE name = ?');
    $stmt->execute([$data['department']]);
    $dept = $stmt->fetchColumn();
    if (!$dept) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid department.']);
        exit;
    }

    // Check duplicate email
    $chk = $pdo->prepare('SELECT id FROM students WHERE email=?');
    $chk->execute([trim($data['email'])]);
    if ($chk->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'This email is already registered.']);
        exit;
    }

    $pdo->exec('LOCK TABLES students WRITE, users WRITE');
    $count = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $sid   = 'STU-' . date('Y') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

    // Admin-added students are immediately Active + Approved
    $pdo->prepare('INSERT INTO students
        (student_id, fname, lname, gender, email, phone, department_id, year_of_study, dob, address, status, admission_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
        ->execute([
            $sid,
            trim($data['fname']),
            trim($data['lname']),
            trim($data['gender'] ?? '') ?: null,
            trim($data['email']),
            trim($data['phone'] ?? ''),
            $dept,
            (int)$data['year'],
            $data['dob'],
            trim($data['address'] ?? ''),
            'Active',
            'Approved'
        ]);

    // Create login account with default password student123
    $hash = password_hash('student123', PASSWORD_BCRYPT);
    $pdo->prepare('INSERT IGNORE INTO users (name, email, password, role) VALUES (?,?,?,?)')
        ->execute([trim($data['fname']).' '.trim($data['lname']), trim($data['email']), $hash, 'student']);

    $pdo->exec('UNLOCK TABLES');

    echo json_encode(['success' => true, 'student_id' => $sid]);
}
