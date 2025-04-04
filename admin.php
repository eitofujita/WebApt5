<?php 
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

$pdo = new PDO(DSN, DB_USER, DB_PASS);

// users + form_data を JOIN（ユーザーが複数メッセージを持てるので LEFT JOIN）
$stmt = $pdo->query("
  SELECT u.id AS user_id, u.fio, u.phone, u.email, f.message, f.updated_at
  FROM users u
  LEFT JOIN form_data f ON u.id = f.user_id
  ORDER BY u.id, f.updated_at DESC
");

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
    <h1>Меню, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
    <h2>Список пользователей и их сообщений</h2>

    <table border="1">
        <tr>
            <th>👤 ID</th>
            <th>ФИО</th>
            <th>📞 Телефон</th>
            <th>📧 Email</th>
            <th>📝 Сообщение</th>
            <th>⏱️ Обновлено</th>
            <th>Действие</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['user_id'] ?></td>
            <td><?= htmlspecialchars($user['fio'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['phone'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['message'] ?? '—') ?></td>
            <td><?= htmlspecialchars($user['updated_at'] ?? '—') ?></td>
            <td>
                <a href="admin_edit.php?id=<?= $user['user_id'] ?>">✏️ Редактировать</a> |
                <a href="admin_delete.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Удалить этого пользователя?')">🗑️ Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="logout.php">Выйти</a></p>
</body>
</html>
