<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculties | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .faculty-block { margin-bottom: 32px; }
        .faculty-header { background: linear-gradient(135deg, var(--primary), #0f3460); color:#fff; border-radius:12px 12px 0 0; padding:20px 24px; display:flex; align-items:center; justify-content:space-between; }
        .faculty-header h3 { margin:0; font-size:1.1rem; }
        .faculty-header .abbr { background:rgba(255,255,255,0.2); padding:4px 12px; border-radius:20px; font-size:0.82rem; font-weight:700; }
        .faculty-header .dean { font-size:0.82rem; opacity:0.85; margin-top:4px; }
        .dept-table { width:100%; border-collapse:collapse; background:#fff; border-radius:0 0 12px 12px; overflow:hidden; box-shadow:0 2px 14px rgba(0,0,0,0.07); }
        .dept-table th { background:#f8f9fa; padding:10px 16px; text-align:left; font-size:0.8rem; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px; }
        .dept-table td { padding:12px 16px; border-top:1px solid #f0f0f0; font-size:0.9rem; }
        .dept-table tr:hover td { background:#fafafa; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2>🎓 Faculties & Departments</h2>
                    <p>All faculties and their departments</p>
                </div>
            </div>

            <div id="facultyList"><p style="color:var(--muted);">Loading…</p></div>

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

fetch('php/get_faculties.php')
    .then(r => r.json())
    .then(faculties => {
        // Teachers only see their own faculty
        const list = _u.role === 'teacher'
            ? faculties.filter(f => f.departments.some(d => d.id == _u.department_id))
            : faculties;

        document.getElementById('facultyList').innerHTML = list.length ? list.map(f => `
                <div class="faculty-block">
                    <div class="faculty-header">
                        <div>
                            <h3>🏛️ ${f.name}</h3>
                            <div class="dean">👤 Dean: ${f.dean}</div>
                        </div>
                        <span class="abbr">${f.abbreviation}</span>
                    </div>
                    <table class="dept-table">
                        <thead><tr><th>Department</th><th>Head</th><th>Active Students</th></tr></thead>
                        <tbody>
                            ${f.departments.map(d => `
                                <tr>
                                    <td><strong>${d.name}</strong><br><small style="color:var(--muted);">${d.description}</small></td>
                                    <td>${d.head}</td>
                                    <td><span class="badge badge-active">${d.student_count}</span></td>
                                </tr>`).join('')}
                        </tbody>
                    </table>
                </div>`).join('') : '<p style="color:var(--muted);">No faculty found.</p>';
    });
</script>
</body>
</html>
