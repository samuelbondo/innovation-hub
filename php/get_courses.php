<?php
require 'db.php';
json_headers();

$dept_id       = intval($_GET['dept_id']       ?? 0);
$teacher_id    = intval($_GET['teacher_id']    ?? 0);
$student_email = trim($_GET['student_email']   ?? '');

if ($student_email) {
    // Courses a student is enrolled in
    $stmt = $pdo->prepare('
        SELECT c.id, c.code, c.title, c.credits, c.year_level, c.semester, c.description,
               d.name AS department, f.name AS faculty,
               u.id AS teacher_id, u.name AS teacher_name, u.email AS teacher_email
        FROM student_enrollments se
        JOIN courses c ON c.id = se.course_id
        JOIN departments d ON d.id = c.department_id
        LEFT JOIN faculties f ON f.id = d.faculty_id
        LEFT JOIN teacher_courses tc ON tc.course_id = c.id
        LEFT JOIN users u ON u.id = tc.teacher_id
        JOIN students s ON s.student_id = se.student_id
        WHERE s.email = ?
        ORDER BY c.semester, c.code');
    $stmt->execute([$student_email]);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($teacher_id) {
    $stmt = $pdo->prepare('
        SELECT c.id, c.code, c.title, c.credits, c.year_level, c.semester, c.description,
               d.name AS department, f.name AS faculty,
               (SELECT COUNT(*) FROM student_enrollments se WHERE se.course_id = c.id) AS student_count
        FROM teacher_courses tc
        JOIN courses c ON c.id = tc.course_id
        JOIN departments d ON d.id = c.department_id
        LEFT JOIN faculties f ON f.id = d.faculty_id
        WHERE tc.teacher_id = ?
        ORDER BY c.year_level, c.semester, c.code');
    $stmt->execute([$teacher_id]);
} elseif ($dept_id) {
    $stmt = $pdo->prepare('
        SELECT c.id, c.code, c.title, c.credits, c.year_level, c.semester, c.description,
               d.name AS department,
               u.id AS teacher_id, u.name AS teacher_name,
               (SELECT COUNT(*) FROM student_enrollments se WHERE se.course_id = c.id) AS student_count
        FROM courses c
        JOIN departments d ON d.id = c.department_id
        LEFT JOIN teacher_courses tc ON tc.course_id = c.id
        LEFT JOIN users u ON u.id = tc.teacher_id
        WHERE c.department_id = ?
        ORDER BY c.year_level, c.semester, c.code');
    $stmt->execute([$dept_id]);
} else {
    $stmt = $pdo->query('
        SELECT c.id, c.code, c.title, c.credits, c.year_level, c.semester,
               d.name AS department, f.name AS faculty,
               u.id AS teacher_id, u.name AS teacher_name,
               (SELECT COUNT(*) FROM student_enrollments se WHERE se.course_id = c.id) AS student_count
        FROM courses c
        JOIN departments d ON d.id = c.department_id
        LEFT JOIN faculties f ON f.id = d.faculty_id
        LEFT JOIN teacher_courses tc ON tc.course_id = c.id
        LEFT JOIN users u ON u.id = tc.teacher_id
        ORDER BY f.id, d.name, c.year_level, c.code');
}

echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
