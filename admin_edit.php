<?php
session_start();
require_once 'config.php';

// 管理者ログインチェック
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// ユーザーIDが指定されていなければリダイレクト
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = $_GET['id'];
$error = '';

// ユーザー情報取得
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Пользователь не найден');
}

// フォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fio = $_POST['fio'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($fio && $phone && $email) {
        $stmt = $pdo->prepare("UPDATE users SET fio = ?, phone = ?, email = ? WHERE id = ?");
        $stmt->execute([$fio, $phone, $email, $id]);
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Пожалуйста, заполните все поля.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать пользователя</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Редактировать пользователя</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>ФИО:<br>
            <input type="text" name="fio" value="<?= htmlspecialchars($user['fio'] ?? '') ?>" required>
        </label><br><br>

        <label>Телефон:<br>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
        </label><br><br>

        <label>Email:<br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </label><br><br>

        <input type="submit" value="Сохранить">
        <a href="admin.php">Назад</a>
    </form>
</body>
</html>
