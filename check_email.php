<?php
require_once 'db.php';

header('Content-Type: application/json');

$email = trim($_GET['email'] ?? '');

if (!empty($email)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $exists = $stmt->fetchColumn() > 0;

    echo json_encode(['exists' => $exists]);
} else {
    echo json_encode(['exists' => false]);
}
?>