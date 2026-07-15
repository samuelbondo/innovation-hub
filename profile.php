<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-wrap { display:grid; grid-template-columns:300px 1fr; gap:24px; align-items:start; }

        /* Avatar card */
        .avatar-card { background:#fff; border-radius:14px; box-shadow:var(--shadow); overflow:hidden; position:sticky; top:80px; }
        .avatar-card-top { background:linear-gradient(135deg,var(--primary),#16213e); padding:36px 20px; text-align:center; }
        .big-avatar {
            width:100px; height:100px; border-radius:50%;
            background:linear-gradient(135deg,var(--accent),#c73652);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:2.2rem; font-weight:700;
            margin:0 auto 16px; overflow:hidden;
            border:4px solid rgba(255,255,255,0.2);
            cursor:pointer; transition:opacity 0.2s; position:relative;
        }
        .big-avatar:hover { opacity:0.85; }
        .big-avatar img { width:100px; height:100px; object-fit:cover; }
        .avatar-hint { font-size:0.75rem; color:rgba(255,255,255,0.5); margin-top:-8px; margin-bottom:12px; }
        .avatar-card-top h3 { color:#fff; font-size:1.1rem; margin-bottom:4px; }
        .avatar-card-top .role-badge {
            display:inline-block; padding:3px 12px; border-radius:20px;
            font-size:0.75rem; font-weight:700; text-transform:capitalize;
            background:rgba(255,255,255,0.15); color:#fff; margin-top:4px;
        }
        .avatar-card-body { padding:16px 20px; }
        .profile-stat { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f4f4f4; font-size:0.84rem; }
        .profile-stat:last-child { border-bottom:none; }
        .profile-stat .ps-label { color:var(--muted); }
        .profile-stat .ps-val { font-weight:600; color:var(--primary); text-align:right; max-width:160px; word-break:break-word; }

        /* Settings panels */
        .settings-stack { display:flex; flex-direction:column; gap:20px; }
        .settings-card { background:#fff; border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        .settings-card-header { padding:16px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px; }
        .settings-card-header .sh-icon { font-size:1.2rem; }
        .settings-card-header h3 { color:var(--primary); font-size:0.97rem; font-weight:700; }
        .settings-card-header p { font-size:0.78rem; color:var(--muted); margin-top:1px; }
        .settings-card-body { padding:22px 24px; }
        .sg2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .fg { margin-bottom:0; }
        .fg label { font-size:0.81rem; color:#444; font-weight:600; display:block; margin-bottom:5px; }
        .fg input, .fg select {
            width:100%; padding:10px 13px; border:1.5px solid var(--border);
            border-radius:8px; font-size:0.9rem; font-family:inherit;
            background:#fafafa; transition:border-color 0.2s,background 0.2s,box-shadow 0.2s;
        }
        .fg input:focus, .fg select:focus { outline:none; border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px rgba(233,69,96,0.08); }
        .fg input[readonly] { background:#f0f2f5; color:var(--primary); font-weight:600; cursor:default; }
        .card-actions { display:flex; gap:10px; margin-top:20px; padding-top:16px; border-top:1px solid #f0f0f0; flex-wrap:wrap; }

        /* Danger zone */
        .danger-zone { border:1.5px solid #f8d7da; border-radius:14px; padding:20px 24px; background:#fff9f9; }
        .danger-zone h4 { color:#dc3545; font-size:0.9rem; margin-bottom:6px; }
        .danger-zone p { font-size:0.82rem; color:#888; margin-bottom:14px; }

        @media(max-width:900px){ .profile-wrap{grid-template-columns:1fr;} .avatar-card{position:static;} }
        @media(max-width:600px){ .sg2{grid-template-columns:1fr;} .settings-card-body{padding:16px;} }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2>&#x1F464; My Profile &amp; Settings</h2>
                    <p>Manage your account information and preferences</p>
                </div>
            </div>

            <div class="error-msg"  id="topErr"></div>
            <div class="success-msg" id="topOk"></div>

            <div class="profile-wrap">

                <!-- Avatar sidebar -->
                <div class="avatar-card">
                    <div class="avatar-card-top">
                        <div class="big-avatar" id="bigAvatar" title="Click to change photo"></div>
                        <p class="avatar-hint">Click photo to change</p>
                        <h3 id="cardName"></h3>
                        <div class="role-badge" id="cardRole"></div>
                    </div>
                    <div class="avatar-card-body">
                        <div class="profile-stat"><span class="ps-label">Email</span><span class="ps-val" id="cardEmail"></span></div>
                        <div class="profile-stat"><span class="ps-label">Phone</span><span class="ps-val" id="cardPhone"></span></div>
                        <div class="profile-stat"><span class="ps-label">Gender</span><span class="ps-val" id="cardGender">—</span></div>
                        <div class="profile-stat"><span class="ps-label">Department</span><span class="ps-val" id="cardDept"></span></div>
                        <div class="profile-stat"><span class="ps-label">User ID</span><span class="ps-val" id="cardId"></span></div>
                    </div>
                </div>

                <!-- Settings panels -->
                <div class="settings-stack">

                    <!-- Personal info -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <span class="sh-icon">&#x1F464;</span>
                            <div><h3>Personal Information</h3><p>Update your name and contact details</p></div>
                        </div>
                        <div class="settings-card-body">
                            <div class="sg2" style="margin-bottom:16px;">
                                <div class="fg"><label>Full Name <span style="color:var(--accent)">*</span></label><input type="text" id="f_name" placeholder="Your full name"></div>
                                <div class="fg"><label>Email Address</label><input type="email" id="f_email" readonly></div>
                            </div>
                            <div class="sg2" id="nonStudentFields">
                                <div class="fg"><label>Phone Number</label><input type="tel" id="f_phone" placeholder="+1 234 567 890"></div>
                                <div class="fg"><label>Role</label><input type="text" id="f_role" readonly></div>
                            </div>
                            <!-- Student-only fields -->
                            <div id="studentFields" style="display:none;">
                                <div class="sg2" style="margin-bottom:16px;">
                                    <div class="fg"><label>Phone Number</label><input type="tel" id="f_phone_s" placeholder="+1 234 567 890"></div>
                                    <div class="fg"><label>Address</label><input type="text" id="f_address_s" placeholder="Your address"></div>
                                </div>
                                <div class="sg2">
                                    <div class="fg"><label>Student ID</label><input type="text" id="f_sid" readonly></div>
                                    <div class="fg"><label>Gender</label><input type="text" id="f_gender_s" readonly></div>
                                    <div class="fg"><label>Department</label><input type="text" id="f_dept_s" readonly></div>
                                    <div class="fg"><label>Year of Study</label><input type="text" id="f_year_s" readonly></div>
                                    <div class="fg"><label>Date of Birth</label><input type="text" id="f_dob_s" readonly></div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <button class="btn" id="saveInfoBtn">&#x1F4BE; Save Changes</button>
                            </div>
                        </div>
                    </div>

                    <!-- Change password -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <span class="sh-icon">&#x1F512;</span>
                            <div><h3>Change Password</h3><p>Use a strong password you don&rsquo;t use elsewhere</p></div>
                        </div>
                        <div class="settings-card-body">
                            <div class="sg2" style="margin-bottom:16px;">
                                <div class="fg" style="grid-column:1/-1;"><label>Current Password</label><input type="password" id="f_current" placeholder="Enter current password"></div>
                            </div>
                            <div class="sg2">
                                <div class="fg"><label>New Password <span style="font-size:0.74rem;color:var(--muted);font-weight:400;">(min 6 chars)</span></label><input type="password" id="f_newpass" placeholder="New password"></div>
                                <div class="fg"><label>Confirm New Password</label><input type="password" id="f_confirm" placeholder="Repeat new password"></div>
                            </div>
                            <div class="card-actions">
                                <button class="btn" id="savePassBtn">&#x1F512; Update Password</button>
                            </div>
                        </div>
                    </div>

                    <!-- Documents (students only) -->
                    <div class="settings-card" id="docsCard" style="display:none;">
                        <div class="settings-card-header">
                            <span class="sh-icon">&#x1F4CE;</span>
                            <div><h3>My Documents</h3><p>Upload and view your admission documents</p></div>
                        </div>
                        <div class="settings-card-body">
                            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:12px;">
                                <select id="myDocType" style="padding:9px 13px;border:1.5px solid var(--border);border-radius:8px;font-size:0.88rem;background:#fafafa;">
                                    <option value="Birth Certificate">Birth Certificate</option>
                                    <option value="National ID">National ID / Passport</option>
                                    <option value="Academic Certificate">Academic Certificate</option>
                                    <option value="Recommendation Letter">Recommendation Letter</option>
                                    <option value="Medical Certificate">Medical Certificate</option>
                                    <option value="Other">Other</option>
                                </select>
                                <button class="btn btn-outline" onclick="document.getElementById('myDocFile').click()">&#x1F4E4; Upload Document</button>
                                <input type="file" id="myDocFile" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none;">
                            </div>
                            <div class="error-msg" id="myDocErr"></div>
                            <div class="success-msg" id="myDocOk"></div>
                            <div id="myDocsList"><p style="color:var(--muted);font-size:0.88rem;">Loading documents…</p></div>
                        </div>
                    </div>

                    <!-- Photo upload -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <span class="sh-icon">&#x1F4F7;</span>
                            <div><h3>Profile Photo</h3><p>JPG, PNG or WEBP &mdash; max 2MB</p></div>
                        </div>
                        <div class="settings-card-body" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                            <div id="previewAvatar" style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;font-weight:700;overflow:hidden;flex-shrink:0;"></div>
                            <div style="flex:1;">
                                <input type="file" id="photoFile" accept="image/*" style="display:none;">
                                <button class="btn btn-outline" id="choosePhotoBtn">&#x1F4C2; Choose Photo</button>
                                <button class="btn" id="uploadPhotoBtn" style="display:none;">&#x2B06; Upload</button>
                                <p style="font-size:0.78rem;color:var(--muted);margin-top:8px;" id="photoName">No file chosen</p>
                            </div>
                        </div>
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
const _u   = JSON.parse(localStorage.getItem('user') || '{}');
const uid  = _u.id;
if (!uid) location.href = 'dashboard.php';

function showMsg(type, msg) {
    const err = document.getElementById('topErr');
    const ok  = document.getElementById('topOk');
    err.style.display = ok.style.display = 'none';
    if (type === 'ok') { ok.textContent = msg; ok.style.display = 'block'; }
    else { err.textContent = msg; err.style.display = 'block'; }
    window.scrollTo({top:0, behavior:'smooth'});
}

function setAvatar(el, user, size) {
    const ini = user.name.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
    el.style.fontSize = size === 'lg' ? '2.2rem' : '1.4rem';
    el.innerHTML = user.photo
        ? `<img src="${user.photo}?t=${Date.now()}" style="width:100%;height:100%;object-fit:cover;">`
        : ini;
}

function updateSidebar(user) {
    // Update sidebar avatar and name live
    const sidebarAv = document.querySelector('.sidebar-user .avatar, .sidebar-user img');
    const topbarAv  = document.querySelector('.topbar-user .avatar, .topbar-user img');
    const sidebarName = document.querySelector('.sidebar-user .name');
    const topbarName  = document.querySelector('.topbar-user span');
    const ini = user.name.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
    const photoSrc = user.photo ? user.photo + '?t=' + Date.now() : null;

    if (sidebarAv) {
        if (photoSrc) { sidebarAv.outerHTML = `<img src="${photoSrc}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;" alt="${user.name}">`; }
        else if (sidebarAv.tagName === 'IMG') { sidebarAv.outerHTML = `<div class="avatar">${ini}</div>`; }
        else { sidebarAv.textContent = ini; }
    }
    if (topbarAv) {
        if (photoSrc) { topbarAv.outerHTML = `<img src="${photoSrc}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:6px;" alt="${user.name}">`; }
        else if (topbarAv.tagName === 'IMG') { topbarAv.outerHTML = `<div class="avatar">${ini}</div>`; }
        else { topbarAv.textContent = ini; }
    }
    if (sidebarName) sidebarName.textContent = user.name;
    if (topbarName)  topbarName.textContent  = user.name;
}

// Load profile
fetch(`php/user_profile.php?id=${uid}`)
    .then(r => r.json())
    .then(u => {
        setAvatar(document.getElementById('bigAvatar'), u, 'lg');
        setAvatar(document.getElementById('previewAvatar'), u, 'sm');
        document.getElementById('cardName').textContent  = u.name;
        document.getElementById('cardRole').textContent  = u.role;
        document.getElementById('cardEmail').textContent = u.email;
        document.getElementById('cardPhone').textContent = u.phone || '—';
        document.getElementById('cardDept').textContent  = u.department || '—';
        document.getElementById('cardId').textContent    = '#' + u.id;
        document.getElementById('f_name').value  = u.name;
        document.getElementById('f_email').value = u.email;

        if (_u.role === 'student') {
            document.getElementById('nonStudentFields').style.display = 'none';
            document.getElementById('studentFields').style.display    = 'block';
            document.getElementById('docsCard').style.display         = 'block';
            // Load student record for enrollment details
            fetch('php/dashboard.php?email=' + encodeURIComponent(_u.email) + '&role=student')
                .then(r => r.json())
                .then(d => {
                    const s = d.student;
                    if (!s) return;
                    document.getElementById('f_phone_s').value   = s.phone || '';
                    document.getElementById('f_address_s').value = s.address || '';
                    document.getElementById('f_sid').value       = s.student_id;
                    document.getElementById('f_gender_s').value  = s.gender || '—';
                    document.getElementById('f_dept_s').value    = s.department;
                    document.getElementById('f_year_s').value    = 'Year ' + s.year_of_study;
                    document.getElementById('f_dob_s').value     = s.dob || '';
                    document.getElementById('cardDept').textContent  = s.department;
                    document.getElementById('cardPhone').textContent = s.phone || '—';
                    document.getElementById('cardGender').textContent = s.gender || '—';
                    loadMyDocs(s.student_id);
                });
        } else {
            document.getElementById('f_phone').value = u.phone || '';
            document.getElementById('f_role').value  = u.role;
        }
    });

// Click big avatar → trigger file input
document.getElementById('bigAvatar').addEventListener('click', () => document.getElementById('photoInput').click());

// Save personal info
document.getElementById('saveInfoBtn').addEventListener('click', async () => {
    if (_u.role === 'student') {
        // Students update phone + address via update_profile.php
        const res  = await fetch('php/update_profile.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                email:   _u.email,
                phone:   document.getElementById('f_phone_s').value.trim(),
                address: document.getElementById('f_address_s').value.trim()
            })
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('cardPhone').textContent = document.getElementById('f_phone_s').value || '—';
            showMsg('ok', 'Profile updated successfully.');
        } else { showMsg('err', data.error || 'Update failed.'); }
        return;
    }
    const name  = document.getElementById('f_name').value.trim();
    const phone = document.getElementById('f_phone').value.trim();
    if (!name) { showMsg('err', 'Name is required.'); return; }
    const res  = await fetch('php/user_profile.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({id: uid, name, phone})
    });
    const data = await res.json();
    if (data.success) {
        _u.name = name;
        localStorage.setItem('user', JSON.stringify(_u));
        document.getElementById('cardName').textContent  = name;
        document.getElementById('cardPhone').textContent = phone || '—';
        updateSidebar(_u);
        showMsg('ok', 'Profile updated successfully.');
    } else { showMsg('err', data.error || 'Update failed.'); }
});

// Change password
document.getElementById('savePassBtn').addEventListener('click', async () => {
    const current = document.getElementById('f_current').value;
    const newPass = document.getElementById('f_newpass').value;
    const confirm = document.getElementById('f_confirm').value;
    if (!current || !newPass || !confirm) { showMsg('err', 'All password fields are required.'); return; }
    if (newPass !== confirm) { showMsg('err', 'New passwords do not match.'); return; }
    if (newPass.length < 6) { showMsg('err', 'Password must be at least 6 characters.'); return; }
    const res  = await fetch('php/reset_password.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({action:'change_own', email: _u.email, current_password: current, new_password: newPass, confirm_password: confirm})
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('f_current').value = '';
        document.getElementById('f_newpass').value  = '';
        document.getElementById('f_confirm').value  = '';
        showMsg('ok', 'Password updated successfully.');
    } else {
        showMsg('err', data.error || 'Password update failed.');
    }
});

// Photo — choose file
document.getElementById('choosePhotoBtn').addEventListener('click', () => document.getElementById('photoFile').click());
document.getElementById('photoFile').addEventListener('change', function() {
    if (!this.files[0]) return;
    document.getElementById('photoName').textContent = this.files[0].name;
    document.getElementById('uploadPhotoBtn').style.display = 'inline-flex';
    // Preview
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('previewAvatar');
        prev.innerHTML = `<img src="${e.target.result}" style="width:72px;height:72px;object-fit:cover;">`;
    };
    reader.readAsDataURL(this.files[0]);
});

// Photo — upload (from choose button)
document.getElementById('uploadPhotoBtn').addEventListener('click', () => uploadPhoto(document.getElementById('photoFile').files[0]));

// Photo — upload (from clicking big avatar)
document.getElementById('photoInput').addEventListener('change', function() {
    if (this.files[0]) uploadPhoto(this.files[0]);
    this.value = '';
});

function loadMyDocs(studentId) {
    fetch('php/documents.php?student_id=' + encodeURIComponent(studentId))
        .then(r => r.json())
        .then(data => {
            const docs = data.documents || [];
            const el = document.getElementById('myDocsList');
            if (!docs.length) { el.innerHTML = '<p style="color:var(--muted);font-size:0.88rem;">No documents uploaded yet.</p>'; return; }
            el.innerHTML = docs.map(d => `
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#f8f9fa;border-radius:8px;margin-bottom:8px;font-size:0.85rem;">
                    <span style="font-size:1.3rem;margin-right:10px;">&#x1F4C4;</span>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:var(--primary);">${d.doc_type}</div>
                        <div style="font-size:0.76rem;color:var(--muted);margin-top:2px;">${d.original_name} &middot; ${new Date(d.uploaded_at).toLocaleDateString()}</div>
                    </div>
                    <a href="${d.filename}" target="_blank" style="color:#0d6efd;font-size:1.1rem;padding:0 6px;text-decoration:none;" title="View">&#x1F441;&#xFE0F;</a>
                </div>`).join('');
        });
}

document.getElementById('myDocFile').addEventListener('change', async function() {
    if (!this.files[0]) return;
    const sid = document.getElementById('f_sid').value;
    if (!sid) return;
    const err = document.getElementById('myDocErr'), ok = document.getElementById('myDocOk');
    err.style.display = ok.style.display = 'none';
    const fd = new FormData();
    fd.append('student_id', sid);
    fd.append('doc_type', document.getElementById('myDocType').value);
    fd.append('uploaded_by', 'student');
    fd.append('document', this.files[0]);
    const res  = await fetch('php/documents.php', { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) { ok.textContent = 'Document uploaded.'; ok.style.display = 'block'; loadMyDocs(sid); }
    else { err.textContent = data.error || 'Upload failed.'; err.style.display = 'block'; }
    this.value = '';
});

async function uploadPhoto(file) {
    if (!file) return;
    const fd = new FormData();
    fd.append('id', uid);
    fd.append('photo', file);
    const res  = await fetch('php/user_profile.php', {method:'POST', body:fd});
    const data = await res.json();
    if (data.success) {
        _u.photo = data.photo;
        localStorage.setItem('user', JSON.stringify(_u));
        setAvatar(document.getElementById('bigAvatar'), _u, 'lg');
        setAvatar(document.getElementById('previewAvatar'), _u, 'sm');
        updateSidebar(_u);
        document.getElementById('uploadPhotoBtn').style.display = 'none';
        document.getElementById('photoName').textContent = 'Photo updated!';
        showMsg('ok', 'Profile photo updated successfully.');
    } else {
        showMsg('err', data.error || 'Upload failed.');
    }
}
</script>
</body>
</html>
