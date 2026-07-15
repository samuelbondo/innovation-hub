<?php $activePage = 'contact'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Group One SMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require 'php/pub_header.php'; ?>

<main class="pub-main-narrow">

    <div style="text-align:center;margin-bottom:48px;">
        <h1 class="pub-section-title">✉️ Contact Us</h1>
        <p class="pub-section-sub">Have a question or want to work with us? We&rsquo;d love to hear from you</p>
    </div>

    <div class="info-grid">
        <div class="info-card"><div class="ic">📧</div><h4>Email</h4><p><a href="mailto:groupone@email.com">groupone@email.com</a></p></div>
        <div class="info-card"><div class="ic">📞</div><h4>Phone</h4><p><a href="tel:+1234567890">+1 234 567 890</a></p></div>
        <div class="info-card"><div class="ic">📍</div><h4>Location</h4><p>123 Dev Street, Tech City</p></div>
        <div class="info-card"><div class="ic">🕐</div><h4>Working Hours</h4><p>Mon &ndash; Fri: 8:00 AM &ndash; 6:00 PM</p></div>
    </div>

    <div class="form-wrap" style="max-width:100%;">
        <h2>Send a Message</h2>
        <div class="success-msg" id="okMsg"></div>
        <form id="contactForm">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" placeholder="Your full name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" placeholder="you@example.com" required>
                </div>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" placeholder="What is this about?" required>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea placeholder="Write your message here&hellip;" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn">📨 Send Message</button>
            </div>
        </form>
    </div>

</main>

<?php require 'php/pub_footer.php'; ?>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const ok = document.getElementById('okMsg');
    ok.textContent = 'Message sent! We will get back to you shortly.';
    ok.style.display = 'block';
    this.reset();
    window.scrollTo({top:0, behavior:'smooth'});
});
</script>
</body>
</html>
