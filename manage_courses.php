<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .assign-select { padding:5px 8px; border:1.5px solid var(--border); border-radius:6px; font-size:0.82rem; background:#fafafa; max-width:180px; }
        .assign-select:focus { outline:none; border-color:var(--accent); }
        .faculty-label { font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--accent); margin:24px 0 8px; padding:0 4px; }
        .enroll-btn { padding:4px 10px; font-size:0.78rem; border-radius:6px; border:1.5px solid var(--accent); background:#fff; color:var(--accent); cursor:pointer; white-space:nowrap; }
        .enroll-btn:hover { background:var(--accent); color:#fff; }
        /* Modal */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:999; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:#fff; border-radius:14px; padding:28px 32px; max-width:560px; width:95%; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 8px 32px rgba(0,0,0,0.18); }
        .modal-box h3 { margin:0 0 4px; }
        .modal-sub { font-size:0.82rem; color:var(--muted); margin-bottom:16px; }
        .modal-search { padding:7px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:0.85rem; width:100%; box-sizing:border-box; margin-bottom:12px; }
        .modal-search:focus { outline:none; border-color:var(--accent); }
        .enroll-list { overflow-y:auto; flex:1; border:1px solid var(--border); border-radius:8px; }
        .enroll-row { display:flex; align-items:center; gap:12px; padding:10px 14px; border-bottom:1px solid #f4f4f4; }
        .enroll-row:last-child { border-bottom:none; }
        .enroll-row:hover { background:#fafafa; }
        .e-av { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.72rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .e-av img { width:32px;height:32px;object-fit:cover; }
        .enroll-toggle { margin-left:auto; padding:4px 14px; border-radius:20px; border:none; font-size:0.78rem; font-weight:600; cursor:pointer; }
        .enroll-toggle.enrolled { background:#d4edda; color:#155724; }
        .enroll-toggle.enrolled:hover { background:#c3e6cb; }
        .enroll-toggle.not-enrolled { background:#e8f4fd; color:#0056b3; }
        .enroll-toggle.not-enrolled:hover { background:#cce5ff; }
        .modal-footer { margin-top:14px; display:flex; justify-content:space-between; align-items:center; }
        .enroll-count { font-size:0.82rem; color:var(--muted); }
        .enroll-filter-btn { padding:4px 12px; border-radius:20px; border:1.5px solid var(--border); background:#fff; font-size:0.78rem; font-weight:600; cursor:pointer; color:var(--muted); }
        .enroll-filter-btn.active { background:var(--accent); color:#fff; border-color:var(--accent); }
        .same-dept-badge { font-size:0.68rem; background:#e8f4fd; color:#0056b3; border-radius:8px; padding:1px 6px; margin-left:4px; font-weight:600; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div><h2>📚 Manage Courses</h2><p>Add courses, assign teachers, and enroll students</p></div>
                <div style="display:flex;gap:8px;">
                    <button class="btn btn-outline" onclick="document.getElementById('csvInput').click()">📥 Import CSV</button>
                    <input type="file" id="csvInput" accept=".csv" style="display:none" onchange="importCSV(this)">
                    <a href="#" onclick="downloadTemplate();return false;" class="btn btn-outline">📄 CSV Template</a>
                    <button class="btn" onclick="toggleForm()">➕ Add Course</button>
                </div>
            </div>

            <!-- CSV Guide -->
            <div class="panel" id="csvGuide" style="margin-bottom:20px;border-left:4px solid var(--accent);">
                <div class="panel-header" style="cursor:pointer;" onclick="toggleGuide()">
                    <h3>📋 CSV Import Format Guide</h3>
                    <span id="guideToggle" style="font-size:0.82rem;color:var(--accent);">▼ Show</span>
                </div>
                <div id="guideBody" style="display:none;padding:16px 24px;">
                    <p style="font-size:0.85rem;color:var(--muted);margin:0 0 12px;">Your CSV must have exactly <strong>6 columns</strong>. First row is the header and will be skipped.</p>
                    <div class="table-wrap" style="margin-bottom:14px;">
                        <table style="font-size:0.83rem;">
                            <thead><tr><th>#</th><th>Column</th><th>Required</th><th>Example</th><th>Notes</th></tr></thead>
                            <tbody>
                                <tr><td>1</td><td><strong>code</strong></td><td>✅</td><td>CS101</td><td>Must be unique. Auto-uppercased.</td></tr>
                                <tr><td>2</td><td><strong>title</strong></td><td>✅</td><td>Intro to Programming</td><td>Full course name.</td></tr>
                                <tr><td>3</td><td><strong>department</strong></td><td>✅</td><td>Computer Science</td><td>Must match existing department exactly.</td></tr>
                                <tr><td>4</td><td><strong>year_level</strong></td><td>✅</td><td>1</td><td>1 – 4.</td></tr>
                                <tr><td>5</td><td><strong>semester</strong></td><td>✅</td><td>1</td><td>1 or 2.</td></tr>
                                <tr><td>6</td><td><strong>credits</strong></td><td>✅</td><td>3</td><td>1 – 9.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="background:#f4f4f4;border-radius:8px;padding:12px 16px;font-family:monospace;font-size:0.82rem;color:#333;">
                        code,title,department,year_level,semester,credits<br>
                        CS101,Introduction to Programming,Computer Science,1,1,3
                    </div>
                </div>
            </div>

            <!-- Add Course Form -->
            <div class="panel" id="addForm" style="display:none;margin-bottom:24px;">
                <div class="panel-header"><h3>Add New Course</h3></div>
                <div style="padding:24px;">
                    <div class="error-msg" id="formErr"></div>
                    <div class="success-msg" id="formOk"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group"><label>Course Code</label><input type="text" id="cCode" placeholder="e.g. SE101"></div>
                        <div class="form-group"><label>Course Title</label><input type="text" id="cTitle" placeholder="e.g. Software Engineering"></div>
                        <div class="form-group"><label>Faculty</label>
                            <select id="cFaculty"><option value="">-- Select Faculty --</option></select>
                        </div>
                        <div class="form-group"><label>Department</label>
                            <select id="cDept" disabled><option value="">-- Select Faculty First --</option></select>
                        </div>
                        <div class="form-group"><label>Year Level</label>
                            <select id="cYear">
                                <option value="1">Year 1</option><option value="2">Year 2</option>
                                <option value="3">Year 3</option><option value="4">Year 4</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Semester</label>
                            <select id="cSem"><option value="1">Semester 1</option><option value="2">Semester 2</option></select>
                        </div>
                        <div class="form-group"><label>Credits</label><input type="number" id="cCredits" value="3" min="1" max="9"></div>
                        <div class="form-group"><label>Description</label><input type="text" id="cDesc" placeholder="Brief description"></div>
                    </div>
                    <div class="form-actions">
                        <button class="btn" onclick="saveCourse()">💾 Save Course</button>
                        <button class="btn btn-outline" onclick="toggleForm()">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="panel">
                <div class="table-toolbar">
                    <div class="table-search">🔍 <input type="text" id="searchInput" placeholder="Search courses…"></div>
                    <span id="countLabel" style="font-size:0.85rem;color:var(--muted);"></span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Code</th><th>Title</th><th>Department</th><th>Year</th><th>Sem</th><th>Credits</th><th>Assigned Teacher</th><th>Enrolled</th><th>Actions</th></tr></thead>
                        <tbody id="courseTbody"><tr><td colspan="9" style="text-align:center;padding:28px;color:var(--muted);">Loading…</td></tr></tbody>
                    </table>
                </div>
            </div>

        </div>
        <footer class="app-footer">
            <span>&copy; 2025 <strong style="color:var(--accent)">Group One</strong>. All rights reserved.</span>
            <span>Web Development Project 2025</span>
        </footer>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:28px 32px;max-width:480px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,0.18);">
        <h3 style="margin:0 0 14px;">📥 Import Result</h3>
        <div id="importResult"></div>
        <button class="btn" style="margin-top:18px;" onclick="document.getElementById('importModal').style.display='none'">Close</button>
    </div>
</div>

<!-- Enroll Students Modal -->
<div class="modal-overlay" id="enrollModal">
    <div class="modal-box">
        <h3 id="enrollModalTitle">Enroll Students</h3>
        <div class="modal-sub" id="enrollModalSub"></div>
        <div style="display:flex;gap:8px;margin-bottom:10px;">
            <button class="enroll-filter-btn active" id="filterAll"    onclick="setFilter('all')">All Students</button>
            <button class="enroll-filter-btn"        id="filterDept"   onclick="setFilter('dept')">Same Dept Only</button>
            <button class="enroll-filter-btn"        id="filterEnrolled" onclick="setFilter('enrolled')">Enrolled Only</button>
        </div>
        <input type="text" class="modal-search" id="enrollSearch" placeholder="Search students…" oninput="filterEnrollList()">
        <div class="enroll-list" id="enrollList"><p style="padding:20px;color:var(--muted);text-align:center;">Loading…</p></div>
        <div class="modal-footer">
            <span class="enroll-count" id="enrollCount"></span>
            <button class="btn btn-outline" onclick="closeEnrollModal()">Done</button>
        </div>
    </div>
</div>

<script src="shared.js?v=2"></script>
<script>
let allCourses = [], teachers = [], facultyData = [], deptIdMap = {};
let enrollStudents = [], currentEnrollCourse = null, activeFilter = 'all';

Promise.all([
    fetch('php/get_courses.php').then(r => r.json()),
    fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'get_teachers'})}).then(r => r.json()),
    fetch('php/get_faculties.php').then(r => r.json())
]).then(([courses, tchs, facs]) => {
    allCourses = courses; teachers = tchs; facultyData = facs;
    facs.forEach(f => f.departments.forEach(d => { deptIdMap[d.name] = d.id; }));
    populateFacultyDropdown();
    renderCourses(courses);
});

function populateFacultyDropdown() {
    const sel = document.getElementById('cFaculty');
    facultyData.forEach(f => { sel.innerHTML += `<option value="${f.id}">${f.name}</option>`; });
}

document.getElementById('cFaculty').addEventListener('change', function() {
    const ds = document.getElementById('cDept');
    ds.innerHTML = '<option value="">-- Select Department --</option>'; ds.disabled = true;
    const fac = facultyData.find(f => f.id == this.value);
    if (!fac) return;
    fac.departments.forEach(d => { ds.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
    ds.disabled = false;
});

function teacherOptions(currentId) {
    return `<option value="">-- Unassigned --</option>` +
        teachers.filter(t => t.role === 'teacher').map(t =>
            `<option value="${t.id}" ${t.id == currentId ? 'selected' : ''}>${t.name}</option>`).join('');
}

function renderCourses(list) {
    document.getElementById('countLabel').textContent = list.length + ' course(s)';
    document.getElementById('courseTbody').innerHTML = list.length ? list.map(c => `
        <tr>
            <td><strong>${c.code}</strong></td>
            <td>${c.title}</td>
            <td>${c.department}<br><small style="color:var(--muted);">${c.faculty || ''}</small></td>
            <td>Year ${c.year_level}</td>
            <td>Sem ${c.semester}</td>
            <td>${c.credits}</td>
            <td>
                <select class="assign-select" data-course="${c.id}" data-current="${c.teacher_id || ''}" onchange="assignTeacher(this)">
                    ${teacherOptions(c.teacher_id)}
                </select>
            </td>
            <td>
                <span id="ec-${c.id}" style="font-size:0.82rem;font-weight:600;color:var(--accent);">${c.student_count || 0}</span>
                <small style="color:var(--muted);"> student${c.student_count != 1 ? 's' : ''}</small>
            </td>
            <td style="display:flex;gap:6px;align-items:center;">
                <button class="enroll-btn" onclick="openEnrollModal(${c.id}, '${c.code}', '${c.title.replace(/'/g,"\\'")}')">👥 Enroll</button>
                <button class="btn btn-sm" style="background:#dc3545;" onclick="deleteCourse(${c.id})">🗑️</button>
            </td>
        </tr>`).join('') :
        '<tr><td colspan="9" style="text-align:center;padding:24px;color:var(--muted);">No courses found.</td></tr>';
}

document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    renderCourses(allCourses.filter(c =>
        c.code.toLowerCase().includes(q) || c.title.toLowerCase().includes(q) ||
        c.department.toLowerCase().includes(q)));
});

async function assignTeacher(sel) {
    const courseId   = sel.dataset.course;
    const oldTeacher = sel.dataset.current;
    const newTeacher = sel.value;
    if (oldTeacher) {
        await fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({action:'unassign', teacher_id:oldTeacher, course_id:courseId})});
    }
    if (newTeacher) {
        await fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({action:'assign', teacher_id:newTeacher, course_id:courseId})});
    }
    sel.dataset.current = newTeacher;
    const c = allCourses.find(x => x.id == courseId);
    if (c) { c.teacher_id = newTeacher; c.teacher_name = teachers.find(t => t.id == newTeacher)?.name || ''; }
}

// ── ENROLL MODAL ─────────────────────────────────────────────────────────────
async function openEnrollModal(courseId, code, title) {
    currentEnrollCourse = courseId;
    activeFilter = 'all';
    document.querySelectorAll('.enroll-filter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('filterAll').classList.add('active');
    document.getElementById('enrollModalTitle').textContent = `👥 Enroll Students — ${code}`;
    document.getElementById('enrollModalSub').textContent = title;
    document.getElementById('enrollSearch').value = '';
    document.getElementById('enrollList').innerHTML = '<p style="padding:20px;color:var(--muted);text-align:center;">Loading…</p>';
    document.getElementById('enrollModal').classList.add('open');

    const res = await fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'get_enrollments', course_id:courseId})});
    enrollStudents = await res.json();
    filterEnrollList();
}

