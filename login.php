<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

<div class="auth-box">
    <div class="auth-logo">
        <div class="logo-text" id="authLogo">Group<span> One</span></div>
        <p id="authTagline">Student Management System</p>
    </div>
    <h2>Sign In to Your Account</h2>
    <div class="error-msg" id="errMsg"></div>
    <form id="loginForm">
        <div class="form-group">
            <label for="email">Email or Registration Number</label>
            <input type="text" id="email" placeholder="you@example.com or STU-2026-001" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div style="position:relative;">
                <input type="password" id="password" placeholder="••••••••" required style="padding-right:40px;width:100%;" autocomplete="current-password">
                <span onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password';this.textContent=this.previousElementSibling.type==='password'?'👁️':'🚫';" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1rem;">👁️</span>
            </div>
        </div>
        <div class="form-group" style="display:flex;justify-content:space-between;align-items:center;">
            <label style="display:flex;align-items:center;gap:6px;font-weight:normal;cursor:pointer;font-size:0.85rem;">
                <input type="checkbox" id="remember"> Remember me
            </label>
            <a href="forgot_password.php" style="font-size:0.83rem;color:var(--accent);">Forgot password?</a>
        </div>
        <button type="submit" class="btn" style="width:100%;justify-content:center;">Sign In</button>
    </form>
    <div class="auth-switch">
        Don't have an account? <a href="signup.php">Create one</a>
    </div>
    <div style="text-align:center;margin-top:14px;">
        <a href="index.php" style="font-size:0.83rem;color:var(--muted);">← Back to Home</a>
    </div>
</div>

<script>
    if (localStorage.getItem('user')) window.location.href = 'dashboard.php';
    localStorage.removeItem('loggedIn');

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

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const err = document.getElementById('errMsg');
        err.style.display = 'none';
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        try {
            const res  = await fetch('php/login.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ email, password })
            });
            const data = await res.json();
            if (data.success) {
                localStorage.setItem('user', JSON.stringify(data.user));
                const redirect = new URLSearchParams(window.location.search).get('redirect');
                window.location.href = redirect || 'dashboard.php';
            } else if (data.blocked && data.sid) {
                err.innerHTML = `
                    <strong>&#x26A0;&#xFE0F; Login Blocked</strong><br>
                    ${data.error}<br>
                    <a href="track_admission.php?id=${encodeURIComponent(data.sid)}" style="color:var(--accent);font-weight:600;">
                        &#x1F50D; Track your admission status &rarr;
                    </a>`;
                err.style.display = 'block';
            } else {
                err.textContent = data.error || 'Invalid credentials.';
                err.style.display = 'block';
            }
        } catch(e) {
            err.textContent = 'Cannot connect to server.';
            err.style.display = 'block';
        }
    });
</script>
</body>
</html>
