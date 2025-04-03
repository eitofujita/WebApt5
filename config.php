
<?php
define('DSN', 'mysql:host=localhost;dbname=webapt5;charset=utf8');
define('DB_USER', 'root');
define('DB_PASS', 'eito0226');

$pdo = new PDO('mysql:host=localhost;dbname=webapt5;charset=utf8','root','eito0226');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>

