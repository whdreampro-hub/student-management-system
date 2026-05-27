<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Student Management System</title>
<meta name="description" content="Secure admin login for the Student Management System">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/student-management/public/assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <h1>EduAdmin</h1>
            <p>Student Management System</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?= htmlspecialchars($_SESSION['error']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <form method="POST" action="?page=login" id="loginForm">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Username" required autocomplete="username">
                <label for="username"><i class="bi bi-person me-2"></i>Username</label>
            </div>
            <div class="form-floating mb-4 position-relative">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Password" required autocomplete="current-password">
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                <button type="button" class="pwd-toggle" onclick="togglePassword()" id="pwdToggleBtn">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100" id="loginBtn">
                <span class="btn-text"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</span>
                <span class="btn-loader d-none"><span class="spinner-border spinner-border-sm me-2"></span>Signing in...</span>
            </button>
        </form>

        <div class="login-footer">
            <small><i class="bi bi-shield-check me-1"></i>Protected by session-based authentication</small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        eye.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        eye.className = 'bi bi-eye';
    }
}
document.getElementById('loginForm').addEventListener('submit', function() {
    document.querySelector('.btn-text').classList.add('d-none');
    document.querySelector('.btn-loader').classList.remove('d-none');
});
</script>
</body>
</html>
