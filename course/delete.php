<?php
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view.php");
    exit();
}

$stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
$stmt->execute([$id]);

header("Location: view.php");
exit();
?>