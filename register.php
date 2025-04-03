<?php
require 'config.php';


$username = 'admin';
$password = 'adminpass';


$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hash]);

echo "Пользователь зарегистрирован!";
?>
