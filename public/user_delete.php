<?php
// public/admin_user_delete.php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

requireAdmin();

$pdo = getPDO();
$id  = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: admin_users.php');
    exit;
}

// Prevent admin from deleting themselves
if ($id === currentUserId()) {
    setFlash('error', 'You cannot delete your own account.');
    header('Location: admin_users.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    setFlash('error', 'User not found.');
    header('Location: admin_users.php');
    exit;
}

// Perform deletion
$pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
setFlash('success', "User '{$user['username']}' has been deleted.");
header('Location: admin_users.php');
exit;
