<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-shell">
    <div class="main-area">
        <div class="page-content">

            <div class="page-header">
                <div><h2>🔒 Change Password</h2><p>Update your account password</p></div>
            </div>

            <div class="form-wrap">
                <div class="error-msg" id="errMsg"></div>
                <div class="success-msg" id="okMsg"></div>
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" id="current" placeholder="Enter current password" required autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" id="newPass" placeholder="Enter new password" required autocomplete="new-password">
                    <div style="height:5px;border-radius:4px;margin-top:6px;background:#e0e0e0;">
                        <div id="strengthFill" style="height:100%;border-radius:4px;width:0%;transition:all 0.3s;"></div>
                    </div>
                    <div id="strengthLabel" style="font-size:0.72rem;margin-top:3px;font-weight:600;"></div>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" id="confirm" placeholder="Repeat new password" required autocomplete="new-password">
                </div>
                <div class="form-actions">
                    <button class="btn" id="saveBtn">🔒 Update Password</button>
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
const _u = JSON.parse(localStorage.getItem('user') || '{}');

function getStrength(pwd) {
    let s = 0;
    if (pwd.length >= 8)          s++;
    if (pwd.length >= 12)         s++;
    if (/[A-Z]/.test(pwd))        s++;
    if (/[0-9]/.test(pwd))        s++;
    if (/[^A-Za-z0-9]/.test(pwd)) s++;
    return s;
}

document.getElementById('newPass').addEventListener('input', function() {
    const levels = [
        { pct:'20%', color:'#dc3545', text:'Very Weak' },
        { pct:'40%', color:'#fd7e14', text:'Weak' },
        { pct:'60%', color:'#ffc107', text:'Fair' },
        { pct:'80%', color:'#20c997', text:'Strong' },
        { pct:'100%',color:'#28a745', text:'Very Strong' }
    ];
    const score = getStrength(this.value);
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    const lvl   = levels[Math.max(0, score - 1)];
    fill.style.width      = this.value ? lvl.pct   : '0%';
    fill.style.background = this.value ? lvl.color : '';
    label.textContent     = this.value ? lvl.text  : '';
    label.style.color     = lvl.color;
});

document.getElementById('saveBtn').addEventListener('click', async () => {
    const err = document.getElementById('errMsg'), ok = document.getElementById('okMsg');
    err.style.display = ok.style.display = 'none';

    const current = document.getElementById('current').value;
    const newPass = document.getElementById('newPass').value;
    const confirm = document.getElementById('confirm').value;

    const res = await fetch('php/reset_password.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'change_own',
            email: _u.email,
            current_password: current,
            new_password: newPass,
            confirm_password: confirm
        })
    });
    const data = await res.json();
    if (data.success) {
        ok.textContent = 'Password updated successfully!';
        ok.style.display = 'block';
        document.getElementById('current').value = '';
        document.getElementById('newPass').value = '';
        document.getElementById('confirm').value = '';
    } else {
        err.textContent = data.error || 'Failed to update password.';
        err.style.display = 'block';
    }
});
</script>
</body>
</html>
