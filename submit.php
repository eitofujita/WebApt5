<?php
）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


$fio = $_POST['fio'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$allowed_genders = ['male', 'female', 'other'];

if (!in_array($gender, $allowed_genders)) {
    $gender = 'other'; 
}
$languages = $_POST['languages'] ?? [];
$bio = $_POST['bio'] ?? '';
$agree = isset($_POST['agree']) ? 1 : 0;


$errors = [];
if (!preg_match('/^[\p{L}\s]+$/u', $fio)) $errors[] = 'fio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';

if (!empty($errors)) {
    $query = http_build_query(['errors' => $errors, 'fio' => $fio, 'email' => $email]);
    header("Location: index.php?$query");
    exit;
}


setcookie('fio', $fio, time() + 3600);
setcookie('phone', $phone, time() + 3600);
setcookie('email', $email, time() + 3600);
setcookie('birthdate', $birthdate, time() + 3600);
setcookie('gender', $gender, time() + 3600);
setcookie('languages', implode(',', $languages), time() + 3600);
setcookie('bio', $bio, time() + 3600);
setcookie('agree', $agree, time() + 3600);


require_once 'config.php';
$pdo = new PDO(DSN, DB_USER, DB_PASS);

$login = bin2hex(random_bytes(4));
$password = bin2hex(random_bytes(4));
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$gender_map = [
    'male' => 'M',
    'female' => 'F',
    
];
$gender = $_POST['gender'] ?? '';
$gender = $gender_map[$gender] ?? 'F';

$stmt = $pdo->prepare("INSERT INTO users (login, password_hash, gender) VALUES (?, ?, ?)");
$stmt->execute([$login, $password_hash, $gender]);

$stmt2 = $pdo->prepare("INSERT INTO form_data (user_id, message) VALUES (?, ?)");
$stmt2->execute([$pdo->lastInsertId(), $bio]);


$_SESSION['flash_login'] = $login;
$_SESSION['flash_password'] = $password;

header('Location: success.php?success=1');
exit;
