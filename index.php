<?php
session_start();

// DB設定読み込み
require_once 'config.php';

// DBからログイン中ユーザーの情報を取得
$values = [
    'fio' => '',
    'phone' => '',
    'email' => '',
    'birthdate' => '',
    'gender' => '',
    'bio' => '',
    'agree' => ''
];
$languages = [];

if (isset($_SESSION['user_id'])) {
    // 認証済みならDBからデータ取得
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $values = [
            'fio' => $user['fio'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'birthdate' => $user['birthdate'],
            'gender' => $user['gender'],
            'bio' => $user['bio'],
            'agree' => $user['agree']
        ];
        $languages = explode(',', $user['languages']);
    }
} else {
    // 未ログインならCookieまたはGET
    $values = [
        'fio' => $_COOKIE['fio'] ?? $_GET['fio'] ?? '',
        'phone' => $_COOKIE['phone'] ?? $_GET['phone'] ?? '',
        'email' => $_COOKIE['email'] ?? $_GET['email'] ?? '',
        'birthdate' => $_COOKIE['birthdate'] ?? $_GET['birthdate'] ?? '',
        'gender' => $_COOKIE['gender'] ?? $_GET['gender'] ?? '',
        'bio' => $_COOKIE['bio'] ?? $_GET['bio'] ?? '',
        'agree' => $_COOKIE['agree'] ?? $_GET['agree'] ?? '',
    ];
    $languages = $_COOKIE['languages'] ?? $_GET['languages'] ?? [];
    if (is_string($languages)) {
        $languages = explode(',', $languages);
    }
}
$errors = $_GET['errors'] ?? [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Задание 5 - Форма</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?= isset($_SESSION['user_id']) ? 'Редактирование данных' : 'Форма задания 5' ?></h1>

  <?php if (isset($_GET['success'])): ?>
    <p class="success">✅ Данные успешно <?= isset($_SESSION['user_id']) ? 'обновлены!' : 'сохранены в Cookies!' ?></p>
  <?php endif; ?>

  <?php if (!empty($_GET['errors'])): ?>
    <p class="error">❌ Обнаружены ошибки. Пожалуйста, проверьте введённые данные.</p>
  <?php endif; ?>

  <form action="<?= isset($_SESSION['user_id']) ? 'update_submit.php' : 'submit.php' ?>" method="POST">
    <label>ФИО:
      <input type="text" name="fio" value="<?= htmlspecialchars($values['fio']) ?>">
      <?= in_array('fio', $errors) ? '<span class="error">Некорректное ФИО</span>' : '' ?>
    </label><br>

    <label>Телефон:
      <input type="tel" name="phone" value="<?= htmlspecialchars($values['phone']) ?>">
    </label><br>

    <label>Email:
      <input type="email" name="email" value="<?= htmlspecialchars($values['email']) ?>">
      <?= in_array('email', $errors) ? '<span class="error">Некорректный Email</span>' : '' ?>
    </label><br>

    <label>Дата рождения:
      <input type="date" name="birthdate" value="<?= htmlspecialchars($values['birthdate']) ?>">
    </label><br>

    <label>Пол:
  <input type="radio" name="gender" value="male" <?= $values['gender'] == 'male' ? 'checked' : '' ?>> М
  <input type="radio" name="gender" value="female" <?= $values['gender'] == 'female' ? 'checked' : '' ?>> Ж
  
  </label><br>

    <label>Любимые ЯП:
      <select name="languages[]" multiple>
        <?php foreach (['C++','PHP','Python','JavaScript'] as $lang): ?>
          <option value="<?= $lang ?>" <?= in_array($lang, $languages) ? 'selected' : '' ?>><?= $lang ?></option>
        <?php endforeach; ?>
      </select>
    </label><br>

    <label>Биография:
      <textarea name="bio"><?= htmlspecialchars($values['bio']) ?></textarea>
    </label><br>

    <label>
      <input type="checkbox" name="agree" value="1" <?= $values['agree'] ? 'checked' : '' ?>>
      С контрактом ознакомлен(а)
    </label><br>

    <input type="submit" value="<?= isset($_SESSION['user_id']) ? 'Обновить' : 'Сохранить' ?>">
  </form>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <p><a href="login.php">Войти для редактирования ранее отправленных данных</a></p>
  <?php else: ?>
    <p><a href="logout.php">Выйти</a></p>
  <?php endif; ?>
</body>
</html>
