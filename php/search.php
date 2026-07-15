<?php
require 'db.php';
json_headers();

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) { echo json_encode(['results' => []]); exit; }

$like = '%' . $q . '%';
$results = [];

// Search departments
$stmt = $pdo->prepare("SELECT d.name, d.head, d.description, d.icon, f.name AS faculty,
    (SELECT COUNT(*) FROM students s WHERE s.department_id=d.id AND s.status='Active') AS students
    FROM departments d LEFT JOIN faculties f ON f.id=d.faculty_id
    WHERE d.name LIKE ? OR d.head LIKE ? OR d.description LIKE ? OR f.name LIKE ?");
$stmt->execute([$like,$like,$like,$like]);
foreach ($stmt->fetchAll() as $r) {
    $results[] = [
        'type'     => 'Department',
        'icon'     => $r['icon'] ?? '🏫',
        'title'    => $r['name'],
        'subtitle' => 'Head: ' . $r['head'] . ' · ' . ($r['faculty'] ?? '') . ' · ' . $r['students'] . ' active students',
        'desc'     => $r['description'] ?? '',
        'link'     => null
    ];
}

// Search courses
$stmt = $pdo->prepare("SELECT c.code, c.title, c.credits, c.year_level, c.semester,
    d.name AS department, u.name AS teacher
    FROM courses c
    JOIN departments d ON d.id=c.department_id
    LEFT JOIN teacher_courses tc ON tc.course_id=c.id
    LEFT JOIN users u ON u.id=tc.teacher_id
    WHERE c.code LIKE ? OR c.title LIKE ? OR d.name LIKE ? OR u.name LIKE ?");
$stmt->execute([$like,$like,$like,$like]);
foreach ($stmt->fetchAll() as $r) {
    $results[] = [
        'type'     => 'Course',
        'icon'     => '📚',
        'title'    => $r['code'] . ' — ' . $r['title'],
        'subtitle' => $r['department'] . ' · Year ' . $r['year_level'] . ', Semester ' . $r['semester'] . ' · ' . $r['credits'] . ' credits',
        'desc'     => $r['teacher'] ? '👨🏫 ' . $r['teacher'] : 'Teacher not assigned',
        'link'     => null
    ];
}

// Search faculties
$stmt = $pdo->prepare("SELECT f.name, f.dean,
    (SELECT COUNT(*) FROM departments d WHERE d.faculty_id=f.id) AS depts
    FROM faculties f WHERE f.name LIKE ? OR f.dean LIKE ?");
$stmt->execute([$like,$like]);
foreach ($stmt->fetchAll() as $r) {
    $results[] = [
        'type'     => 'Faculty',
        'icon'     => '🏛️',
        'title'    => $r['name'],
        'subtitle' => 'Dean: ' . $r['dean'] . ' · ' . $r['depts'] . ' departments',
        'desc'     => '',
        'link'     => null
    ];
}

// Static school info keywords
$info = [
    ['keywords'=>['contact','email','phone','location','address','reach'],'icon'=>'✉️','title'=>'Contact Us','subtitle'=>'Get in touch with Group One SMS','desc'=>'📧 groupone@email.com · 📞 +1 234 567 890 · 📍 123 Dev Street, Tech City','link'=>'contact.php'],
    ['keywords'=>['about','team','mission','who','group one','members','values'],'icon'=>'ℹ️','title'=>'About Group One','subtitle'=>'Learn about our team and mission','desc'=>'A student-led team building a fully functional student management system.','link'=>'about.php'],
    ['keywords'=>['login','sign in','signin','access','account','password'],'icon'=>'🔑','title'=>'Student / Staff Login','subtitle'=>'Sign in to your account','desc'=>'Access your dashboard, profile, courses and more.','link'=>'login.php'],
    ['keywords'=>['register','signup','sign up','create account','new account','join'],'icon'=>'📝','title'=>'Create an Account','subtitle'=>'Register as a new student','desc'=>'Sign up to get access to your student portal.','link'=>'signup.php'],
    ['keywords'=>['department','departments','all departments','list'],'icon'=>'🏫','title'=>'Departments','subtitle'=>'8 departments across 5 faculties','desc'=>'Computer Science, IT, Business, Engineering, Mathematics, Sciences, Arts, Education','link'=>null],
    ['keywords'=>['course','courses','subjects','classes','curriculum'],'icon'=>'📖','title'=>'Courses','subtitle'=>'Browse all available courses','desc'=>'Courses are organized by department, year level, and semester.','link'=>null],
];

$ql = strtolower($q);
foreach ($info as $item) {
    foreach ($item['keywords'] as $kw) {
        if (str_contains($ql, $kw) || str_contains($kw, $ql)) {
            $results[] = ['type'=>'Info','icon'=>$item['icon'],'title'=>$item['title'],'subtitle'=>$item['subtitle'],'desc'=>$item['desc'],'link'=>$item['link']];
            break;
        }
    }
}

echo json_encode(['results' => $results, 'count' => count($results)], JSON_UNESCAPED_UNICODE);
