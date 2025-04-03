<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}


$stmt = $pdo->query("SELECT language, COUNT(*) as count FROM users GROUP BY language");
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Статистика по языкам</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Статистика по языкам программирования</h1>

    <table border="1">
        <tr>
            <th>Язык</th>
            <th>Количество пользователей</th>
        </tr>
        <?php foreach ($stats as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['language'] ?? '—') ?></td>
                <td><?= $row['count'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="admin.php">Назад</a></p>
</body>
</html>
