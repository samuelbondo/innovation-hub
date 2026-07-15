<?php
require 'db.php';
json_headers();

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('
        SELECT s.*, d.name AS department, f.name AS faculty
        FROM students s
        JOIN departments d ON d.id = s.department_id
        LEFT JOIN faculties f ON f.id = d.faculty_id
        WHERE s.student_id = ?');
    $stmt->execute([$_GET['id']]);
    $row = $stmt->fetch();
    echo json_encode($row ?: ['error' => 'Not found'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_GET['email'])) {
    $stmt = $pdo->prepare('
        SELECT s.student_id, s.fname, s.lname, s.email, s.phone,
               d.name AS department, d.id AS department_id,
               s.year_of_study, s.dob, s.address,
               s.status, s.admission_status, s.photo, s.created_at
        FROM students s
        JOIN departments d ON d.id = s.department_id
        WHERE s.email = ?');
    $stmt->execute([trim($_GET['email'])]);
    $row = $stmt->fetch();
    echo json_encode($row ?: ['error' => 'Not found'], JSON_UNESCAPED_UNICODE);
    exit;
}

$rows = $pdo->query('
    SELECT s.student_id, s.fname, s.lname, s.email, s.phone,
           d.name AS department, d.id AS department_id,
           s.year_of_study, s.dob, s.address,
           s.status, s.admission_status, s.photo, s.created_at
    FROM students s
    JOIN departments d ON d.id = s.department_id
    ORDER BY s.id ASC
')->fetchAll();

echo json_encode($rows, JSON_UNESCAPED_UNICODE);
