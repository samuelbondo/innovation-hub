<?php
require 'db.php';
json_headers();

$rows = $pdo->query('
    SELECT d.id, d.name, d.head, d.icon, d.description, f.name AS faculty,
           (SELECT COUNT(*) FROM students s WHERE s.department_id = d.id AND s.status = "Active") AS student_count
    FROM departments d
    LEFT JOIN faculties f ON f.id = d.faculty_id
    ORDER BY f.id, d.name
')->fetchAll();

echo json_encode($rows, JSON_UNESCAPED_UNICODE);
