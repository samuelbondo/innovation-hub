<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses & Classes | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ── Stats ── */
        .tag { display:inline-block; padding:2px 7px; border-radius:10px; font-size:0.68rem; font-weight:600; white-space:nowrap; }
        .tag-blue   { background:#e8f4fd; color:#0056b3; }
        .tag-orange { background:#fff3cd; color:#856404; }
        .tag-green  { background:#d4edda; color:#155724; }
        .tag-muted  { background:#f0f0f0; color:#555; }

        /* ── Course overview cards ── */
        .course-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:16px; margin-bottom:32px; }
        .course-card { background:#fff; border-radius:12px; padding:18px 20px; box-shadow:var(--shadow); border-left:4px solid var(--accent); cursor:pointer; transition:box-shadow 0.15s,transform 0.15s; display:flex; flex-direction:column; gap:6px; }
        .course-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.12); transform:translateY(-2px); }
        .course-card.selected { border-left-color:var(--primary); background:#fef2f4; }
        .course-card .c-code { font-size:0.7rem; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; }
        .course-card h4 { color:var(--primary); margin:0; font-size:0.9rem; line-height:1.35; word-break:break-word; }
        .course-card .c-meta { display:flex; gap:5px; flex-wrap:wrap; margin-top:4px; }
        .course-card .c-dept { font-size:0.75rem; color:var(--muted); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* ── Manage section ── */
        .manage-section { display:none; }
        .manage-section.visible { display:block; }
        .class-tabs { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:20px; }
        .class-tab { padding:8px 18px; border-radius:20px; border:1.5px solid var(--border); background:#fff;
                     font-size:0.82rem; font-weight:600; cursor:pointer; transition:all .15s; color:var(--primary); }
        .class-tab.active { background:var(--accent); color:#fff; border-color:var(--accent); }
        .class-tab:hover:not(.active) { border-color:var(--accent); color:var(--accent); }
        .tab-panel { display:none; }
        .tab-panel.active { display:block; }

        /* ── Student rows ── */
        .student-row { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid #f4f4f4; }
        .student-row:last-child { border-bottom:none; }
        .s-av { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));
                display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .s-av img { width:36px;height:36px;object-fit:cover; }

        /* ── Grades ── */
        .grade-table input[type=number], .grade-table input[type=text], .grade-table select {
            padding:5px 8px; border:1.5px solid var(--border); border-radius:6px; font-size:0.82rem;
            width:100%; box-sizing:border-box; background:#fafafa; }
        .grade-table input:focus, .grade-table select:focus { outline:none; border-color:var(--accent); }
        .save-grade-btn { padding:4px 12px; font-size:0.78rem; border-radius:6px; border:none;
                          background:var(--accent); color:#fff; cursor:pointer; }
        .save-grade-btn:hover { opacity:.85; }
        .grade-saved { color:#155724; font-size:0.75rem; margin-left:6px; display:none; }

        /* ── Announcements ── */
        .ann-card { background:#f8f9fa; border-radius:10px; padding:14px 18px; margin-bottom:12px;
                    border-left:4px solid var(--accent); position:relative; }
        .ann-card h4 { margin:0 0 5px; font-size:0.9rem; color:var(--primary); }
        .ann-card p  { margin:0; font-size:0.84rem; color:#444; white-space:pre-wrap; }
        .ann-meta { font-size:0.72rem; color:var(--muted); margin-top:6px; }
        .ann-del  { position:absolute; top:10px; right:12px; background:none; border:none;
                    cursor:pointer; font-size:1rem; color:#dc3545; opacity:.7; }
        .ann-del:hover { opacity:1; }
        .ann-form textarea { width:100%; box-sizing:border-box; padding:8px 10px; border:1.5px solid var(--border);
                             border-radius:7px; font-size:0.85rem; resize:vertical; min-height:80px; }
        .ann-form textarea:focus { outline:none; border-color:var(--accent); }

        .course-info-bar { background:#fff; border-radius:10px; padding:12px 20px; box-shadow:var(--shadow);
                           margin-bottom:20px; display:flex; gap:16px; flex-wrap:wrap; align-items:center; }
        .cib-item { font-size:0.82rem; color:var(--muted); }
        .cib-item strong { color:var(--primary); }
        .no-data { text-align:center; padding:40px; color:var(--muted); font-size:0.88rem; }
        .section-divider { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px;
                           color:var(--accent); margin:0 0 14px; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2>📚 My Courses &amp; Classes</h2>
                    <p>Your assigned courses — click a course to manage students, grades and announcements</p>
                </div>
            </div>

            <!-- Stats -->
            <div id="statsRow" class="stat-grid" style="margin-bottom:24px;"></div>

            <!-- Course overview cards -->
            <div class="section-divider">📋 Assigned Courses</div>
            <div id="courseGrid" class="course-grid">
                <p style="color:var(--muted);">Loading…</p>
            </div>

            <!-- Manage section — shown when a course is selected -->
            <div class="manage-section" id="manageSection">

                <div class="course-info-bar" id="courseInfoBar"></div>

                <div class="class-tabs">
                    <button class="class-tab active" onclick="switchTab('students',this)">🎓 Students</button>
                    <button class="class-tab" onclick="switchTab('grades',this)">📝 Grades</button>
                    <button class="class-tab" onclick="switchTab('announcements',this)">📢 Announcements</button>
                </div>

                <!-- Students -->
                <div class="panel tab-panel active" id="tab-students">
                    <div class="panel-header">
                        <h3>🎓 Enrolled Students</h3>
                        <span id="studentCount" style="font-size:0.82rem;color:var(--muted);"></span>
                    </div>
                    <div style="padding:16px 24px;" id="studentList"><p class="no-data">Loading…</p></div>
                </div>

                <!-- Grades -->
                <div class="panel tab-panel" id="tab-grades">
                    <div class="panel-header">
                        <h3>📝 Student Grades</h3>
                        <span style="font-size:0.78rem;color:var(--muted);">Saves per row</span>
                    </div>
                    <div class="table-wrap">
                        <table class="grade-table">
                            <thead><tr><th>Student</th><th>ID</th><th style="width:90px;">Score (/100)</th><th style="width:80px;">Grade</th><th>Remark</th><th style="width:70px;">Save</th></tr></thead>
                            <tbody id="gradesTbody"><tr><td colspan="6" class="no-data">Select a course above.</td></tr></tbody>
                        </table>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="panel tab-panel" id="tab-announcements">
                    <div class="panel-header"><h3>📢 Announcements</h3></div>
                    <div style="padding:20px;">
                        <div class="ann-form" style="background:#fff;border:1.5px solid var(--border);border-radius:10px;padding:18px;margin-bottom:20px;">
                            <div class="form-group" style="margin-bottom:10px;">
                                <input type="text" id="annTitle" placeholder="Announcement title…"
                                    style="width:100%;box-sizing:border-box;padding:8px 10px;border:1.5px solid var(--border);border-radius:7px;font-size:0.88rem;">
                            </div>
                            <textarea id="annBody" placeholder="Write your announcement here…"></textarea>
                            <div style="margin-top:10px;display:flex;gap:8px;">
                                <button class="btn" onclick="postAnnouncement()">📤 Post</button>
                                <span id="annMsg" style="font-size:0.82rem;color:var(--muted);align-self:center;"></span>
                            </div>
                        </div>
                        <div id="annList"><p class="no-data">No announcements yet.</p></div>
                    </div>
                </div>

            </div>

        </div>
        <footer class="app-footer"><span></span><span></span></footer>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');
let courses = [], currentCourse = null;

async function init() {
    const data = await fetch(`php/dashboard.php?email=${encodeURIComponent(_u.email)}&role=teacher`).then(r => r.json());
    courses = data.courses || [];
    const s = data.stats || {};

    // Stats
    const totalEnrolled = courses.reduce((sum, c) => sum + (parseInt(c.student_count) || 0), 0);
    const deptTotal = s.dept_students || 0;
    const deptCourses = s.dept_courses || 0;
    document.getElementById('statsRow').innerHTML = `
        <div class="stat-card" data-color="blue"><div class="stat-icon">📚</div><div class="stat-info"><div class="num">${courses.length}</div><p>My Courses</p></div></div>
        <div class="stat-card" data-color="green"><div class="stat-icon">🎓</div><div class="stat-info"><div class="num">${totalEnrolled}</div><p>Students Enrolled</p></div></div>
        <div class="stat-card" data-color="orange"><div class="stat-icon">🏫</div><div class="stat-info"><div class="num">${deptTotal}</div><p>Students in Dept</p></div></div>
        <div class="stat-card" data-color="teal"><div class="stat-icon">📖</div><div class="stat-info"><div class="num">${deptCourses}</div><p>Courses in Dept</p></div></div>`;

    if (!courses.length) {
        document.getElementById('courseGrid').innerHTML =
            '<p style="color:var(--muted);padding:20px;">No courses assigned yet. Contact your administrator.</p>';
        return;
    }

    document.getElementById('courseGrid').innerHTML = courses.map(c => `
        <div class="course-card" id="card-${c.id}" onclick="selectCourse(${c.id})">
            <div class="c-code">${c.code}</div>
            <h4>${c.title}</h4>
            <div class="c-meta">
                <span class="tag tag-blue">Year ${c.year_level}</span>
                <span class="tag tag-orange">Sem ${c.semester}</span>
                <span class="tag tag-muted">${c.credits} cr</span>
                <span class="tag tag-green">${c.student_count || 0} student${c.student_count != 1 ? 's' : ''}</span>
            </div>
            <div class="c-dept">🏫 ${c.department}</div>
        </div>`).join('');
}

function selectCourse(id) {
    // Highlight selected card
    document.querySelectorAll('.course-card').forEach(el => el.classList.remove('selected'));
    document.getElementById('card-' + id)?.classList.add('selected');

    currentCourse = courses.find(c => c.id == id);
    if (!currentCourse) return;

    // Show info bar
    document.getElementById('courseInfoBar').innerHTML = `
        <div class="cib-item"><strong>${currentCourse.code}</strong></div>
        <div class="cib-item">📚 <strong>${currentCourse.title}</strong></div>
        <div class="cib-item">🏫 <strong>${currentCourse.department}</strong></div>
        <div class="cib-item">📅 Year <strong>${currentCourse.year_level}</strong> · Sem <strong>${currentCourse.semester}</strong></div>
        <div class="cib-item">⭐ <strong>${currentCourse.credits}</strong> credits</div>`;

    // Show manage section, scroll to it
    document.getElementById('manageSection').classList.add('visible');
    document.getElementById('manageSection').scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Reset to students tab
    switchTab('students', document.querySelector('.class-tab'));
}

function switchTab(name, btn) {
    document.querySelectorAll('.class-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    ['students','grades','announcements'].forEach(t => {
        const el = document.getElementById('tab-' + t);
        el.style.display = t === name ? 'block' : 'none';
        el.classList.toggle('active', t === name);
    });
    if (name === 'students') loadStudents();
    else if (name === 'grades') loadGrades();
    else loadAnnouncements();
}

// ── STUDENTS ─────────────────────────────────────────────────────────────────
async function loadStudents() {
    document.getElementById('studentList').innerHTML = '<p class="no-data">Loading…</p>';
    const students = await fetch(`php/class_manager.php?action=get_grades&course_id=${currentCourse.id}&email=${encodeURIComponent(_u.email)}`).then(r => r.json());
    document.getElementById('studentCount').textContent = students.length + ' student(s)';
    document.getElementById('studentList').innerHTML = students.length ? students.map(s => {
        const ini = (s.fname[0] + s.lname[0]).toUpperCase();
        const gradeTag = s.grade ? `<span class="tag tag-blue" style="margin-left:auto;">${s.grade}${s.score !== null ? ' · ' + s.score : ''}</span>` : '';
        return `<div class="student-row">
            <div class="s-av">${s.photo ? `<img src="${s.photo}" alt="${ini}">` : ini}</div>
            <div>
                <div style="font-weight:600;font-size:0.88rem;color:var(--primary);">${s.fname} ${s.lname}</div>
                <div style="font-size:0.75rem;color:var(--muted);">${s.student_id}</div>
            </div>
            ${gradeTag}
        </div>`;
    }).join('') : '<p class="no-data">No active students enrolled in this course yet.</p>';
}

// ── GRADES ────────────────────────────────────────────────────────────────────
async function loadGrades() {
    document.getElementById('gradesTbody').innerHTML = '<tr><td colspan="6" class="no-data">Loading…</td></tr>';
    const students = await fetch(`php/class_manager.php?action=get_grades&course_id=${currentCourse.id}&email=${encodeURIComponent(_u.email)}`).then(r => r.json());
    document.getElementById('gradesTbody').innerHTML = students.length ? students.map(s => `
        <tr>
            <td><strong>${s.fname} ${s.lname}</strong></td>
            <td style="font-size:0.78rem;color:var(--muted);">${s.student_id}</td>
            <td><input type="number" min="0" max="100" step="0.5" value="${s.score ?? ''}" placeholder="0–100" id="sc-${s.student_id}"></td>
            <td>
                <select id="gr-${s.student_id}">
                    <option value="">—</option>
                    ${['A+','A','A-','B+','B','B-','C+','C','C-','D','F'].map(g =>
                        `<option ${s.grade===g?'selected':''}>${g}</option>`).join('')}
                </select>
            </td>
            <td><input type="text" value="${s.remark ?? ''}" placeholder="Optional remark" id="rm-${s.student_id}"></td>
            <td>
                <button class="save-grade-btn" onclick="saveGrade('${s.student_id}')">💾</button>
                <span class="grade-saved" id="ok-${s.student_id}">✓</span>
            </td>
        </tr>`).join('') :
        '<tr><td colspan="6" class="no-data">No active students in this course.</td></tr>';
}

async function saveGrade(sid) {
    await fetch('php/class_manager.php', { method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ action:'save_grade', email:_u.email, course_id:currentCourse.id,
            student_id:sid,
            score: document.getElementById(`sc-${sid}`).value,
            grade: document.getElementById(`gr-${sid}`).value,
            remark: document.getElementById(`rm-${sid}`).value }) });
    const ok = document.getElementById(`ok-${sid}`);
    ok.style.display = 'inline';
    setTimeout(() => ok.style.display = 'none', 2000);
}

// ── ANNOUNCEMENTS ─────────────────────────────────────────────────────────────
async function loadAnnouncements() {
    document.getElementById('annList').innerHTML = '<p class="no-data">Loading…</p>';
    const anns = await fetch(`php/class_manager.php?action=get_announcements&course_id=${currentCourse.id}&email=${encodeURIComponent(_u.email)}`).then(r => r.json());
    document.getElementById('annList').innerHTML = anns.length ? anns.map(a => `
        <div class="ann-card" id="ann-${a.id}">
            <h4>${a.title}</h4>
            <p>${a.body}</p>
            <div class="ann-meta">📅 ${new Date(a.created_at).toLocaleString()} · ${a.teacher_name}</div>
            <button class="ann-del" onclick="deleteAnn(${a.id})" title="Delete">🗑️</button>
        </div>`).join('') : '<p class="no-data">No announcements yet. Post one above.</p>';
}

async function postAnnouncement() {
    const title = document.getElementById('annTitle').value.trim();
    const body  = document.getElementById('annBody').value.trim();
    const msg   = document.getElementById('annMsg');
    if (!title || !body) { msg.textContent = 'Title and body are required.'; return; }
    await fetch('php/class_manager.php', { method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ action:'post_announcement', email:_u.email, course_id:currentCourse.id, title, body }) });
    document.getElementById('annTitle').value = '';
    document.getElementById('annBody').value  = '';
    msg.textContent = '✓ Posted!';
    setTimeout(() => msg.textContent = '', 2000);
    loadAnnouncements();
}

async function deleteAnn(id) {
    if (!confirm('Delete this announcement?')) return;
    await fetch('php/class_manager.php', { method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ action:'delete_announcement', email:_u.email, id }) });
    document.getElementById('ann-' + id)?.remove();
}

init();
</script>
</body>
</html>
