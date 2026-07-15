<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Department | Group One SMS</title>
</head>
<body>
<script>
const _u = JSON.parse(localStorage.getItem('user') || '{}');
fetch('php/dashboard.php?email=' + encodeURIComponent(_u.email) + '&role=student')
    .then(r => r.json())
    .then(d => {
        if (d.student && d.student.department_id) {
            window.location.replace('department_detail.php?id=' + d.student.department_id);
        } else {
            window.location.replace('dashboard.php');
        }
    })
    .catch(() => window.location.replace('dashboard.php'));
</script>
</body>
</html>
