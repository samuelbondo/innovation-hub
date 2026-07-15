<?php
require 'db.php';
json_headers();

$email = trim($_GET['email'] ?? '');
$role  = trim($_GET['role']  ?? '');

if ($email && $role === 'student') {
    $stmt = $pdo->prepare('SELECT s.student_id, s.fname, s.lname, s.email, s.phone,
        s.year_of_study, s.dob, s.address, s.status, s.admission_status, s.photo, s.created_at,
        d.name AS department, d.id AS department_id, f.name AS faculty
        FROM students s
        JOIN departments d ON d.id = s.department_id
        LEFT JOIN faculties f ON f.id = d.faculty_id
        WHERE s.email = ?');
    $stmt->execute([$email]);
    $student = $stmt->fetch();
    $courses = [];
    if ($student) {
        $cs = $pdo->prepare('
            SELECT c.code, c.title, c.credits, c.semester,
                   u.name AS teacher_name
            FROM student_enrollments se
            JOIN courses c ON c.id = se.course_id
            LEFT JOIN teacher_courses tc ON tc.course_id = c.id
            LEFT JOIN users u ON u.id = tc.teacher_id
            WHERE se.student_id = ?
            ORDER BY c.semester, c.code');
        $cs->execute([$student['student_id']]);
        $courses = $cs->fetchAll();
    }
    echo json_encode(['student' => $student, 'courses' => $courses], JSON_UNESCAPED_UNICODE);

} elseif ($email && $role === 'teacher') {
    $u = $pdo->prepare('SELECT id, name, email, phone, department_id FROM users WHERE email = ?');
    $u->execute([$email]);
    $teacher = $u->fetch();
    $myCourses = []; $stats = []; $recentStudents = [];

    if ($teacher) {
        $cs = $pdo->prepare('
            SELECT c.id, c.code, c.title, c.credits, c.year_level, c.semester,
                   d.name AS department,
                   (SELECT COUNT(*) FROM student_enrollments se WHERE se.course_id = c.id) AS student_count
            FROM teacher_courses tc
            JOIN courses c ON c.id = tc.course_id
            JOIN departments d ON d.id = c.department_id
            WHERE tc.teacher_id = ?
            ORDER BY c.year_level, c.code');
        $cs->execute([$teacher['id']]);
        $myCourses = $cs->fetchAll();

        $stats['my_courses']     = count($myCourses);
        $stats['dept_students']  = 0;
        $stats['dept_courses']   = 0;
        $stats['total_enrolled'] = array_sum(array_column($myCourses, 'student_count'));

        if ($teacher['department_id']) {
            $q = $pdo->prepare('SELECT COUNT(*) FROM students WHERE department_id = ?');
            $q->execute([$teacher['department_id']]);
            $stats['dept_students'] = (int)$q->fetchColumn();

            $q2 = $pdo->prepare('SELECT COUNT(*) FROM courses WHERE department_id = ?');
            $q2->execute([$teacher['department_id']]);
            $stats['dept_courses'] = (int)$q2->fetchColumn();

            $rs = $pdo->prepare('SELECT s.student_id, s.fname, s.lname, s.year_of_study, s.status, s.photo
                FROM students s WHERE s.department_id = ?
                ORDER BY s.status ASC, s.created_at DESC LIMIT 8');
            $rs->execute([$teacher['department_id']]);
            $recentStudents = $rs->fetchAll();
        }
    }
    echo json_encode([
        'teacher'         => $teacher,
        'courses'         => $myCourses,
        'stats'           => $stats,
        'recent_students' => $recentStudents
    ], JSON_UNESCAPED_UNICODE);

} else {
    $total     = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $active    = $pdo->query('SELECT COUNT(*) FROM students WHERE status = "Active"')->fetchColumn();
    $inactive  = $pdo->query('SELECT COUNT(*) FROM students WHERE status = "Inactive"')->fetchColumn();
    $suspended = $pdo->query('SELECT COUNT(*) FROM students WHERE status = "Suspended"')->fetchColumn();
    $pending   = $pdo->query('SELECT COUNT(*) FROM students WHERE admission_status IN ("Pending","Under Review")')->fetchColumn();
    $depts     = $pdo->query('SELECT COUNT(*) FROM departments')->fetchColumn();
    $faculties = $pdo->query('SELECT COUNT(*) FROM faculties')->fetchColumn();
    $users     = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $courses   = $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
    $teachers  = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "teacher"')->fetchColumn();
    $recent    = $pdo->query('SELECT s.student_id, s.fname, s.lname, d.name AS department,
        s.status, s.admission_status, s.created_at
        FROM students s JOIN departments d ON d.id = s.department_id
        ORDER BY s.created_at DESC LIMIT 5')->fetchAll();
    echo json_encode(compact(
        'total','active','inactive','suspended','pending',
        'depts','faculties','users','courses','teachers','recent'
    ), JSON_UNESCAPED_UNICODE);
}
