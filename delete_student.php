<?php
session_start();
require_once 'db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();

        if ($student) {
            if (!empty($student['profile_picture']) && file_exists($student['profile_picture'])) {
                unlink($student['profile_picture']);
            }
            $deleteStmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $deleteStmt->execute([$id]);
        }
    }
}

header('Location: view_students.php?msg=deleted');
exit();
?>
