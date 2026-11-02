<?php
session_start();
require_once 'db.php';

// حماية الصفحة: إعادة التوجيه لصفحة الدخول إذا لم يسبق تسجيل الدخول
if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

$errors  = [];
$student = [];

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    $student = $_POST;

    // 1. التحقق من صحة البيانات
    if ($name === '') {
        $errors['name'] = 'Please enter the student name.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = 'This email is already used.';
        }
    }
    if ($phone === '') {
        $errors['phone'] = 'Please enter a phone number.';
    }
    if ($address === '') {
        $errors['address'] = 'Please enter an address.';
    }

    // 2. معالجة رفع الصورة الشخصية (اختياري)
    $profile_picture = '';
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
                    $profile_picture = $targetPath;
                } else {
                    $errors['profile_picture'] = 'Failed to save the uploaded image.';
                }
            }
        } else {
            $errors['profile_picture'] = 'Error uploading file.';
        }
    }

    // 3. الإدخال في قاعدة البيانات إذا لم توجد أخطاء
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, address, profile_picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $address, $profile_picture]);
        header('Location: view_students.php?msg=added');
        exit();
    }
}

// ---- Template settings (Section 5) ----
$pageTitle   = 'Add Student';
$activePage  = 'add';
$formMode    = 'add';
$student     = $student ?? [];
$errors      = $errors  ?? [];
$pageScripts = ['assets/js/main.js'];

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