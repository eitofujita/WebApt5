<?php
session_start();
require_once 'config.php';

$pdo = new PDO(DSN, DB_USER, DB_PASS);


$total_stmt = $pdo->query("SELECT COUNT(*) FROM form_data");
$total_posts = $total_stmt->fetchColumn();


$user_posts_stmt = $pdo->query("
  SELECT u.fio, COUNT(f.id) AS post_count
  FROM users u
  LEFT JOIN form_data f ON u.id = f.user_id
  GROUP BY u.id
");


$lang_stmt = $pdo->query("SELECT languages FROM users");
$lang_counts = [];
while ($row = $lang_stmt->fetch(PDO::FETCH_ASSOC)) {
    $langs = explode(',', $row['languages']);
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
</head>
<body>
  <h1>📊 Общая статистика</h1>
  <p>Всего сообщений: <?= $total_posts ?></p>

  <h2>🧑‍💻 Сообщения по пользователям</h2>
  <ul>
    <?php while ($row = $user_posts_stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <li><?= htmlspecialchars($row['fio']) ?>: <?= $row['post_count'] ?> сообщений</li>
    <?php endwhile; ?>
  </ul>

  <h2>📚 Используемые языки программирования</h2>
  <ul>
    <?php foreach ($lang_counts as $lang => $count): ?>
      <li><?= htmlspecialchars($lang) ?>: <?= $count ?> раз(а)</li>
    <?php endforeach; ?>
  </ul>

  <p><a href="admin.php">← Назад в админку</a></p>
</body>
</html>
