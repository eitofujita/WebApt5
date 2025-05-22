<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("ログインが必要です");
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];

    try {
        $pdo = new PDO(DSN, DB_USER, DB_PASS);
        $stmt = $pdo->prepare("INSERT INTO form_data (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user_id, $message]);
        $success = "✅ 登録が完了しました";
    } catch (PDOException $e) {
        $error = "エラー: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>フォーム送信</title>
</head>
<body>
    <h1>メッセージフォーム</h1>

    <?php if ($success): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="submit.php" method="POST">
        <textarea name="message" rows="5" cols="30" placeholder="メッセージを入力してください"></textarea><br>
        <button type="submit">送信</button>
    </form>
</body>
</html>
