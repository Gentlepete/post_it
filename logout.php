<?php
session_start();

if(isset($_COOKIE['rememberMe']))
{
    unset($_COOKIE['rememberMe']);
    unset($_COOKIE['rememberMeToken']);
    setcookie('rememberMe', ' ', time() -3600);
    setcookie('rememberMeToken', ' ', time() -3600);
}

session_destroy();
$_SESSION = array();

session_start();

$_SESSION['notice'] = "Erfolgreich ausgeloggt";

header('Location: index.php');
