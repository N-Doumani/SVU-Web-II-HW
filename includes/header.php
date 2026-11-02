<?php
/**
 * Shared page header (Section 5 — Styling & Responsiveness)
 * ------------------------------------------------------------------
 * Usage at the top of any PHP page:
 *
 *     $pageTitle  = 'View Students';   // text in the browser tab
 *     $activePage = 'view';            // home | add | view  → highlights the menu item
 *     require __DIR__ . '/includes/header.php';
 *
 * Backend note: the navbar shows the logged-in user from
 * $_SESSION['username']. Set it in login.php after password_verify().
 */

require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle   = $pageTitle  ?? 'Student Registration System';
$activePage  = $activePage ?? '';
$currentUser = $_SESSION['username'] ?? null;

/** Highlights the current menu item (class + aria-current for screen readers). */
$navClass = fn (string $key): string => $key === $activePage ? 'nav-link active' : 'nav-link';
$navAria  = fn (string $key): string => $key === $activePage ? ' aria-current="page"' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Student Registration System — manage student records.">
    <meta name="theme-color" content="#4f46e5">
    <title><?= e($pageTitle) ?> · Student Registration System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 + Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Project theme (always last) -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
</head>
<body>

<a class="visually-hidden-focusable position-absolute top-0 start-0 m-2 btn btn-primary" href="#main">Skip to content</a>

<!-- ===================== Navbar ===================== -->
<nav class="navbar navbar-expand-lg app-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
            <span class="d-none d-sm-inline">SVU · Student Registration</span>
            <span class="d-sm-none">SVU SRS</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto ms-lg-4 gap-lg-1">
                <li class="nav-item">
                    <a class="<?= $navClass('home') ?>"<?= $navAria('home') ?> href="index.php"><i class="bi bi-house"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="<?= $navClass('add') ?>"<?= $navAria('add') ?> href="add_student.php"><i class="bi bi-person-plus"></i> Add Student</a>
                </li>
                <li class="nav-item">
                    <a class="<?= $navClass('view') ?>"<?= $navAria('view') ?> href="view_students.php"><i class="bi bi-people"></i> View Students</a>
                </li>
            </ul>

            <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-3 mt-lg-0">
                <?php if ($currentUser): ?>
                    <div class="dropdown">
                        <button class="btn btn-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= avatar_html(null, $currentUser, 'sm') ?>
                            <span><?= e($currentUser) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted-soft">Signed in as <strong><?= e($currentUser) ?></strong></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Log out</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-outline-primary" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Sign in</a>
                    <a class="btn btn-primary" href="register.php">Create account</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<!-- ===================== Page content ===================== -->
<main id="main" class="app-main">
    <div class="container">
