<?php
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['advocate_id'])) {
    header("Location: login.php");
    exit;
}

$advocate_id = $_SESSION['advocate_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("DELETE FROM cases WHERE id = ? AND advocate_id = ?");
$stmt->execute([$id, $advocate_id]);

header("Location: dashboard.php");
exit;
?>
