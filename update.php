<?php
session_start();
require_once 'config.php';

// 未ログインならログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 成功後は 2 秒後にリダイレクト
$showSuccess = isset($_GET['success']);
if ($showSuccess) {
    header("Refresh: 2; URL=index.php");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование данных</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Редактирование данных</h1>

<?php if ($showSuccess): ?>
  <p class="success">✅ Данные обновлены! (Через 2 секунды вы будете перенаправлены на главную)</p>
<?php else: ?>
  
  <form action="update_submit.php" method="POST" class="login-form">
    <a href="index.php" class="back-button">← Вернуться на главную</a>
  </form>
<?php endif; ?>

</body>
</html>
