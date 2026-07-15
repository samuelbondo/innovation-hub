<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Detail | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dept-hero { display:flex; align-items:center; gap:20px; background:#fff; border-radius:14px; padding:28px 32px; box-shadow:var(--shadow); margin-bottom:24px; border-left:6px solid var(--accent); }
        .dept-hero .big-icon { font-size:3.5rem; flex-shrink:0; }
        .dept-hero h2 { color:var(--primary); font-size:1.5rem; margin-bottom:4px; }
        .dept-hero p { color:var(--muted); font-size:0.88rem; line-height:1.6; max-width:600px; }
        .dept-hero .meta { display:flex; gap:20px; flex-wrap:wrap; margin-top:12px; font-size:0.82rem; color:#555; }
        .dept-hero .meta span { display:flex; align-items:center; gap:5px; }
        .tabs { display:flex; gap:4px; margin-bottom:20px; background:#fff; border-radius:10px; padding:6px; box-shadow:var(--shadow); width:fit-content; }
        .tab { padding:9px 22px; border-radius:7px; cursor:pointer; font-size:0.88rem; font-weight:600; color:var(--muted); border:none; background:none; transition:background 0.2s,color 0.2s; }
        .tab.active { background:var(--accent); color:#fff; }
        .tab-panel { display:none; }
        .tab-panel.active { display:block; }
        .student-row { display:flex; align-items:center; gap:14px; padding:12px 16px; border-bottom:1px solid #f4f4f4; cursor:pointer; transition:background 0.15s; border-radius:8px; margin-bottom:2px; }
        .student-row:last-child { border-bottom:none; }
        .student-row:hover { background:#fef2f4; }
        .s-av { width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.78rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .s-av img { width:38px;height:38px;border-radius:50%;object-fit:cover; }
        .s-info { flex:1; }
        .s-info .name { font-weight:600; font-size:0.9rem; color:var(--primary); }
        .s-info .sub { font-size:0.76rem; color:var(--muted); margin-top:2px; display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
        .adm-pill { display:inline-block;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:700; }
        .adm-Pending      { background:#fff8e1;color:#856404; }
        .adm-Under-Review { background:#e8f4fd;color:#0d6efd; }
        .adm-Approved     { background:#d4edda;color:#155724; }
        .adm-Rejected     { background:#f8d7da;color:#721c24; }
        .course-row { display:flex; align-items:center; justify-content:space-between; padding:13px 0; border-bottom:1px solid #f4f4f4; gap:12px; flex-wrap:wrap; }
        .course-row:last-child { border-bottom:none; }
        .course-row .c-code { font-size:0.72rem; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; }
        .course-row .c-title { font-weight:600; color:var(--primary); font-size:0.9rem; }
        .course-row .c-meta { font-size:0.76rem; color:var(--muted); display:flex; gap:10px; flex-wrap:wrap; margin-top:3px; }
        .course-row .c-teacher { font-size:0.8rem; color:#555; }
        .teacher-row { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f4f4f4; }
        .teacher-row:last-child { border-bottom:none; }
        .t-av { width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#007bff,#0056b3);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.78rem;font-weight:700;flex-shrink:0; }
        .empty-state { text-align:center; padding:40px; color:var(--muted); font-size:0.9rem; }
        .sem-badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:0.7rem; font-weight:600; background:#e8f4fd; color:#0056b3; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2 id="pageTitle">🏫 Department</h2>
                    <p id="pageSubtitle">Loading…</p>
                </div>
                <a href="department.php" class="btn btn-outline" id="backBtn">← Back to Departments</a>
            </div>

            <div id="mainContent" style="display:none;">

                <div class="dept-hero">
                    <div class="big-icon" id="dIcon"></div>
                    <div>
                        <h2 id="dName"></h2>
                        <p id="dDesc"></p>
                        <div class="meta">
                            <span>🏛️ <span id="dFaculty"></span></span>
                            <span>👤 <span id="dHead"></span></span>
                            <span id="metaStudents">🎓 <span id="dStudents"></span> students</span>
                            <span>📚 <span id="dCourses"></span> courses</span>
                            <span id="metaTeachers">👨‍🏫 <span id="dTeachers"></span> teachers</span>
                        </div>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active" data-tab="students">🎓 Students</button>
                    <button class="tab" data-tab="courses">📚 Courses</button>
                    <button class="tab" data-tab="teachers">👨🏫 Teachers</button>
                </div>

                <!-- Students -->
                <div class="tab-panel active panel" id="tab-students">
                    <div class="panel-header">
                        <h3>🎓 Enrolled Students</h3>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="studentSearch" placeholder="Search students…" style="padding:7px 12px;border:1.5px solid var(--border);border-radius:7px;font-size:0.85rem;outline:none;width:180px;">
                            <select id="studentAdmFilter" style="padding:7px 12px;border:1.5px solid var(--border);border-radius:7px;font-size:0.84rem;background:#fff;outline:none;">
                                <option value="">All Admissions</option>
                                <option value="Pending">Pending</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div style="padding:8px 16px;" id="studentList"></div>
                </div>

                <!-- Courses -->
                <div class="tab-panel panel" id="tab-courses">
                    <div class="panel-header"><h3>📚 Department Courses</h3></div>
                    <div style="padding:16px 24px;" id="courseList"></div>
                </div>

                <!-- Teachers -->
                <div class="tab-panel panel" id="tab-teachers">
                    <div class="panel-header"><h3>👨🏫 Department Teachers</h3></div>
                    <div style="padding:16px 24px;" id="teacherList"></div>
                </div>

            </div>

            <div id="notFound" style="display:none;text-align:center;padding:60px;color:var(--muted);">
                <div style="font-size:3rem;">⚠️</div>
                <p style="margin-top:12px;">Department not found.</p>
                <a href="department.php" class="btn" style="margin-top:16px;">← Back</a>
            </div>

        </div>
        <footer class="app-footer">
            <span>&copy; 2025 <strong style="color:var(--accent)">Group One</strong>. All rights reserved.</span>
            <span>Web Development Project 2025</span>
        </footer>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const id = new URLSearchParams(location.search).get('id');
if (!id) location.href = 'department.php';

const _u = JSON.parse(localStorage.getItem('user') || '{}');
const isStudent = _u.role === 'student';
const isTeacher = _u.role === 'teacher';
const canViewStudent = _u.role === 'admin' || _u.role === 'staff';

// Back button
document.getElementById('backBtn').href = isStudent ? 'dashboard.php' : 'department.php';
document.getElementById('backBtn').textContent = isStudent ? '← Back to Dashboard' : '← Back to Departments';

// Students tab: hidden for students and teachers
if (isStudent || isTeacher) {
    document.querySelector('[data-tab="students"]').style.display = 'none';
}
// Teachers tab + hero meta: hidden for teachers
if (isTeacher) {
    document.querySelector('[data-tab="teachers"]').style.display = 'none';
    document.getElementById('metaStudents').style.display = 'none';
    document.getElementById('metaTeachers').style.display = 'none';
}
// Default active tab for teachers = courses
if (isTeacher) {
    document.querySelector('.tab.active').classList.remove('active');
    document.querySelector('[data-tab="courses"]').classList.add('active');
    document.getElementById('tab-students').classList.remove('active');
    document.getElementById('tab-courses').classList.add('active');
}

let allStudents = [];
let myCoursesIds = new Set();

// For teachers, pre-load their assigned course IDs
const deptFetch = fetch('php/manage_departments.php?id=' + id).then(r => r.json());
const teacherFetch = isTeacher
    ? fetch('php/dashboard.php?email=' + encodeURIComponent(_u.email) + '&role=teacher').then(r => r.json())
    : Promise.resolve(null);

Promise.all([deptFetch, teacherFetch]).then(([data, tData]) => {
    if (tData && tData.courses) tData.courses.forEach(c => myCoursesIds.add(String(c.id)));
    if (data.error) { document.getElementById('notFound').style.display = 'block'; return; }
        const { dept, students, courses, teachers } = data;

        document.title = dept.name + ' | Group One SMS';
        document.getElementById('pageTitle').textContent = (dept.icon || '🏫') + ' ' + dept.name;
        document.getElementById('pageSubtitle').textContent = dept.faculty || 'Department Details';
        document.getElementById('mainContent').style.display = 'block';

        document.getElementById('dIcon').textContent     = dept.icon || '🏫';
        document.getElementById('dName').textContent     = dept.name;
        document.getElementById('dDesc').textContent     = dept.description || '—';
        document.getElementById('dFaculty').textContent  = dept.faculty || '—';
        document.getElementById('dHead').textContent     = dept.head;
        document.getElementById('dStudents').textContent = students.length;
        document.getElementById('dCourses').textContent  = isTeacher ? myCoursesIds.size : courses.length;
        document.getElementById('dTeachers').textContent = teachers.length;

        // Students
        allStudents = students;
        renderStudents(students);

        // Courses — teachers see only their own assigned courses
        const visibleCourses = isTeacher
            ? courses.filter(c => myCoursesIds.has(String(c.id)))
            : courses;
        const byYear = {};
        visibleCourses.forEach(c => { if (!byYear[c.year_level]) byYear[c.year_level] = []; byYear[c.year_level].push(c); });
        document.getElementById('courseList').innerHTML = Object.keys(byYear).sort().map(yr => `
            <div style="margin-bottom:20px;">
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--accent);margin-bottom:10px;">Year ${yr}</div>
                ${byYear[yr].map(c => `
                <div class="course-row">
                    <div>
                        <div class="c-code">${c.code}</div>
                        <div class="c-title">${c.title}</div>
                        <div class="c-meta">
                            <span class="sem-badge">Semester ${c.semester}</span>
                            <span>💳 ${c.credits} credits</span>
                        </div>
                    </div>
                    ${!isTeacher ? `<div class="c-teacher">👨🏫 ${c.teacher || 'Not assigned'}</div>` : ''}
                </div>`).join('')}
            </div>`).join('') || '<div class="empty-state">No courses found.</div>';

        // Teachers
        document.getElementById('teacherList').innerHTML = teachers.length ? teachers.map(t => {
            const ini = t.name.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
            return `<div class="teacher-row">
                <div class="t-av">${ini}</div>
                <div>
                    <div style="font-weight:600;color:var(--primary);font-size:0.9rem;">${t.name}</div>
                    <div style="font-size:0.76rem;color:var(--muted);">${t.email}</div>
                </div>
            </div>`;
        }).join('') : '<div class="empty-state">No teachers assigned to this department.</div>';
    });

function renderStudents(list) {
    document.getElementById('studentList').innerHTML = list.length ? list.map(s => {
        const ini  = (s.fname[0] + s.lname[0]).toUpperCase();
        const sc   = s.status === 'Active' ? 'active' : s.status === 'Suspended' ? 'suspended' : 'inactive';
        const adm  = (s.admission_status || 'Pending').replace(' ', '-');
        const link = canViewStudent ? `onclick="location.href='student_detail.php?id=${s.student_id}'"` : '';
        return `<div class="student-row" ${link}>
            <div class="s-av">${s.photo ? `<img src="${s.photo}">` : ini}</div>
            <div class="s-info">
                <div class="name">${s.fname} ${s.lname}</div>
                <div class="sub">
                    <span>${s.student_id}</span>
                    <span>· Year ${s.year_of_study}</span>
                    <span>· ${s.email}</span>
                    <span class="badge badge-${sc}">${s.status}</span>
                    <span class="adm-pill adm-${adm}">${s.admission_status || 'Pending'}</span>
                </div>
            </div>
        </div>`;
    }).join('') : '<div class="empty-state">No students found.</div>';
}

// Student search + admission filter
function filterStudents() {
    const q   = document.getElementById('studentSearch').value.toLowerCase();
    const adm = document.getElementById('studentAdmFilter').value;
    renderStudents(allStudents.filter(s =>
        (!q   || (s.fname+' '+s.lname).toLowerCase().includes(q) || s.student_id.toLowerCase().includes(q) || (s.email||'').toLowerCase().includes(q)) &&
        (!adm || s.admission_status === adm)
    ));
}
document.getElementById('studentSearch').addEventListener('input', filterStudents);
document.getElementById('studentAdmFilter').addEventListener('change', filterStudents);

// Tabs
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
    });
});
</script>
</body>
</html>
