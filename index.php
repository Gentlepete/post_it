<?php
session_start();
require_once 'functions.inc.php';

if(isset($_COOKIE['rememberMeToken']) && isset($_COOKIE['rememberMe']) && !isset($_SESSION['logged'])){
    if(doAutoLogin()){
        $_SESSION['notice'] = "eingeloggt";
    }
}

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>Post-it</title>
    </head>
    <body>
        <?php include_once 'flash_messages.php'; ?>
        <div id="navigation" class="container_element">
            <?php include_once 'nav.php'; ?>
        </div>
        <div id="head_menu" class="container_element">
            <?php include_once 'head.php'; ?>
        </div>
        <div id="content" class="container_element">
            
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
