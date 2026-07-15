<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .course-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:16px; }
        .course-card { background:#fff; border-radius:12px; padding:18px; box-shadow:var(--shadow); border-left:4px solid var(--accent); cursor:pointer; }
        .course-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.12); }
        .course-card .code { font-size:0.72rem; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; }
        .course-card h4 { color:var(--primary); margin:5px 0 4px; font-size:0.9rem; }
        .course-card .meta { font-size:0.76rem; color:var(--muted); display:flex; gap:8px; flex-wrap:wrap; margin-top:6px; }
        .course-card .teacher { font-size:0.78rem; color:#555; margin-top:5px; }
        .sem-badge { display:inline-block; padding:2px 7px; border-radius:10px; font-size:0.7rem; font-weight:600; background:#e8f4fd; color:#0056b3; }
        .profile-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; padding:20px 24px; }
        .profile-item label { font-size:0.72rem; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:3px; }
        .profile-item p { font-weight:600; color:var(--primary); font-size:0.88rem; word-break:break-word; }
        .student-mini { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid #f4f4f4; }
        .student-mini:last-child { border-bottom:none; }
        .student-mini .av { width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .student-mini .av img { width:34px;height:34px;object-fit:cover; }
        .student-mini .info .name { font-weight:600; font-size:0.86rem; color:var(--primary); }
        .student-mini .info .sub { font-size:0.74rem; color:var(--muted); }
        .dash-two-col { display:grid; grid-template-columns:1fr 300px; gap:20px; margin-top:20px; }
        .dash-two-col-lg { display:grid; grid-template-columns:1fr 340px; gap:20px; margin-top:20px; }
        @media(max-width:900px){ .dash-two-col,.dash-two-col-lg{ grid-template-columns:1fr; } }
        @media(max-width:600px){ .profile-grid{ grid-template-columns:1fr; } }
        .report-shortcuts { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px; padding:20px; }
        .report-shortcut { display:flex; align-items:center; gap:12px; padding:14px 16px; border:1.5px solid var(--border); border-radius:10px; text-decoration:none; transition:all 0.15s; background:#fff; }
        .report-shortcut:hover { border-color:var(--accent); background:#fef2f4; }
        .report-shortcut .rs-icon { font-size:1.5rem; flex-shrink:0; }
        .report-shortcut .rs-title { font-size:0.86rem; font-weight:600; color:var(--primary); }
        .report-shortcut .rs-sub { font-size:0.72rem; color:var(--muted); }
        .alert-banner { border-radius:10px; padding:12px 18px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:0.88rem; flex-wrap:wrap; }
        .alert-warning { background:#fff3cd; border:1px solid #ffc107; }
        .alert-danger  { background:#f8d7da; border:1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content" id="pageContent">
            <p style="color:var(--muted);padding:40px;text-align:center;">Loading&hellip;</p>
        </div>
        <footer class="app-footer">
            <span>&copy; 2025 <strong style="color:var(--accent)">Group One</strong>. All rights reserved.</span>
            <span>Web Development Project 2025</span>
        </footer>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');
const pc = document.getElementById('pageContent');

function statCard(icon, num, label, color, link) {
    const inner = `<div class="stat-icon">${icon}</div><div class="stat-info"><div class="num">${num}</div><p>${label}</p></div>`;
    return link
        ? `<a href="${link}" class="stat-card" data-color="${color}" style="text-decoration:none;">${inner}</a>`
        : `<div class="stat-card" data-color="${color}">${inner}</div>`;
}

/* ── ADMIN / STAFF ── */
function renderAdmin(d) {
    const pendingBanner = d.pending > 0
        ? `<div class="alert-banner alert-warning">
            <span style="font-size:1.2rem;">&#x23F3;</span>
            <span><strong>${d.pending}</strong> student admission${d.pending > 1 ? 's' : ''} awaiting review.</span>
            <a href="view_student.php?admission=Pending" class="btn btn-sm" style="margin-left:auto;">Review Now</a>
           </div>` : '';

    const admBadge = s => {
        const c = s.admission_status === 'Approved' ? 'active' : s.admission_status === 'Rejected' ? 'inactive' : 'pending';
        return `<span class="badge badge-${c}">${s.admission_status}</span>`;
    };

    pc.innerHTML = `
    <div class="page-header">
        <div><h2>&#x1F4CA; Dashboard</h2><p>Welcome back, ${_u.name}! Here&rsquo;s your system overview.</p></div>
    </div>
    ${pendingBanner}
    <div class="stat-grid">
        ${statCard('&#x1F393;', d.total,     'Total Students',    'default', 'view_student.php')}
        ${statCard('&#x2705;',  d.active,    'Active',            'green',   'view_student.php')}
        ${statCard('&#x274C;',  d.inactive,  'Inactive',          'red',     'view_student.php')}
        ${d.suspended > 0 ? statCard('&#x1F6AB;', d.suspended, 'Suspended', 'orange', 'view_student.php') : ''}
        ${statCard('&#x23F3;',  d.pending,   'Pending Admission', 'orange',  'view_student.php?admission=Pending')}
        ${statCard('&#x1F3EB;', d.depts,     'Departments',       'teal',    'department.php')}
        ${statCard('&#x1F3DB;&#xFE0F;', d.faculties, 'Faculties','purple',  'faculties.php')}
        ${statCard('&#x1F4DA;', d.courses,   'Courses',           'blue',    'manage_courses.php')}
        ${statCard('&#x1F468;&#x200D;&#x1F3EB;', d.teachers, 'Teachers', 'orange', 'manage_users.php')}
        ${statCard('&#x1F465;', d.users,     'Total Users',       'default', 'manage_users.php')}
    </div>
    <div class="panel">
        <div class="panel-header">
            <h3>&#x1F550; Recently Added Students</h3>
            <a href="view_student.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Student ID</th><th>Full Name</th><th>Department</th><th>Status</th><th>Admission</th><th>Registered</th></tr></thead>
                <tbody>${d.recent.length ? d.recent.map(s => `
                    <tr style="cursor:pointer;" onclick="location.href='student_detail.php?id=${s.student_id}'">
                        <td><strong>${s.student_id}</strong></td>
                        <td>${s.fname} ${s.lname}</td>
                        <td>${s.department}</td>
                        <td><span class="badge badge-${s.status === 'Active' ? 'active' : 'inactive'}">${s.status}</span></td>
                        <td>${admBadge(s)}</td>
                        <td>${new Date(s.created_at).toLocaleDateString()}</td>
                    </tr>`).join('') : '<tr><td colspan="6" style="text-align:center;color:var(--muted);padding:24px;">No students yet.</td></tr>'}
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel" style="margin-top:20px;">
        <div class="panel-header"><h3>&#x26A1; Quick Access</h3></div>
        <div class="report-shortcuts">
            <a href="reports.php?type=students" class="report-shortcut">
                <div class="rs-icon">&#x1F393;</div>
                <div><div class="rs-title">Student Report</div><div class="rs-sub">All registered students</div></div>
            </a>
            <a href="reports.php?type=admissions" class="report-shortcut">
                <div class="rs-icon">&#x1F4CB;</div>
                <div><div class="rs-title">Admission Report</div><div class="rs-sub">Admission statuses</div></div>
            </a>
            <a href="manage_courses.php" class="report-shortcut">
                <div class="rs-icon">&#x1F4DA;</div>
                <div><div class="rs-title">Manage Courses</div><div class="rs-sub">Add &amp; assign courses</div></div>
            </a>
            <a href="manage_users.php" class="report-shortcut">
                <div class="rs-icon">&#x1F465;</div>
                <div><div class="rs-title">Manage Users</div><div class="rs-sub">Teachers &amp; staff accounts</div></div>
            </a>
        </div>
    </div>`;
}

/* ── TEACHER ── */
function renderTeacher(d) {
    const s = d.stats || {};

    const noDeptNote = !d.teacher || !d.teacher.department_id
        ? `<div class="alert-banner alert-warning">&#x26A0;&#xFE0F; You have no department assigned. Contact admin to update your profile.</div>` : '';

    const coursesHTML = (d.courses || []).map(c => `
        <div class="course-card" onclick="location.href='my_classes.php'">
            <div class="code">${c.code}</div>
            <h4>${c.title}</h4>
            <div class="meta">
                <span>Year ${c.year_level}</span>
                <span class="sem-badge">Sem ${c.semester}</span>
                <span>${c.credits} credits</span>
                <span>${c.student_count || 0} student${c.student_count != 1 ? 's' : ''}</span>
            </div>
            <div class="teacher">${c.department}</div>
        </div>`).join('') || '<p style="color:var(--muted);font-size:0.88rem;padding:8px 0;">No courses assigned yet. Contact admin.</p>';

    pc.innerHTML = `
    <div class="page-header">
        <div><h2>&#x1F468;&#x200D;&#x1F3EB; Teacher Dashboard</h2><p>Welcome, ${_u.name}! Here&rsquo;s your teaching overview.</p></div>
        <a href="my_classes.php" class="btn">&#x1F4DA; My Courses &amp; Classes</a>
    </div>
    ${noDeptNote}
    <div class="stat-grid">
        ${statCard('&#x1F4DA;', s.my_courses     || 0, 'My Courses',        'blue',   'my_classes.php')}
        ${statCard('&#x1F393;', s.total_enrolled || 0, 'Students in My Courses', 'green', 'my_classes.php')}
        ${statCard('&#x2705;',  s.dept_students  || 0, 'Active in Dept',    'teal')}
        ${statCard('&#x1F3EB;', s.dept_courses   || 0, 'Dept. Courses',     'orange')}
    </div>
    <div class="panel" style="margin-top:20px;">
        <div class="panel-header">
            <h3>&#x1F4DA; My Assigned Courses</h3>
            <a href="my_classes.php" class="btn btn-sm btn-outline">Manage Classes</a>
        </div>
        <div style="padding:20px;"><div class="course-grid">${coursesHTML}</div></div>
    </div>`;
}

/* ── STUDENT ── */
async function renderStudent(d) {
    const s = d.student;
    if (!s) {
        pc.innerHTML = `<div style="text-align:center;padding:60px;color:var(--muted);"><div style="font-size:3rem;">&#x26A0;&#xFE0F;</div><p style="margin-top:12px;">No student record found for your account.</p></div>`;
        return;
    }

    const admColors = { Approved:'active', Rejected:'inactive', Pending:'pending', 'Under Review':'pending' };
    const admissionBanner = s.admission_status !== 'Approved'
        ? `<div class="alert-banner ${s.admission_status === 'Rejected' ? 'alert-danger' : 'alert-warning'}">
            <span>${s.admission_status === 'Rejected' ? '&#x274C;' : '&#x23F3;'}</span>
            <span><strong>Admission ${s.admission_status}</strong> &mdash; Your account is limited until admission is approved.</span>
            <a href="track_admission.php" class="btn btn-sm btn-outline" style="margin-left:auto;">Track Status</a>
           </div>` : '';

    // Check enrollment window
    const enrollBanner = await fetch('php/enrollment_window.php?action=check').then(r=>r.json()).then(w => {
        if (!w.open) return '';
        const until = new Date(w.window.open_until);
        const opts  = {month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit'};
        const fmt   = until.toLocaleDateString('en-US', opts);
        return `<div class="alert-banner" style="background:#e8f4fd;border:1px solid #b8daff;">
            <span>&#x1F4DA;</span>
            <span><strong>Enrollment is OPEN</strong> &mdash; closes ${fmt}. You can self-enroll in 1 course.</span>
            <a href="enroll_courses.php" class="btn btn-sm" style="margin-left:auto;">Enroll Now</a>
        </div>`;
    }).catch(() => '');

    const coursesHTML = (d.courses || []).map(c => `
        <div class="course-card" onclick="location.href='student_courses.php'">
            <div class="code">${c.code}</div>
            <h4>${c.title}</h4>
            <div class="meta">
                <span class="sem-badge">Semester ${c.semester}</span>
                <span>${c.credits} credits</span>
            </div>
            <div class="teacher">&#x1F468;&#x200D;&#x1F3EB; ${c.teacher_name || '<em style="color:var(--muted)">Not assigned</em>'}</div>
        </div>`).join('') || '<p style="color:var(--muted);font-size:0.88rem;padding:8px 0;">No courses found for your year.</p>';

    pc.innerHTML = `
    <div class="page-header">
        <div><h2>&#x1F393; My Dashboard</h2><p>Welcome back, ${s.fname}!</p></div>
    </div>
    ${admissionBanner}
    ${enrollBanner}
    <div class="stat-grid">
        ${statCard('&#x1FAA6;', s.student_id,             'Student ID',    'default')}
        ${statCard('&#x1F3EB;', s.department,              'Department',    'blue')}
        ${statCard('&#x1F4C5;', 'Year ' + s.year_of_study, 'Year of Study', 'orange')}
        ${statCard('&#x2705;',  s.status,                  'Status',        s.status === 'Active' ? 'green' : 'red')}
        ${statCard('&#x1F4CB;', s.admission_status,        'Admission',     admColors[s.admission_status] || 'default', 'track_admission.php')}
    </div>
    <div class="dash-two-col">
        <div class="panel">
            <div class="panel-header">
                <h3>&#x1F4DA; My Courses &mdash; Year ${s.year_of_study}</h3>
                <a href="student_courses.php" class="btn btn-sm btn-outline">View All</a>
            </div>
            <div style="padding:20px;"><div class="course-grid">${coursesHTML}</div></div>
        </div>
        <div class="panel">
            <div class="panel-header"><h3>&#x1F464; My Profile</h3></div>
            <div class="profile-grid">
                <div class="profile-item"><label>Full Name</label><p>${s.fname} ${s.lname}</p></div>
                <div class="profile-item"><label>Faculty</label><p>${s.faculty || '&mdash;'}</p></div>
                <div class="profile-item"><label>Email</label><p>${s.email}</p></div>
                <div class="profile-item"><label>Phone</label><p>${s.phone || '&mdash;'}</p></div>
                <div class="profile-item"><label>Date of Birth</label><p>${s.dob || '&mdash;'}</p></div>
                <div class="profile-item"><label>Enrolled</label><p>${new Date(s.created_at).toLocaleDateString()}</p></div>
            </div>
        </div>
    </div>`;
}

/* ── LOAD ── */
if (_u.role === 'student') {
    fetch(`php/dashboard.php?email=${encodeURIComponent(_u.email)}&role=student`)
        .then(r => r.json()).then(renderStudent);
} else if (_u.role === 'teacher') {
    fetch(`php/dashboard.php?email=${encodeURIComponent(_u.email)}&role=teacher`)
        .then(r => r.json()).then(renderTeacher);
} else {
    fetch('php/dashboard.php').then(r => r.json()).then(renderAdmin);
}
</script>
</body>
</html>
