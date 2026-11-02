<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        header("Location: register.php?msg=mismatch");
        exit();
    }

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hashed_password]);
            header("Location: login.php?msg=registered");
            exit();
        } catch (PDOException $e) {
            header("Location: register.php?msg=exists");
            exit();
        }
    } else {
        header("Location: register.php?msg=error");
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
    <title>Create account · Student Registration System</title>

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

        <!-- Left: brand panel (large screens only) -->
        <aside class="col-lg-6 d-none d-lg-flex auth-aside">
            <a class="navbar-brand" href="index.html">
                <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                Student Registration
            </a>
            <div>
                <h2 class="mb-3">Create your account.</h2>
                <p class="mb-4 fs-5">One account gives you full access to the student directory.</p>
                <ul class="list-unstyled auth-points mb-0">
                    <li><i class="bi bi-shield-lock-fill"></i><span>Passwords are stored as secure hashes</span></li>
                    <li><i class="bi bi-person-badge-fill"></i><span>Unique username and email per account</span></li>
                    <li><i class="bi bi-phone-fill"></i><span>Fully responsive on every screen size</span></li>
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

                <h1>Create account</h1>
                <p class="text-muted-soft mb-4">It only takes a minute.</p>

                <div id="pageAlert" aria-live="polite"></div>

                <form id="registerForm" action="register.php" method="post" class="needs-validation" novalidate data-loading>
                    <div class="field mb-3">
                        <label for="username" class="form-label">Username<span class="req">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="e.g. ahmad_k" maxlength="100" autocomplete="username" required>
                            <div class="invalid-feedback">Please choose a username.</div>
                        </div>
                    </div>

                    <div class="field mb-3">
                        <label for="email" class="form-label">Email<span class="req">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="you@example.com" maxlength="100" autocomplete="email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6 field">
                            <label for="password" class="form-label">Password<span class="req">*</span></label>
                            <div class="input-group has-validation">
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Min. 8 characters" minlength="8" autocomplete="new-password" required>
                                <button class="btn btn-password-toggle" type="button" data-toggle-password="#password"
                                        aria-label="Show password" aria-pressed="false"><i class="bi bi-eye"></i></button>
                                <div class="invalid-feedback">At least 8 characters.</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 field">
                            <label for="confirm_password" class="form-label">Confirm password<span class="req">*</span></label>
                            <div class="input-group has-validation">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                       placeholder="Repeat password" minlength="8" autocomplete="new-password" required>
                                <button class="btn btn-password-toggle" type="button" data-toggle-password="#confirm_password"
                                        aria-label="Show password" aria-pressed="false"><i class="bi bi-eye"></i></button>
                                <div class="invalid-feedback">Passwords must match.</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-text"><i class="bi bi-info-circle"></i> Use 8+ characters with letters and numbers.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100" data-loading-text="Creating account…">
                        <i class="bi bi-person-check"></i> Create account
                    </button>
                </form>

                <div class="auth-divider my-4">or</div>

                <p class="text-center mb-0">
                    Already have an account? <a href="login.php" class="fw-semibold">Sign in</a>
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
<script src="assets/js/main.js"></script>
</body>
</html>