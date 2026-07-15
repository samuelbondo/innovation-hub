<?php
require 'db.php';
json_headers();

$data     = json_decode(file_get_contents('php://input'), true);
$fname    = trim($data['fname']      ?? '');
$lname    = trim($data['lname']      ?? '');
$gender   = trim($data['gender']     ?? '');
$email    = trim($data['email']      ?? '');
$phone    = trim($data['phone']      ?? '');
$dept     = trim($data['department'] ?? '');
$year     = (int)($data['year']      ?? 0);
$dob      = trim($data['dob']        ?? '');
$address  = trim($data['address']    ?? '');
$password = $data['password']        ?? '';
$confirm  = $data['confirm']         ?? '';

// Validation
if (!$fname || !$lname || !$email || !$dept || !$year || !$dob || !$password || !$confirm) {
    http_response_code(400); echo json_encode(['error'=>'All required fields must be filled.']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); echo json_encode(['error'=>'Invalid email address.']); exit;
}
if ($dob >= date('Y-m-d')) {
    http_response_code(400); echo json_encode(['error'=>'Date of birth must be before today.']); exit;
}
if (strlen($password) < 6) {
    http_response_code(400); echo json_encode(['error'=>'Password must be at least 6 characters.']); exit;
}
if ($password !== $confirm) {
    http_response_code(400); echo json_encode(['error'=>'Passwords do not match.']); exit;
}

// Check duplicate email in students or users
$chk = $pdo->prepare('SELECT id FROM students WHERE email=?');
$chk->execute([$email]);
if ($chk->fetch()) { http_response_code(409); echo json_encode(['error'=>'This email is already registered.']); exit; }

// Resolve department
$ds = $pdo->prepare('SELECT id FROM departments WHERE name=?');
$ds->execute([$dept]);
$dept_id = $ds->fetchColumn();
if (!$dept_id) { http_response_code(400); echo json_encode(['error'=>'Invalid department selected.']); exit; }

try {
    $pdo->beginTransaction();

    $count = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $sid   = 'STU-' . date('Y') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

    // Insert student with Pending admission status
    $pdo->prepare('INSERT INTO students
        (student_id, fname, lname, gender, email, phone, department_id, year_of_study, dob, address, status, admission_status, submitted_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())')
        ->execute([$sid, $fname, $lname, $gender ?: null, $email, $phone, $dept_id, $year, $dob, $address, 'Inactive', 'Pending']);

    // Create login account — inactive until admission approved
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $pdo->prepare('INSERT IGNORE INTO users (name, email, password, role) VALUES (?,?,?,?)')
        ->execute([$fname . ' ' . $lname, $email, $hash, 'student']);

    $pdo->commit();
    echo json_encode(['success'=>true, 'student_id'=>$sid, 'name'=>$fname.' '.$lname]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error'=>'Registration failed: '.$e->getMessage()]);
}