function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.enroll-filter-btn').forEach(b => b.classList.remove('active'));
    const map = {all:'filterAll', dept:'filterDept', enrolled:'filterEnrolled'};
    document.getElementById(map[f]).classList.add('active');
    filterEnrollList();
}

function closeEnrollModal() {
    document.getElementById('enrollModal').classList.remove('open');
    if (currentEnrollCourse) {
        const enrolled = enrollStudents.filter(s => s.enrolled == 1).length;
        const el = document.getElementById('ec-' + currentEnrollCourse);
        if (el) el.textContent = enrolled;
        const c = allCourses.find(x => x.id == currentEnrollCourse);
        if (c) c.student_count = enrolled;
    }
}

function filterEnrollList() {
    const q = document.getElementById('enrollSearch').value.toLowerCase();
    let list = enrollStudents;
    if (activeFilter === 'dept')     list = list.filter(s => s.same_dept == 1);
    if (activeFilter === 'enrolled') list = list.filter(s => s.enrolled == 1);
    if (q) list = list.filter(s =>
        (s.fname + ' ' + s.lname).toLowerCase().includes(q) ||
        s.student_id.toLowerCase().includes(q) ||
        s.department.toLowerCase().includes(q));
    renderEnrollList(list);
}

function renderEnrollList(list) {
    const totalEnrolled = enrollStudents.filter(s => s.enrolled == 1).length;
    document.getElementById('enrollCount').textContent = `${totalEnrolled} enrolled`;
    if (!list.length) {
        document.getElementById('enrollList').innerHTML = '<p style="padding:20px;color:var(--muted);text-align:center;">No students found.</p>';
        return;
    }
    document.getElementById('enrollList').innerHTML = list.map(s => {
        const ini = (s.fname[0] + s.lname[0]).toUpperCase();
        const isEnrolled = s.enrolled == 1;
        const deptBadge = s.same_dept == 1 ? '<span class="same-dept-badge">same dept</span>' : '';
        return `<div class="enroll-row" id="er-${s.student_id}">
            <div class="e-av">${s.photo ? `<img src="${s.photo}" alt="${ini}">` : ini}</div>
            <div>
                <div style="font-weight:600;font-size:0.86rem;color:var(--primary);">${s.fname} ${s.lname}${deptBadge}</div>
                <div style="font-size:0.74rem;color:var(--muted);">${s.student_id} · ${s.department} · Year ${s.year_of_study}</div>
            </div>
            <button class="enroll-toggle ${isEnrolled ? 'enrolled' : 'not-enrolled'}"
                onclick="toggleEnroll('${s.student_id}', this)">
                ${isEnrolled ? '✓ Enrolled' : '+ Enroll'}
            </button>
        </div>`;
    }).join('');
}

