<?php
require 'db.php';
$hash = password_hash('admin123', PASSWORD_BCRYPT);
$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->execute([$hash, 'sam@gmail.com']);
echo json_encode(['done' => true, 'hash' => $hash]);
