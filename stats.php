<?php
session_start();
require_once 'config.php';

$pdo = new PDO(DSN, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 総メッセージ数
$total_stmt = $pdo->query("SELECT COUNT(*) FROM form_data");
$total_posts = $total_stmt->fetchColumn();

// ユーザー別メッセージ数
$user_posts_stmt = $pdo->query("
  SELECT u.fio, COUNT(f.id) AS post_count
  FROM users u
  LEFT JOIN form_data f ON u.id = f.user_id
  GROUP BY u.id
");

// 言語統計
$lang_stmt = $pdo->query("SELECT languages FROM users");
$lang_counts = [];
while ($row = $lang_stmt->fetch(PDO::FETCH_ASSOC)) {
    $langs_raw = $row['languages'] ?? '';
    $langs = explode(',', $langs_raw);
    foreach ($langs as $lang) {
        $lang = trim($lang);
        if ($lang !== '') {
            $lang_counts[$lang] = ($lang_counts[$lang] ?? 0) + 1;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>📊 Статистика</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>📊 Общая статистика</h1>
  <p>Всего сообщений: <?= htmlspecialchars($total_posts ?? '0') ?></p>

  <h2>🧑‍💻 Сообщения по пользователям</h2>
  <ul>
    <?php while ($row = $user_posts_stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <li><?= htmlspecialchars($row['fio'] ?? '—') ?>: <?= htmlspecialchars($row['post_count'] ?? '0') ?> сообщений</li>
    <?php endwhile; ?>
  </ul>

  <h2>📚 Используемые языки программирования</h2>
  <ul>
    <?php foreach ($lang_counts as $lang => $count): ?>
      <li><?= htmlspecialchars($lang) ?>: <?= htmlspecialchars($count) ?> раз(а)</li>
    <?php endforeach; ?>
  </ul>

  <p><a href="admin.php">← Назад в админку</a></p>
</body>
</html>
