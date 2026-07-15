<?php
require 'db.php';
json_headers();
$rows = $pdo->query('SELECT k, v FROM settings')->fetchAll();
$out  = [];
foreach ($rows as $r) $out[$r['k']] = $r['v'];
echo json_encode($out, JSON_UNESCAPED_UNICODE);
