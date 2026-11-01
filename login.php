<?php
session_start();
require_once 'db.php';

// إذا كان المستخدم مسجلاً دخوله بالفعل، نقله مباشرة للوحة التحكم
if (isset($_SESSION['user_id'])) {
    header("Location: view_students.php");
    exit();
}

// معالجة طلب تسجيل الدخول عند الضغط على الزر
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($login) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: view_students.php");
            exit();
        } else {
            header("Location: login.php?msg=invalid");
            exit();
        }
    } else {
        header("Location: login.php?msg=invalid");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4f46e5">
    <title>Sign in · Student Registration System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
</head>
<body class="auth-body">

<main id="main" class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- Left: brand panel -->
        <aside class="col-lg-6 d-none d-lg-flex auth-aside">
            <a class="navbar-brand" href="index.html">
                <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                Student Registration
            </a>
            <div>
                <h2 class="mb-3">Welcome back.</h2>
                <p class="mb-4 fs-5">Sign in to manage student records, search the directory and keep everything up to date.</p>
                <ul class="list-unstyled auth-points mb-0">
                    <li><i class="bi bi-check-circle-fill"></i><span>Add, edit and delete students in seconds</span></li>
                    <li><i class="bi bi-check-circle-fill"></i><span>Search by name, email or phone</span></li>
                    <li><i class="bi bi-check-circle-fill"></i><span>Works on desktop, tablet and mobile</span></li>
                </ul>
            </div>
            <small class="opacity-75">&copy; <span data-year>2026</span> Student Registration System · BWP501</small>
        </aside>

        <!-- Right: form -->
        <div class="col-12 col-lg-6 auth-panel d-flex align-items-center justify-content-center p-3 p-sm-4 p-lg-5">
            <div class="auth-card w-100">

                <a class="navbar-brand d-lg-none mb-4" href="index.html">
                    <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                    Student Registration
                </a>

                <h1>Sign in</h1>
                <p class="text-muted-soft mb-4">Use your username or email and your password.</p>

                <div id="pageAlert" aria-live="polite"></div>

                <form id="loginForm" action="login.php" method="post" class="needs-validation" novalidate data-loading>
                    <div class="field mb-3">
                        <label for="login" class="form-label">Username or email<span class="req">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="login" name="login"
                                   placeholder="e.g. ahmad or ahmad@example.com" autocomplete="username" required>
                            <div class="invalid-feedback">Please enter your username or email.</div>
                        </div>
                    </div>

                    <div class="field mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label">Password<span class="req">*</span></label>
                        </div>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Your password" autocomplete="current-password" required>
                            <button class="btn btn-password-toggle" type="button" data-toggle-password="#password"
                                    aria-label="Show password" aria-pressed="false"><i class="bi bi-eye"></i></button>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100" data-loading-text="Signing in…">
                        <i class="bi bi-box-arrow-in-right"></i> Sign in
                    </button>
                </form>

                <div class="auth-divider my-4">or</div>

                <p class="text-center mb-0">
                    Don’t have an account? <a href="register.php" class="fw-semibold">Create one</a>
                </p>
                <p class="text-center mt-3 mb-0 small">
                    <a href="index.html" class="text-muted-soft text-decoration-none"><i class="bi bi-arrow-left"></i> Back to home</a>
                </p>
            </div>
        </div>
    </div>
</main>

<div id="toastArea" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/ui.js"></script>
</body>
</html>