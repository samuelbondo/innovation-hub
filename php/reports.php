<?php
require 'db.php';
json_headers();

// Server-side guard: only admin or staff may access reports
$reqEmail = trim($_SERVER['HTTP_X_USER_EMAIL'] ?? $_GET['_email'] ?? '');
if ($reqEmail) {
    $chk = $pdo->prepare('SELECT role FROM users WHERE email = ?');
    $chk->execute([$reqEmail]);
    $chkRole = $chk->fetchColumn();
    if (!in_array($chkRole, ['admin', 'staff'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied.']);
        exit;
    }
}

$type = $_GET['type'] ?? '';

switch ($type) {

    case 'students':
        $dept   = $_GET['dept']   ?? '';
        $year   = $_GET['year']   ?? '';
        $status = $_GET['status'] ?? '';

        $sql    = 'SELECT s.student_id, s.fname, s.lname, s.gender, s.email, s.phone,
                          d.name AS department, s.year_of_study, s.dob,
                          s.status, s.admission_status, s.created_at
                   FROM students s
                   JOIN departments d ON d.id = s.department_id
                   WHERE 1=1';
        $params = [];
        if ($dept)   { $sql .= ' AND d.name = ?';          $params[] = $dept; }
        if ($year)   { $sql .= ' AND s.year_of_study = ?'; $params[] = $year; }
        if ($status) { $sql .= ' AND s.status = ?';        $params[] = $status; }
        $sql .= ' ORDER BY s.created_at DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
        break;

    case 'enrollments':
        // All students with their enrolled courses
        $dept   = $_GET['dept']   ?? '';
        $year   = $_GET['year']   ?? '';

        $sql = 'SELECT s.student_id, s.fname, s.lname, s.email,
                       d.name AS department, s.year_of_study,
                       c.code AS course_code, c.title AS course_title,
                       c.credits, c.year_level, c.semester,
                       u.name AS teacher_name
                FROM student_enrollments se
                JOIN students s ON s.student_id = se.student_id
                JOIN courses c ON c.id = se.course_id
                JOIN departments d ON d.id = s.department_id
                LEFT JOIN teacher_courses tc ON tc.course_id = c.id
                LEFT JOIN users u ON u.id = tc.teacher_id
                WHERE s.admission_status = "Approved"';
        $params = [];
        if ($dept) { $sql .= ' AND d.name = ?'; $params[] = $dept; }
        if ($year) { $sql .= ' AND s.year_of_study = ?'; $params[] = $year; }
        $sql .= ' ORDER BY d.name, s.lname, s.fname, c.year_level, c.semester, c.code';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
        break;

    case 'course_summary':
        // Each course with enrolled student count, teacher, dept
        $dept = $_GET['dept'] ?? '';
        $sql = 'SELECT c.code, c.title, c.credits, c.year_level, c.semester,
                       d.name AS department, f.name AS faculty,
                       u.name AS teacher_name,
                       COUNT(se.student_id) AS enrolled_count
                FROM courses c
                JOIN departments d ON d.id = c.department_id
                LEFT JOIN faculties f ON f.id = d.faculty_id
                LEFT JOIN teacher_courses tc ON tc.course_id = c.id
                LEFT JOIN users u ON u.id = tc.teacher_id
                LEFT JOIN student_enrollments se ON se.course_id = c.id
                WHERE 1=1';
        $params = [];
        if ($dept) { $sql .= ' AND d.name = ?'; $params[] = $dept; }
        $sql .= ' GROUP BY c.id ORDER BY d.name, c.year_level, c.semester, c.code';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
        break;

    case 'departments':
        $rows = $pdo->query('
            SELECT d.name, d.head, d.icon, f.name AS faculty,
                   (SELECT COUNT(*) FROM students s WHERE s.department_id = d.id) AS total_students,
                   (SELECT COUNT(*) FROM students s WHERE s.department_id = d.id AND s.status="Active") AS active_students,
                   (SELECT COUNT(DISTINCT se.student_id) FROM student_enrollments se JOIN students s ON s.student_id=se.student_id WHERE s.department_id=d.id) AS enrolled_students,
                   (SELECT COUNT(*) FROM courses c WHERE c.department_id = d.id) AS total_courses
            FROM departments d
            LEFT JOIN faculties f ON f.id = d.faculty_id
            ORDER BY f.name, d.name
        ')->fetchAll();
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        break;

    case 'faculties':
        $rows = $pdo->query('
            SELECT f.name AS faculty, f.dean,
                   COUNT(DISTINCT d.id) AS dept_count,
                   COUNT(DISTINCT s.id) AS total_students,
                   SUM(s.status="Active") AS active_students
            FROM faculties f
            LEFT JOIN departments d ON d.faculty_id = f.id
            LEFT JOIN students s ON s.department_id = d.id
            GROUP BY f.id
            ORDER BY f.name
        ')->fetchAll();
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        break;

    case 'admissions':
        $rows = $pdo->query('
            SELECT s.student_id, s.fname, s.lname, s.gender, s.email,
                   d.name AS department, s.year_of_study,
                   s.admission_status, s.created_at
            FROM students s
            JOIN departments d ON d.id = s.department_id
            ORDER BY s.created_at DESC
        ')->fetchAll();
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid report type']);
}
