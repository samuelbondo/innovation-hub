<?php
require 'db.php';
json_headers();

$faculties = $pdo->query('SELECT id, name, name AS abbreviation, dean FROM faculties ORDER BY id')->fetchAll();

foreach ($faculties as &$f) {
    $stmt = $pdo->prepare('SELECT id, name, head, description,
        (SELECT COUNT(*) FROM students s WHERE s.department_id = d.id AND s.status = "Active") AS student_count
        FROM departments d WHERE d.faculty_id = ? ORDER BY d.name');
    $stmt->execute([$f['id']]);
    $f['departments'] = $stmt->fetchAll();
}

echo json_encode($faculties, JSON_UNESCAPED_UNICODE);
