<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .role-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px; }
        .role-tab { padding:7px 18px; border-radius:20px; border:1.5px solid var(--border); background:#fff; font-size:0.83rem; font-weight:600; cursor:pointer; transition:all 0.15s; }
        .role-tab.active { background:var(--accent); color:#fff; border-color:var(--accent); }
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:999; align-items:center; justify-content:center; }
        .modal-box { background:#fff; border-radius:14px; padding:28px 32px; max-width:520px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.18); }
        .modal-box h3 { margin:0 0 18px; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div><h2>👥 Manage Users</h2><p>Add and manage teachers and staff accounts</p></div>
                <button class="btn" onclick="toggleForm()">➕ Add User</button>
            </div>

            <!-- Add User Form -->
            <div class="panel" id="addForm" style="display:none;margin-bottom:24px;">
                <div class="panel-header"><h3>Add New User</h3></div>
                <div style="padding:24px;">
                    <div class="error-msg" id="formErr"></div>
                    <div class="success-msg" id="formOk"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group"><label>Full Name</label><input type="text" id="uName" placeholder="e.g. Dr. John Doe" required></div>
                        <div class="form-group"><label>Email</label><input type="email" id="uEmail" placeholder="teacher@school.edu" required></div>
                        <div class="form-group"><label>Phone</label><input type="tel" id="uPhone" placeholder="+1 234 567 890"></div>
                        <div class="form-group"><label>Role</label>
                            <select id="uRole">
                                <option value="teacher">Teacher / Lecturer</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Faculty</label>
                            <select id="uFaculty"><option value="">-- Select Faculty --</option></select>
                        </div>
                        <div class="form-group"><label>Department</label>
                            <select id="uDept" disabled><option value="">-- Select Faculty First --</option></select>
                        </div>
                        <div class="form-group"><label>Password</label><input type="password" id="uPass" placeholder="Min 6 chars" required></div>
                        <div class="form-group"><label>Confirm Password</label><input type="password" id="uPass2" placeholder="Repeat password" required></div>
                    </div>
                    <div class="form-actions">
                        <button class="btn" onclick="saveUser()">💾 Save User</button>
                        <button class="btn btn-outline" onclick="toggleForm()">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Role Filter Tabs -->
            <div class="role-tabs">
                <button class="role-tab active" data-role="">All Users</button>
                <button class="role-tab" data-role="teacher">👨‍🏫 Teachers</button>
                <button class="role-tab" data-role="staff">🧑‍💼 Staff</button>
                <button class="role-tab" data-role="admin">🔐 Admin</button>
            </div>

            <!-- Users Table -->
            <div class="panel">
                <div class="table-toolbar">
                    <div class="table-search">🔍 <input type="text" id="searchInput" placeholder="Search users…"></div>
                    <span id="countLabel" style="font-size:0.85rem;color:var(--muted);"></span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Department</th><th>Actions</th></tr></thead>
                        <tbody id="userTbody"><tr><td colspan="7" style="text-align:center;padding:28px;color:var(--muted);">Loading…</td></tr></tbody>
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

<!-- Edit User Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <h3>✏️ Edit User</h3>
        <div class="error-msg" id="editErr"></div>
        <div class="success-msg" id="editOk"></div>
        <input type="hidden" id="eId">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group"><label>Full Name</label><input type="text" id="eName" required></div>
            <div class="form-group"><label>Email</label><input type="email" id="eEmail" required></div>
            <div class="form-group"><label>Phone</label><input type="tel" id="ePhone"></div>
            <div class="form-group"><label>Role</label>
                <select id="eRole">
                    <option value="teacher">Teacher / Lecturer</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group"><label>Faculty</label>
                <select id="eFaculty"><option value="">-- Select Faculty --</option></select>
            </div>
            <div class="form-group"><label>Department</label>
                <select id="eDept"><option value="">-- None --</option></select>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn" onclick="saveEdit()">💾 Save Changes</button>
            <button class="btn btn-outline" onclick="closeEdit()">Cancel</button>
        </div>
    </div>
</div>

<script src="shared.js?v=2"></script>
<script>
let allUsers = [], facultyData = [], deptMap = {};
let activeRole = '';

fetch('php/get_faculties.php').then(r=>r.json()).then(data => {
    facultyData = data;
    ['uFaculty','eFaculty'].forEach(id => {
        const sel = document.getElementById(id);
        data.forEach(f => { sel.innerHTML += `<option value="${f.id}">${f.name}</option>`; });
    });
    data.forEach(f => f.departments.forEach(d => { deptMap[d.id] = d.name; }));
});

document.getElementById('uFaculty').addEventListener('change', function() {
    populateDepts(this.value, 'uDept');
});
document.getElementById('eFaculty').addEventListener('change', function() {
    populateDepts(this.value, 'eDept');
});

function populateDepts(facId, deptSelId) {
    const ds = document.getElementById(deptSelId);
    ds.innerHTML = '<option value="">-- None --</option>';
    const fac = facultyData.find(f => f.id == facId);
    if (!fac) return;
    fac.departments.forEach(d => { ds.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
}

function loadUsers() {
    fetch('php/manage_courses.php', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'get_teachers'})})
        .then(r=>r.json()).then(data => { allUsers = data; applyFilter(); });
}

function applyFilter() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    let list = allUsers;
    if (activeRole) list = list.filter(u => u.role === activeRole);
    if (q) list = list.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
    renderUsers(list);
}

function renderUsers(list) {
    document.getElementById('countLabel').textContent = list.length + ' user(s)';
    document.getElementById('userTbody').innerHTML = list.length ? list.map((u,i) => `
        <tr>
            <td>${i+1}</td>
            <td><strong>${u.name}</strong></td>
            <td>${u.email}</td>
            <td>${u.phone||'—'}</td>
            <td><span class="badge badge-${u.role==='admin'?'admin':u.role==='teacher'?'active':'staff'}">${u.role}</span></td>
            <td>${u.department_name || '—'}</td>
            <td style="display:flex;gap:6px;">
                <button class="btn btn-sm btn-outline" onclick="openEdit(${u.id})">✏️ Edit</button>
                <button class="btn btn-sm btn-outline" onclick="resetPassword(${u.id}, '${u.name.replace(/'/g,"\\'")}')">🔑 Reset</button>
                <button class="btn btn-sm" style="background:#dc3545;color:#fff;border:none;" onclick="deleteUser(${u.id}, '${u.name.replace(/'/g,"\\'")}')">🗑️</button>
            </td>
        </tr>`).join('') : '<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted);">No users found.</td></tr>';
}

document.getElementById('searchInput').addEventListener('input', applyFilter);

document.querySelectorAll('.role-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        activeRole = this.dataset.role;
        applyFilter();
    });
});

