<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .report-cards { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:14px; margin-bottom:24px; }
        .report-card { background:#fff; border:2px solid var(--border); border-radius:12px; padding:18px 16px; cursor:pointer; text-align:center; transition:all 0.18s; }
        .report-card:hover, .report-card.active { border-color:var(--accent); background:#fef2f4; }
        .report-card .rc-icon { font-size:2rem; margin-bottom:8px; }
        .report-card h4 { font-size:0.9rem; color:var(--primary); margin:0 0 4px; }
        .report-card p { font-size:0.75rem; color:var(--muted); margin:0; }
        .filter-bar { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; padding:16px 20px; background:#f9f9f9; border-radius:10px; margin-bottom:18px; }
        .filter-bar .fg { display:flex; flex-direction:column; gap:4px; }
        .filter-bar label { font-size:0.72rem; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px; }
        .filter-bar select { padding:7px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:0.84rem; background:#fff; outline:none; }
        .filter-bar select:focus { border-color:var(--accent); }
        #previewWrap { display:none; }
        #previewWrap .panel-header { display:flex; align-items:center; justify-content:space-between; }
        .btn-pdf { background:var(--accent); color:#fff; border:none; border-radius:8px; padding:9px 20px; font-size:0.88rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px; }
        .btn-pdf:hover { opacity:0.88; }
        #reportTable th { background:var(--primary); color:#fff; }

        /* ── Print styles ── */
        @media print {
            body * { visibility:hidden; }
            #printArea, #printArea * { visibility:visible; }
            #printArea { position:fixed; inset:0; padding:24px 32px; background:#fff; }
            .print-header { display:flex !important; }
        }
        .print-header { display:none; align-items:center; justify-content:space-between; margin-bottom:18px; border-bottom:2px solid #333; padding-bottom:10px; }
        .print-header h2 { font-size:1.1rem; color:#111; margin:0; }
        .print-header small { font-size:0.75rem; color:#555; }
        #printArea table { width:100%; border-collapse:collapse; font-size:0.82rem; }
        #printArea th, #printArea td { border:1px solid #ccc; padding:7px 10px; text-align:left; }
        #printArea th { background:#f0f0f0; font-weight:700; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div><h2>📄 Reports</h2><p>Generate and download PDF reports</p></div>
            </div>

            <!-- Report type selector -->
            <div class="report-cards">
                <div class="report-card active" data-type="students" onclick="selectReport(this)">
                    <div class="rc-icon">🎓</div>
                    <h4>Student Report</h4>
                    <p>All registered students with filters</p>
                </div>
                <div class="report-card" data-type="enrollments" onclick="selectReport(this)">
                    <div class="rc-icon">📋</div>
                    <h4>Enrollment Report</h4>
                    <p>Students with their enrolled courses</p>
                </div>
                <div class="report-card" data-type="course_summary" onclick="selectReport(this)">
                    <div class="rc-icon">📚</div>
                    <h4>Course Summary</h4>
                    <p>Courses with enrollment counts</p>
                </div>
                <div class="report-card" data-type="departments" onclick="selectReport(this)">
                    <div class="rc-icon">🏫</div>
                    <h4>Department Report</h4>
                    <p>Departments with student &amp; course counts</p>
                </div>
                <div class="report-card" data-type="faculties" onclick="selectReport(this)">
                    <div class="rc-icon">🏛️</div>
                    <h4>Faculty Report</h4>
                    <p>Faculties with dept &amp; student stats</p>
                </div>
                <div class="report-card" data-type="admissions" onclick="selectReport(this)">
                    <div class="rc-icon">📝</div>
                    <h4>Admission Report</h4>
                    <p>Student admission status overview</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-bar" id="studentFilters">
                <div class="fg">
                    <label>Department</label>
                    <select id="fDept"><option value="">All Departments</option></select>
                </div>
                <div class="fg" id="fYearWrap">
                    <label>Year</label>
                    <select id="fYear">
                        <option value="">All Years</option>
                        <option value="1">Year 1</option>
                        <option value="2">Year 2</option>
                        <option value="3">Year 3</option>
                        <option value="4">Year 4</option>
                    </select>
                </div>
                <div class="fg" id="fStatusWrap">
                    <label>Status</label>
                    <select id="fStatus">
                        <option value="">All</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <button class="btn" onclick="generateReport()">🔍 Generate</button>
            </div>

            <!-- Preview -->
            <div id="previewWrap" class="panel">
                <div class="panel-header">
                    <h3 id="previewTitle">Report Preview</h3>
                    <div style="display:flex;gap:8px;">
                        <button class="btn-pdf" style="background:#28a745;" onclick="downloadCSV()">📥 CSV</button>
                        <button class="btn-pdf" onclick="downloadPDF()">🖨️ PDF</button>
                    </div>
                </div>
                <div id="printArea" style="padding:0 20px 20px;">
                    <div class="print-header">
                        <div>
                            <h2 id="pTitle"></h2>
                            <small id="pMeta"></small>
                        </div>
                        <small>Group One Student Management System</small>
                    </div>
                    <div class="table-wrap">
                        <table id="reportTable">
                            <thead id="reportHead"></thead>
                            <tbody id="reportBody"></tbody>
                        </table>
                    </div>
                </div>
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
let currentType = 'students', reportData = [];

const filtersEl   = document.getElementById('studentFilters');
const fYearWrap   = document.getElementById('fYearWrap');
const fStatusWrap = document.getElementById('fStatusWrap');

// Auto-select from URL param
const _urlType = new URLSearchParams(location.search).get('type');
if (_urlType) {
    currentType = _urlType;
    document.querySelectorAll('.report-card').forEach(c => c.classList.toggle('active', c.dataset.type === currentType));
    applyFilterVisibility();
    setTimeout(generateReport, 300);
}

// Load departments
fetch('php/get_departments.php').then(r => r.json()).then(data => {
    const sel = document.getElementById('fDept');
    [...new Set(data.map(d => d.name))].sort().forEach(n =>
        sel.innerHTML += `<option value="${n}">${n}</option>`);
});

function applyFilterVisibility() {
    const showFilters = ['students','enrollments','course_summary'].includes(currentType);
    filtersEl.style.display = showFilters ? 'flex' : 'none';
    // year + status only relevant for students/enrollments
    const showYear   = ['students','enrollments'].includes(currentType);
    const showStatus = currentType === 'students';
    fYearWrap.style.display   = showYear   ? 'flex' : 'none';
    fStatusWrap.style.display = showStatus ? 'flex' : 'none';
}

function selectReport(el) {
    document.querySelectorAll('.report-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    currentType = el.dataset.type;
    applyFilterVisibility();
    document.getElementById('previewWrap').style.display = 'none';
    if (!['students','enrollments','course_summary'].includes(currentType)) generateReport();
}

const _user = JSON.parse(localStorage.getItem('user') || '{}');

function generateReport() {
    let url = `php/reports.php?type=${currentType}&_email=${encodeURIComponent(_user.email || '')}`;
    const dept   = document.getElementById('fDept').value;
    const year   = document.getElementById('fYear').value;
    const status = document.getElementById('fStatus').value;
    if (dept)   url += `&dept=${encodeURIComponent(dept)}`;
    if (year)   url += `&year=${year}`;
    if (status) url += `&status=${status}`;

    fetch(url).then(r => r.json()).then(data => {
        if (!Array.isArray(data) || !data.length) { alert('No data found.'); return; }
        reportData = data;
        renderPreview(data);
    });
}

const configs = {
    students: {
        title: 'Student Report',
        cols: ['#','Student ID','Full Name','Email','Department','Year','Status','Admission','Registered'],
        row:  (s,i) => [i+1, s.student_id, `${s.fname} ${s.lname}`, s.email, s.department,
                        `Year ${s.year_of_study}`, s.status, s.admission_status||'Pending',
                        new Date(s.created_at).toLocaleDateString()]
    },
    enrollments: {
        title: 'Enrollment Report',
        cols: ['#','Student ID','Full Name','Department','Year','Course Code','Course Title','Credits','Semester','Teacher'],
        row:  (r,i) => [i+1, r.student_id, `${r.fname} ${r.lname}`, r.department,
                        `Year ${r.year_of_study}`, r.course_code, r.course_title,
                        r.credits, `Sem ${r.semester}`, r.teacher_name||'—']
    },
    course_summary: {
        title: 'Course Enrollment Summary',
        cols: ['#','Code','Title','Department','Year','Sem','Credits','Teacher','Enrolled Students'],
        row:  (r,i) => [i+1, r.code, r.title, r.department, `Year ${r.year_level}`,
                        `Sem ${r.semester}`, r.credits, r.teacher_name||'—', r.enrolled_count]
    },
    departments: {
        title: 'Department Report',
        cols: ['#','Department','Faculty','Head','Total Students','Active','Enrolled','Courses'],
        row:  (d,i) => [i+1, d.name, d.faculty||'—', d.head, d.total_students,
                        d.active_students, d.enrolled_students, d.total_courses]
    },
    faculties: {
        title: 'Faculty Report',
        cols: ['#','Faculty','Dean','Departments','Total Students','Active Students'],
        row:  (f,i) => [i+1, f.faculty, f.dean||'—', f.dept_count, f.total_students, f.active_students]
    },
    admissions: {
        title: 'Admission Status Report',
        cols: ['#','Student ID','Full Name','Email','Department','Year','Admission Status','Applied On'],
        row:  (s,i) => [i+1, s.student_id, `${s.fname} ${s.lname}`, s.email, s.department,
                        `Year ${s.year_of_study}`, s.admission_status||'Pending',
                        new Date(s.created_at).toLocaleDateString()]
    }
};

function renderPreview(data) {
    const cfg = configs[currentType];
    document.getElementById('previewTitle').textContent = `${cfg.title} — ${data.length} record(s)`;
    document.getElementById('pTitle').textContent   = cfg.title;
    document.getElementById('pMeta').textContent    = `Generated: ${new Date().toLocaleString()} · ${data.length} record(s)`;
    document.getElementById('reportHead').innerHTML = '<tr>' + cfg.cols.map(c => `<th>${c}</th>`).join('') + '</tr>';
    document.getElementById('reportBody').innerHTML = data.map((row,i) =>
        '<tr>' + cfg.row(row,i).map(v => `<td>${v??'—'}</td>`).join('') + '</tr>').join('');
    document.getElementById('previewWrap').style.display = 'block';
    document.getElementById('previewWrap').scrollIntoView({behavior:'smooth'});
}

function downloadPDF() {
    const cfg = configs[currentType];
    document.title = cfg.title;
    window.print();
    document.title = 'Reports | Group One SMS';
}

function downloadCSV() {
    const cfg  = configs[currentType];
    const rows = [cfg.cols, ...reportData.map((r,i) => cfg.row(r,i))];
    const csv  = rows.map(r => r.map(v => `"${String(v??'').replace(/"/g,'""')}"`).join(',')).join('\n');
    const a    = document.createElement('a');
    a.href     = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = cfg.title.replace(/\s+/g,'_') + '_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
</script>
</body>
</html>
