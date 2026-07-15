<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .settings-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        @media(max-width:700px){ .settings-grid{ grid-template-columns:1fr; } }
        .preview-box { background:var(--primary); color:#fff; border-radius:12px; padding:20px 24px; margin-top:16px; }
        .preview-box .pv-name { font-size:1.3rem; font-weight:800; }
        .preview-box .pv-name span { color:var(--accent); }
        .preview-box .pv-tag { font-size:0.8rem; opacity:0.6; margin-top:2px; }
        .preview-footer { background:#f4f4f4; border-radius:8px; padding:12px 16px; margin-top:10px; font-size:0.8rem; color:#555; display:flex; justify-content:space-between; flex-wrap:wrap; gap:6px; }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div><h2>⚙️ System Settings</h2><p>Customize branding, name and footer</p></div>
            </div>

            <div class="success-msg" id="saveOk"></div>
            <div class="error-msg"   id="saveErr"></div>

            <div class="panel" style="margin-bottom:20px;">
                <div class="panel-header"><h3>🖼️ System Logo</h3></div>
                <div style="padding:24px;display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                    <div id="logoPreviewWrap" style="width:80px;height:80px;border:2px dashed var(--border);border-radius:12px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#f9f9f9;">
                        <img id="logoPreview" src="" alt="Logo" style="max-width:100%;max-height:100%;display:none;">
                        <span id="logoPlaceholder" style="font-size:2rem;">🏫</span>
                    </div>
                    <div>
                        <label class="btn btn-outline" style="cursor:pointer;">
                            📁 Choose Logo
                            <input type="file" id="logoFile" accept="image/png,image/jpeg,image/gif,image/svg+xml,image/webp" style="display:none;">
                        </label>
                        <p style="font-size:0.75rem;color:var(--muted);margin:6px 0 0;">PNG, JPG, SVG, WEBP · Max 2MB<br>Shown in sidebar and public header</p>
                        <p id="logoMsg" style="font-size:0.8rem;margin:6px 0 0;"></p>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>🏷️ Branding</h3></div>
                <div style="padding:24px;">
                    <div class="settings-grid">
                        <div class="form-group">
                            <label>System Name</label>
                            <input type="text" id="system_name" placeholder="e.g. My University">
                        </div>
                        <div class="form-group">
                            <label>Tagline</label>
                            <input type="text" id="system_tagline" placeholder="e.g. Student Management System">
                        </div>
                    </div>
                    <label style="font-size:0.78rem;color:var(--muted);font-weight:600;">Live Preview</label>
                    <div class="preview-box">
                        <div class="pv-name" id="pvName">Group<span> One</span></div>
                        <div class="pv-tag"  id="pvTag">Student Management System</div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:20px;">
                <div class="panel-header"><h3>📋 Footer</h3></div>
                <div style="padding:24px;">
                    <div class="settings-grid">
                        <div class="form-group">
                            <label>Copyright Text</label>
                            <input type="text" id="footer_copy" placeholder="e.g. © 2025 My University. All rights reserved.">
                        </div>
                        <div class="form-group">
                            <label>Footer Note</label>
                            <input type="text" id="footer_note" placeholder="e.g. Powered by SMS v1.0">
                        </div>
                    </div>
                    <label style="font-size:0.78rem;color:var(--muted);font-weight:600;">Live Preview</label>
                    <div class="preview-footer">
                        <span id="pvCopy"></span>
                        <span id="pvNote"></span>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:20px;">
                <div class="panel-header"><h3>📬 Contact Info</h3></div>
                <div style="padding:24px;">
                    <div class="settings-grid">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="contact_email" placeholder="info@school.edu">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" id="contact_phone" placeholder="+1 234 567 890">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" id="contact_address" placeholder="123 Main St, City">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slideshow -->
            <div class="panel" style="margin-top:20px;">
                <div class="panel-header"><h3>🖼️ Home Slideshow</h3></div>
                <div style="padding:24px;">
                    <p style="font-size:0.85rem;color:var(--muted);margin:0 0 18px;">Images appear as the hero background on the home page. Drag to reorder. Supports upload or external URL.</p>

                    <!-- Add by URL -->
                    <div style="background:#f8f9fa;border-radius:10px;padding:16px 20px;margin-bottom:16px;">
                        <div style="font-weight:700;font-size:0.88rem;margin-bottom:10px;">🔗 Add by URL</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;">
                            <div class="form-group" style="margin:0;"><label>Image URL</label><input type="url" id="slideUrl" placeholder="https://example.com/image.jpg"></div>
                            <div class="form-group" style="margin:0;"><label>Caption (optional)</label><input type="text" id="slideUrlCaption" placeholder="e.g. Campus Life"></div>
                            <button class="btn" onclick="addSlideUrl()">➕ Add</button>
                        </div>
                        <!-- URL live preview -->
                        <div id="urlPreviewWrap" style="display:none;margin-top:12px;">
                            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:6px;">Preview</div>
                            <img id="urlPreview" src="" alt="preview" style="max-height:140px;border-radius:8px;border:1px solid var(--border);object-fit:cover;">
                        </div>
                    </div>

                    <!-- Add by Upload -->
                    <div style="background:#f8f9fa;border-radius:10px;padding:16px 20px;margin-bottom:20px;">
                        <div style="font-weight:700;font-size:0.88rem;margin-bottom:10px;">📁 Upload Image</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;">
                            <div class="form-group" style="margin:0;"><label>Image File</label>
                                <input type="file" id="slideFile" accept="image/*" style="padding:5px;border:1.5px solid var(--border);border-radius:8px;font-size:0.85rem;width:100%;" onchange="previewUpload(this)">
                            </div>
                            <div class="form-group" style="margin:0;"><label>Caption (optional)</label><input type="text" id="slideUploadCaption" placeholder="e.g. Graduation Day"></div>
                            <button class="btn" onclick="uploadSlide()">⬆️ Upload</button>
                        </div>
                        <!-- Upload live preview -->
                        <div id="uploadPreviewWrap" style="display:none;margin-top:12px;">
                            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:6px;">Preview</div>
                            <img id="uploadPreview" src="" alt="preview" style="max-height:140px;border-radius:8px;border:1px solid var(--border);object-fit:cover;">
                        </div>
                        <div id="uploadMsg" style="font-size:0.8rem;margin-top:8px;"></div>
                    </div>

                    <!-- Current slides -->
                    <div style="font-weight:700;font-size:0.88rem;margin-bottom:10px;">🖼️ Current Slides <span style="font-size:0.75rem;font-weight:400;color:var(--muted);">(drag to reorder)</span></div>
                    <div id="slideList" style="display:flex;flex-direction:column;gap:10px;"></div>
                </div>
            </div>

            <!-- Enrollment Windows -->
            <div class="panel" style="margin-top:20px;" id="enrollWindowPanel">
                <div class="panel-header"><h3>📅 Enrollment Windows</h3></div>
                <div style="padding:24px;">
                    <p style="font-size:0.85rem;color:var(--muted);margin:0 0 16px;">Set a date/time range when students can self-enroll. A <strong>course-specific</strong> window overrides the global one. Admin can enroll students at any time regardless of these windows.</p>

                    <!-- Global window -->
                    <div style="background:#f8f9fa;border-radius:10px;padding:16px 20px;margin-bottom:20px;">
                        <div style="font-weight:700;font-size:0.88rem;margin-bottom:12px;">🌐 Global Window <span style="font-size:0.75rem;font-weight:400;color:var(--muted);">(applies to all courses without a specific window)</span></div>
                        <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;">
                            <div class="form-group" style="margin:0;"><label>Open From</label><input type="datetime-local" id="gw_from"></div>
                            <div class="form-group" style="margin:0;"><label>Open Until</label><input type="datetime-local" id="gw_until"></div>
                            <div style="display:flex;gap:6px;">
                                <button class="btn" onclick="saveWindow(null)">💾 Save</button>
                                <button class="btn btn-outline" style="color:#dc3545;border-color:#dc3545;" onclick="deleteWindow(null)">🗑️</button>
                            </div>
                        </div>
                        <div id="gw_status" style="margin-top:8px;font-size:0.8rem;"></div>
                    </div>

                    <!-- Per-course window -->
                    <div style="font-weight:700;font-size:0.88rem;margin-bottom:10px;">📚 Course-Specific Windows</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:10px;align-items:end;margin-bottom:14px;">
                        <div class="form-group" style="margin:0;"><label>Course</label>
                            <select id="cw_course" style="padding:7px 10px;border:1.5px solid var(--border);border-radius:8px;font-size:0.85rem;width:100%;">
                                <option value="">-- Select Course --</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;"><label>Open From</label><input type="datetime-local" id="cw_from"></div>
                        <div class="form-group" style="margin:0;"><label>Open Until</label><input type="datetime-local" id="cw_until"></div>
                        <button class="btn" style="margin-top:22px;" onclick="saveWindow(document.getElementById('cw_course').value)">💾 Save</button>
                    </div>

                    <!-- Existing windows list -->
                    <div id="windowsList"></div>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button class="btn" onclick="saveSettings()">💾 Save Settings</button>
            </div>

        </div>
        <footer class="app-footer">
            <span id="footerCopy"></span>
            <span id="footerNote"></span>
        </footer>
    </div>
</div>
<script src="shared.js?v=2"></script>
<script>
const fields = ['system_name','system_tagline','footer_copy','footer_note','contact_email','contact_phone','contact_address'];

// Logo upload
document.getElementById('logoFile').addEventListener('change', async function() {
    const file = this.files[0];
    if (!file) return;
    const msg = document.getElementById('logoMsg');
    msg.style.color = 'var(--muted)'; msg.textContent = 'Uploading…';
    const fd = new FormData();
    fd.append('logo', file);
    const res  = await fetch('php/upload_logo.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
        msg.style.color = 'green'; msg.textContent = '✅ Logo uploaded.';
        showLogo(data.path + '?t=' + Date.now());
    } else {
        msg.style.color = 'red'; msg.textContent = data.error || 'Upload failed.';
    }
});

function showLogo(src) {
    const img = document.getElementById('logoPreview');
    const ph  = document.getElementById('logoPlaceholder');
    img.src = src;
    img.style.display = 'block';
    ph.style.display  = 'none';
}

fetch('php/get_settings.php').then(r=>r.json()).then(s => {
    fields.forEach(k => { if (document.getElementById(k)) document.getElementById(k).value = s[k] || ''; });
    updatePreviews(s);
    if (s.system_logo) showLogo(s.system_logo + '?t=' + Date.now());
});

function updatePreviews(s) {
    const name = s.system_name || 'Group One';
    const parts = name.trim().split(' ');
    const first = parts.slice(0,-1).join(' ') || parts[0];
    const last  = parts.length > 1 ? ' ' + parts[parts.length-1] : '';
    document.getElementById('pvName').innerHTML  = first + `<span>${last}</span>`;
    document.getElementById('pvTag').textContent  = s.system_tagline || '';
    document.getElementById('pvCopy').textContent = s.footer_copy || '';
    document.getElementById('pvNote').textContent = s.footer_note || '';
    document.getElementById('footerCopy').textContent = s.footer_copy || '';
    document.getElementById('footerNote').textContent = s.footer_note || '';
}

// ── Enrollment Windows ───────────────────────────────────────────────────────
let allCoursesEW = [];

fetch('php/get_courses.php').then(r=>r.json()).then(courses => {
    allCoursesEW = courses;
    const sel = document.getElementById('cw_course');
    courses.forEach(c => { sel.innerHTML += `<option value="${c.id}">${c.code} — ${c.title}</option>`; });
});

fetch('php/enrollment_window.php?action=list').then(r=>r.json()).then(renderWindows);

function renderWindows(list) {
    // Fill global window inputs
    const gw = list.find(w => !w.course_id);
    if (gw) {
        document.getElementById('gw_from').value  = gw.open_from.replace(' ','T').slice(0,16);
        document.getElementById('gw_until').value = gw.open_until.replace(' ','T').slice(0,16);
        const now = new Date(), from = new Date(gw.open_from), until = new Date(gw.open_until);
        const isOpen = now >= from && now <= until;
        document.getElementById('gw_status').innerHTML =
            isOpen ? '<span style="color:#155724;font-weight:600;">✅ Currently OPEN</span>'
                   : '<span style="color:#721c24;font-weight:600;">🔒 Currently CLOSED</span>';
    }
    // Course-specific list
    const courseWins = list.filter(w => w.course_id);
    if (!courseWins.length) {
        document.getElementById('windowsList').innerHTML = '<p style="font-size:0.82rem;color:var(--muted);">No course-specific windows set.</p>';
        return;
    }
    const now = new Date();
    document.getElementById('windowsList').innerHTML = `
        <table style="width:100%;font-size:0.83rem;border-collapse:collapse;">
            <thead><tr style="background:#f4f4f4;">
                <th style="padding:8px 12px;text-align:left;">Course</th>
                <th style="padding:8px 12px;text-align:left;">Open From</th>
                <th style="padding:8px 12px;text-align:left;">Open Until</th>
                <th style="padding:8px 12px;text-align:left;">Status</th>
                <th style="padding:8px 12px;"></th>
            </tr></thead>
            <tbody>${courseWins.map(w => {
                const isOpen = now >= new Date(w.open_from) && now <= new Date(w.open_until);
                return `<tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:8px 12px;"><strong>${w.code}</strong><br><small style="color:var(--muted);">${w.title}</small></td>
                    <td style="padding:8px 12px;">${w.open_from}</td>
                    <td style="padding:8px 12px;">${w.open_until}</td>
                    <td style="padding:8px 12px;">${isOpen
                        ? '<span style="color:#155724;font-weight:600;">✅ Open</span>'
                        : '<span style="color:#721c24;font-weight:600;">🔒 Closed</span>'}</td>
                    <td style="padding:8px 12px;text-align:center;">
                        <button class="btn btn-outline" style="font-size:0.75rem;padding:3px 10px;color:#dc3545;border-color:#dc3545;"
                            onclick="deleteWindow(${w.course_id})">🗑️ Remove</button>
                    </td>
                </tr>`;
            }).join('')}</tbody>
        </table>`;
}

async function saveWindow(courseId) {
    const isGlobal = !courseId;
    const from  = document.getElementById(isGlobal ? 'gw_from'  : 'cw_from').value;
    const until = document.getElementById(isGlobal ? 'gw_until' : 'cw_until').value;
    if (!from || !until) { alert('Please set both open from and open until dates.'); return; }
    if (new Date(from) >= new Date(until)) { alert('Open Until must be after Open From.'); return; }
    const _u = JSON.parse(localStorage.getItem('user') || '{}');
    const res  = await fetch('php/enrollment_window.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({action:'save', email:_u.email, course_id: courseId || null, open_from: from.replace('T',' '), open_until: until.replace('T',' ')})
    });
    const data = await res.json();
    if (data.success) {
        fetch('php/enrollment_window.php?action=list').then(r=>r.json()).then(renderWindows);
    } else { alert(data.error || 'Failed to save window.'); }
}

async function deleteWindow(courseId) {
    if (!confirm('Remove this enrollment window?')) return;
    const _u = JSON.parse(localStorage.getItem('user') || '{}');
    await fetch('php/enrollment_window.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({action:'delete', email:_u.email, course_id: courseId || null})
    });
    if (!courseId) {
        document.getElementById('gw_from').value = '';
        document.getElementById('gw_until').value = '';
        document.getElementById('gw_status').innerHTML = '';
    }
    fetch('php/enrollment_window.php?action=list').then(r=>r.json()).then(renderWindows);
}
['system_name','system_tagline','footer_copy','footer_note'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const s = {};
        fields.forEach(k => { s[k] = document.getElementById(k)?.value || ''; });
        updatePreviews(s);
    });
});

