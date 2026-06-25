<?php
require_once __DIR__ . '/../config/constants.php';
session_start();

$_SESSION = [];

setcookie('username', '', time() - 86400, "/");
setcookie('password', '', time() - 86400, "/");
setcookie('remember', '', time() - 86400, "/");

session_destroy();

header('Location: ' . BASE_URL . '/pages/login.php?logout=1');
?>