async function toggleEnroll(studentId, btn) {
    const s = enrollStudents.find(x => x.student_id === studentId);
    if (!s) return;
    const enrolling = s.enrolled != 1;
    const action = enrolling ? 'enroll' : 'unenroll';
    await fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action, student_id:studentId, course_id:currentEnrollCourse})});
    s.enrolled = enrolling ? 1 : 0;
    btn.className = `enroll-toggle ${enrolling ? 'enrolled' : 'not-enrolled'}`;
    btn.textContent = enrolling ? '✓ Enrolled' : '+ Enroll';
    const totalEnrolled = enrollStudents.filter(x => x.enrolled == 1).length;
    document.getElementById('enrollCount').textContent = `${totalEnrolled} enrolled`;
}

async function deleteCourse(id) {
    if (!confirm('Delete this course?')) return;
    await fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'delete_course', course_id:id})});
    allCourses = allCourses.filter(c => c.id != id);
    renderCourses(allCourses);
}

function toggleForm() {
    const f = document.getElementById('addForm');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

function toggleGuide() {
    const body = document.getElementById('guideBody');
    const lbl  = document.getElementById('guideToggle');
    const open = body.style.display === 'none';
    body.style.display = open ? 'block' : 'none';
    lbl.textContent    = open ? '▲ Hide' : '▼ Show';
}

function downloadTemplate() {
    const csv = 'code,title,department,year_level,semester,credits\nCS101,Introduction to Programming,Computer Science,1,1,3\nIT201,Networking Fundamentals,Information Technology,2,1,3';
    const a = document.createElement('a');
    a.href = 'data:text/csv,' + encodeURIComponent(csv);
    a.download = 'courses_template.csv';
    a.click();
}

async function importCSV(input) {
    const file = input.files[0];
    if (!file) return;
    const fd = new FormData();
    fd.append('csv', file);
    input.value = '';
    const res  = await fetch('php/import_courses.php', {method:'POST', body:fd});
    const data = await res.json();
    if (data.error) { alert(data.error); return; }
    let html = `<p style="color:#155724;font-weight:600;">✅ ${data.inserted} course(s) imported.</p>`;
    if (data.skipped.length) {
        html += `<p style="color:#856404;margin-top:8px;font-weight:600;">⚠️ ${data.skipped.length} row(s) skipped:</p>`;
        html += '<ul style="font-size:0.83rem;color:#555;margin:6px 0 0 16px;">' + data.skipped.map(s => `<li>${s}</li>`).join('') + '</ul>';
    }
    document.getElementById('importResult').innerHTML = html;
    document.getElementById('importModal').style.display = 'flex';
    if (data.inserted) location.reload();
}

async function saveCourse() {
    const err = document.getElementById('formErr'), ok = document.getElementById('formOk');
    err.style.display = ok.style.display = 'none';
    const deptId = document.getElementById('cDept').value;
    if (!deptId) { err.textContent = 'Please select a department.'; err.style.display = 'block'; return; }
    const res = await fetch('php/manage_courses.php', {method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            action: 'add_course',
            code: document.getElementById('cCode').value.trim().toUpperCase(),
            title: document.getElementById('cTitle').value.trim(),
            credits: document.getElementById('cCredits').value,
            department_id: deptId,
            year_level: document.getElementById('cYear').value,
            semester: document.getElementById('cSem').value,
            description: document.getElementById('cDesc').value.trim()
        })});
    const data = await res.json();
    if (data.success) { ok.textContent = 'Course added.'; ok.style.display = 'block'; location.reload(); }
    else { err.textContent = data.error || 'Failed.'; err.style.display = 'block'; }
}

// Close modal on overlay click
document.getElementById('enrollModal').addEventListener('click', function(e) {
    if (e.target === this) closeEnrollModal();
});
</script>
</body>
</html>
