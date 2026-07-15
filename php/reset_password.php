<?php
require 'db.php';
json_headers();

$data   = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

// Admin resets any user's password
if ($action === 'admin_reset') {
    $userId   = intval($data['user_id'] ?? 0);
    $newPass  = trim($data['new_password'] ?? '');
    if (!$userId || strlen($newPass) < 6) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data.']); exit;
    }
    $hash = password_hash($newPass, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->execute([$hash, $userId]);
    // Also update students table if student account
    $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $userId]);
    echo json_encode(['success' => true]);

// Logged-in user changes own password
} elseif ($action === 'change_own') {
    $email      = trim($data['email']        ?? '');
    $current    = $data['current_password']  ?? '';
    $newPass    = $data['new_password']      ?? '';
    $confirm    = $data['confirm_password']  ?? '';

    if (!$email || !$current || !$newPass || !$confirm) {
        http_response_code(400); echo json_encode(['error' => 'All fields required.']); exit;
    }
    if ($newPass !== $confirm) {
        http_response_code(400); echo json_encode(['error' => 'New passwords do not match.']); exit;
    }
    if (strlen($newPass) < 6) {
        http_response_code(400); echo json_encode(['error' => 'Password must be at least 6 characters.']); exit;
    }

    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password'])) {
        http_response_code(401); echo json_encode(['error' => 'Current password is incorrect.']); exit;
    }

    $hash = password_hash($newPass, PASSWORD_BCRYPT);
    $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $user['id']]);
    echo json_encode(['success' => true]);

// Forgot password — user provides email/student_id and sets new password
} elseif ($action === 'forgot') {
    $identifier = trim($data['identifier']     ?? '');
    $newPass    = $data['new_password']        ?? '';
    $confirm    = $data['confirm_password']    ?? '';

    if (!$identifier || !$newPass || !$confirm) {
        http_response_code(400); echo json_encode(['error' => 'All fields required.']); exit;
    }
    if ($newPass !== $confirm) {
        http_response_code(400); echo json_encode(['error' => 'Passwords do not match.']); exit;
    }
    if (strlen($newPass) < 6) {
        http_response_code(400); echo json_encode(['error' => 'Password must be at least 6 characters.']); exit;
    }

    // Find user by email or student_id
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$identifier]);
    $user = $stmt->fetch();

    if (!$user) {
        // Try student_id lookup
        $s = $pdo->prepare('SELECT email FROM students WHERE student_id = ?');
        $s->execute([$identifier]);
        $row = $s->fetch();
        if ($row) {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$row['email']]);
            $user = $stmt->fetch();
        }
    }

    if (!$user) {
        http_response_code(404); echo json_encode(['error' => 'No account found with that email or registration number.']); exit;
    }

    $hash = password_hash($newPass, PASSWORD_BCRYPT);
    $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $user['id']]);
    echo json_encode(['success' => true]);

} else {
    http_response_code(400); echo json_encode(['error' => 'Unknown action.']);
}
