<?php
session_start();
require_once 'functions.inc.php';


if(isset($_POST['btn_send'])){
    
    if(isFormComplete()){
        if(isMailValid(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING))){
            
        }else{
            $_SESSION['error'] = "Bitte gib eine gültige E-Mail ein";
        }
    }else{
        $_SESSION['error'] = "Bitte fülle Das Formular komplett aus";
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
        <title>Registrieren</title>
    </head>
    <body>
        <?php if(isset($_SESSION['error'])){ ?>
        <div style="float:left; color:red;margin-top: -50px;" class="container_element">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
        <?php } ?>
        <div id="navigation" class="container_element">
           <?php include_once 'nav.php'; ?> 
        </div>
        <div id="content" class="container_element">
            <h2>Registrierung</h2>
            <form action="sign_up.php" method="post">
                <fieldset>
                    <legend>Bitte füllen Sie alle Formularfelder aus:</legend>
                    <p>
                        <label>Username:</label><br>
                        <input name="username" required>
                    </p>
                    <p>
                        <label>E-Mail:</label><br>
                        <input name="email" required>
                    </p>
                    <p>
                        <label>Passwort:</label><br>
                        <input name="password" required>
                    </p>
                    <p>
                        <label>Passwort wiederholen:</label><br>
                        <input name="password2" required>
                    </p>
                    <button type="submit" name="btn_send" value="sign_up">Registrieren</button>
                </fieldset>
            </form>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
