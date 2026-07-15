<?php
require 'db.php';
json_headers();
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) { http_response_code(400); echo json_encode(['error'=>'No data.']); exit; }

$stmt = $pdo->prepare('INSERT INTO settings (k,v) VALUES (?,?) ON DUPLICATE KEY UPDATE v=VALUES(v)');
foreach ($data as $k => $v) {
    $stmt->execute([preg_replace('/[^a-z0-9_]/','',$k), trim($v)]);
}
echo json_encode(['success' => true]);
