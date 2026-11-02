<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Student Registration System — register, search and manage student records.">
    <meta name="theme-color" content="#4f46e5">
    <title>Home · Student Registration System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">

    <style>
        .team-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.25rem;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .team-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.08);
            border-color: #cbd5e1;
            background-color: #ffffff;
        }
        .role-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: 50rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-house"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="add_student.php"><i class="bi bi-person-plus"></i> Add Student</a></li>
                <li class="nav-item"><a class="nav-link" href="view_students.php"><i class="bi bi-people"></i> View Students</a></li>
            </ul>
            <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-3 mt-lg-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text me-3 d-none d-lg-block">
                        Welcome, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>
                    </span>
                    <a class="btn btn-outline-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sign out</a>
                <?php else: ?>
                    <a class="btn btn-outline-primary" href="login.php?redirect=index.php"><i class="bi bi-box-arrow-in-right"></i> Sign in</a>
                    <a class="btn btn-primary" href="register.php">Create account</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main id="main" class="app-main">
    <div class="container">

        <!-- ===================== Hero Section ===================== -->
        <section class="hero mb-5">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-7">
                    <!-- شارة الجامعة والمادة -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="hero-badge"><i class="bi bi-mortarboard-fill"></i> Syrian Virtual University (SVU)</span>
                        <span class="badge bg-white text-primary fw-semibold px-3 py-2 rounded-pill d-inline-flex align-items-center">
                            BWP501 · Web Programming II
                        </span>
                    </div>

                    <h1 class="mb-3">Welcome! Manage every student record in one place.</h1>
                    
                    <!-- اسم الدكتور المباشر -->
                    <p class="lead mb-4">
                        <i class="bi bi-person-check-fill text-warning me-1"></i> Course Instructor: <strong>Dr. Bassel Al-Khatib</strong>
                    </p>

                    <div class="hero-cta d-flex flex-wrap gap-2">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="view_students.php" class="btn btn-primary btn-lg"><i class="bi bi-people-fill"></i> View all students</a>
                            <a href="add_student.php" class="btn btn-light btn-lg"><i class="bi bi-person-plus-fill"></i> Add student</a>
                        <?php else: ?>
                            <a href="register.php" class="btn btn-light btn-lg"><i class="bi bi-person-plus-fill"></i> Get started</a>
                            <a href="login.php?redirect=index.php" class="btn btn-ghost-light btn-lg"><i class="bi bi-box-arrow-in-right"></i> Sign in</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Decorative app preview (desktop only) -->
                <div class="col-lg-5 d-none d-lg-block" aria-hidden="true">
                    <div class="hero-preview">
                        <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                            <strong class="small">Students</strong>
                            <span class="badge rounded-pill text-bg-primary">42 total</span>
                        </div>
                        <div class="row-line"><span class="avatar avatar-sm avatar-initials tone-1">AK</span><div class="flex-grow-1"><div class="fw-semibold">Ahmad Khalil</div><div class="skeleton w-75 mt-1"></div></div><i class="bi bi-pencil text-primary"></i></div>
                        <div class="row-line"><span class="avatar avatar-sm avatar-initials tone-2">LH</span><div class="flex-grow-1"><div class="fw-semibold">Lina Haddad</div><div class="skeleton w-50 mt-1"></div></div><i class="bi bi-pencil text-primary"></i></div>
                        <div class="row-line"><span class="avatar avatar-sm avatar-initials tone-3">OS</span><div class="flex-grow-1"><div class="fw-semibold">Omar Saleh</div><div class="skeleton w-75 mt-1"></div></div><i class="bi bi-pencil text-primary"></i></div>
                        <div class="row-line"><span class="avatar avatar-sm avatar-initials tone-4">RY</span><div class="flex-grow-1"><div class="fw-semibold">Rana Youssef</div><div class="skeleton w-50 mt-1"></div></div><i class="bi bi-pencil text-primary"></i></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Quick actions ===================== -->
        <section class="mb-5" aria-labelledby="quickTitle">
            <div class="d-flex align-items-end justify-content-between mb-3">
                <div>
                    <h2 id="quickTitle" class="h4 mb-1">Quick actions</h2>
                    <p class="text-muted-soft mb-0">Everything you need, one click away.</p>
                </div>
            </div>

            <div class="row g-3 g-lg-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card feature-card position-relative">
                        <div class="card-body p-4">
                            <span class="icon-badge"><i class="bi bi-person-plus"></i></span>
                            <h3 class="card-title">Add Student</h3>
                            <p class="card-text">Create a new record with contact details and an optional photo.</p>
                            <a href="add_student.php" class="go stretched-link">Add now <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card feature-card position-relative">
                        <div class="card-body p-4">
                            <span class="icon-badge"><i class="bi bi-people"></i></span>
                            <h3 class="card-title">View Students</h3>
                            <p class="card-text">Browse all students with sorting and 10 records per page.</p>
                            <a href="view_students.php" class="go stretched-link">Open list <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card feature-card position-relative">
                        <div class="card-body p-4">
                            <span class="icon-badge"><i class="bi bi-search"></i></span>
                            <h3 class="card-title">Search</h3>
                            <p class="card-text">Find students instantly by name, email or phone number.</p>
                            <a href="view_students.php#search" class="go stretched-link">Search <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card feature-card position-relative">
                        <div class="card-body p-4">
                            <span class="icon-badge"><i class="bi bi-shield-lock"></i></span>
                            <h3 class="card-title">Secure Access</h3>
                            <p class="card-text">Hashed passwords and session-protected pages keep data safe.</p>
                            <a href="register.php" class="go stretched-link">Create account <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== How it works ===================== -->
        <section class="card mb-5" aria-labelledby="howTitle">
            <div class="card-body p-4 p-lg-5">
                <h2 id="howTitle" class="h4 mb-4">How it works</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="d-flex gap-3">
                            <span class="step-num">1</span>
                            <div>
                                <h3 class="h6 mb-1">Create an account</h3>
                                <p class="text-muted-soft small mb-0">Register with a username, email and a secure password.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex gap-3">
                            <span class="step-num">2</span>
                            <div>
                                <h3 class="h6 mb-1">Add students</h3>
                                <p class="text-muted-soft small mb-0">Fill in the form — fields are validated instantly as you type.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex gap-3">
                            <span class="step-num">3</span>
                            <div>
                                <h3 class="h6 mb-1">Manage records</h3>
                                <p class="text-muted-soft small mb-0">Search, edit or delete any student from the list.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Project Development Team ===================== -->
        <section class="card mb-5" aria-labelledby="teamTitle">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                    <div>
                        <h2 id="teamTitle" class="h4 mb-1">Project Development Team</h2>
                        <p class="text-muted-soft small mb-0">BWP501 · Web Programming II — Project Contributors &amp; Student IDs</p>
                    </div>
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                            <i class="bi bi-person-workspace me-1"></i> Supervised by: <strong>Dr. Bassel Al-Khatib</strong>
                        </span>
                    </div>
                </div>

                <!-- 3 cards on top row, 2 centered cards on bottom row -->
                <div class="row g-3 g-lg-4 justify-content-center">
                    <!-- Student 1 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="team-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="avatar avatar-sm avatar-initials tone-1 flex-shrink-0">M1</span>
                                <div class="min-w-0">
                                    <h3 class="h6 mb-0 fw-bold text-truncate">Student Name 1</h3>
                                    <span class="badge bg-white text-secondary border mt-1">ID: 123456</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top">
                                <span class="role-badge bg-primary-subtle text-primary">Database &amp; Auth</span>
                                <p class="small text-muted-soft mb-0">SQL Database Setup, Password Hashing &amp; Session Management</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student 2 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="team-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="avatar avatar-sm avatar-initials tone-2 flex-shrink-0">M2</span>
                                <div class="min-w-0">
                                    <h3 class="h6 mb-0 fw-bold text-truncate">Student Name 2</h3>
                                    <span class="badge bg-white text-secondary border mt-1">ID: 234567</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top">
                                <span class="role-badge bg-success-subtle text-success">Frontend &amp; UI</span>
                                <p class="small text-muted-soft mb-0">UI Layout Design &amp; Mobile Responsiveness with Bootstrap 5</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student 3 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="team-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="avatar avatar-sm avatar-initials tone-3 flex-shrink-0">M3</span>
                                <div class="min-w-0">
                                    <h3 class="h6 mb-0 fw-bold text-truncate">Student Name 3</h3>
                                    <span class="badge bg-white text-secondary border mt-1">ID: 345678</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top">
                                <span class="role-badge bg-info-subtle text-info-emphasis">AJAX &amp; Validation</span>
                                <p class="small text-muted-soft mb-0">Real-time Email Validation via AJAX &amp; Client-side Form Checks</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student 4 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="team-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="avatar avatar-sm avatar-initials tone-4 flex-shrink-0">M4</span>
                                <div class="min-w-0">
                                    <h3 class="h6 mb-0 fw-bold text-truncate">Student Name 4</h3>
                                    <span class="badge bg-white text-secondary border mt-1">ID: 456789</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top">
                                <span class="role-badge bg-warning-subtle text-warning-emphasis">CRUD &amp; Uploads</span>
                                <p class="small text-muted-soft mb-0">Student Records Management (CRUD) &amp; Profile Picture Uploads</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student 5 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="team-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="avatar avatar-sm avatar-initials tone-1 flex-shrink-0">M5</span>
                                <div class="min-w-0">
                                    <h3 class="h6 mb-0 fw-bold text-truncate">Student Name 5</h3>
                                    <span class="badge bg-white text-secondary border mt-1">ID: 567890</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top">
                                <span class="role-badge bg-secondary-subtle text-secondary-emphasis">Search &amp; Pagination</span>
                                <p class="small text-muted-soft mb-0">SQL Search Filtering, Pagination &amp; Final Documentation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

<!-- ===================== Footer ===================== -->
<footer class="app-footer py-4 border-top">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
        <div>
            <div class="fw-bold">&copy; 2026 Syrian Virtual University (SVU)</div>
            <small class="text-muted-soft">BWP501 · Web Programming II | Supervised by: <strong>Dr. Bassel Al-Khatib</strong></small>
        </div>
        <nav class="d-flex gap-3" aria-label="Footer">
            <a href="index.php">Home</a>
            <a href="view_students.php">Students</a>
            <a href="add_student.php">Add Student</a>
        </nav>
    </div>
</footer>

<div id="toastArea" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/ui.js"></script>
</body>
</html>