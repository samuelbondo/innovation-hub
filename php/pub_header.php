<?php
$activePage = $activePage ?? '';
$pdo ?? require __DIR__ . '/db.php';
$sysName = $pdo->query("SELECT v FROM settings WHERE k='system_name'")->fetchColumn() ?: 'Group One';
$sysTag  = $pdo->query("SELECT v FROM settings WHERE k='system_tagline'")->fetchColumn() ?: 'Student Management System';
$sysLogo = $pdo->query("SELECT v FROM settings WHERE k='system_logo'")->fetchColumn() ?: '';
$nameParts = explode(' ', trim($sysName));
$first = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 0, -1)) : $nameParts[0];
$last  = count($nameParts) > 1 ? ' ' . end($nameParts) : '';
?>
<header class="pub-header">
    <a href="index.php" class="pub-logo">
        <?php if ($sysLogo): ?>
            <img src="<?= htmlspecialchars($sysLogo) ?>" alt="<?= htmlspecialchars($sysName) ?>" style="max-height:40px;max-width:140px;object-fit:contain;vertical-align:middle;margin-right:6px;">
        <?php else: ?>
            <?= htmlspecialchars($first) ?><span><?= htmlspecialchars($last) ?></span>
        <?php endif; ?>
    </a>
    <button class="pub-hamburger" id="pubHamburger">☰</button>
    <nav class="pub-nav" id="pubNav">
        <a href="index.php"           class="<?= $activePage==='home'    ? 'active':'' ?>">Home</a>
        <a href="about.php"           class="<?= $activePage==='about'   ? 'active':'' ?>">About</a>
        <a href="contact.php"         class="<?= $activePage==='contact' ? 'active':'' ?>">Contact</a>
        <a href="track_admission.php" class="<?= $activePage==='track'   ? 'active':'' ?>">Track Admission</a>
        <div class="pub-nav-divider"></div>
        <a href="login.php"  class="btn-nav">Login</a>
        <a href="signup.php" class="btn-nav btn-nav-secondary">Apply</a>
        <div class="nav-search" id="navSearch">
            <input type="text" id="navSearchInput" placeholder="Search…" autocomplete="off">
            <button id="navSearchBtn">🔍</button>
            <div class="nav-search-results" id="navSearchResults"></div>
        </div>
    </nav>
</header>
<script>
// Mobile nav toggle
document.getElementById('pubHamburger').addEventListener('click', () => {
    document.getElementById('pubNav').classList.toggle('open');
});
// Nav search
(function(){
    const input = document.getElementById('navSearchInput');
    const results = document.getElementById('navSearchResults');
    let timer;
    function doSearch(q) {
        q = q.trim();
        if (q.length < 2) { results.style.display='none'; return; }
        fetch('php/search.php?q=' + encodeURIComponent(q))
            .then(r=>r.json()).then(data => {
                if (!data.results.length) {
                    results.innerHTML='<div class="nav-sr-empty">No results for &ldquo;'+q+'&rdquo;</div>';
                    results.style.display='block'; return;
                }
                results.innerHTML = data.results.map(r => {
                    const tag = r.link ? 'a' : 'div';
                    const href = r.link ? 'href="'+r.link+'"' : '';
                    return `<${tag} class="nav-sr-item" ${href}>
                        <div class="nav-sr-icon">${r.icon}</div>
                        <div>
                            <div class="nav-sr-type">${r.type}</div>
                            <div class="nav-sr-title">${r.title}</div>
                            <div class="nav-sr-sub">${r.subtitle}</div>
                        </div>
                    </${tag}>`;
                }).join('');
                results.style.display='block';
            });
    }
    input.addEventListener('input', ()=>{ clearTimeout(timer); timer=setTimeout(()=>doSearch(input.value),300); });
    document.getElementById('navSearchBtn').addEventListener('click', ()=>doSearch(input.value));
    input.addEventListener('keydown', e=>{ if(e.key==='Enter') doSearch(input.value); });
    document.addEventListener('click', e=>{ if(!document.getElementById('navSearch').contains(e.target)) results.style.display='none'; });
})();
</script>
