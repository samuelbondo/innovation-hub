<?php
require 'db.php';
json_headers();

$data   = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

if ($action === 'assign') {
    $stmt = $pdo->prepare('INSERT IGNORE INTO teacher_courses (teacher_id, course_id) VALUES (?,?)');
    $stmt->execute([$data['teacher_id'], $data['course_id']]);
    echo json_encode(['success' => true]);

} elseif ($action === 'unassign') {
    $stmt = $pdo->prepare('DELETE FROM teacher_courses WHERE teacher_id=? AND course_id=?');
    $stmt->execute([$data['teacher_id'], $data['course_id']]);
    echo json_encode(['success' => true]);

} elseif ($action === 'add_course') {
    try {
        $stmt = $pdo->prepare('INSERT INTO courses (code,title,credits,department_id,year_level,semester,description) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$data['code'],$data['title'],$data['credits'],$data['department_id'],$data['year_level'],$data['semester'],$data['description'] ?? '']);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (Exception $e) {
        http_response_code(409);
        echo json_encode(['error' => 'Course code already exists.']);
    }

} elseif ($action === 'delete_course') {
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id=?');
    $stmt->execute([$data['course_id']]);
    echo json_encode(['success' => true]);

} elseif ($action === 'add_user') {
    $hash = password_hash($data['password'], PASSWORD_BCRYPT);
    try {
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role,department_id,phone) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$data['name'],$data['email'],$hash,$data['role'],$data['department_id'] ?? null,$data['phone'] ?? null]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already exists.']);
    }

} elseif ($action === 'edit_user') {
    try {
        $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, phone=?, role=?, department_id=? WHERE id=?');
        $stmt->execute([$data['name'], $data['email'], $data['phone'] ?? null, $data['role'], $data['department_id'] ?? null, $data['id']]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already in use.']);
    }

} elseif ($action === 'delete_user') {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id=?');
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);

} elseif ($action === 'get_teachers') {
    $rows = $pdo->query("SELECT u.id, u.name, u.email, u.phone, u.department_id, u.role,
        d.name AS department_name
        FROM users u
        LEFT JOIN departments d ON d.id = u.department_id
        WHERE u.role IN ('teacher','staff','admin')
        ORDER BY u.role, u.name")->fetchAll();
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} elseif ($action === 'get_enrollments') {
    // Get course's department so we can sort same-dept students first
    $courseId = intval($data['course_id']);
    $deptStmt = $pdo->prepare('SELECT department_id FROM courses WHERE id = ?');
    $deptStmt->execute([$courseId]);
    $courseDeptId = $deptStmt->fetchColumn();

    $stmt = $pdo->prepare('
        SELECT s.student_id, s.fname, s.lname, s.year_of_study, s.status, s.photo,
               d.name AS department, s.department_id,
               IF(se.student_id IS NOT NULL, 1, 0) AS enrolled
        FROM students s
        JOIN departments d ON d.id = s.department_id
        LEFT JOIN student_enrollments se ON se.student_id = s.student_id AND se.course_id = ?
        WHERE s.admission_status = "Approved"
        ORDER BY enrolled DESC, (s.department_id = ?) DESC, s.fname, s.lname');
    $stmt->execute([$courseId, $courseDeptId]);
    $rows = $stmt->fetchAll();
    // Tag same-dept students
    foreach ($rows as &$r) {
        $r['same_dept'] = ($r['department_id'] == $courseDeptId) ? 1 : 0;
    }
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} elseif ($action === 'enroll') {
    // Admin/staff enroll — no window check, mark as admin
    $pdo->prepare('INSERT IGNORE INTO student_enrollments (student_id, course_id, enrolled_by) VALUES (?,?,"admin")')
        ->execute([$data['student_id'], $data['course_id']]);
    echo json_encode(['success' => true]);

} elseif ($action === 'unenroll') {
    // Admin/staff unenroll — no window check
    $pdo->prepare('DELETE FROM student_enrollments WHERE student_id=? AND course_id=?')
        ->execute([$data['student_id'], $data['course_id']]);
    echo json_encode(['success' => true]);

} elseif ($action === 'self_enroll' || $action === 'self_unenroll') {
    $courseId  = intval($data['course_id']);
    $studentId = trim($data['student_id']);
    $now       = date('Y-m-d H:i:s');

    // Resolve active window (course-specific first, then global)
    $wStmt = $pdo->prepare('SELECT id, open_from, open_until FROM enrollment_windows WHERE course_id=?');
    $wStmt->execute([$courseId]);
    $win = $wStmt->fetch();
    if (!$win) {
        $win = $pdo->query('SELECT id, open_from, open_until FROM enrollment_windows WHERE course_id IS NULL')->fetch();
    }
    if (!$win || $now < $win['open_from'] || $now > $win['open_until']) {
        http_response_code(403);
        echo json_encode(['error' => 'Enrollment is currently closed.']);
        exit;
    }

    if ($action === 'self_enroll') {
        // Check: has this student already self-enrolled during this window?
        $chk = $pdo->prepare('
            SELECT COUNT(*) FROM student_enrollments
            WHERE student_id = ? AND window_id = ? AND enrolled_by = "self"');
        $chk->execute([$studentId, $win['id']]);
        if ((int)$chk->fetchColumn() > 0) {
            http_response_code(403);
            echo json_encode(['error' => 'You can only self-enroll in one course per enrollment window.']);
            exit;
        }
        $pdo->prepare('INSERT IGNORE INTO student_enrollments (student_id, course_id, enrolled_by, window_id) VALUES (?,?,"self",?)')
            ->execute([$studentId, $courseId, $win['id']]);
    } else {
        // Only allow dropping a self-enrolled course (not admin-enrolled)
        $pdo->prepare('DELETE FROM student_enrollments WHERE student_id=? AND course_id=? AND enrolled_by="self"')
            ->execute([$studentId, $courseId]);
    }
    echo json_encode(['success' => true]);

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown action.']);
}
