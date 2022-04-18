<?php
// Удаляю сессию и перемещаю пользователя на страницу с авторизацией
session_reset();
session_start();
$_SESSION = array();
 
session_destroy();
 
header("location: login.php");
exit;
?>