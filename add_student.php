<?php
session_start();
require_once 'db.php';

// حماية الصفحة: إعادة التوجيه لصفحة الدخول إذا لم يسبق تسجيل الدخول
if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

/* =====================================================================
 * add_student.php — Add Student page
 * ---------------------------------------------------------------------
 * LAYOUT & STYLING: Section 5 (done — please don't change the markup)
 *
 * BACKEND (Sections 3 & 4) — write your PHP in this block:
 *   1. Auth check:   session_start();
 *                    if (empty($_SESSION['user_id'])) { header('Location: login.html?msg=login_required'); exit; }
 *   2. On POST:      validate → move_uploaded_file() → INSERT with PDO prepared statement
 *   3. Success:      header('Location: view_students.php?msg=added'); exit;
 *   4. Errors:       $errors['email'] = 'This email is already used';  and  $student = $_POST;
 *                    (the form will show the messages and keep what the user typed)
 * ===================================================================== */

// ---- BACKEND CODE GOES HERE ----


// ---- Template settings (Section 5) ----
$pageTitle   = 'Add Student';
$activePage  = 'add';
$formMode    = 'add';
$student     = $student ?? [];
$errors      = $errors  ?? [];
$pageScripts = $pageScripts ?? [];   // e.g. ['assets/js/validation.js'] for AJAX validation (Section 4)

require __DIR__ . '/includes/header.php';
?>

<!-- Page header -->
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="view_students.php">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
        <h1 class="page-title">Add a new student</h1>
        <p class="page-subtitle">Fields marked <span class="text-danger">*</span> are required. The profile picture is optional.</p>
    </div>
    <div class="page-actions">
        <a href="view_students.php" class="btn btn-light"><i class="bi bi-arrow-left"></i> Back to list</a>
    </div>
</div>

<!-- Messages from the server appear here -->
<div id="pageAlert" aria-live="polite"></div>

<?php require __DIR__ . '/includes/student_form.php'; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
