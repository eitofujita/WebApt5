<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// フォームからの入力
$fio = $_POST['fio'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$bio = $_POST['bio'] ?? '';
$agree = isset($_POST['agree']) ? 1 : 0;
$languages = $_POST['languages'] ?? [];

// gender のバリデーションと変換
$gender_input = $_POST['gender'] ?? '';
$gender_map = ['male' => 'M', 'female' => 'F'];
$gender = $gender_map[$gender_input] ?? 'F'; // ← F をデフォルト

// バリデーション
$errors = [];
if (!preg_match('/^[\p{L}\s]+$/u', $fio)) $errors[] = 'fio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';

if (!empty($errors)) {
    $query = http_build_query(['errors' => $errors, 'fio' => $fio, 'email' => $email]);
    header("Location: index.php?$query");
    exit;
}

// Cookieに保存
setcookie('fio', $fio, time() + 3600);
setcookie('phone', $phone, time() + 3600);
setcookie('email', $email, time() + 3600);
setcookie('birthdate', $birthdate, time() + 3600);
setcookie('gender', $gender, time() + 3600);
setcookie('languages', implode(',', $languages), time() + 3600);
setcookie('bio', $bio, time() + 3600);
setcookie('agree', $agree, time() + 3600);

// DB登録
require_once 'config.php';
$pdo = new PDO(DSN, DB_USER, DB_PASS);

// login & password 生成
$login = bin2hex(random_bytes(4));
$password = bin2hex(random_bytes(4));
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// INSERT
$stmt = $pdo->prepare("
    INSERT INTO users (login, password_hash, fio, phone, email, birthdate, gender, languages, bio, agree)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $login,
    $password_hash,
    $fio,
    $phone,
    $email,
    $birthdate,
    $gender, // ← 必ず M か F
    implode(',', $languages),
    $bio,
    $agree
]);

// form_dataにも登録
$stmt2 = $pdo->prepare("INSERT INTO form_data (user_id, message) VALUES (?, ?)");
$stmt2->execute([$pdo->lastInsertId(), $bio]);

// セッションに保存してリダイレクト
$_SESSION['flash_login'] = $login;
$_SESSION['flash_password'] = $password;

header('Location: success.php?success=1');
exit;
