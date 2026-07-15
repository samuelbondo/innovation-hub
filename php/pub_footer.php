<?php
$pdo ?? require __DIR__ . '/db.php';
$rows = $pdo->query('SELECT k, v FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
$sysName  = $rows['system_name']     ?? 'Group One';
$footCopy = $rows['footer_copy']     ?? '&copy; 2025 ' . $sysName . '. All rights reserved.';
$footNote = $rows['footer_note']     ?? '';
$email    = $rows['contact_email']   ?? '';
$phone    = $rows['contact_phone']   ?? '';
$address  = $rows['contact_address'] ?? '';
$nameParts = explode(' ', trim($sysName));
$first = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 0, -1)) : $nameParts[0];
$last  = count($nameParts) > 1 ? ' ' . end($nameParts) : '';
?>
<footer class="pub-footer">
    <div class="pub-footer-inner">
        <div class="pub-footer-brand">
            <div class="pub-footer-logo"><?= htmlspecialchars($first) ?><span><?= htmlspecialchars($last) ?></span></div>
            <p><?= htmlspecialchars($rows['system_tagline'] ?? 'Student Management System') ?></p>
        </div>
        <div class="pub-footer-links">
            <div class="pfl-col">
                <div class="pfl-title">Navigation</div>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
            </div>
            <div class="pfl-col">
                <div class="pfl-title">Portal</div>
                <a href="login.php">Login</a>
                <a href="signup.php">Apply / Sign Up</a>
                <a href="track_admission.php">Track Admission</a>
                <a href="forgot_password.php">Forgot Password</a>
            </div>
            <?php if ($email || $phone || $address): ?>
            <div class="pfl-col">
                <div class="pfl-title">Contact</div>
                <?php if ($email):   ?><a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a><?php endif; ?>
                <?php if ($phone):   ?><a href="tel:<?= htmlspecialchars($phone) ?>"><?= htmlspecialchars($phone) ?></a><?php endif; ?>
                <?php if ($address): ?><span><?= htmlspecialchars($address) ?></span><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="pub-footer-bottom">
        <span><?= htmlspecialchars($footCopy) ?></span>
        <?php if ($footNote): ?><span><?= htmlspecialchars($footNote) ?></span><?php endif; ?>
    </div>
</footer>
