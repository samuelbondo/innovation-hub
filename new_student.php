<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .add-student-wrap {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }
        .form-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .form-card-header {
            padding: 20px 28px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, var(--primary), #16213e);
            color: #fff;
        }
        .form-card-header h3 { font-size: 1rem; margin-bottom: 2px; }
        .form-card-header p  { font-size: 0.78rem; opacity: 0.7; }
        .form-card-body { padding: 28px 32px; }
        .form-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: var(--accent);
            margin: 24px 0 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .form-section-label:first-child { margin-top: 0; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .form-grid-2:last-of-type { margin-bottom: 0; }
        .form-group { margin-bottom: 0; }
        .form-group label { font-size: 0.82rem; color: #444; font-weight: 600; display: block; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 11px 14px; border: 1.5px solid #e0e0e0;
            border-radius: 8px; font-size: 0.9rem; font-family: inherit;
            background: #fafafa; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            color: var(--text);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; border-color: var(--accent); background: #fff;
            box-shadow: 0 0 0 3px rgba(233,69,96,0.08);
        }
        .form-group input[readonly] {
            background: #f0f2f5; color: var(--primary); font-weight: 700;
            cursor: default; border-color: #e0e0e0;
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .form-actions { display: flex; gap: 10px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #f0f0f0; flex-wrap: wrap; }
        /* Sidebar info card */
        .info-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: sticky;
            top: 80px;
        }
        .info-card-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, var(--accent), #c73652);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .info-card-body { padding: 16px 20px; }
        .info-item { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f4f4f4; }
        .info-item:last-child { border-bottom: none; }
        .info-item .ii-icon { font-size: 1.2rem; flex-shrink: 0; margin-top: 1px; }
        .info-item .ii-text { font-size: 0.82rem; color: #555; line-height: 1.5; }
        .info-item .ii-text strong { display: block; color: var(--primary); font-size: 0.85rem; margin-bottom: 1px; }
        /* Success card */
        .success-card {
            display: none;
            background: #f0fdf4;
            border: 2px solid #28a745;
            border-radius: 14px;
            padding: 40px 32px;
            text-align: center;
        }
        .success-card .s-icon { font-size: 3.5rem; margin-bottom: 14px; }
        .success-card h3 { color: #155724; font-size: 1.2rem; margin-bottom: 8px; }
        .success-card p { color: #555; margin-bottom: 24px; font-size: 0.9rem; }
        .id-badge {
            background: #fff;
            border-radius: 12px;
            padding: 20px 28px;
            display: inline-block;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 24px;
            min-width: 260px;
        }
        .id-badge .id-label { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .id-badge .id-num { font-size: 1.9rem; font-weight: 800; color: var(--accent); letter-spacing: 2px; }
        .id-badge hr { margin: 12px 0; border: none; border-top: 1px solid #f0f0f0; }
        .id-badge .id-name { font-size: 1rem; font-weight: 700; color: var(--primary); }
        .id-badge .id-meta { font-size: 0.82rem; color: var(--muted); margin-top: 4px; }
        .doc-section { border-top: 2px solid #f0f0f0; margin-top: 24px; padding-top: 20px; }
        .doc-upload-row { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:12px; }
        .doc-upload-row select { padding:9px 13px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:0.88rem; background:#fafafa; }
        .doc-list { list-style:none; padding:0; margin:0; }
        .doc-list li { display:flex; align-items:center; justify-content:space-between; padding:8px 12px; background:#f8f9fa; border-radius:8px; margin-bottom:6px; font-size:0.84rem; }
        .doc-list li .doc-name { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-right:8px; color:var(--primary); font-weight:600; }
        .doc-list li .doc-type { font-size:0.76rem; color:var(--muted); margin-right:8px; }
        .doc-list li button { background:none; border:none; cursor:pointer; color:#dc3545; font-size:1rem; padding:0 4px; }
        @media (max-width: 900px) {
            .add-student-wrap { grid-template-columns: 1fr; }
            .info-card { position: static; }
        }
        @media (max-width: 600px) {
            .form-grid-2 { grid-template-columns: 1fr; }
            .form-card-body { padding: 18px 16px; }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div>
                    <h2>🎓 Add New Student</h2>
                    <p>Fill in the details below to register a new student</p>
                </div>
                <a href="view_student.php" class="btn btn-outline">📋 View All Students</a>
            </div>

            <!-- Success card (full width) -->
            <div class="success-card" id="successCard">
                <div class="s-icon">🎉</div>
                <h3>Student Registered Successfully!</h3>
                <p>The student has been added to the system. Here are their registration details:</p>
                <div class="id-badge">
                    <div class="id-label">Registration Number</div>
                    <div class="id-num" id="confirmedID"></div>
                    <hr>
                    <div class="id-name" id="confirmedName"></div>
                    <div class="id-meta" id="confirmedDept"></div>
                    <div class="id-meta" id="confirmedYear"></div>
                </div>
                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                    <button onclick="window.print()" class="btn btn-outline" style="color:var(--primary);border-color:var(--primary);">🖨️ Print</button>
                    <a href="new_student.php" class="btn btn-success">➕ Add Another</a>
                    <a href="view_student.php" class="btn">📋 View All Students</a>
                </div>
            </div>

            <div class="add-student-wrap" id="formWrap">

                <!-- Main form -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3>👤 Student Information</h3>
                        <p>All fields marked are required to complete registration</p>
                    </div>
                    <div class="form-card-body">
                        <div class="error-msg" id="errMsg"></div>
                        <form id="studentForm">

                            <div class="form-section-label">🪪 Identity</div>
                            <div class="form-group" style="margin-bottom:16px;">
                                <label>Student ID <span style="font-size:0.75rem;color:var(--muted);font-weight:400;">(Auto-generated)</span></label>
                                <input type="text" id="student_id" readonly>
                            </div>
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label>First Name <span style="color:var(--accent);">*</span></label>
                                    <input type="text" id="fname" placeholder="e.g. John" required>
                                </div>
                                <div class="form-group">
                                    <label>Last Name <span style="color:var(--accent);">*</span></label>
                                    <input type="text" id="lname" placeholder="e.g. Doe" required>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:16px;">
                                <label>Gender <span style="color:var(--accent);">*</span></label>
                                <div style="display:flex;gap:20px;margin-top:6px;">
                                    <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="gender" value="Male" required> Male</label>
                                    <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="gender" value="Female"> Female</label>
                                    <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="gender" value="Other"> Other</label>
                                </div>
                            </div>

                            <div class="form-section-label">📬 Contact</div>
                            <div class="form-grid-2" style="margin-bottom:16px;">
                                <div class="form-group">
                                    <label>Email Address <span style="color:var(--accent);">*</span></label>
                                    <input type="email" id="email" placeholder="student@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="tel" id="phone" placeholder="+1 234 567 890">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea id="address" placeholder="Student's residential address"></textarea>
                            </div>

                            <div class="form-section-label">🏫 Enrollment</div>
                            <div class="form-grid-2" style="margin-bottom:16px;">
                                <div class="form-group">
                                    <label>Faculty <span style="color:var(--accent);">*</span></label>
                                    <select id="faculty" required>
                                        <option value="">— Select Faculty —</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Department <span style="color:var(--accent);">*</span></label>
                                    <select id="department" required disabled>
                                        <option value="">— Select Faculty First —</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label>Year of Study <span style="color:var(--accent);">*</span></label>
                                    <select id="year" required>
                                        <option value="">— Select Year —</option>
                                        <option value="1">Year 1</option>
                                        <option value="2">Year 2</option>
                                        <option value="3">Year 3</option>
                                        <option value="4">Year 4</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Date of Birth <span style="color:var(--accent);">*</span></label>
                                    <input type="date" id="dob" required>
                                </div>
                            </div>

                            <div class="doc-section">
                                <div class="form-section-label">📎 Documents <span style="font-weight:400;font-size:0.75rem;color:var(--muted);text-transform:none;letter-spacing:0;">(optional — PDF, JPG, PNG, DOC, DOCX, max 5MB)</span></div>
                                <div class="doc-upload-row">
                                    <select id="docType">
                                        <option value="Birth Certificate">Birth Certificate</option>
                                        <option value="National ID">National ID / Passport</option>
                                        <option value="Academic Certificate">Academic Certificate</option>
                                        <option value="Recommendation Letter">Recommendation Letter</option>
                                        <option value="Medical Certificate">Medical Certificate</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <button type="button" class="btn btn-outline" onclick="document.getElementById('docFileInput').click()">📤 Add Document</button>
                                    <input type="file" id="docFileInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none;">
                                </div>
                                <div class="error-msg" id="docErr"></div>
                                <ul class="doc-list" id="docList"></ul>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn">💾 Save Student</button>
                                <a href="view_student.php" class="btn btn-outline">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar info -->
                <div class="info-card">
                    <div class="info-card-header">📌 Registration Guide</div>
                    <div class="info-card-body">
                        <div class="info-item">
                            <div class="ii-icon">🪪</div>
                            <div class="ii-text"><strong>Student ID</strong>Auto-generated on save. Format: STU-YEAR-###</div>
                        </div>
                        <div class="info-item">
                            <div class="ii-icon">📧</div>
                            <div class="ii-text"><strong>Email</strong>Must be unique. Used for login access.</div>
                        </div>
                        <div class="info-item">
                            <div class="ii-icon">🏫</div>
                            <div class="ii-text"><strong>Faculty &amp; Department</strong>Select faculty first to load its departments.</div>
                        </div>
                        <div class="info-item">
                            <div class="ii-icon">📅</div>
                            <div class="ii-text"><strong>Date of Birth</strong>Must be a past date. Used for age verification.</div>
                        </div>
                        <div class="info-item">
                            <div class="ii-icon">🔑</div>
                            <div class="ii-text"><strong>Default Password</strong>Set to <code style="background:#f0f0f0;padding:1px 5px;border-radius:4px;">student123</code> — student can change it after login.</div>
                        </div>
                        <div class="info-item">
                            <div class="ii-icon">📎</div>
                            <div class="ii-text"><strong>Documents</strong>Upload supporting docs before saving. You can also add them later from Student Detail.</div>
                        </div>
                        <div class="info-item">
                            <div class="ii-icon">✅</div>
                            <div class="ii-text"><strong>Status</strong>New students are set to <em>Active</em> by default.</div>
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
<script src="shared.js?v=2"></script>
<script>
fetch('php/students.php')
    .then(r => r.json())
    .then(d => { document.getElementById('student_id').value = d.student_id; });

let facultyData = [], pendingDocs = [], savedStudentId = null;

fetch('php/get_faculties.php')
    .then(r => r.json())
    .then(data => {
        facultyData = data;
        const sel = document.getElementById('faculty');
        data.forEach(f => {
            const o = document.createElement('option');
            o.value = f.id; o.textContent = f.name;
            sel.appendChild(o);
        });
    });

document.getElementById('faculty').addEventListener('change', function () {
    const deptSel = document.getElementById('department');
    deptSel.innerHTML = '<option value="">— Select Department —</option>';
    deptSel.disabled = true;
    const fac = facultyData.find(f => f.id == this.value);
    if (!fac) return;
    fac.departments.forEach(d => {
        const o = document.createElement('option');
        o.value = d.name; o.textContent = d.name;
        deptSel.appendChild(o);
    });
    deptSel.disabled = false;
});

const yesterday = new Date();
yesterday.setDate(yesterday.getDate() - 1);
document.getElementById('dob').max = yesterday.toISOString().split('T')[0];

// Doc file picker
document.getElementById('docFileInput').addEventListener('change', function () {
    if (!this.files[0]) return;
    const file = this.files[0];
    const type = document.getElementById('docType').value;
    pendingDocs.push({ file, type });
    renderDocList();
    this.value = '';
});

function renderDocList() {
    const ul = document.getElementById('docList');
    ul.innerHTML = pendingDocs.map((d, i) => `
        <li>
            <span class="doc-type">${d.type}</span>
            <span class="doc-name">📄 ${d.file.name}</span>
            <button type="button" onclick="pendingDocs.splice(${i},1);renderDocList()" title="Remove">✕</button>
        </li>`).join('');
}

document.getElementById('studentForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const err = document.getElementById('errMsg');
    err.style.display = 'none';
    const btn = this.querySelector('button[type=submit]');
    btn.disabled = true; btn.textContent = 'Saving…';

    const res = await fetch('php/students.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            fname:      document.getElementById('fname').value.trim(),
            lname:      document.getElementById('lname').value.trim(),
            gender:     document.querySelector('input[name=gender]:checked')?.value || '',
            email:      document.getElementById('email').value.trim(),
            phone:      document.getElementById('phone').value.trim(),
            department: document.getElementById('department').value,
            year:       document.getElementById('year').value,
            dob:        document.getElementById('dob').value,
            address:    document.getElementById('address').value.trim()
        })
    });
    const data = await res.json();
    if (!data.success) {
        err.textContent = data.error || 'Could not save student.';
        err.style.display = 'block';
        btn.disabled = false; btn.textContent = '💾 Save Student';
        return;
    }

    savedStudentId = data.student_id;

    // Upload any pending docs
    for (const d of pendingDocs) {
        const fd = new FormData();
        fd.append('student_id', savedStudentId);
        fd.append('doc_type', d.type);
        fd.append('uploaded_by', 'admin');
        fd.append('document', d.file);
        await fetch('php/documents.php', { method: 'POST', body: fd });
    }

    document.getElementById('formWrap').style.display    = 'none';
    document.getElementById('confirmedID').textContent   = data.student_id;
    document.getElementById('confirmedName').textContent = document.getElementById('fname').value + ' ' + document.getElementById('lname').value;
    document.getElementById('confirmedDept').textContent = '🏫 ' + document.getElementById('department').value;
    document.getElementById('confirmedYear').textContent = '📅 Year ' + document.getElementById('year').value + '  ·  DOB: ' + document.getElementById('dob').value;
    document.getElementById('successCard').style.display = 'block';
});
</script>
</body>
</html>
