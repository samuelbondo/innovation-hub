<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Detail | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-wrap { display:grid; grid-template-columns:280px 1fr; gap:24px; align-items:start; }
        /* Profile card */
        .profile-card { background:#fff; border-radius:14px; box-shadow:var(--shadow); overflow:hidden; position:sticky; top:80px; }
        .profile-card-top { background:linear-gradient(135deg,var(--primary),#16213e); padding:32px 20px; text-align:center; }
        .profile-avatar { width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#c73652);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;margin:0 auto 14px;overflow:hidden;border:4px solid rgba(255,255,255,0.2); }
        .profile-avatar img { width:88px;height:88px;object-fit:cover; }
        .profile-card-top h3 { color:#fff;font-size:1.1rem;margin-bottom:4px; }
        .profile-card-top .sid { color:rgba(255,255,255,0.6);font-size:0.8rem; }
        .profile-card-body { padding:16px 20px; }
        .profile-stat { display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f4f4f4;font-size:0.84rem; }
        .profile-stat:last-child { border-bottom:none; }
        .profile-stat .ps-label { color:var(--muted); }
        .profile-stat .ps-val { font-weight:600;color:var(--primary); }
        .profile-card-actions { padding:16px 20px;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:8px; }
        /* Edit form */
        .edit-card { background:#fff;border-radius:14px;box-shadow:var(--shadow);overflow:hidden; }
        .edit-card-header { padding:18px 28px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center; }
        .edit-card-header h3 { color:var(--primary);font-size:1rem; }
        .edit-card-body { padding:24px 28px; }
        .section-label { font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:1.3px;color:var(--accent);margin:22px 0 14px;padding-bottom:7px;border-bottom:2px solid #f0f0f0; }
        .section-label:first-child { margin-top:0; }
        .fg { margin-bottom:0; }
        .fg label { font-size:0.81rem;color:#444;font-weight:600;display:block;margin-bottom:5px; }
        .fg input,.fg select,.fg textarea { width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:inherit;background:#fafafa;transition:border-color 0.2s,background 0.2s,box-shadow 0.2s; }
        .fg input:focus,.fg select:focus,.fg textarea:focus { outline:none;border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(233,69,96,0.08); }
        .fg input[readonly] { background:#f0f2f5;color:var(--primary);font-weight:700;cursor:default; }
        .fg textarea { resize:vertical;min-height:75px; }
        .form-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px; }
        .form-actions { display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid #f0f0f0;flex-wrap:wrap; }
        .btn-danger  { background:#dc3545;color:#fff;border:none;padding:10px 22px;border-radius:7px;cursor:pointer;font-weight:600;font-size:0.9rem;transition:background 0.2s; }
        .btn-danger:hover  { background:#a71d2a; }
        .btn-warning { background:#ffc107;color:#212529;border:none;padding:10px 22px;border-radius:7px;cursor:pointer;font-weight:600;font-size:0.9rem;transition:background 0.2s; }
        .btn-warning:hover { background:#e0a800; }
        .btn-secondary { background:#6c757d;color:#fff;border:none;padding:10px 22px;border-radius:7px;cursor:pointer;font-weight:600;font-size:0.9rem;transition:background 0.2s; }
        .btn-secondary:hover { background:#545b62; }
        /* Tabs */
        .tab-bar { display:flex; border-bottom:2px solid var(--border); margin-bottom:0; }
        .tab-btn { padding:12px 20px; border:none; background:none; cursor:pointer; font-size:0.88rem; font-weight:600; color:var(--muted); border-bottom:3px solid transparent; margin-bottom:-2px; transition:color 0.2s,border-color 0.2s; }
        .tab-btn.active { color:var(--accent); border-bottom-color:var(--accent); }
        .tab-pane { display:none; padding:24px 28px; }
        .tab-pane.active { display:block; }
        /* Documents */
        .doc-item { display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#f8f9fa;border-radius:8px;margin-bottom:8px;font-size:0.85rem; }
        .doc-item .doc-info { flex:1; }
        .doc-item .doc-name { font-weight:600;color:var(--primary); }
        .doc-item .doc-meta { font-size:0.76rem;color:var(--muted);margin-top:2px; }
        .doc-upload-area { border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;cursor:pointer;transition:border-color 0.2s;margin-bottom:12px; }
        .doc-upload-area:hover { border-color:var(--accent); }
        /* Admission */
        .adm-badge { display:inline-block;padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:700; }
        .adm-Pending      { background:#fff8e1;color:#856404; }
        .adm-Under.Review { background:#e8f4fd;color:#0d6efd; }
        .adm-Approved     { background:#d4edda;color:#155724; }
        .adm-Rejected     { background:#f8d7da;color:#721c24; }
        @media(max-width:900px){ .detail-wrap{grid-template-columns:1fr;} .profile-card{position:static;} }
        @media(max-width:600px){ .form-grid-2{grid-template-columns:1fr;} .edit-card-body{padding:18px 16px;} .tab-btn{padding:10px 12px;font-size:0.8rem;} }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2 id="pageTitle">🎓 Student Detail</h2>
                    <p id="pageSubtitle">Loading…</p>
                </div>
                <a href="view_student.php" class="btn btn-outline">← Back to Students</a>
            </div>

            <div class="error-msg" id="topErr"></div>
            <div class="success-msg" id="topOk"></div>

            <div class="detail-wrap" id="detailWrap" style="display:none;">

                <!-- Profile sidebar -->
                <div class="profile-card">
                    <div class="profile-card-top">
                        <div class="profile-avatar" id="avatarEl"></div>
                        <h3 id="cardName"></h3>
                        <div class="sid" id="cardSid"></div>
                    </div>
                    <div class="profile-card-body">
                        <div class="profile-stat"><span class="ps-label">Department</span><span class="ps-val" id="cardDept"></span></div>
                        <div class="profile-stat"><span class="ps-label">Gender</span><span class="ps-val" id="cardGender"></span></div>
                        <div class="profile-stat"><span class="ps-label">Year</span><span class="ps-val" id="cardYear"></span></div>
                        <div class="profile-stat"><span class="ps-label">Status</span><span class="ps-val" id="cardStatus"></span></div>
                        <div class="profile-stat"><span class="ps-label">Admission</span><span class="ps-val" id="cardAdmission"></span></div>
                        <div class="profile-stat"><span class="ps-label">Enrolled</span><span class="ps-val" id="cardEnrolled"></span></div>
                    </div>
                    <div class="profile-card-actions">
                        <button class="btn btn-outline" id="photoBtn" style="justify-content:center;">📷 Change Photo</button>
                        <button class="btn-warning" id="suspendBtn">⏸️ Suspend</button>
                        <button class="btn-secondary" id="deactivateBtn">🔒 Deactivate</button>
                        <button class="btn-danger" id="deleteBtn">🗑️ Delete Student</button>
                    </div>
                </div>

                <!-- Tabbed card -->
                <div class="edit-card">
                    <div class="tab-bar">
                        <button class="tab-btn active" onclick="switchTab('info',this)">✏️ Info</button>
                        <button class="tab-btn" onclick="switchTab('docs',this)">📎 Documents</button>
                        <button class="tab-btn" onclick="switchTab('admission',this)">🎓 Admission</button>
                    </div>

                    <!-- TAB: Info -->
                    <div class="tab-pane active" id="tab-info">
                        <div class="section-label">🪪 Identity</div>
                        <div class="form-grid-2">
                            <div class="fg"><label>Student ID</label><input type="text" id="f_sid" readonly></div>
                            <div class="fg"><label>Status</label>
                                <select id="f_status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-grid-2">
                            <div class="fg"><label>First Name <span style="color:var(--accent)">*</span></label><input type="text" id="f_fname" required></div>
                            <div class="fg"><label>Last Name <span style="color:var(--accent)">*</span></label><input type="text" id="f_lname" required></div>
                        </div>
                        <div class="fg" style="margin-bottom:16px;">
                            <label>Gender</label>
                            <div style="display:flex;gap:20px;margin-top:6px;">
                                <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="f_gender" value="Male"> Male</label>
                                <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="f_gender" value="Female"> Female</label>
                                <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="f_gender" value="Other"> Other</label>
                            </div>
                        </div>
                        <div class="section-label">📬 Contact</div>
                        <div class="form-grid-2">
                            <div class="fg"><label>Email <span style="color:var(--accent)">*</span></label><input type="email" id="f_email" required></div>
                            <div class="fg"><label>Phone</label><input type="tel" id="f_phone" placeholder="+1 234 567 890"></div>
                        </div>
                        <div class="fg" style="margin-bottom:0"><label>Address</label><textarea id="f_address"></textarea></div>
                        <div class="section-label">🏫 Enrollment</div>
                        <div class="form-grid-2">
                            <div class="fg"><label>Faculty</label><select id="f_faculty"><option value="">— Select Faculty —</option></select></div>
                            <div class="fg"><label>Department <span style="color:var(--accent)">*</span></label><select id="f_dept" required><option value="">— Select Faculty First —</option></select></div>
                        </div>
                        <div class="form-grid-2">
                            <div class="fg"><label>Year of Study <span style="color:var(--accent)">*</span></label>
                                <select id="f_year" required>
                                    <option value="1">Year 1</option><option value="2">Year 2</option>
                                    <option value="3">Year 3</option><option value="4">Year 4</option>
                                </select>
                            </div>
                            <div class="fg"><label>Date of Birth</label><input type="date" id="f_dob"></div>
                        </div>
                        <div class="section-label">🔑 Reset Password</div>
                        <div class="fg" style="max-width:320px;">
                            <label>New Password <span style="color:var(--muted);font-weight:400;font-size:0.75rem;">(min 6 chars — leave blank to keep)</span></label>
                            <input type="password" id="f_newpass" placeholder="Enter new password">
                        </div>
                        <div class="form-actions">
                            <button class="btn" id="saveBtn">💾 Save Changes</button>
                            <a href="view_student.php" class="btn btn-outline">Cancel</a>
                        </div>
                    </div>

                    <!-- TAB: Documents -->
                    <div class="tab-pane" id="tab-docs">
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:14px;">
                            <select id="adminDocType" style="padding:9px 13px;border:1.5px solid var(--border);border-radius:8px;font-size:0.88rem;background:#fafafa;">
                                <option value="Birth Certificate">Birth Certificate</option>
                                <option value="National ID">National ID / Passport</option>
                                <option value="Academic Certificate">Academic Certificate</option>
                                <option value="Recommendation Letter">Recommendation Letter</option>
                                <option value="Medical Certificate">Medical Certificate</option>
                                <option value="Other">Other</option>
                            </select>
                            <button class="btn" onclick="document.getElementById('adminDocInput').click()">📤 Upload Document</button>
                            <input type="file" id="adminDocInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none;">
                        </div>
                        <div class="error-msg" id="docErr"></div>
                        <div class="success-msg" id="docOk"></div>
                        <div id="docsList"><p style="color:var(--muted);font-size:0.88rem;">Loading documents…</p></div>
                    </div>

                    <!-- TAB: Admission -->
                    <div class="tab-pane" id="tab-admission">
                        <div class="section-label">🎓 Admission Status</div>
                        <div class="form-grid-2" style="margin-bottom:16px;">
                            <div class="fg"><label>Status</label>
                                <select id="adm_status">
                                    <option value="Pending">Pending</option>
                                    <option value="Under Review">Under Review</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="fg" style="margin-bottom:16px;">
                            <label>Note to Student <span style="color:var(--muted);font-weight:400;font-size:0.75rem;">(shown on tracking page)</span></label>
                            <textarea id="adm_note" placeholder="e.g. Missing birth certificate." style="min-height:80px;"></textarea>
                        </div>
                        <div class="form-actions">
                            <button class="btn" id="admSaveBtn">💾 Update Admission</button>
                        </div>
                        <div class="error-msg" id="admErr" style="margin-top:10px;"></div>
                        <div class="success-msg" id="admOk" style="margin-top:10px;"></div>
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
<input type="file" id="photoInput" accept="image/*" style="display:none;">
<script src="shared.js?v=2"></script>
<script>
const sid = new URLSearchParams(location.search).get('id');
if (!sid) location.href = 'view_student.php';

let student = null, facultyData = [];

function setAvatar(s) {
    const el = document.getElementById('avatarEl');
    const ini = (s.fname[0] + s.lname[0]).toUpperCase();
    el.innerHTML = s.photo ? `<img src="${s.photo}">` : ini;
}

function updateSidebar(s) {
    setAvatar(s);
    document.getElementById('cardName').textContent     = s.fname + ' ' + s.lname;
    document.getElementById('cardSid').textContent      = s.student_id;
    document.getElementById('cardDept').textContent     = s.department;
    document.getElementById('cardGender').textContent   = s.gender || '—';
    document.getElementById('cardYear').textContent     = 'Year ' + s.year_of_study;
    document.getElementById('cardEnrolled').textContent = new Date(s.created_at).toLocaleDateString();
    const sc = s.status === 'Active' ? 'active' : s.status === 'Suspended' ? 'suspended' : 'inactive';
    document.getElementById('cardStatus').innerHTML    = `<span class="badge badge-${sc}">${s.status}</span>`;
    const ac = s.admission_status ? s.admission_status.replace(' ','-') : 'Pending';
    document.getElementById('cardAdmission').innerHTML = `<span class="adm-badge adm-${ac}">${s.admission_status || 'Pending'}</span>`;
}

// Load faculties
fetch('php/get_faculties.php').then(r => r.json()).then(data => {
    facultyData = data;
    const sel = document.getElementById('f_faculty');
    data.forEach(f => sel.innerHTML += `<option value="${f.id}">${f.name}</option>`);
});

document.getElementById('f_faculty').addEventListener('change', function () {
    const dSel = document.getElementById('f_dept');
    dSel.innerHTML = '<option value="">— Select Department —</option>';
    const fac = facultyData.find(f => f.id == this.value);
    if (!fac) return;
    fac.departments.forEach(d => dSel.innerHTML += `<option value="${d.name}">${d.name}</option>`);
    // Re-select current dept
    if (student) dSel.value = student.department;
});

// Load student
fetch('php/get_students.php?id=' + encodeURIComponent(sid))
    .then(r => r.json())
    .then(data => {
        if (!data || data.error) { document.getElementById('topErr').textContent = 'Student not found.'; document.getElementById('topErr').style.display = 'block'; return; }
        student = data;
        document.getElementById('pageTitle').textContent    = '🎓 ' + data.fname + ' ' + data.lname;
        document.getElementById('pageSubtitle').textContent = data.student_id + ' · ' + data.department;
        document.getElementById('detailWrap').style.display = 'grid';
        updateSidebar(data);

        document.getElementById('f_sid').value     = data.student_id;
        document.getElementById('f_fname').value   = data.fname;
        document.getElementById('f_lname').value   = data.lname;
        if (data.gender) { const r = document.querySelector(`input[name=f_gender][value="${data.gender}"]`); if (r) r.checked = true; }
        document.getElementById('f_email').value   = data.email;
        document.getElementById('f_phone').value   = data.phone || '';
        document.getElementById('f_address').value = data.address || '';
        document.getElementById('f_year').value    = data.year_of_study;
        document.getElementById('f_dob').value     = data.dob || '';
        document.getElementById('f_status').value  = data.status;
        document.getElementById('adm_status').value = data.admission_status || 'Pending';
        document.getElementById('adm_note').value   = data.admission_note || '';

        fetch('php/get_faculties.php').then(r => r.json()).then(facs => {
            facultyData = facs;
            const fSel = document.getElementById('f_faculty');
            fSel.innerHTML = '<option value="">— Select Faculty —</option>';
            facs.forEach(f => fSel.innerHTML += `<option value="${f.id}">${f.name}</option>`);
            const fac = facs.find(f => f.departments.some(d => d.name === data.department));
            if (fac) {
                fSel.value = fac.id;
                const dSel = document.getElementById('f_dept');
                dSel.innerHTML = '<option value="">— Select Department —</option>';
                fac.departments.forEach(d => dSel.innerHTML += `<option value="${d.name}">${d.name}</option>`);
                dSel.value = data.department;
            }
        });
        loadDocs();
    });

// Save
document.getElementById('saveBtn').addEventListener('click', async () => {
    const err = document.getElementById('topErr'), ok = document.getElementById('topOk');
    err.style.display = ok.style.display = 'none';
    const body = {
        student_id:   sid,
        fname:        document.getElementById('f_fname').value.trim(),
        lname:        document.getElementById('f_lname').value.trim(),
        gender:       document.querySelector('input[name=f_gender]:checked')?.value || '',
        email:        document.getElementById('f_email').value.trim(),
        phone:        document.getElementById('f_phone').value.trim(),
        address:      document.getElementById('f_address').value.trim(),
        department:   document.getElementById('f_dept').value,
        year:         document.getElementById('f_year').value,
        dob:          document.getElementById('f_dob').value,
        status:       document.getElementById('f_status').value,
        new_password: document.getElementById('f_newpass').value
    };
    if (body.new_password && body.new_password.length < 6) {
        err.textContent = 'Password must be at least 6 characters.';
        err.style.display = 'block';
        return;
    }
    const res  = await fetch('php/update_student.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
    const data = await res.json();
    if (data.success) {
        ok.textContent = 'Student updated successfully.';
        ok.style.display = 'block';
        student = {...student, ...body, year_of_study: body.year};
        updateSidebar(student);
        document.getElementById('f_newpass').value = '';
        window.scrollTo({top:0, behavior:'smooth'});
    } else {
        err.textContent = data.error || 'Update failed.';
        err.style.display = 'block';
    }
});

// Photo
document.getElementById('photoBtn').addEventListener('click', () => document.getElementById('photoInput').click());
document.getElementById('photoInput').addEventListener('change', async function () {
    if (!this.files[0]) return;
    const fd = new FormData();
    fd.append('student_id', sid);
    fd.append('photo', this.files[0]);
    const res  = await fetch('php/upload_photo.php', { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) { student.photo = data.photo + '?t=' + Date.now(); setAvatar(student); }
    else alert(data.error || 'Upload failed.');
    this.value = '';
});

// Delete
document.getElementById('deleteBtn').addEventListener('click', async () => {
    if (!confirm('Delete this student permanently? This cannot be undone.')) return;
    const res  = await fetch('php/update_student.php', { method:'DELETE', headers:{'Content-Type':'application/json'}, body:JSON.stringify({student_id: sid}) });
    const data = await res.json();
    if (data.success) location.href = 'view_student.php';
    else alert(data.error || 'Delete failed.');
});

// Suspend
document.getElementById('suspendBtn').addEventListener('click', async () => {
    if (!confirm('Suspend this student? They will not be able to log in.')) return;
    document.getElementById('f_status').value = 'Suspended';
    document.getElementById('saveBtn').click();
});

// Deactivate
document.getElementById('deactivateBtn').addEventListener('click', async () => {
    if (!confirm('Deactivate this student account?')) return;
    document.getElementById('f_status').value = 'Inactive';
    document.getElementById('saveBtn').click();
});

// Tab switcher
function switchTab(name, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// Load documents
function loadDocs() {
    fetch('php/documents.php?student_id=' + encodeURIComponent(sid))
        .then(r => r.json())
        .then(data => {
            const docs = data.documents || [];
            const el = document.getElementById('docsList');
            if (!docs.length) { el.innerHTML = '<p style="color:var(--muted);font-size:0.88rem;">No documents uploaded yet.</p>'; return; }
            el.innerHTML = docs.map(d => `
                <div class="doc-item">
                    <span style="font-size:1.4rem;margin-right:10px;">📄</span>
                    <div class="doc-info">
                        <div class="doc-name">${d.doc_type}</div>
                        <div class="doc-meta">${d.original_name} &middot; ${new Date(d.uploaded_at).toLocaleDateString()} &middot; by ${d.uploaded_by}</div>
                    </div>
                    <a href="${d.filename}" target="_blank" style="background:none;border:none;cursor:pointer;color:#0d6efd;font-size:1.1rem;padding:0 6px;text-decoration:none;" title="View">👁️</a>
                    <button onclick="deleteDoc(${d.id})" style="background:none;border:none;cursor:pointer;color:#dc3545;font-size:1.1rem;padding:0 4px;" title="Delete">🗑️</button>
                </div>`).join('');
        });
}

async function deleteDoc(id) {
    if (!confirm('Delete this document?')) return;
    const res  = await fetch('php/documents.php', { method:'DELETE', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id}) });
    const data = await res.json();
    if (data.success) { loadDocs(); document.getElementById('docOk').textContent = 'Document deleted.'; document.getElementById('docOk').style.display = 'block'; }
    else { document.getElementById('docErr').textContent = data.error || 'Delete failed.'; document.getElementById('docErr').style.display = 'block'; }
}

// Admin doc upload
document.getElementById('adminDocInput').addEventListener('change', async function () {
    if (!this.files[0]) return;
    const err = document.getElementById('docErr'), ok = document.getElementById('docOk');
    err.style.display = ok.style.display = 'none';
    const fd = new FormData();
    fd.append('student_id', sid);
    fd.append('doc_type', document.getElementById('adminDocType').value);
    fd.append('uploaded_by', 'admin');
    fd.append('document', this.files[0]);
    const res  = await fetch('php/documents.php', { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) { ok.textContent = 'Document uploaded.'; ok.style.display = 'block'; loadDocs(); }
    else { err.textContent = data.error || 'Upload failed.'; err.style.display = 'block'; }
    this.value = '';
});

// Admission update
document.getElementById('admSaveBtn').addEventListener('click', async () => {
    const err = document.getElementById('admErr'), ok = document.getElementById('admOk');
    err.style.display = ok.style.display = 'none';
    const res  = await fetch('php/documents.php', {
        method: 'PUT',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ student_id: sid, admission_status: document.getElementById('adm_status').value, admission_note: document.getElementById('adm_note').value })
    });
    const data = await res.json();
    if (data.success) {
        ok.textContent = 'Admission status updated.';
        ok.style.display = 'block';
        student.admission_status = document.getElementById('adm_status').value;
        updateSidebar(student);
        if (student.admission_status === 'Approved')  { document.getElementById('f_status').value = 'Active';   student.status = 'Active'; }
        if (student.admission_status === 'Rejected')  { document.getElementById('f_status').value = 'Inactive'; student.status = 'Inactive'; }
    } else { err.textContent = data.error || 'Update failed.'; err.style.display = 'block'; }
});
</script>
</body>
</html>
