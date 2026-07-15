<?php
require 'db.php';
json_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['csv'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded.']);
    exit;
}

$file = $_FILES['csv']['tmp_name'];
if (!$file || !is_readable($file)) {
    echo json_encode(['error' => 'Cannot read uploaded file.']);
    exit;
}

// Cache department name -> id
$deptMap = [];
foreach ($pdo->query('SELECT id, name FROM departments')->fetchAll() as $d) {
    $deptMap[strtolower(trim($d['name']))] = $d['id'];
}

$handle   = fopen($file, 'r');
$inserted = 0;
$skipped  = [];
$rowNum   = 0;

// Skip header row
fgetcsv($handle);

$stmt = $pdo->prepare('INSERT IGNORE INTO courses (code, title, department_id, year_level, semester, credits) VALUES (?,?,?,?,?,?)');

while (($row = fgetcsv($handle)) !== false) {
    $rowNum++;
    if (count($row) < 6) { $skipped[] = "Row $rowNum: not enough columns."; continue; }

    [$code, $title, $dept, $year, $sem, $credits] = array_map('trim', $row);

    if (!$code || !$title || !$dept) { $skipped[] = "Row $rowNum: missing code, title or department."; continue; }

    $deptId = $deptMap[strtolower($dept)] ?? null;
    if (!$deptId) { $skipped[] = "Row $rowNum: department '$dept' not found."; continue; }

    $year    = max(1, min(4, intval($year) ?: 1));
    $sem     = max(1, min(2, intval($sem)  ?: 1));
    $credits = max(1, min(9, intval($credits) ?: 3));

    $stmt->execute([strtoupper($code), $title, $deptId, $year, $sem, $credits]);
    if ($stmt->rowCount()) $inserted++;
    else $skipped[] = "Row $rowNum: code '$code' already exists (skipped).";
}

fclose($handle);
echo json_encode(['success' => true, 'inserted' => $inserted, 'skipped' => $skipped], JSON_UNESCAPED_UNICODE);
