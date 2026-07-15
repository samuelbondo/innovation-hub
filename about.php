<?php $activePage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require 'php/pub_header.php'; ?>

<main class="pub-main">

    <div style="text-align:center;margin-bottom:48px;">
        <h1 class="pub-section-title">ℹ️ About Group One</h1>
        <p class="pub-section-sub">Learn who we are, our mission, and the team behind this project</p>
    </div>

    <div class="feature-cards">
        <div class="feature-card">
            <div class="fc-icon">👥</div>
            <h3>Who We Are</h3>
            <p>Group One is a student-led development team formed to design and build a fully functional student management system. We combine our individual strengths in design, development, and problem-solving to deliver a quality project.</p>
        </div>
        <div class="feature-card">
            <div class="fc-icon">🎯</div>
            <h3>Our Mission</h3>
            <p>To collaborate, innovate, and deliver a high-quality web-based student management platform while continuously improving our skills as developers and designers.</p>
        </div>
        <div class="feature-card">
            <div class="fc-icon">💡</div>
            <h3>Our Values</h3>
            <p>Teamwork &nbsp;&middot;&nbsp; Integrity &nbsp;&middot;&nbsp; Continuous Learning &nbsp;&middot;&nbsp; Quality &nbsp;&middot;&nbsp; Creativity</p>
        </div>
    </div>

    <div style="text-align:center;margin:56px 0 28px;">
        <h2 class="pub-section-title">👥 Meet the Team</h2>
        <p class="pub-section-sub">The people who make Group One great</p>
    </div>

    <div class="team-grid">
        <div class="team-card"><div class="av">👤</div><h4>Member One</h4><span>Lead Developer</span></div>
        <div class="team-card"><div class="av">👤</div><h4>Member Two</h4><span>UI/UX Designer</span></div>
        <div class="team-card"><div class="av">👤</div><h4>Member Three</h4><span>Backend Developer</span></div>
        <div class="team-card"><div class="av">👤</div><h4>Member Four</h4><span>Project Manager</span></div>
        <div class="team-card"><div class="av">👤</div><h4>Member Five</h4><span>QA &amp; Testing</span></div>
    </div>

</main>

<?php require 'php/pub_footer.php'; ?>

</body>
</html>
