<?php $activePage = 'student_courses'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .course-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
        .course-card { background:#fff; border-radius:12px; padding:20px; box-shadow:var(--shadow); border-left:4px solid var(--accent); }
        .course-card .code { font-size:0.72rem; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; }
        .course-card h4 { color:var(--primary); margin:6px 0 4px; font-size:0.95rem; }
        .course-card .meta { font-size:0.76rem; color:var(--muted); display:flex; gap:8px; flex-wrap:wrap; margin-top:6px; }
        .course-card .teacher { font-size:0.8rem; color:#555; margin-top:8px; }
        .sem-badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:0.7rem; font-weight:600; background:#e8f4fd; color:#0056b3; }
        .sem-section { margin-bottom:32px; }
        .sem-section h3 { font-size:0.9rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:14px; padding-bottom:6px; border-bottom:2px solid var(--border); }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content" id="pageContent">
            <p style="color:var(--muted);padding:40px;text-align:center;">Loading&hellip;</p>
        </div>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');
const pc = document.getElementById('pageContent');

if (_u.role !== 'student') {
    location.replace('dashboard.php');
}

fetch(`php/dashboard.php?email=${encodeURIComponent(_u.email)}&role=student`)
    .then(r => r.json())
    .then(d => {
        const courses = d.courses || [];
        const s = d.student || {};

        const bySem = {};
        courses.forEach(c => {
            const key = `Semester ${c.semester}`;
            if (!bySem[key]) bySem[key] = [];
            bySem[key].push(c);
        });

        const sectionsHTML = Object.keys(bySem).sort().map(sem => `
            <div class="sem-section">
                <h3>${sem}</h3>
                <div class="course-grid">
                    ${bySem[sem].map(c => `
                        <div class="course-card">
                            <div class="code">${c.code}</div>
                            <h4>${c.title}</h4>
                            <div class="meta">
                                <span class="sem-badge">Semester ${c.semester}</span>
                                <span>${c.credits} credit${c.credits != 1 ? 's' : ''}</span>
                            </div>
                            <div class="teacher">&#x1F468;&#x200D;&#x1F3EB; ${c.teacher_name || '<em style="color:var(--muted)">Not assigned</em>'}</div>
                        </div>`).join('')}
                </div>
            </div>`).join('') || '<p style="color:var(--muted);padding:20px 0;">You are not enrolled in any courses yet.</p>';

        pc.innerHTML = `
        <div class="page-header">
            <div>
                <h2>&#x1F4DA; My Courses</h2>
                <p>Year ${s.year_of_study || ''} &mdash; ${s.department || ''}</p>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <h3>Enrolled Courses <span style="font-size:0.8rem;font-weight:400;color:var(--muted);">(${courses.length} total)</span></h3>
            </div>
            <div style="padding:20px;">${sectionsHTML}</div>
        </div>`;
    })
    .catch(() => {
        pc.innerHTML = '<p style="color:var(--muted);padding:40px;text-align:center;">Failed to load courses.</p>';
    });
</script>
</body>
</html>