// ── Slideshow ─────────────────────────────────────────────────────────────
let slideData = [];

function loadSlides() {
    fetch('php/slideshow.php?action=list').then(r=>r.json()).then(list => {
        slideData = list;
        renderSlides();
    });
}
loadSlides();

function renderSlides() {
    const el = document.getElementById('slideList');
    if (!slideData.length) {
        el.innerHTML = '<p style="font-size:0.82rem;color:var(--muted);">No slides yet. Add one above.</p>';
        return;
    }
    el.innerHTML = slideData.map((s,i) => `
        <div class="slide-item" data-id="${s.id}" style="display:flex;align-items:center;gap:12px;background:#fff;border:1px solid var(--border);border-radius:10px;padding:10px 14px;cursor:grab;">
            <span style="font-size:1.1rem;color:var(--muted);cursor:grab;">&#9776;</span>
            <img src="${s.src}" alt="slide" style="width:80px;height:52px;object-fit:cover;border-radius:6px;border:1px solid var(--border);flex-shrink:0;">
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.82rem;font-weight:600;color:var(--primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.src}</div>
                <div style="font-size:0.75rem;color:var(--muted);">${s.caption || '<em>No caption</em>'} &middot; <span style="background:#e8f4fd;color:#0056b3;border-radius:8px;padding:1px 7px;font-size:0.7rem;font-weight:600;">${s.src_type}</span></div>
            </div>
            <button onclick="deleteSlide(${s.id})" style="background:none;border:none;color:#dc3545;font-size:1.1rem;cursor:pointer;padding:4px 8px;border-radius:6px;" title="Delete">🗑️</button>
        </div>`).join('');
    initDrag();
}

