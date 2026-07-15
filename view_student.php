<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .toolbar { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--border); flex-wrap:wrap; gap:12px; }
        .search-box { display:flex; align-items:center; gap:8px; background:var(--bg); border:1.5px solid var(--border); border-radius:8px; padding:8px 14px; flex:1; max-width:360px; }
        .search-box input { border:none; background:transparent; outline:none; font-size:0.88rem; width:100%; }
        .filter-row { display:flex; gap:10px; flex-wrap:wrap; }
        .filter-row select { padding:7px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:0.84rem; background:#fff; outline:none; cursor:pointer; }
        .filter-row select:focus { border-color:var(--accent); }
        .count-badge { font-size:0.82rem; color:var(--muted); white-space:nowrap; }
        /* Table */
        .student-row { cursor:pointer; }
        .student-row:hover td { background:#fef2f4 !important; }
        .s-av { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;overflow:hidden;vertical-align:middle; }
        .s-av img { width:36px;height:36px;object-fit:cover; }
        .name-cell { display:flex; align-items:center; gap:10px; }
        .name-cell .n { font-weight:600; color:var(--primary); font-size:0.9rem; }
        .name-cell .sid { font-size:0.75rem; color:var(--muted); }
        .action-cell { display:flex; gap:6px; }
        .btn-icon { background:none; border:1.5px solid var(--border); border-radius:6px; padding:5px 9px; cursor:pointer; font-size:0.8rem; transition:all 0.15s; }
        .btn-icon:hover { border-color:var(--accent); color:var(--accent); background:#fef2f4; }
        @media(max-width:700px){
            .toolbar { flex-direction:column; align-items:stretch; }
            .search-box { max-width:100%; }
            th:nth-child(5), td:nth-child(5),
            th:nth-child(6), td:nth-child(6) { display:none; }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2>🎓 Student Records</h2>
                    <p>All registered students in the system</p>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button class="btn btn-outline" id="pendingBanner" style="display:none;" onclick="filterPending()"></button>
                    <a href="new_student.php" class="btn">➕ Add New Student</a>
                </div>
            </div>

            <div class="panel">
                <div class="toolbar">
                    <div class="search-box">
                        🔍 <input type="text" id="searchInput" placeholder="Search name, ID, email…">
                    </div>
                    <div class="filter-row">
                        <select id="filterDept"><option value="">All Departments</option></select>
                        <select id="filterYear">
                            <option value="">All Years</option>
                            <option value="1">Year 1</option>
                            <option value="2">Year 2</option>
                            <option value="3">Year 3</option>
                            <option value="4">Year 4</option>
                        </select>
                        <select id="filterStatus">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                        <select id="filterAdm">
                            <option value="">All Admissions</option>
                            <option value="Pending">Pending</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <span class="count-badge" id="countLabel"></span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Admission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentTbody">
                            <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--muted);">Loading&hellip;</td></tr>
                        </tbody>
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
<script src="shared.js?v=2"></script>
<script>
let allStudents = [];

function av(s) {
    const ini = (s.fname[0] + s.lname[0]).toUpperCase();
    return s.photo
        ? `<div class="s-av"><img src="${s.photo}" alt="${ini}"></div>`
        : `<div class="s-av">${ini}</div>`;
}

function updatePendingBanner(data) {
    const count = data.filter(s => s.admission_status === 'Pending' || s.admission_status === 'Under Review').length;
    const btn = document.getElementById('pendingBanner');
    if (count) {
        btn.style.cssText = 'display:flex;background:#fff8e1;color:#856404;border-color:#ffc107;font-weight:700;';
        btn.textContent = '🔔 ' + count + ' Pending Signup' + (count > 1 ? 's' : '');
    } else {
        btn.style.display = 'none';
    }
}

function filterPending() {
    const q = document.getElementById('searchInput');
    const dept = document.getElementById('filterDept');
    const year = document.getElementById('filterYear');
    const stat = document.getElementById('filterStatus');
    q.value = ''; dept.value = ''; year.value = ''; stat.value = '';
    renderTable(allStudents.filter(s => s.admission_status === 'Pending' || s.admission_status === 'Under Review'));
}

function admBadge(a) {
    const map = {
        'Pending':      'background:#fff8e1;color:#856404;',
        'Under Review': 'background:#e8f4fd;color:#0d6efd;',
        'Approved':     'background:#d4edda;color:#155724;',
        'Rejected':     'background:#f8d7da;color:#721c24;'
    };
    const st = a || 'Pending';
    return `<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;${map[st]||map['Pending']}">${st}</span>`;
}

function renderTable(list) {
    document.getElementById('countLabel').textContent = list.length + ' student(s)';
    const tbody = document.getElementById('studentTbody');
    if (!list.length) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--muted);">No students found.</td></tr>';
        return;
    }
    tbody.innerHTML = list.map((s, i) => {
        const sc = s.status === 'Active' ? 'active' : s.status === 'Suspended' ? 'suspended' : 'inactive';
        const approveBtn = s.admission_status !== 'Approved'
            ? `<button class="btn-icon approve-btn" title="Approve Admission" data-id="${s.student_id}" style="color:#155724;border-color:#28a745;">✅ Approve</button>`
            : '';
        return `
        <tr class="student-row" data-id="${s.student_id}">
            <td style="color:var(--muted);font-size:0.82rem;">${i + 1}</td>
            <td>
                <div class="name-cell">
                    ${av(s)}
                    <div>
                        <div class="n">${s.fname} ${s.lname}</div>
                        <div class="sid">${s.student_id} &middot; ${s.email} ${s.admission_status === 'Pending' ? '<span style="background:#e94560;color:#fff;font-size:0.65rem;font-weight:700;padding:2px 6px;border-radius:10px;margin-left:4px;">NEW</span>' : ''}</div>
                    </div>
                </div>
            </td>
            <td>${s.department}</td>
            <td>Year ${s.year_of_study}</td>
            <td><span class="badge badge-${sc}">${s.status}</span></td>
            <td>${admBadge(s.admission_status)}</td>
            <td>
                <div class="action-cell" onclick="event.stopPropagation()">
                    <button class="btn-icon" title="View &amp; Edit" onclick="location.href='student_detail.php?id=${s.student_id}'">✏️ Edit</button>
                    ${approveBtn}
                    <button class="btn-icon del-btn" title="Delete Student" data-id="${s.student_id}" style="color:#dc3545;border-color:#dc3545;">🗑️</button>
                </div>
            </td>
        </tr>`;
    }).join('');

    document.querySelectorAll('.student-row').forEach(row =>
        row.addEventListener('click', () => location.href = 'student_detail.php?id=' + row.dataset.id)
    );

    document.querySelectorAll('.approve-btn').forEach(btn =>
        btn.addEventListener('click', async () => {
            if (!confirm('Approve admission for this student?')) return;
            const res  = await fetch('php/documents.php', {
                method: 'PUT', headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ student_id: btn.dataset.id, admission_status: 'Approved', admission_note: '' })
            });
            const data = await res.json();
            if (data.success) {
                const s = allStudents.find(x => x.student_id === btn.dataset.id);
                if (s) { s.admission_status = 'Approved'; s.status = 'Active'; }
                updatePendingBanner(allStudents);
                applyFilters();
            } else alert(data.error || 'Failed.');
        })
    );

    document.querySelectorAll('.del-btn').forEach(btn =>
        btn.addEventListener('click', async () => {
            if (!confirm('Delete this student permanently?')) return;
            const res  = await fetch('php/update_student.php', {
                method: 'DELETE', headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ student_id: btn.dataset.id })
            });
            const data = await res.json();
            if (data.success) { allStudents = allStudents.filter(x => x.student_id !== btn.dataset.id); applyFilters(); }
            else alert(data.error || 'Delete failed.');
        })
    );
}

