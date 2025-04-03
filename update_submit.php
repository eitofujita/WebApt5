<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$fio = $_POST['fio'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$languages = $_POST['languages'] ?? [];
$bio = $_POST['bio'] ?? '';
$agree = isset($_POST['agree']) ? 1 : 0;


$errors = [];
if (empty($fio)) $errors[] = 'fio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';

if (!empty($errors)) {
   
    $query = http_build_query(['errors' => $errors]);
    header("Location: update.php?$query");
    exit;
}

$languages_str = implode(',', $languages);


$pdo = new PDO(DSN, DB_USER, DB_PASS);
$stmt = $pdo->prepare("
    UPDATE users SET 
        fio = ?, phone = ?, email = ?, birthdate = ?, gender = ?, 
        languages = ?, bio = ?, agree = ?
    WHERE id = ?
");
$stmt->execute([
    $fio, $phone, $email, $birthdate, $gender,
    $languages_str, $bio, $agree,
    $_SESSION['user_id']
]);


header('Location: update.php?success=1');
exit;
