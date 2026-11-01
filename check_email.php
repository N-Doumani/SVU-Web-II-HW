<?php
require_once 'db.php';
header('Content-Type: application/json');

$email = trim($_GET['email'] ?? '');
$id = $_GET['id'] ?? null;

if (!empty($email)) {
    if ($id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
        $stmt->execute([$email]);
    }
    echo json_encode(['exists' => $stmt->fetchColumn() > 0]);
} else {
    echo json_encode(['exists' => false]);
}
?>