<?php
session_start();
require_once 'db.php';

// حماية الصفحة: إعادة التوجيه لصفحة الدخول إذا لم يسبق تسجيل الدخول
if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

/* =====================================================================
 * update_student.php — Edit Student page
 * ---------------------------------------------------------------------
 * LAYOUT & STYLING: Section 5 (done — please don't change the markup)
 *
 * BACKEND (Section 3) — write your PHP in this block:
 *   1. session_start(); + auth check (same as add_student.php)
 *   2. GET  ?id=5  → SELECT the student into $student
 *                    not found → header('Location: view_students.php?msg=not_found'); exit;
 *   3. POST        → validate → optional new picture (move_uploaded_file) → UPDATE
 *                    success  → header('Location: view_students.php?msg=updated'); exit;
 *                    errors   → $errors['field'] = 'message'; $student = $_POST + old picture;
 * ===================================================================== */

// ---- BACKEND CODE GOES HERE ----


// ---- DEMO DATA (Section 5) — delete this block once the SELECT query is ready ----
if (!isset($student)) {
    $student = [
        'id'              => 42,
        'name'            => 'Ahmad Khalil',
        'email'           => 'ahmad.khalil@example.com',
        'phone'           => '0933123456',
        'address'         => 'Damascus, Mazzeh, Building 12',
        'profile_picture' => '',
        'created_at'      => '2026-10-02 10:15:00',
    ];
}
// ---- END DEMO DATA ----


// ---- Template settings (Section 5) ----
$pageTitle   = 'Edit Student';
$activePage  = 'view';
$formMode    = 'edit';
$errors      = $errors ?? [];
$pageScripts = $pageScripts ?? [];

require __DIR__ . '/includes/header.php';
?>

<!-- Page header -->
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="view_students.php">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3">
            <?= avatar_html($student['profile_picture'] ?? '', $student['name'] ?? '', 'lg') ?>
            <div>
                <h1 class="page-title">Edit <?= e($student['name'] ?? 'student') ?></h1>
                <p class="page-subtitle">
                    Student #<?= e($student['id'] ?? '') ?>
                    <?php if (!empty($student['created_at'])): ?>
                        · added <?= e(date('M j, Y', strtotime($student['created_at']))) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="page-actions">
        <a href="view_students.php" class="btn btn-light"><i class="bi bi-arrow-left"></i> Back to list</a>
    </div>
</div>

<div id="pageAlert" aria-live="polite"></div>

<?php require __DIR__ . '/includes/student_form.php'; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
