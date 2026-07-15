<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .doc-upload-area { border:2px dashed var(--border); border-radius:10px; padding:20px; text-align:center; cursor:pointer; transition:border-color 0.2s; margin-bottom:12px; }
        .doc-upload-area:hover { border-color:var(--accent); }
        .doc-list { list-style:none; padding:0; margin:0; }
        .doc-list li { display:flex; align-items:center; justify-content:space-between; padding:8px 12px; background:#f8f9fa; border-radius:8px; margin-bottom:6px; font-size:0.85rem; }
        .doc-list li .doc-name { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-right:8px; }
        .doc-list li button { background:none; border:none; cursor:pointer; color:#dc3545; font-size:1rem; padding:0 4px; }
        .step-indicator { display:flex; gap:0; margin-bottom:24px; }
        .step { flex:1; text-align:center; padding:10px 6px; font-size:0.78rem; font-weight:600; color:var(--muted); border-bottom:3px solid var(--border); }
        .step.active { color:var(--accent); border-bottom-color:var(--accent); }
        .step.done { color:#28a745; border-bottom-color:#28a745; }
        .track-box { background:#fff8e1; border:1.5px solid #ffc107; border-radius:10px; padding:16px; margin-top:16px; text-align:center; }
        .track-box p { font-size:0.85rem; color:#555; margin-bottom:8px; }
    </style>
</head>
<body class="auth-page" style="padding: 40px 20px;">

<div class="auth-box" style="max-width:560px; width:100%;">
    <div class="auth-logo">
        <div class="logo-text" id="authLogo">Group<span> One</span></div>
        <p id="authTagline">Student Management System</p>
    </div>

    <!-- Step indicators -->
    <div class="step-indicator" id="stepIndicator">
        <div class="step active" id="step1Label">1. Register</div>
        <div class="step" id="step2Label">2. Upload Docs</div>
        <div class="step" id="step3Label">3. Done</div>
    </div>

    <!-- STEP 1: Registration form -->
    <div id="formSection">
        <h2>Student Registration</h2>
        <div class="error-msg" id="errMsg"></div>
        <form id="signupForm" autocomplete="off">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" placeholder="e.g. John" required>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" placeholder="e.g. Doe" required>
                </div>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <div style="display:flex;gap:20px;margin-top:4px;">
                    <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="gender" value="Male" required> Male</label>
                    <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="gender" value="Female"> Female</label>
                    <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;"><input type="radio" name="gender" value="Other"> Other</label>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" placeholder="you@example.com" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" placeholder="+1 234 567 890">
            </div>
            <div class="form-group">
                <label for="faculty">Faculty</label>
                <select id="faculty" required>
                    <option value="">-- Select Faculty --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" required disabled>
                    <option value="">-- Select Faculty First --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year of Study</label>
                <select id="year" required>
                    <option value="">-- Select --</option>
                    <option value="1">Year 1</option>
                    <option value="2">Year 2</option>
                    <option value="3">Year 3</option>
                    <option value="4">Year 4</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" placeholder="Your residential address">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password" placeholder="••••••••" required style="padding-right:40px;width:100%;" autocomplete="new-password">
                        <span onclick="togglePwd('password','eyeP')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1rem;" id="eyeP">👁️</span>
                    </div>
                    <div id="strengthBar" style="height:5px;border-radius:4px;margin-top:6px;background:#e0e0e0;transition:all 0.3s;">
                        <div id="strengthFill" style="height:100%;border-radius:4px;width:0%;transition:all 0.3s;"></div>
                    </div>
                    <div id="strengthLabel" style="font-size:0.72rem;margin-top:3px;font-weight:600;"></div>
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <div style="position:relative;">
                        <input type="password" id="confirm" placeholder="••••••••" required style="padding-right:40px;width:100%;" autocomplete="new-password">
                        <span onclick="togglePwd('confirm','eyeC')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1rem;" id="eyeC">👁️</span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn" style="width:100%; justify-content:center; margin-top:4px;">Create Account →</button>
        </form>
        <div class="auth-switch">Already have an account? <a href="login.php">Sign in</a></div>
    </div>

    <!-- STEP 2: Document upload -->
    <div id="docSection" style="display:none;">
        <h2>📎 Upload Your Documents</h2>
        <p style="color:var(--muted);font-size:0.88rem;margin-bottom:16px;">Upload supporting documents for your admission. You can upload multiple files. Allowed: PDF, JPG, PNG, DOC, DOCX (max 5MB each).</p>
        <div class="error-msg" id="docErr"></div>
        <div class="success-msg" id="docOk"></div>

        <div style="margin-bottom:14px;">
            <label style="font-size:0.82rem;font-weight:600;display:block;margin-bottom:6px;">Document Type</label>
            <select id="docType" style="width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;background:#fafafa;">
                <option value="Birth Certificate">Birth Certificate</option>
                <option value="National ID">National ID / Passport</option>
                <option value="Academic Certificate">Academic Certificate / Transcript</option>
                <option value="Recommendation Letter">Recommendation Letter</option>
                <option value="Medical Certificate">Medical Certificate</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="doc-upload-area" id="dropArea" onclick="document.getElementById('docFileInput').click()">
            <div style="font-size:2rem;margin-bottom:6px;">📁</div>
            <div style="font-size:0.88rem;color:var(--muted);">Click to select a file or drag & drop here</div>
        </div>
        <input type="file" id="docFileInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none;">

        <ul class="doc-list" id="docList"></ul>

        <div style="display:flex;gap:10px;margin-top:16px;">
            <button class="btn" id="finishBtn" style="flex:1;justify-content:center;" onclick="finishUpload()">✅ Submit & Finish</button>
            <button class="btn btn-outline" onclick="skipDocs()" style="flex:1;justify-content:center;">Skip for now</button>
        </div>
    </div>

    <!-- STEP 3: Done -->
    <div id="successCard" style="display:none; text-align:center;">
        <div style="font-size:3rem; margin-bottom:10px;">🎉</div>
        <h3 style="color:#155724; margin-bottom:6px;">Registration Submitted!</h3>
        <p style="color:#555; font-size:0.9rem; margin-bottom:20px;">Your application is under review. Save your registration number to track your admission status.</p>
        <div style="background:#f0fdf4; border:2px solid #28a745; border-radius:10px; padding:20px; margin-bottom:16px;">
            <div style="font-size:0.75rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Registration Number</div>
            <div id="confirmedID" style="font-size:2rem; font-weight:800; color:var(--accent); letter-spacing:3px;"></div>
            <hr style="margin:14px 0; border-color:#d4edda;">
            <div id="confirmedName" style="font-size:1rem; font-weight:600; color:var(--primary);"></div>
            <div id="confirmedDept" style="font-size:0.85rem; color:var(--muted); margin-top:4px;"></div>
        </div>
        <div class="track-box">
            <p>🔍 You can track your admission status anytime using your registration number.</p>
            <a id="trackLink" href="#" class="btn" style="width:100%;justify-content:center;margin-bottom:8px;">Track My Admission</a>
        </div>
        <a href="login.php" class="btn btn-outline" style="width:100%; justify-content:center; margin-top:10px;">Go to Login</a>
    </div>
</div>

<script>
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    document.getElementById('dob').max = yesterday.toISOString().split('T')[0];

    // Load system name
    fetch('php/get_settings.php').then(r=>r.json()).then(s => {
        if (s.system_logo) {
            document.getElementById('authLogo').innerHTML = `<img src="${s.system_logo}" alt="Logo" style="max-height:60px;max-width:180px;object-fit:contain;">`;
        } else if (s.system_name) {
            const parts = s.system_name.trim().split(' ');
            const first = parts.slice(0,-1).join(' ') || parts[0];
            const last  = parts.length > 1 ? '<span> ' + parts[parts.length-1] + '</span>' : '';
            document.getElementById('authLogo').innerHTML = first + last;
        }
        if (s.system_tagline) document.getElementById('authTagline').textContent = s.system_tagline;
    }).catch(()=>{});

    let facultyData = [], registeredSID = '';

    fetch('php/get_faculties.php')
        .then(r => r.json())
        .then(data => {
            facultyData = data;
            const sel = document.getElementById('faculty');
            data.forEach(f => {
                const o = document.createElement('option');
                o.value = f.id; o.textContent = f.name + ' (' + f.abbreviation + ')';
                sel.appendChild(o);
            });
        });

    document.getElementById('faculty').addEventListener('change', function() {
        const deptSel = document.getElementById('department');
        deptSel.innerHTML = '<option value="">-- Select Department --</option>';
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

    // Drag & drop
    const dropArea = document.getElementById('dropArea');
    dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.style.borderColor = 'var(--accent)'; });
    dropArea.addEventListener('dragleave', () => { dropArea.style.borderColor = 'var(--border)'; });
    dropArea.addEventListener('drop', e => {
        e.preventDefault(); dropArea.style.borderColor = 'var(--border)';
        if (e.dataTransfer.files[0]) uploadDoc(e.dataTransfer.files[0]);
    });
    document.getElementById('docFileInput').addEventListener('change', function() {
        if (this.files[0]) uploadDoc(this.files[0]);
        this.value = '';
    });

    async function uploadDoc(file) {
        const err = document.getElementById('docErr'), ok = document.getElementById('docOk');
        err.style.display = ok.style.display = 'none';
        const fd = new FormData();
        fd.append('student_id', registeredSID);
        fd.append('doc_type', document.getElementById('docType').value);
        fd.append('uploaded_by', 'student');
        fd.append('document', file);
        const res  = await fetch('php/documents.php', { method:'POST', body:fd });
        const data = await res.json();
        if (data.success) {
            ok.textContent = '✅ ' + file.name + ' uploaded successfully.';
            ok.style.display = 'block';
            addDocToList(data.original_name, data.filename);
        } else {
            err.textContent = data.error || 'Upload failed.';
            err.style.display = 'block';
        }
    }

    function addDocToList(name, filename) {
        const li = document.createElement('li');
        li.innerHTML = `<span class="doc-name">📄 ${name}</span><button onclick="this.parentElement.remove()" title="Remove from list">✕</button>`;
        document.getElementById('docList').appendChild(li);
    }

    function finishUpload() {
        document.getElementById('docSection').style.display = 'none';
        document.getElementById('successCard').style.display = 'block';
        document.getElementById('step2Label').className = 'step done';
        document.getElementById('step3Label').className = 'step active';
    }

    function skipDocs() { finishUpload(); }

    function togglePwd(inputId, eyeId) {
        const inp = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        eye.textContent = inp.type === 'password' ? '\ud83d\udc41\ufe0f' : '\ud83d\udeab';
    }

    function getStrength(pwd) {
        let score = 0;
        if (pwd.length >= 8)  score++;
        if (pwd.length >= 12) score++;
        if (/[A-Z]/.test(pwd)) score++;
        if (/[0-9]/.test(pwd)) score++;
        if (/[^A-Za-z0-9]/.test(pwd)) score++;
        return score;
    }

    document.getElementById('password').addEventListener('input', function() {
        const score = getStrength(this.value);
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        const levels = [
            { pct:'20%', color:'#dc3545', text:'Very Weak' },
            { pct:'40%', color:'#fd7e14', text:'Weak' },
            { pct:'60%', color:'#ffc107', text:'Fair' },
            { pct:'80%', color:'#20c997', text:'Strong' },
            { pct:'100%',color:'#28a745', text:'Very Strong' }
        ];
        const lvl = levels[Math.max(0, score - 1)] || levels[0];
        fill.style.width = this.value ? lvl.pct : '0%';
        fill.style.background = lvl.color;
        label.textContent = this.value ? lvl.text : '';
        label.style.color = lvl.color;
    });

    document.getElementById('signupForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const err = document.getElementById('errMsg');
        err.style.display = 'none';
        const pwd = document.getElementById('password').value;
        if (getStrength(pwd) < 3) {
            err.textContent = 'Password is too weak. Use at least 8 characters with uppercase, numbers, or symbols.';
            err.style.display = 'block';
            return;
        }
        const btn = this.querySelector('button[type=submit]');
        btn.disabled = true; btn.textContent = 'Registering…';

        try {
            const res = await fetch('php/signup.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    fname:      document.getElementById('fname').value.trim(),
                    lname:      document.getElementById('lname').value.trim(),
                    gender:     document.querySelector('input[name=gender]:checked')?.value || '',
                    email:      document.getElementById('email').value.trim(),
                    phone:      document.getElementById('phone').value.trim(),
                    department: document.getElementById('department').value,
                    year:       document.getElementById('year').value,
                    dob:        document.getElementById('dob').value,
                    address:    document.getElementById('address').value.trim(),
                    password:   document.getElementById('password').value,
                    confirm:    document.getElementById('confirm').value
                })
            });
            const data = await res.json();
            if (data.success) {
                registeredSID = data.student_id;
                document.getElementById('confirmedID').textContent   = data.student_id;
                document.getElementById('confirmedName').textContent = data.name;
                document.getElementById('confirmedDept').textContent = document.getElementById('department').value + ' · Year ' + document.getElementById('year').value;
                document.getElementById('trackLink').href = 'track_admission.php?id=' + encodeURIComponent(data.student_id);
                // Go to step 2
                document.getElementById('formSection').style.display = 'none';
                document.getElementById('docSection').style.display  = 'block';
                document.getElementById('step1Label').className = 'step done';
                document.getElementById('step2Label').className = 'step active';
            } else {
                err.textContent = data.error || 'Registration failed.';
                err.style.display = 'block';
                btn.disabled = false; btn.textContent = 'Create Account →';
            }
        } catch(ex) {
            err.textContent = 'Connection error.';
            err.style.display = 'block';
            btn.disabled = false; btn.textContent = 'Create Account →';
        }
    });
</script>
</body>
</html>