// URL live preview
document.getElementById('slideUrl').addEventListener('input', function() {
    const wrap = document.getElementById('urlPreviewWrap');
    const img  = document.getElementById('urlPreview');
    if (this.value.trim()) {
        img.src = this.value.trim();
        wrap.style.display = 'block';
    } else {
        wrap.style.display = 'none';
    }
});

// Upload live preview
function previewUpload(input) {
    const file = input.files[0];
    if (!file) return;
    const wrap = document.getElementById('uploadPreviewWrap');
    const img  = document.getElementById('uploadPreview');
    img.src = URL.createObjectURL(file);
    wrap.style.display = 'block';
}

async function addSlideUrl() {
    const src     = document.getElementById('slideUrl').value.trim();
    const caption = document.getElementById('slideUrlCaption').value.trim();
    if (!src) { alert('Please enter an image URL.'); return; }
    const _u = JSON.parse(localStorage.getItem('user') || '{}');
    const res  = await fetch('php/slideshow.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'add_url', email:_u.email, src, caption})
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('slideUrl').value = '';
        document.getElementById('slideUrlCaption').value = '';
        document.getElementById('urlPreviewWrap').style.display = 'none';
        loadSlides();
    } else { alert(data.error || 'Failed.'); }
}

async function uploadSlide() {
    const file    = document.getElementById('slideFile').files[0];
    const caption = document.getElementById('slideUploadCaption').value.trim();
    const msg     = document.getElementById('uploadMsg');
    if (!file) { alert('Please choose a file.'); return; }
    msg.style.color = 'var(--muted)'; msg.textContent = 'Uploading…';
    const _u = JSON.parse(localStorage.getItem('user') || '{}');
    const fd = new FormData();
    fd.append('action', 'upload');
    fd.append('email', _u.email);
    fd.append('image', file);
    fd.append('caption', caption);
    const res  = await fetch('php/slideshow.php', {method:'POST', body:fd});
    const data = await res.json();
    if (data.success) {
        msg.style.color = 'green'; msg.textContent = '✅ Uploaded.';
        document.getElementById('slideFile').value = '';
        document.getElementById('slideUploadCaption').value = '';
        document.getElementById('uploadPreviewWrap').style.display = 'none';
        loadSlides();
    } else { msg.style.color = 'red'; msg.textContent = data.error || 'Upload failed.'; }
}

