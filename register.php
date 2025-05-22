<?php
require 'config.php';

// ユーザー名とパスワードを固定で登録
$username = 'admin';
$password = 'adminpass';

// パスワードをハッシュ化して登録
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hash]);

echo "Пользователь зарегистрирован!";
?>
