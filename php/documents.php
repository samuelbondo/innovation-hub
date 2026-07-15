<?php
require 'db.php';
json_headers();

$method = $_SERVER['REQUEST_METHOD'];

// ── GET: list documents + admission status for a student ─────────────────────
if ($method === 'GET') {
    $sid = trim($_GET['student_id'] ?? '');
    if (!$sid) { http_response_code(400); echo json_encode(['error'=>'Missing student_id']); exit; }

    $stmt = $pdo->prepare('SELECT admission_status, admission_note, submitted_at FROM students WHERE student_id=?');
    $stmt->execute([$sid]);
    $student = $stmt->fetch();

    if (!$student) { http_response_code(404); echo json_encode(['error'=>'Student not found']); exit; }

    $docs = $pdo->prepare('SELECT id, doc_type, filename, original_name, uploaded_by, uploaded_at FROM student_documents WHERE student_id=? ORDER BY uploaded_at DESC');
    $docs->execute([$sid]);

    echo json_encode([
        'admission_status' => $student['admission_status'] ?? 'Pending',
        'admission_note'   => $student['admission_note']   ?? '',
        'submitted_at'     => $student['submitted_at']     ?? null,
        'documents'        => $docs->fetchAll()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST: upload document ─────────────────────────────────────────────────────
if ($method === 'POST' && !empty($_FILES)) {
    $sid      = trim($_POST['student_id']  ?? '');
    $docType  = trim($_POST['doc_type']    ?? 'Document');
    $uploader = trim($_POST['uploaded_by'] ?? 'student');
    $file     = $_FILES['document']        ?? null;

    if (!$sid || !$file) { http_response_code(400); echo json_encode(['error'=>'Missing data']); exit; }

    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['pdf','jpg','jpeg','png','doc','docx'];
    if (!in_array($ext, $allowed)) {
        http_response_code(400); echo json_encode(['error'=>'Allowed: PDF, JPG, PNG, DOC, DOCX']); exit;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        http_response_code(400); echo json_encode(['error'=>'File must be under 5MB']); exit;
    }

    // Create uploads/docs directory if needed
    $docsDir = dirname(__DIR__) . '/uploads/docs/';
    if (!is_dir($docsDir)) mkdir($docsDir, 0755, true);

    $safeSid  = preg_replace('/[^a-zA-Z0-9\-]/', '_', $sid);
    $filename = 'uploads/docs/' . $safeSid . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $file['name']);
    $dest     = dirname(__DIR__) . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        http_response_code(500); echo json_encode(['error'=>'Upload failed']); exit;
    }

    $pdo->prepare('INSERT INTO student_documents (student_id, doc_type, filename, original_name, uploaded_by) VALUES (?,?,?,?,?)')
        ->execute([$sid, $docType, $filename, $file['name'], $uploader]);

    // If student uploading, mark as Under Review (only if still Pending)
    if ($uploader === 'student') {
        $pdo->prepare("UPDATE students SET admission_status='Under Review', submitted_at=NOW() WHERE student_id=? AND admission_status IN ('Pending')")
            ->execute([$sid]);
    }

    echo json_encode(['success'=>true, 'filename'=>$filename, 'original_name'=>$file['name']], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── PUT: update admission status ──────────────────────────────────────────────
if ($method === 'PUT') {
    $data   = json_decode(file_get_contents('php://input'), true) ?? [];
    $sid    = trim($data['student_id']      ?? '');
    $status = trim($data['admission_status']?? '');
    $note   = trim($data['admission_note']  ?? '');
    $valid  = ['Pending','Under Review','Approved','Rejected'];

    if (!$sid || !in_array($status, $valid)) {
        http_response_code(400); echo json_encode(['error'=>'Invalid data']); exit;
    }

    $pdo->prepare('UPDATE students SET admission_status=?, admission_note=? WHERE student_id=?')
        ->execute([$status, $note, $sid]);

    // If approved → ensure student account is Active
    if ($status === 'Approved') {
        $pdo->prepare("UPDATE students SET status='Active' WHERE student_id=?")->execute([$sid]);
    }

    // If rejected → deactivate
    if ($status === 'Rejected') {
        $pdo->prepare("UPDATE students SET status='Inactive' WHERE student_id=?")->execute([$sid]);
    }

    echo json_encode(['success'=>true]);
    exit;
}

// ── DELETE: remove a document ─────────────────────────────────────────────────
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = intval($data['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }

    $row = $pdo->prepare('SELECT filename FROM student_documents WHERE id=?');
    $row->execute([$id]); $doc = $row->fetch();
    if ($doc) {
        $path = dirname(__DIR__) . '/' . $doc['filename'];
        if (file_exists($path)) unlink($path);
        $pdo->prepare('DELETE FROM student_documents WHERE id=?')->execute([$id]);
    }
    echo json_encode(['success'=>true]);
    exit;
}

http_response_code(405); echo json_encode(['error'=>'Method not allowed']);
