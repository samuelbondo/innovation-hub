<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Courses | SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .window-banner { padding:12px 18px; border-radius:10px; margin-bottom:16px; font-size:0.88rem; font-weight:600; display:flex; flex-direction:column; gap:4px; }
        .window-open   { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .window-closed { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        .year-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
        .year-tab { padding:6px 16px; border-radius:20px; border:1.5px solid var(--border); background:#fff; font-size:0.82rem; font-weight:600; cursor:pointer; color:var(--muted); transition:all .15s; }
        .year-tab.active { background:var(--accent); color:#fff; border-color:var(--accent); }
        .year-tab:hover:not(.active) { border-color:var(--accent); color:var(--accent); }
        .course-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
        .course-card { background:#fff; border-radius:12px; padding:20px; box-shadow:var(--shadow); border-left:4px solid var(--border); transition:border-color .2s; display:flex; flex-direction:column; gap:6px; }
        .course-card.enrolled { border-left-color:var(--accent); }
        .course-card .code { font-size:0.7rem; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; }
        .course-card h4 { color:var(--primary); margin:0; font-size:0.9rem; line-height:1.35; }
        .tag { display:inline-block; padding:2px 8px; border-radius:10px; font-size:0.7rem; font-weight:600; white-space:nowrap; }
        .tag-blue   { background:#e8f4fd; color:#0056b3; }
        .tag-orange { background:#fff3cd; color:#856404; }
        .tag-green  { background:#d4edda; color:#155724; }
        .enroll-btn { margin-top:6px; width:100%; padding:7px; border-radius:8px; border:none; font-size:0.82rem; font-weight:600; cursor:pointer; }
        .enroll-btn.add    { background:#e8f4fd; color:#0056b3; }
        .enroll-btn.add:hover { background:#0056b3; color:#fff; }
        .enroll-btn.drop   { background:#d4edda; color:#155724; }
        .enroll-btn.drop:hover { background:#dc3545; color:#fff; }
        .enroll-btn.locked { background:#f0f0f0; color:#999; }
        .enroll-btn:disabled { opacity:.55; cursor:not-allowed; }
        .sem-label { font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--accent); margin:20px 0 10px; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">
            <div class="page-header">
                <div><h2>📋 Enroll in Courses</h2><p>Browse your department courses and self-enroll — 1 course per window</p></div>
            </div>
            <div id="windowBanner"></div>
            <div class="year-tabs" id="yearTabs"></div>
            <div id="courseList"><p style="color:var(--muted);padding:40px;text-align:center;">Loading…</p></div>
        </div>
        <footer class="app-footer"><span></span><span></span></footer>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');
let windowOpen = false, studentId = null;
let allCourses = [], enrolledIds = new Set(), selfEnrolledThisWindow = false;
let activeYear = 1;

async function load() {
    const student = await fetch(`php/get_students.php?email=${encodeURIComponent(_u.email)}`).then(r => r.json());
    if (!student || student.error) {
        document.getElementById('courseList').innerHTML = '<p style="color:var(--muted);padding:40px;text-align:center;">Student record not found.</p>';
        return;
    }
    studentId   = student.student_id;
    activeYear  = parseInt(student.year_of_study);

    const [wData, courses, enrolled, selfData] = await Promise.all([
        fetch('php/enrollment_window.php?action=check').then(r => r.json()),
        fetch(`php/get_courses.php?dept_id=${student.department_id}`).then(r => r.json()),
        fetch(`php/get_courses.php?student_email=${encodeURIComponent(_u.email)}`).then(r => r.json()),
        fetch(`php/enrollment_window.php?action=self_check&student_id=${encodeURIComponent(student.student_id)}`).then(r => r.json())
    ]);

    windowOpen              = wData.open;
    allCourses              = courses;
    enrolledIds             = new Set(enrolled.map(c => parseInt(c.id)));
    selfEnrolledThisWindow  = selfData.enrolled;

    // Banner
    const banner = document.getElementById('windowBanner');
    if (windowOpen) {
        const until = new Date(wData.window.open_until);
        const fmt   = until.toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit'});
        banner.innerHTML = `<div class="window-banner window-open">
            <span>✅ Enrollment is <strong>OPEN</strong> — closes ${fmt}</span>
            <span style="font-size:0.8rem;font-weight:400;">You may self-enroll in <strong>1 course</strong> per window. Drop your pick to choose a different one.</span>
        </div>`;
    } else if (wData.window) {
        const from  = new Date(wData.window.open_from).toLocaleDateString('en-US',  {month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit'});
        const until = new Date(wData.window.open_until).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit'});
        banner.innerHTML = `<div class="window-banner window-closed">🔒 Enrollment is <strong>CLOSED</strong> — window: ${from} → ${until}</div>`;
    } else {
        banner.innerHTML = `<div class="window-banner window-closed">🔒 Enrollment is <strong>CLOSED</strong> — no enrollment period has been set.</div>`;
    }

    if (!courses.length) {
        document.getElementById('courseList').innerHTML = '<p style="color:var(--muted);padding:40px;text-align:center;">No courses available for your department.</p>';
        return;
    }

    // Year tabs
    const years   = [...new Set(courses.map(c => parseInt(c.year_level)))].sort();
    const tabsEl  = document.getElementById('yearTabs');
    if (!years.includes(activeYear)) activeYear = years[0];

    years.forEach(y => {
        const btn = document.createElement('button');
        btn.className = 'year-tab' + (y === activeYear ? ' active' : '');
        btn.textContent = 'Year ' + y + (y === parseInt(student.year_of_study) ? ' ★' : '');
        btn.title = y === parseInt(student.year_of_study) ? 'Your current year' : '';
        btn.onclick = () => {
            activeYear = y;
            document.querySelectorAll('.year-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderCourses();
        };
        tabsEl.appendChild(btn);
    });

    renderCourses();
}

function renderCourses() {
    const filtered = allCourses.filter(c => parseInt(c.year_level) === activeYear);
    const sem1 = filtered.filter(c => c.semester == 1);
    const sem2 = filtered.filter(c => c.semester == 2);

    function card(c) {
        const isEnrolled = enrolledIds.has(parseInt(c.id));
        const slotUsed   = windowOpen && selfEnrolledThisWindow && !isEnrolled;
        const disabled   = !windowOpen || slotUsed;
        const btnClass   = isEnrolled ? 'drop' : (slotUsed ? 'locked' : 'add');
        const btnLabel   = isEnrolled ? '✓ Drop Course' : (slotUsed ? '🔒 Slot Used' : '+ Enroll');
        const tip        = slotUsed ? ' title="Drop your current self-enrolled course first to pick another."' : '';
        return `<div class="course-card ${isEnrolled ? 'enrolled' : ''}" id="cc-${c.id}">
            <div class="code">${c.code}</div>
            <h4>${c.title}</h4>
            <div style="display:flex;gap:5px;flex-wrap:wrap;">
                <span class="tag tag-blue">Year ${c.year_level}</span>
                <span class="tag tag-orange">${c.credits} cr · Sem ${c.semester}</span>
                ${isEnrolled ? '<span class="tag tag-green">✓ Enrolled</span>' : ''}
            </div>
            <button id="btn-${c.id}" class="enroll-btn ${btnClass}" ${disabled ? 'disabled' : ''}${tip}
                onclick="toggleEnroll(${c.id}, ${isEnrolled})">
                ${btnLabel}
            </button>
        </div>`;
    }

    let html = '';
    if (sem1.length) html += `<div class="sem-label">📘 Semester 1</div><div class="course-grid">${sem1.map(card).join('')}</div>`;
    if (sem2.length) html += `<div class="sem-label">📗 Semester 2</div><div class="course-grid">${sem2.map(card).join('')}</div>`;
    if (!html) html = '<p style="color:var(--muted);padding:20px;">No courses for this year.</p>';
    document.getElementById('courseList').innerHTML = html;
}

async function toggleEnroll(courseId, isEnrolled) {
    if (!windowOpen) return;
    const btn = document.getElementById('btn-' + courseId);
    btn.disabled = true;
    btn.textContent = '…';

    const action = isEnrolled ? 'self_unenroll' : 'self_enroll';
    const res  = await fetch('php/manage_courses.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action, student_id: studentId, course_id: courseId})
    });
    const data = await res.json();

    if (data.error) {
        alert(data.error);
        btn.disabled    = false;
        btn.textContent = isEnrolled ? '✓ Drop Course' : '+ Enroll';
        return;
    }
    location.reload();
}

load();
</script>
</body>
</html>