function toggleForm() {
    const f = document.getElementById('addForm');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

async function saveUser() {
    const err = document.getElementById('formErr'), ok = document.getElementById('formOk');
    err.style.display = ok.style.display = 'none';
    const pass = document.getElementById('uPass').value;
    if (pass !== document.getElementById('uPass2').value) { err.textContent='Passwords do not match.'; err.style.display='block'; return; }
    if (pass.length < 6) { err.textContent='Password must be at least 6 characters.'; err.style.display='block'; return; }
    const res = await fetch('php/manage_courses.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            action:'add_user',
            name: document.getElementById('uName').value.trim(),
            email: document.getElementById('uEmail').value.trim(),
            phone: document.getElementById('uPhone').value.trim(),
            role: document.getElementById('uRole').value,
            department_id: document.getElementById('uDept').value || null,
            password: pass
        })
    });
    const data = await res.json();
    if (data.success) {
        ok.textContent = 'User added successfully.'; ok.style.display = 'block';
        loadUsers();
        ['uName','uEmail','uPhone','uPass','uPass2'].forEach(id => document.getElementById(id).value = '');
    } else { err.textContent = data.error||'Failed.'; err.style.display='block'; }
}

function openEdit(id) {
    const u = allUsers.find(x => x.id == id);
    if (!u) return;
    document.getElementById('eId').value    = u.id;
    document.getElementById('eName').value  = u.name;
    document.getElementById('eEmail').value = u.email;
    document.getElementById('ePhone').value = u.phone || '';
    document.getElementById('eRole').value  = u.role;
    // Set faculty & dept
    if (u.department_id) {
        const fac = facultyData.find(f => f.departments.some(d => d.id == u.department_id));
        if (fac) {
            document.getElementById('eFaculty').value = fac.id;
            populateDepts(fac.id, 'eDept');
            setTimeout(() => { document.getElementById('eDept').value = u.department_id; }, 50);
        }
    } else {
        document.getElementById('eFaculty').value = '';
        document.getElementById('eDept').innerHTML = '<option value="">-- None --</option>';
    }
    document.getElementById('editErr').style.display = 'none';
    document.getElementById('editOk').style.display  = 'none';
    document.getElementById('editModal').style.display = 'flex';
}

function closeEdit() {
    document.getElementById('editModal').style.display = 'none';
}

async function saveEdit() {
    const err = document.getElementById('editErr'), ok = document.getElementById('editOk');
    err.style.display = ok.style.display = 'none';
    const res = await fetch('php/manage_courses.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            action: 'edit_user',
            id:            document.getElementById('eId').value,
            name:          document.getElementById('eName').value.trim(),
            email:         document.getElementById('eEmail').value.trim(),
            phone:         document.getElementById('ePhone').value.trim(),
            role:          document.getElementById('eRole').value,
            department_id: document.getElementById('eDept').value || null
        })
    });
    const data = await res.json();
    if (data.success) {
        ok.textContent = 'User updated.'; ok.style.display = 'block';
        loadUsers();
        setTimeout(closeEdit, 900);
    } else { err.textContent = data.error||'Failed.'; err.style.display='block'; }
}

async function deleteUser(id, name) {
    if (!confirm(`Delete user "${name}"? This cannot be undone.`)) return;
    const res  = await fetch('php/manage_courses.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ action:'delete_user', id })
    });
    const data = await res.json();
    if (data.success) { allUsers = allUsers.filter(u => u.id != id); applyFilter(); }
    else alert(data.error || 'Delete failed.');
}

async function resetPassword(userId, userName) {
    const newPass = prompt(`Reset password for ${userName}\nEnter new password (min 6 chars):`);
    if (!newPass) return;
    if (newPass.length < 6) { alert('Password must be at least 6 characters.'); return; }
    const res = await fetch('php/reset_password.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ action: 'admin_reset', user_id: userId, new_password: newPass })
    });
    const data = await res.json();
    if (data.success) alert(`Password for ${userName} has been reset successfully.`);
    else alert(data.error || 'Reset failed.');
}

loadUsers();
</script>
</body>
</html>
