<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<div class="auth-box">
    <div class="auth-logo">
        <div class="logo-text">Group<span> One</span></div>
        <p>Student Management System</p>
    </div>
    <h2>🔒 Reset Password</h2>

    <div id="formSection">
        <div class="error-msg" id="errMsg"></div>
        <div class="success-msg" id="okMsg"></div>
        <div class="form-group">
            <label>Email or Registration Number</label>
            <input type="text" id="identifier" placeholder="you@example.com or STU-2026-001" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" id="newPass" placeholder="••••••••" required autocomplete="new-password">
            <div style="height:5px;border-radius:4px;margin-top:6px;background:#e0e0e0;">
                <div id="strengthFill" style="height:100%;border-radius:4px;width:0%;transition:all 0.3s;"></div>
            </div>
            <div id="strengthLabel" style="font-size:0.72rem;margin-top:3px;font-weight:600;"></div>
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" id="confirm" placeholder="••••••••" required autocomplete="new-password">
        </div>
        <button class="btn" style="width:100%;justify-content:center;" onclick="doReset()">Reset Password</button>
        <div class="auth-switch" style="margin-top:16px;">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>
</div>

<script>
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

async function doReset() {
    const err = document.getElementById('errMsg'), ok = document.getElementById('okMsg');
    err.style.display = ok.style.display = 'none';

    const identifier = document.getElementById('identifier').value.trim();
    const newPass    = document.getElementById('newPass').value;
    const confirm    = document.getElementById('confirm').value;

    if (!identifier || !newPass || !confirm) {
        err.textContent = 'All fields are required.'; err.style.display = 'block'; return;
    }
    if (newPass !== confirm) {
        err.textContent = 'Passwords do not match.'; err.style.display = 'block'; return;
    }
    if (newPass.length < 6) {
        err.textContent = 'Password must be at least 6 characters.'; err.style.display = 'block'; return;
    }

    const res = await fetch('php/reset_password.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ action: 'forgot', identifier, new_password: newPass, confirm_password: confirm })
    });
    const data = await res.json();
    if (data.success) {
        ok.textContent = 'Password reset successfully! Redirecting to login…';
        ok.style.display = 'block';
        setTimeout(() => window.location.href = 'login.php', 2000);
    } else {
        err.textContent = data.error || 'Reset failed.';
        err.style.display = 'block';
    }
}
</script>
</body>
</html>
