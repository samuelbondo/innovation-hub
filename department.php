<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .faculty-section { margin-bottom: 32px; }
        .faculty-label { display:flex; align-items:center; gap:8px; font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:var(--accent); margin-bottom:14px; padding-bottom:8px; border-bottom:2px solid #f0f0f0; }
        .dept-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
        .dept-card { background:#fff; border-radius:12px; padding:22px; box-shadow:var(--shadow); border-top:4px solid var(--accent); cursor:pointer; transition:transform 0.2s,box-shadow 0.2s; position:relative; }
        .dept-card:hover { transform:translateY(-4px); box-shadow:0 8px 28px rgba(0,0,0,0.13); }
        .dept-card .dept-icon { font-size:2rem; margin-bottom:10px; }
        .dept-card h3 { color:var(--primary); font-size:0.95rem; margin-bottom:6px; }
        .dept-card p { font-size:0.81rem; color:var(--muted); line-height:1.55; margin-bottom:14px; }
        .dept-meta { display:flex; gap:12px; flex-wrap:wrap; font-size:0.77rem; color:var(--muted); }
        .dept-card .card-actions { position:absolute; top:12px; right:12px; display:flex; gap:6px; opacity:0; transition:opacity 0.2s; }
        .dept-card:hover .card-actions { opacity:1; }
        .card-actions button { background:none; border:none; cursor:pointer; font-size:0.85rem; padding:4px 7px; border-radius:5px; transition:background 0.15s; }
        .card-actions .edit-btn:hover { background:#e8f4fd; }
        .card-actions .del-btn:hover { background:#fef2f4; }
        /* MODAL */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:500; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal { background:#fff; border-radius:14px; padding:32px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,0.25); max-height:90vh; overflow-y:auto; }
        .modal h3 { color:var(--primary); margin-bottom:22px; font-size:1.1rem; }
        .modal-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:20px; }
        .btn-danger { background:#dc3545; color:#fff; border:none; padding:9px 20px; border-radius:7px; cursor:pointer; font-weight:600; font-size:0.88rem; transition:background 0.2s; }
        .btn-danger:hover { background:#a71d2a; }
        .icon-picker { display:flex; flex-wrap:wrap; gap:8px; margin-top:6px; }
        .icon-opt { font-size:1.4rem; padding:6px 10px; border-radius:8px; cursor:pointer; border:2px solid transparent; transition:border-color 0.15s,background 0.15s; }
        .icon-opt:hover, .icon-opt.selected { border-color:var(--accent); background:#fef2f4; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2>🏫 Departments</h2>
                    <p>All departments grouped by faculty</p>
                </div>
                <button class="btn" id="addDeptBtn" style="display:none;">➕ Add Department</button>
            </div>

            <div id="deptContainer"><p style="color:var(--muted);">Loading…</p></div>

        </div>
        <footer class="app-footer">
            <span>&copy; 2025 <strong style="color:var(--accent)">Group One</strong>. All rights reserved.</span>
            <span>Web Development Project 2025</span>
        </footer>
    </div>
</div>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="deptModal">
    <div class="modal">
        <h3 id="modalTitle">➕ Add Department</h3>
        <div class="error-msg" id="modalErr"></div>
        <input type="hidden" id="editId">
        <div class="form-group">
            <label>Department Name</label>
            <input type="text" id="dName" placeholder="e.g. Computer Science">
        </div>
        <div class="form-group">
            <label>Department Head</label>
            <input type="text" id="dHead" placeholder="e.g. Dr. A. Mensah">
        </div>
        <div class="form-group">
            <label>Faculty</label>
            <select id="dFaculty"><option value="">— Select Faculty —</option></select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea id="dDesc" rows="3" placeholder="Brief description of the department"></textarea>
        </div>
        <div class="form-group">
            <label>Icon</label>
            <div class="icon-picker" id="iconPicker">
                <span class="icon-opt" data-icon="🏫">🏫</span>
                <span class="icon-opt" data-icon="🖥️">🖥️</span>
                <span class="icon-opt" data-icon="🌐">🌐</span>
                <span class="icon-opt" data-icon="📊">📊</span>
                <span class="icon-opt" data-icon="⚙️">⚙️</span>
                <span class="icon-opt" data-icon="📐">📐</span>
                <span class="icon-opt" data-icon="🔬">🔬</span>
                <span class="icon-opt" data-icon="🎭">🎭</span>
                <span class="icon-opt" data-icon="📚">📚</span>
                <span class="icon-opt" data-icon="⚖️">⚖️</span>
                <span class="icon-opt" data-icon="🏥">🏥</span>
                <span class="icon-opt" data-icon="🧠">🧠</span>
                <span class="icon-opt" data-icon="💰">💰</span>
                <span class="icon-opt" data-icon="🌾">🌾</span>
                <span class="icon-opt" data-icon="👥">👥</span>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-outline" id="modalCancel">Cancel</button>
            <button class="btn" id="modalSave">Save</button>
        </div>
    </div>
</div>

<script src="shared.js?v=2"></script>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');
const isAdmin = _u.role === 'admin' || _u.role === 'staff';

// Teachers go straight to their own department
if (_u.role === 'teacher') {
    fetch('php/dashboard.php?email=' + encodeURIComponent(_u.email) + '&role=teacher')
        .then(r => r.json())
        .then(d => {
            if (d.teacher && d.teacher.department_id)
                window.location.replace('department_detail.php?id=' + d.teacher.department_id);
            else
                window.location.replace('dashboard.php');
        });
}
let faculties = [], allDepts = [], selectedIcon = '🏫';

if (isAdmin) document.getElementById('addDeptBtn').style.display = 'inline-flex';

// Load faculties for dropdown
fetch('php/manage_departments.php?faculties=1')
    .then(r => r.json()).then(f => {
        faculties = f;
        const sel = document.getElementById('dFaculty');
        f.forEach(fac => sel.innerHTML += `<option value="${fac.id}">${fac.name}</option>`);
    });

function loadDepts() {
    fetch('php/get_departments.php')
        .then(r => r.json())
        .then(depts => {
            allDepts = depts;
            const grouped = {};
            depts.forEach(d => {
                const key = d.faculty || 'General';
                if (!grouped[key]) grouped[key] = [];
                grouped[key].push(d);
            });

            document.getElementById('deptContainer').innerHTML = Object.entries(grouped).map(([faculty, list]) => `
                <div class="faculty-section">
                    <div class="faculty-label">🏛️ ${faculty}</div>
                    <div class="dept-grid">
                        ${list.map(d => `
                        <div class="dept-card" onclick="openDetail(${d.id}, event)">
                            ${isAdmin ? `<div class="card-actions">
                                <button class="edit-btn" title="Edit" onclick="openEdit(${d.id},event)">✏️</button>
                                <button class="del-btn" title="Delete" onclick="deleteDept(${d.id},'${d.name.replace(/'/g,"\\'")}',event)">🗑️</button>
                            </div>` : ''}
                            <div class="dept-icon">${d.icon}</div>
                            <h3>${d.name}</h3>
                            <p>${d.description || '—'}</p>
                            <div class="dept-meta">
                                <span>👤 ${d.head}</span>
                                <span>🎓 ${d.student_count} Active Students</span>
                            </div>
                        </div>`).join('')}
                    </div>
                </div>`).join('');
        });
}
loadDepts();

function openDetail(id, e) {
    if (e && e.target.closest('.card-actions')) return;
    window.location.href = 'department_detail.php?id=' + id;
}

// Icon picker
document.getElementById('iconPicker').addEventListener('click', e => {
    const opt = e.target.closest('.icon-opt');
    if (!opt) return;
    document.querySelectorAll('.icon-opt').forEach(o => o.classList.remove('selected'));
    opt.classList.add('selected');
    selectedIcon = opt.dataset.icon;
});

function setIcon(icon) {
    selectedIcon = icon;
    document.querySelectorAll('.icon-opt').forEach(o => {
        o.classList.toggle('selected', o.dataset.icon === icon);
    });
}

// Add
document.getElementById('addDeptBtn').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = '➕ Add Department';
    document.getElementById('editId').value = '';
    document.getElementById('dName').value = '';
    document.getElementById('dHead').value = '';
    document.getElementById('dDesc').value = '';
    document.getElementById('dFaculty').value = '';
    document.getElementById('modalErr').style.display = 'none';
    setIcon('🏫');
    document.getElementById('deptModal').classList.add('open');
});

// Edit
function openEdit(id, e) {
    e.stopPropagation();
    const d = allDepts.find(x => x.id == id);
    if (!d) return;
    document.getElementById('modalTitle').textContent = '✏️ Edit Department';
    document.getElementById('editId').value = d.id;
    document.getElementById('dName').value = d.name;
    document.getElementById('dHead').value = d.head;
    document.getElementById('dDesc').value = d.description || '';
    document.getElementById('modalErr').style.display = 'none';
    // Set faculty
    const fac = faculties.find(f => f.name === d.faculty);
    document.getElementById('dFaculty').value = fac ? fac.id : '';
    setIcon(d.icon || '🏫');
    document.getElementById('deptModal').classList.add('open');
}

// Save
document.getElementById('modalSave').addEventListener('click', async () => {
    const err = document.getElementById('modalErr');
    err.style.display = 'none';
    const id   = document.getElementById('editId').value;
    const body = {
        name: document.getElementById('dName').value.trim(),
        head: document.getElementById('dHead').value.trim(),
        description: document.getElementById('dDesc').value.trim(),
        faculty_id: document.getElementById('dFaculty').value,
        icon: selectedIcon
    };
    if (id) body.id = id;
    const res = await fetch('php/manage_departments.php', {
        method: id ? 'PUT' : 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify(body)
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('deptModal').classList.remove('open');
        loadDepts();
    } else {
        err.textContent = data.error || 'Failed to save.';
        err.style.display = 'block';
    }
});

// Delete
function deleteDept(id, name, e) {
    e.stopPropagation();
    if (!confirm(`Delete department "${name}"?\nThis cannot be undone.`)) return;
    fetch('php/manage_departments.php', {
        method: 'DELETE',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({id})
    }).then(r => r.json()).then(data => {
        if (data.success) loadDepts();
        else alert(data.error || 'Delete failed.');
    });
}

document.getElementById('modalCancel').addEventListener('click', () => document.getElementById('deptModal').classList.remove('open'));
document.getElementById('deptModal').addEventListener('click', e => { if (e.target === document.getElementById('deptModal')) document.getElementById('deptModal').classList.remove('open'); });
</script>
</body>
</html>
