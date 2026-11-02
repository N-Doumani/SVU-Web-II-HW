<?php
session_start();
require_once 'db.php';

// حماية الصفحة: إعادة التوجيه لصفحة الدخول إذا لم يسبق تسجيل الدخول
if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

$errors = [];
$id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);

if ($id <= 0) {
    header('Location: view_students.php?msg=not_found');
    exit();
}

// جلب بيانات الطالب الحالية من قاعدة البيانات
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$existingStudent = $stmt->fetch();

if (!$existingStudent) {
    header('Location: view_students.php?msg=not_found');
    exit();
}

$student = $existingStudent;

// معالجة التحديث عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $student = array_merge($existingStudent, $_POST);

    // 1. التحقق من الحقول
    if ($name === '') {
        $errors['name'] = 'Please enter the student name.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = 'This email is already used by another student.';
        }
    }
    if ($phone === '') {
        $errors['phone'] = 'Please enter a phone number.';
    }
    if ($address === '') {
        $errors['address'] = 'Please enter an address.';
    }

    // 2. معالجة رفع صورة جديدة (اختياري)
    $profile_picture = $existingStudent['profile_picture'];
    if (!empty($_FILES['profile_picture']['name'])) {
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowed)) {
                $errors['profile_picture'] = 'Only JPG, PNG, GIF, and WEBP images are allowed.';
            } elseif ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
                $errors['profile_picture'] = 'Image size must not exceed 2MB.';
            } else {
                if (!is_dir(__DIR__ . '/uploads')) {
                    mkdir(__DIR__ . '/uploads', 0755, true);
                }
                $newFilename = uniqid('stu_', true) . '.' . $ext;
                $targetPath  = 'uploads/' . $newFilename;
                
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], __DIR__ . '/' . $targetPath)) {
                    // حذف الصورة القديمة إن وجدت
                    if (!empty($existingStudent['profile_picture']) && file_exists(__DIR__ . '/' . $existingStudent['profile_picture'])) {
                        unlink(__DIR__ . '/' . $existingStudent['profile_picture']);
                    }
                    $profile_picture = $targetPath;
                } else {
                    $errors['profile_picture'] = 'Failed to save the uploaded image.';
                }
            }
        } else {
            $errors['profile_picture'] = 'Error uploading file.';
        }
    }

    // 3. تحديث السجل في قاعدة البيانات
    if (empty($errors)) {
        $updateStmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ?, address = ?, profile_picture = ? WHERE id = ?");
        $updateStmt->execute([$name, $email, $phone, $address, $profile_picture, $id]);
        header('Location: view_students.php?msg=updated');
        exit();
    }
}

// ---- Template settings (Section 5) ----
$pageTitle   = 'Edit Student';
$activePage  = 'view';
$formMode    = 'edit';
$errors      = $errors ?? [];
$pageScripts = ['assets/js/main.js'];

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