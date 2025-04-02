<?php
require 'config.php';
// セッションを破棄してログインページへ
session_destroy();
header('Location: login.php');
?>
