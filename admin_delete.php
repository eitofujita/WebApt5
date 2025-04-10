<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}


if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = $_GET['id'];


$pdo->prepare("DELETE FROM form_data WHERE user_id = ?")->execute([$id]);


$pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);

header('Location: admin.php');
exit;

ef258a47