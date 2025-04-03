<?php 
session_start();
require_once 'config.php';

if(!isset($_SESSION['admin_logged_in'])){
  header('Location: admin_login.php');
  exit; }

  $stmt = $pdo->query("select*from users");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Добро пожаловать, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
    <h2>Список пользователей</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Действие</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['fio']) ?></td>
            <td><?= htmlspecialchars($user['phone']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <a href="admin_edit.php?id=<?= $user['id'] ?>">✏️ Редактировать</a> |
                <a href="admin_delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Удалить этого пользователя?')">🗑️ Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="logout.php">Выйти</a></p>
</body>
</html>