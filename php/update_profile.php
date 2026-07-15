<?php
require 'db.php';
json_headers();

$data    = json_decode(file_get_contents('php://input'), true);
$email   = trim($data['email']   ?? '');
$phone   = trim($data['phone']   ?? '');
$address = trim($data['address'] ?? '');

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$stmt = $pdo->prepare('UPDATE students SET phone = ?, address = ? WHERE email = ?');
$stmt->execute([$phone, $address, $email]);

echo json_encode(['success' => true]);