async function deleteSlide(id) {
    if (!confirm('Remove this slide?')) return;
    const _u = JSON.parse(localStorage.getItem('user') || '{}');
    await fetch('php/slideshow.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'delete', email:_u.email, id})
    });
    loadSlides();
}

// Drag-to-reorder
function initDrag() {
    const list = document.getElementById('slideList');
    let dragging = null;
    list.querySelectorAll('.slide-item').forEach(item => {
        item.addEventListener('dragstart', () => { dragging = item; item.style.opacity = '0.4'; });
        item.addEventListener('dragend',   () => { dragging = null; item.style.opacity = '1'; saveOrder(); });
        item.addEventListener('dragover',  e => {
            e.preventDefault();
            const after = getDragAfter(list, e.clientY);
            if (after) list.insertBefore(dragging, after);
            else list.appendChild(dragging);
        });
        item.setAttribute('draggable', true);
    });
}

function getDragAfter(container, y) {
    const items = [...container.querySelectorAll('.slide-item:not([style*="opacity: 0.4"])')]
        .filter(el => el.style.opacity !== '0.4');
    return items.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        return offset < 0 && offset > closest.offset ? {offset, element: child} : closest;
    }, {offset: Number.NEGATIVE_INFINITY}).element;
}

async function saveOrder() {
    const order = [...document.querySelectorAll('.slide-item')].map(el => el.dataset.id);
    const _u = JSON.parse(localStorage.getItem('user') || '{}');
    await fetch('php/slideshow.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'reorder', email:_u.email, order})
    });
}

async function saveSettings() {
    const ok = document.getElementById('saveOk'), err = document.getElementById('saveErr');
    ok.style.display = err.style.display = 'none';
    const payload = {};
    fields.forEach(k => { payload[k] = document.getElementById(k)?.value.trim() || ''; });
    const res  = await fetch('php/save_settings.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) });
    const data = await res.json();
    if (data.success) {
        ok.textContent = '✅ Settings saved successfully.';
        ok.style.display = 'block';
        window.scrollTo({top:0,behavior:'smooth'});
    } else {
        err.textContent = data.error || 'Failed to save.';
        err.style.display = 'block';
    }
}
</script>
</body>
</html>