function applyFilters() {
    const q    = document.getElementById('searchInput').value.toLowerCase();
    const dept = document.getElementById('filterDept').value;
    const year = document.getElementById('filterYear').value;
    const stat = document.getElementById('filterStatus').value;
    const adm  = document.getElementById('filterAdm').value;
    renderTable(allStudents.filter(s =>
        (!q    || (s.fname+' '+s.lname).toLowerCase().includes(q) || s.student_id.toLowerCase().includes(q) || s.email.toLowerCase().includes(q)) &&
        (!dept || s.department === dept) &&
        (!year || s.year_of_study == year) &&
        (!stat || s.status === stat) &&
        (!adm  || s.admission_status === adm)
    ));
}

fetch('php/get_students.php')
    .then(r => r.json())
    .then(data => {
        allStudents = data;
        updatePendingBanner(data);
        const depts = [...new Set(data.map(s => s.department))].sort();
        const sel = document.getElementById('filterDept');
        depts.forEach(d => sel.innerHTML += `<option value="${d}">${d}</option>`);
        // Pre-apply filter from URL param
        const urlAdm = new URLSearchParams(location.search).get('admission');
        if (urlAdm) document.getElementById('filterAdm').value = urlAdm;
        applyFilters();
    });

['searchInput','filterDept','filterYear','filterStatus','filterAdm'].forEach(id =>
    document.getElementById(id).addEventListener('input', applyFilters)
);
</script>
</body>
</html>
