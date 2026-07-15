// =============================================================================
// shared.js — Group One Student Management System
// Injects sidebar navigation, topbar, and enforces role-based access control.
//
// ROLES:
//   admin   — full access to student registration, departments, faculties
//   staff   — same as admin minus some admin-only actions
//   teacher — read-only access to departments and faculties
//   student — access to own profile, department view, change password
//
// MODULE STATUS (as of current phase):
//   ✅ ACTIVE   — Dashboard, Students, Departments, Faculties, About, Contact
//   🔒 HIDDEN   — Courses (manage_courses.php) — reserved for Academic System
//   🔒 HIDDEN   — Users  (manage_users.php)    — reserved for User Management System
//
// To re-enable hidden modules:
//   1. Uncomment the nav entries marked [HIDDEN MODULE] below
//   2. Remove the page names from the hiddenModulePages array
// =============================================================================
(function () {
    // Remove legacy auth key from older system versions
    localStorage.removeItem('loggedIn');

    let user = null;
    try { user = JSON.parse(localStorage.getItem('user')); } catch(e) { localStorage.removeItem('user'); }

    if (!user || !user.name) {
        const page = location.pathname.split('/').pop() || 'dashboard.php';
        window.location.href = 'login.php?redirect=' + encodeURIComponent(page);
        return;
    }

    const page      = location.pathname.split('/').pop() || 'dashboard.php';
    const role      = user.role;
    const isStudent = role === 'student';
    const isAdmin   = role === 'admin';
    const isStaff   = role === 'staff';
    const isTeacher = role === 'teacher';

    // -------------------------------------------------------------------------
    // ACCESS CONTROL
    // Pages only admin/staff can access
    // Pages students cannot access
    const adminOnlyPages = [
        'view_student.php',
        'new_student.php',
        'student_detail.php',
        'department.php',
        'faculties.php',
        'manage_users.php',
        'manage_courses.php',
        'reports.php',
        'settings.php',
    ];
    // profile.php is accessible by ALL logged-in roles

    // Pages teachers cannot access (on top of student restrictions)
    const teacherBlockPages = [
        'new_student.php',
        'view_student.php',
        'manage_users.php',
        'manage_courses.php',
        'reports.php',
        'settings.php',
        'student_courses.php',
        'student_detail.php',
    ];

    // Pages teachers CAN access (override adminOnlyPages for teachers)
    const teacherAllowPages = [
        'department.php',
        'department_detail.php',
        'faculties.php',
    ];

    // Pages only students can access
    const studentOnlyPages = ['student_courses.php', 'enroll_courses.php'];
    // Pages only teachers can access (students + admins blocked)
    const teacherOnlyPages = ['my_classes.php'];

    // Redirect old my_courses.php to my_classes.php for teachers
    if (isTeacher && page === 'my_courses.php') { window.location.replace('my_classes.php'); return; }

    // Hidden modules — redirect anyone who tries to access directly via URL
    // To re-enable: remove entries from this array and uncomment nav items below
    const hiddenModulePages = [];

    if (hiddenModulePages.includes(page)) { window.location.href = 'dashboard.php'; return; }
    if (isStudent && adminOnlyPages.includes(page)) { window.location.href = 'dashboard.php'; return; }
    if (isTeacher && teacherBlockPages.includes(page)) { window.location.href = 'dashboard.php'; return; }
    if (isTeacher && adminOnlyPages.includes(page) && !teacherAllowPages.includes(page)) { window.location.href = 'dashboard.php'; return; }
    if (!isStudent && !isTeacher && studentOnlyPages.includes(page)) { window.location.href = 'dashboard.php'; return; }
    if (!isTeacher && teacherOnlyPages.includes(page)) { window.location.href = 'dashboard.php'; return; }

    // -------------------------------------------------------------------------
    // NAVIGATION
    // Student nav — profile management focused
    const studentNav = [
        { href: 'dashboard.php',       icon: '📊', label: 'Dashboard' },
        { href: 'student_courses.php', icon: '📚', label: 'My Courses' },
        { href: 'enroll_courses.php',  icon: '📋', label: 'Enroll in Courses' },
        { href: 'profile.php',         icon: '👤', label: 'My Profile' },
        { href: 'my_department.php',   icon: '🏫', label: 'My Department' },
        { href: 'track_admission.php', icon: '🔍', label: 'Track Admission' },
        { href: 'change_password.php', icon: '🔑', label: 'Change Password' },
    ];

    // Teacher nav — own courses and classes only
    const teacherNav = [
        { href: 'dashboard.php',    icon: '📊', label: 'Dashboard' },
        { href: 'my_classes.php',   icon: '📚', label: 'My Courses & Classes' },
        { href: 'department.php',   icon: '🏫', label: 'My Department' },
        { href: 'faculties.php',    icon: '🏛️', label: 'Faculties' },
    ];

    // Admin/Staff nav — student registration system
    const adminNav = [
        { href: 'dashboard.php',    icon: '📊', label: 'Dashboard' },
        { href: 'view_student.php', icon: '🎓', label: 'Students' },
        { href: 'department.php',   icon: '🏫', label: 'Departments' },
        { href: 'faculties.php',    icon: '🏛️', label: 'Faculties' },
        { href: 'reports.php',      icon: '📄', label: 'Reports' },
        { href: 'manage_courses.php', icon: '📚', label: 'Courses' },
        { href: 'manage_users.php',   icon: '👥', label: 'Users'   },
        ...(isAdmin ? [{ href: 'settings.php', icon: '⚙️', label: 'Settings' }] : []),
    ];

    const nav = isStudent ? studentNav : isTeacher ? teacherNav : adminNav;
    // -------------------------------------------------------------------------

    const navLinks = nav.map(n =>
        `<a href="${n.href}" class="${page === n.href ? 'active' : ''}">
            <span class="nav-icon">${n.icon}</span>${n.label}
         </a>`
    ).join('');

    const initials = user.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
    const avatarSidebar = user.photo
        ? `<img src="${user.photo}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;" alt="${user.name}">`
        : `<div class="avatar">${initials}</div>`;
    const avatarTopbar = user.photo
        ? `<img src="${user.photo}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:6px;" alt="${user.name}">`
        : `<div class="avatar">${initials}</div>`;

    const sidebarHTML = `
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div id="sidebarLogoImg" style="display:none;margin-bottom:6px;"><img id="sidebarLogoImgEl" src="" alt="Logo" style="max-height:48px;max-width:140px;object-fit:contain;"></div>
            <div class="logo-text" id="sidebarLogoText">Group<span> One</span></div>
            <small id="sidebarTagline">Student Management System</small>
        </div>
        <div class="sidebar-user">
            ${avatarSidebar}
            <div class="user-info">
                <div class="name">${user.name}</div>
                <div class="role">${user.role}</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Main Menu</div>
            ${navLinks}
        </nav>
        <div class="sidebar-footer">
            ${!isStudent ? '<a href="change_password.php">🔑 Change Password</a>' : ''}
            <a href="login.php" id="logoutBtn">🚪 Logout</a>
        </div>
    </aside>`;

    const topbarHTML = `
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger">☰</button>
            <h1 id="topbarTitle">Dashboard</h1>
        </div>
        <div class="topbar-right">
            <a href="profile.php" class="topbar-user" style="text-decoration:none;" title="My Profile &amp; Settings">
                ${avatarTopbar}
                <span>${user.name}</span>
                <span style="font-size:0.7rem;color:var(--muted);margin-left:2px;">&#x2699;&#xFE0F;</span>
            </a>
        </div>
    </div>`;

    // Inject sidebar before .main-area, topbar inside .main-area
    const shell = document.querySelector('.app-shell');
    shell.insertAdjacentHTML('afterbegin', sidebarHTML);
    document.querySelector('.main-area').insertAdjacentHTML('afterbegin', topbarHTML);

    // Set topbar title from the page <title> tag
    const titleEl = document.querySelector('title');
    if (titleEl) {
        document.getElementById('topbarTitle').textContent = titleEl.textContent.split('|')[0].trim();
    }

    // Refresh student photo on every page load to keep avatar in sync
    if (isStudent) {
        fetch('php/dashboard.php?email=' + encodeURIComponent(user.email) + '&role=student')
            .then(r => r.json())
            .then(d => {
                if (d.student && d.student.photo && d.student.photo !== user.photo) {
                    user.photo = d.student.photo;
                    localStorage.setItem('user', JSON.stringify(user));
                    document.querySelectorAll('.sidebar-user img, .topbar-user img')
                        .forEach(img => img.src = d.student.photo + '?t=' + Date.now());
                }
            }).catch(() => {});
    }

    // Mobile sidebar toggle
    document.getElementById('hamburger').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    });
    document.getElementById('sidebarOverlay').addEventListener('click', () => {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    });

    // Logout — clears session from localStorage
    document.getElementById('logoutBtn').addEventListener('click', (e) => {
        e.preventDefault();
        localStorage.removeItem('user');
        window.location.href = 'login.php';
    });

    // Load system settings — update sidebar name, tagline, footer
    fetch('php/get_settings.php').then(r => r.json()).then(s => {
        const nameEl = document.getElementById('sidebarLogoText');
        const tagEl  = document.getElementById('sidebarTagline');
        if (nameEl && s.system_name) {
            const parts = s.system_name.trim().split(' ');
            const first = parts.slice(0, -1).join(' ') || parts[0];
            const last  = parts.length > 1 ? '<span> ' + parts[parts.length - 1] + '</span>' : '';
            nameEl.innerHTML = first + last;
        }
        if (tagEl && s.system_tagline) tagEl.textContent = s.system_tagline;
        if (s.system_logo) {
            const logoWrap = document.getElementById('sidebarLogoImg');
            const logoImg  = document.getElementById('sidebarLogoImgEl');
            if (logoWrap && logoImg) {
                logoImg.src = s.system_logo + '?t=' + Date.now();
                logoWrap.style.display = 'block';
            }
        }
        // Update app-footer
        const footerSpans = document.querySelectorAll('.app-footer span');
        if (footerSpans[0] && s.footer_copy) footerSpans[0].textContent = s.footer_copy;
        if (footerSpans[1] && s.footer_note) footerSpans[1].textContent = s.footer_note;
        // Update page title prefix
        const titleEl = document.querySelector('title');
        if (titleEl && s.system_name) {
            const parts = titleEl.textContent.split('|');
            if (parts.length > 1) titleEl.textContent = parts[0].trim() + ' | ' + s.system_name;
        }
    }).catch(() => {});
})();