<?php $activePage = 'home'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ── Slideshow hero ── */
        .hero {
            position: relative;
            min-height: 520px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            overflow: hidden;
            background: linear-gradient(160deg,#1a1a2e 0%,#0f3460 55%,#16213e 100%);
        }
        .slide-bg { position:absolute;inset:0;background-size:cover;background-position:center;transition:opacity 1s ease;opacity:0; }
        .slide-bg.active { opacity:1; }
        .slide-bg::after { content:'';position:absolute;inset:0;background:rgba(0,0,0,0.48); }
        .hero-content { position:relative;z-index:2;padding:110px 24px 90px;display:flex;flex-direction:column;align-items:center; }
        .hero h1 { font-size:2.9rem;font-weight:800;margin-bottom:14px;line-height:1.2;max-width:700px; }
        .hero h1 span { color:var(--accent); }
        .hero .hero-sub { font-size:1.05rem;color:#aac4e0;max-width:520px;margin-bottom:44px;line-height:1.8; }
        .slide-caption { position:absolute;bottom:60px;left:50%;transform:translateX(-50%);z-index:3;background:rgba(0,0,0,0.45);color:#fff;padding:6px 18px;border-radius:20px;font-size:0.85rem;white-space:nowrap;max-width:90%;overflow:hidden;text-overflow:ellipsis;opacity:0;transition:opacity .5s; }
        .slide-caption.show { opacity:1; }
        .slide-dots { position:absolute;bottom:22px;left:50%;transform:translateX(-50%);z-index:3;display:flex;gap:8px; }
        .slide-dot { width:9px;height:9px;border-radius:50%;background:rgba(255,255,255,0.4);cursor:pointer;transition:background .3s; }
        .slide-dot.active { background:#fff; }
        .slide-arrow { position:absolute;top:50%;transform:translateY(-50%);z-index:3;background:rgba(0,0,0,0.35);border:none;color:#fff;font-size:1.4rem;width:40px;height:40px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s; }
        .slide-arrow:hover { background:rgba(0,0,0,0.65); }
        .slide-arrow.prev { left:16px; }
        .slide-arrow.next { right:16px; }
        @media(max-width:600px){ .hero-content{padding:80px 20px 70px;} .hero h1{font-size:2rem;} }
    </style>
</head>
<body>

<?php require 'php/pub_header.php'; ?>

<section class="hero" id="heroSection">
    <!-- slide backgrounds injected by JS -->
    <button class="slide-arrow prev" id="slidePrev" style="display:none;">&#8249;</button>
    <button class="slide-arrow next" id="slideNext" style="display:none;">&#8250;</button>
    <div class="hero-content">
        <h1 id="heroTitle">Welcome to <span>Group One</span> SMS</h1>
        <p class="hero-sub" id="heroSub">A dedicated student management platform built and designed by Group One. Manage students, departments, and more &mdash; all in one place.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="login.php" class="btn" style="padding:13px 34px;font-size:0.97rem;">Login to Portal</a>
            <a href="signup.php" style="background:transparent;border:2px solid rgba(255,255,255,0.55);color:#fff;border-radius:7px;padding:11px 34px;font-size:0.97rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;transition:background 0.2s;">Apply Now</a>
        </div>
    </div>
    <div class="slide-caption" id="slideCaption"></div>
    <div class="slide-dots" id="slideDots"></div>
</section>

<main class="pub-main">

    <h2 class="pub-section-title">What We Offer</h2>
    <p class="pub-section-sub">Everything you need to manage students and departments in one place</p>

    <div class="feature-cards">
        <div class="feature-card"><div class="fc-icon">🎓</div><h3>Student Management</h3><p>Add, view, and manage student records including personal details, department, and enrollment status.</p></div>
        <div class="feature-card"><div class="fc-icon">🏫</div><h3>Department Overview</h3><p>Browse all departments, their heads, and the number of enrolled students per department.</p></div>
        <div class="feature-card"><div class="fc-icon">🔒</div><h3>Secure Access</h3><p>Role-based login system ensures only authorized members can access and modify records.</p></div>
        <div class="feature-card"><div class="fc-icon">📈</div><h3>Admission Tracking</h3><p>Students can track their admission status anytime using their registration number — no login required.</p></div>
        <div class="feature-card"><div class="fc-icon">📱</div><h3>Mobile Friendly</h3><p>Fully responsive and works seamlessly on phones, tablets, and desktops.</p></div>
        <div class="feature-card"><div class="fc-icon">👥</div><h3>Built by Group One</h3><p>Designed and developed collaboratively by Group One as part of our web development project.</p></div>
    </div>

    <div class="pub-stats">
        <div class="pub-stat"><div class="num" id="statStudents">&mdash;</div><p>Students Enrolled</p></div>
        <div class="pub-stat"><div class="num" id="statDepts">&mdash;</div><p>Departments</p></div>
        <div class="pub-stat"><div class="num">5</div><p>Team Members</p></div>
        <div class="pub-stat"><div class="num">100%</div><p>Built by Group One</p></div>
    </div>

</main>

<?php require 'php/pub_footer.php'; ?>

<script>
// ── Stats ──
fetch('php/dashboard.php')
    .then(r => r.json())
    .then(d => {
        document.getElementById('statStudents').textContent = (d.total || 0) + '+';
        document.getElementById('statDepts').textContent    = (d.depts  || 8);
    })
    .catch(() => {
        document.getElementById('statStudents').textContent = '10+';
        document.getElementById('statDepts').textContent    = '8';
    });

// ── Slideshow ──
let slides = [], current = 0, timer = null;

fetch('php/slideshow.php?action=list').then(r => r.json()).then(data => {
    if (!data.length) return;
    slides = data;
    const hero = document.getElementById('heroSection');
    const dots = document.getElementById('slideDots');

    slides.forEach((s, i) => {
        const bg = document.createElement('div');
        bg.className = 'slide-bg' + (i === 0 ? ' active' : '');
        bg.style.backgroundImage = `url('${s.src}')`;
        bg.id = 'slide-' + i;
        hero.insertBefore(bg, hero.firstChild);

        const dot = document.createElement('div');
        dot.className = 'slide-dot' + (i === 0 ? ' active' : '');
        dot.onclick = () => goTo(i);
        dots.appendChild(dot);
    });

    if (slides.length > 1) {
        document.getElementById('slidePrev').style.display = 'flex';
        document.getElementById('slideNext').style.display = 'flex';
    }
    showCaption(0);
    startTimer();
});

function goTo(n) {
    document.getElementById('slide-' + current)?.classList.remove('active');
    document.querySelectorAll('.slide-dot')[current]?.classList.remove('active');
    current = (n + slides.length) % slides.length;
    document.getElementById('slide-' + current)?.classList.add('active');
    document.querySelectorAll('.slide-dot')[current]?.classList.add('active');
    showCaption(current);
    resetTimer();
}

function showCaption(i) {
    const el = document.getElementById('slideCaption');
    const cap = slides[i]?.caption;
    el.textContent = cap || '';
    el.classList.toggle('show', !!cap);
}

function startTimer() { timer = setInterval(() => goTo(current + 1), 5000); }
function resetTimer() { clearInterval(timer); startTimer(); }

document.getElementById('slidePrev').onclick = () => goTo(current - 1);
document.getElementById('slideNext').onclick = () => goTo(current + 1);
</script>
</body>
</html>
