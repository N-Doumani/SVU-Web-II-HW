<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['id'] ?? null;

if ($student_id) {
    // Fetch student to get picture path
    $stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if ($student) {
        // Remove profile image file from server if it exists
        if ($student['profile_picture'] && file_exists('uploads/' . $student['profile_picture'])) {
            unlink('uploads/' . $student['profile_picture']);
        }

        // Delete record from database
        $delete_stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $delete_stmt->execute([$student_id]);
    }
}

header("Location: dashboard.php");
exit();
?>