<?php
session_start();
$login = $_SESSION['flash_login'] ?? null;
$password = $_SESSION['flash_password'] ?? null;
unset($_SESSION['flash_login'], $_SESSION['flash_password']);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8"><title>Успех</title></head>
<body>
<h1>🎉 Данные успешно отправлены!</h1>
<?php if ($login && $password): ?>
<p><strong>Ваш логин:</strong> <?= htmlspecialchars($login) ?></p>
<p><strong>Ваш пароль:</strong> <?= htmlspecialchars($password) ?></p>
<p><em>Пожалуйста, сохраните эти данные. Они будут показаны только один раз.</em></p>
<?php else: ?>
<p>Нет новых данных для отображения.</p>
<?php endif; ?>
<a href="login.php">Войти и отредактировать данные</a>
</body>
</html>
