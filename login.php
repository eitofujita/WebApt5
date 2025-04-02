<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Вход</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="login-align.css"> 
</head>
<body>

<h2>Вход</h2>

<form action="auth.php" method="post" class="login-form">
  <label>
    Логин:
    <input type="text" name="login">
  </label>

  <label>
    Пароль:
    <input type="password" name="password">
  </label>

  <input type="submit" value="Войти">
</form>
<a href="index.php">Если не получается начни сначала</a>
</body>
</html>

