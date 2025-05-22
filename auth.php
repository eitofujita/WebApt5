<?php
session_start();
require_once 'config.php';

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$pdo = new PDO(DSN, DB_USER, DB_PASS);
$stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
$stmt->execute([$login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    
    // ✅ ログイン成功 → update.php へ移動
    header('Location: update.php');
    exit;
} else {
    header('Location: login.php?error=1');
    exit;
}
