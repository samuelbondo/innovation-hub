<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses | SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .course-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
        .course-meta { display:flex; gap:8px; flex-wrap:wrap; margin-top:6px; }
        .tag { display:inline-block; padding:2px 9px; border-radius:10px; font-size:0.72rem; font-weight:600; }
        .tag-blue   { background:#e8f4fd; color:#0056b3; }
        .tag-orange { background:#fff3cd; color:#856404; }
        .tag-green  { background:#d4edda; color:#155724; }
        .tag-muted  { background:#f0f0f0; color:#555; }
        .course-panel { background:#fff; border-radius:12px; box-shadow:var(--shadow); margin-bottom:20px; overflow:hidden; }
        .course-panel-head { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; cursor:pointer; user-select:none; }
        .course-panel-head:hover { background:#fafafa; }
        .course-code { font-size:0.75rem; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; }
        .course-title { font-size:1rem; font-weight:700; color:var(--primary); margin:3px 0; }
        .course-dept  { font-size:0.78rem; color:var(--muted); }
        .course-body  { display:none; padding:0 24px 20px; }
        .course-body.open { display:block; }
        .student-row  { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f4f4f4; }
        .student-row:last-child { border-bottom:none; }
        .s-av { width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .s-av img { width:34px;height:34px;object-fit:cover; }
        .no-courses { text-align:center; padding:60px 20px; color:var(--muted); }
        .no-courses div { font-size:3rem; margin-bottom:12px; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div><h2>📚 My Courses</h2><p>Your assigned courses and enrolled students</p></div>
            </div>

            <div id="statsRow" class="stat-grid" style="margin-bottom:20px;"></div>
            <div id="courseList"><p style="color:var(--muted);padding:40px;text-align:center;">Loading…</p></div>

        </div>
        <footer class="app-footer">
            <span id="footerCopy"></span><span id="footerNote"></span>
        </footer>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');

async function load() {
    // Get teacher id from users
    const res  = await fetch(`php/dashboard.php?email=${encodeURIComponent(_u.email)}&role=teacher`);
    const data = await res.json();
    const teacher = data.teacher;
    if (!teacher) return;

    const courses = data.courses || [];

    // Stats
    document.getElementById('statsRow').innerHTML = `
        <div class="stat-card" data-color="blue"><div class="stat-icon">📚</div><div class="stat-info"><div class="num">${courses.length}</div><p>My Courses</p></div></div>
        <div class="stat-card" data-color="green"><div class="stat-icon">🎓</div><div class="stat-info"><div class="num">${data.stats?.dept_students || 0}</div><p>Active Students (Dept.)</p></div></div>
        <div class="stat-card" data-color="orange"><div class="stat-icon">📖</div><div class="stat-info"><div class="num">${courses.reduce((s,c)=>s+(c.student_count||0),0)}</div><p>Students Across My Courses</p></div></div>`;

    if (!courses.length) {
        document.getElementById('courseList').innerHTML = `
            <div class="no-courses"><div>📭</div><p>No courses assigned to you yet.<br>Contact your administrator.</p></div>`;
        return;
    }

    document.getElementById('courseList').innerHTML = courses.map(c => `
        <div class="course-panel">
            <div class="course-panel-head" onclick="toggleCourse(this, ${c.id})">
                <div>
                    <div class="course-code">${c.code}</div>
                    <div class="course-title">${c.title}</div>
                    <div class="course-dept">${c.department}</div>
                    <div class="course-meta">
                        <span class="tag tag-blue">Year ${c.year_level}</span>
                        <span class="tag tag-orange">Sem ${c.semester}</span>
                        <span class="tag tag-muted">${c.credits} credits</span>
                        <span class="tag tag-green" id="cnt-${c.id}">— students</span>
                    </div>
                </div>
                <span style="font-size:1.2rem;color:var(--muted);">▼</span>
            </div>
            <div class="course-body" id="body-${c.id}"></div>
        </div>`).join('');

    // Load student counts
    courses.forEach(async c => {
        const students = await fetch(`php/class_manager.php?action=get_grades&course_id=${c.id}&email=${encodeURIComponent(_u.email)}`).then(r => r.json());
        const cnt = document.getElementById('cnt-' + c.id);
        if (cnt) cnt.textContent = students.length + ' student' + (students.length !== 1 ? 's' : '');
        const body = document.getElementById('body-' + c.id);
        if (body) body.dataset.students = JSON.stringify(students);
    });
}

function toggleCourse(head, courseId) {
    const body  = document.getElementById('body-' + courseId);
    const arrow = head.querySelector('span:last-child');
    const isOpen = body.classList.toggle('open');
    arrow.textContent = isOpen ? '\u25b2' : '\u25bc';
    if (!isOpen || body.dataset.rendered) return;
    body.dataset.rendered = 'true';
    const students = JSON.parse(body.dataset.students || '[]');
    const studentsHTML = students.length ? students.map(s => {
        const ini = (s.fname[0] + s.lname[0]).toUpperCase();
        return `<div class="student-row">
            <div class="s-av">${s.photo ? `<img src="${s.photo}" alt="${ini}">` : ini}</div>
            <div>
                <div style="font-weight:600;font-size:0.88rem;color:var(--primary);">${s.fname} ${s.lname}</div>
                <div style="font-size:0.75rem;color:var(--muted);">${s.student_id}</div>
            </div>
            <span class="tag tag-green" style="margin-left:auto;">Active</span>
        </div>`;
    }).join('') : '<p style="color:var(--muted);font-size:0.85rem;padding:12px 0;">No active students enrolled yet.</p>';
    body.innerHTML = `<div style="padding:16px 0;"><div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--accent);margin-bottom:10px;">🎓 Enrolled Students (${students.length})</div>${studentsHTML}</div>`;
}

load();
</script>
</body>
</html>
