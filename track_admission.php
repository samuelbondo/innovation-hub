<?php $activePage = 'track'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Admission | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * { box-sizing:border-box; }

        /* ── Hero ── */
        .track-hero { background:linear-gradient(135deg,var(--primary),#16213e); padding:48px 24px 80px; text-align:center; color:#fff; }
        .track-hero h1 { font-size:1.9rem; font-weight:800; margin-bottom:8px; }
        .track-hero p  { color:rgba(255,255,255,0.6); font-size:0.92rem; margin-bottom:32px; }
        .search-card { background:#fff; border-radius:16px; padding:24px 28px; max-width:560px; margin:0 auto; box-shadow:0 20px 60px rgba(0,0,0,0.25); }
        .search-card label { display:block; font-size:0.78rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; }
        .search-row { display:flex; gap:10px; }
        .search-row input { flex:1; padding:13px 16px; border:1.5px solid var(--border); border-radius:8px; font-size:0.95rem; outline:none; font-family:inherit; letter-spacing:1px; transition:border-color 0.2s; }
        .search-row input:focus { border-color:var(--accent); }
        .search-row button { padding:0 24px; background:var(--accent); color:#fff; border:none; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer; transition:background 0.2s; white-space:nowrap; }
        .search-row button:hover { background:#c73652; }
        .error-msg { margin-top:10px; }

        /* ── Main content ── */
        .track-body { max-width:760px; margin:-40px auto 48px; padding:0 20px; }

        /* ── Result card ── */
        .result-wrap { display:grid; grid-template-columns:220px 1fr; gap:20px; align-items:start; margin-bottom:20px; }

        /* Student card */
        .stu-card { background:#fff; border-radius:14px; box-shadow:0 2px 16px rgba(0,0,0,0.08); overflow:hidden; }
        .stu-card-top { background:linear-gradient(135deg,var(--primary),#16213e); padding:28px 16px; text-align:center; }
        .stu-avatar { width:72px; height:72px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#c73652); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.6rem; font-weight:700; margin:0 auto 12px; overflow:hidden; border:3px solid rgba(255,255,255,0.2); }
        .stu-avatar img { width:72px; height:72px; object-fit:cover; }
        .stu-card-top h3 { color:#fff; font-size:0.95rem; font-weight:700; margin-bottom:3px; }
        .stu-card-top .stu-id { color:rgba(255,255,255,0.55); font-size:0.75rem; }
        .stu-card-body { padding:14px 16px; }
        .stu-stat { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f4f4f4; font-size:0.8rem; }
        .stu-stat:last-child { border-bottom:none; }
        .stu-stat .sl { color:var(--muted); }
        .stu-stat .sv { font-weight:600; color:var(--primary); text-align:right; }

        /* Status + info panel */
        .info-panel { background:#fff; border-radius:14px; box-shadow:0 2px 16px rgba(0,0,0,0.08); overflow:hidden; }

        /* Status banner */
        .status-banner { padding:24px 28px; display:flex; align-items:center; gap:18px; }
        .status-banner.st-pending  { background:#fff8e1; border-bottom:3px solid #ffc107; }
        .status-banner.st-review   { background:#e8f4fd; border-bottom:3px solid #0d6efd; }
        .status-banner.st-approved { background:#f0fdf4; border-bottom:3px solid #28a745; }
        .status-banner.st-rejected { background:#fff5f5; border-bottom:3px solid #dc3545; }
        .status-banner .sb-icon { font-size:2.4rem; flex-shrink:0; }
        .status-banner .sb-title { font-size:1.05rem; font-weight:800; margin-bottom:3px; }
        .status-banner .sb-msg   { font-size:0.83rem; color:#555; line-height:1.5; }

        /* Timeline */
        .timeline { display:flex; padding:20px 28px; gap:0; border-bottom:1px solid var(--border); }
        .tl-step { flex:1; text-align:center; position:relative; }
        .tl-step::before { content:''; position:absolute; top:14px; left:-50%; right:50%; height:2px; background:var(--border); z-index:0; }
        .tl-step:first-child::before { display:none; }
        .tl-dot { width:28px; height:28px; border-radius:50%; background:var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 6px; font-size:0.75rem; font-weight:700; color:#fff; position:relative; z-index:1; }
        .tl-dot.done    { background:#28a745; }
        .tl-dot.current { background:var(--accent); box-shadow:0 0 0 4px rgba(233,69,96,0.2); }
        .tl-dot.reject  { background:#dc3545; }
        .tl-label { font-size:0.7rem; font-weight:600; color:var(--muted); }
        .tl-label.done    { color:#28a745; }
        .tl-label.current { color:var(--accent); }
        .tl-label.reject  { color:#dc3545; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; padding:4px 0; }
        .ig-item { padding:14px 28px; border-bottom:1px solid #f4f4f4; }
        .ig-item:nth-child(odd) { border-right:1px solid #f4f4f4; }
        .ig-item .ig-label { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:var(--muted); margin-bottom:4px; }
        .ig-item .ig-val   { font-size:0.9rem; font-weight:600; color:var(--primary); }

        /* Note */
        .adm-note { margin:0 28px 20px; background:#fff3cd; border:1px solid #ffc107; border-radius:8px; padding:12px 16px; font-size:0.84rem; }
        .adm-note strong { display:block; margin-bottom:4px; }

        /* Docs */
        .docs-section { padding:0 28px 20px; }
        .docs-section h4 { font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin-bottom:12px; }
        .doc-row { display:flex; align-items:center; gap:12px; padding:10px 14px; background:#f8f9fa; border-radius:8px; margin-bottom:8px; }
        .doc-row .dr-icon { font-size:1.4rem; flex-shrink:0; }
        .doc-row .dr-type { font-weight:600; font-size:0.85rem; color:var(--primary); }
        .doc-row .dr-meta { font-size:0.75rem; color:var(--muted); margin-top:2px; }

        /* CTA */
        .cta-row { padding:16px 28px 24px; display:flex; gap:10px; flex-wrap:wrap; }

        @media(max-width:700px){
            .result-wrap { grid-template-columns:1fr; }
            .info-grid { grid-template-columns:1fr; }
            .ig-item:nth-child(odd) { border-right:none; }
            .timeline { padding:16px; }
            .track-hero h1 { font-size:1.4rem; }
            .search-card { padding:18px; }
        }
    </style>
</head>
<body>

<?php require 'php/pub_header.php'; ?>

<div class="track-hero">
    <h1>🔍 Admission Status Tracker</h1>
    <p>Enter your registration number to check your current admission status.</p>
    <div class="search-card">
        <label for="sidInput">Registration Number</label>
        <div class="search-row">
            <input type="text" id="sidInput" placeholder="e.g. STU-2026-001" autocomplete="off" oninput="this.value=this.value.toUpperCase()">
            <button onclick="trackAdmission()">Check Status</button>
        </div>
        <div class="error-msg" id="trackErr"></div>
    </div>
</div>

<div class="track-body pub-main" style="max-width:820px;">
    <div id="resultArea" style="display:none;"></div>
</div>

<script>
const urlId = new URLSearchParams(location.search).get('id');
if (urlId) {
    document.getElementById('sidInput').value = urlId.toUpperCase();
    window.addEventListener('DOMContentLoaded', trackAdmission);
}

async function trackAdmission() {
    const sid = document.getElementById('sidInput').value.trim();
    const err = document.getElementById('trackErr');
    const out = document.getElementById('resultArea');
    err.style.display = 'none';
    out.style.display = 'none';
    if (!sid) { err.textContent = 'Please enter your registration number.'; err.style.display = 'block'; return; }

    const btn = document.querySelector('.search-row button');
    btn.textContent = 'Checking…'; btn.disabled = true;

    try {
        const [sRes, dRes] = await Promise.all([
            fetch('php/get_students.php?id=' + encodeURIComponent(sid)),
            fetch('php/documents.php?student_id=' + encodeURIComponent(sid))
        ]);
        const sData = await sRes.json();
        const dData = await dRes.json();

        if (!sData || sData.error) {
            err.textContent = 'No record found for "' + sid + '". Please check your registration number.';
            err.style.display = 'block';
            return;
        }

        const status = dData.admission_status || 'Pending';
        const steps  = ['Pending','Under Review','Approved'];
        const isRej  = status === 'Rejected';

        const stMap = {
            'Pending':      { cls:'st-pending',  icon:'⏳', title:'Application Pending',        msg:'Your application has been received. Please upload your supporting documents to proceed.' },
            'Under Review': { cls:'st-review',   icon:'🔎', title:'Under Review',                msg:'Your documents are being reviewed by the admissions team. You will be notified of the outcome.' },
            'Approved':     { cls:'st-approved', icon:'✅', title:'Admission Approved!',         msg:'Congratulations! Your admission has been approved. You may now log in to your student portal.' },
            'Rejected':     { cls:'st-rejected', icon:'❌', title:'Application Unsuccessful',    msg:'Unfortunately your application was not successful. Please contact the admissions office for further guidance.' }
        };
        const st = stMap[status];

        // Timeline steps
        const tlHTML = (isRej ? ['Pending','Under Review','Rejected'] : steps).map((step, i) => {
            const idx     = isRej ? i : steps.indexOf(step);
            const curIdx  = isRej ? ['Pending','Under Review','Rejected'].indexOf(status) : steps.indexOf(status);
            let dotCls = '', lblCls = '', icon = (i + 1).toString();
            if (isRej && step === 'Rejected') { dotCls = 'reject'; lblCls = 'reject'; icon = '✕'; }
            else if (idx < curIdx)            { dotCls = 'done';   lblCls = 'done';   icon = '✓'; }
            else if (idx === curIdx)          { dotCls = 'current';lblCls = 'current'; }
            return `<div class="tl-step"><div class="tl-dot ${dotCls}">${icon}</div><div class="tl-label ${lblCls}">${step}</div></div>`;
        }).join('');

        // Docs
        const docs = dData.documents || [];
        const docsHTML = docs.length
            ? docs.map(d => `<div class="doc-row"><span class="dr-icon">📄</span><div><div class="dr-type">${d.doc_type}</div><div class="dr-meta">${d.original_name} &middot; ${new Date(d.uploaded_at).toLocaleDateString()} &middot; uploaded by ${d.uploaded_by}</div></div></div>`).join('')
            : '<p style="color:var(--muted);font-size:0.85rem;margin:0;">No documents uploaded yet.</p>';

        const noteHTML = dData.admission_note
            ? `<div class="adm-note"><strong>📝 Note from Admissions:</strong>${dData.admission_note}</div>` : '';

        const ini = (sData.fname[0] + sData.lname[0]).toUpperCase();
        const avatarHTML = sData.photo
            ? `<img src="${sData.photo}" alt="${ini}">`
            : ini;

        const ctaHTML = status === 'Approved'
            ? `<a href="login.php" class="btn" style="flex:1;justify-content:center;">🔐 Login to Portal</a>`
            : status === 'Pending'
            ? `<a href="signup.php" class="btn btn-outline" style="flex:1;justify-content:center;">📎 Upload Documents</a>`
            : '';

        out.style.display = 'block';
        out.innerHTML = `
        <div class="result-wrap">

            <!-- Student card -->
            <div class="stu-card">
                <div class="stu-card-top">
                    <div class="stu-avatar">${avatarHTML}</div>
                    <h3>${sData.fname} ${sData.lname}</h3>
                    <div class="stu-id">${sData.student_id}</div>
                </div>
                <div class="stu-card-body">
                    <div class="stu-stat"><span class="sl">Department</span><span class="sv">${sData.department}</span></div>
                    <div class="stu-stat"><span class="sl">Faculty</span><span class="sv">${sData.faculty || '—'}</span></div>
                    <div class="stu-stat"><span class="sl">Year</span><span class="sv">Year ${sData.year_of_study}</span></div>
                    <div class="stu-stat"><span class="sl">Documents</span><span class="sv">${docs.length} file${docs.length !== 1 ? 's' : ''}</span></div>
                </div>
            </div>

            <!-- Info panel -->
            <div class="info-panel">
                <div class="status-banner ${st.cls}">
                    <div class="sb-icon">${st.icon}</div>
                    <div>
                        <div class="sb-title">${st.title}</div>
                        <div class="sb-msg">${st.msg}</div>
                    </div>
                </div>

                <div class="timeline">${tlHTML}</div>

                <div class="info-grid">
                    <div class="ig-item"><div class="ig-label">Registration No.</div><div class="ig-val">${sData.student_id}</div></div>
                    <div class="ig-item"><div class="ig-label">Admission Status</div><div class="ig-val">${status}</div></div>
                    <div class="ig-item"><div class="ig-label">Applied On</div><div class="ig-val">${dData.submitted_at ? new Date(dData.submitted_at).toLocaleDateString('en-GB',{day:'numeric',month:'long',year:'numeric'}) : '—'}</div></div>
                    <div class="ig-item"><div class="ig-label">Account Status</div><div class="ig-val">${sData.status}</div></div>
                </div>

                ${noteHTML}

                <div class="docs-section">
                    <h4>📎 Submitted Documents (${docs.length})</h4>
                    ${docsHTML}
                </div>

                ${ctaHTML ? `<div class="cta-row">${ctaHTML}<a href="index.php" class="btn btn-outline" style="flex:1;justify-content:center;">← Home</a></div>` : ''}
            </div>

        </div>`;
    } finally {
        btn.textContent = 'Check Status'; btn.disabled = false;
    }
}

document.getElementById('sidInput').addEventListener('keydown', e => { if (e.key === 'Enter') trackAdmission(); });
</script>

<?php require 'php/pub_footer.php'; ?>
</body>
</html>
