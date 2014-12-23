<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_send']))
{
    if(isFormComplete()){
        $dbCon = getDbConnection('post_it');
        
        if(doUserLogin($dbCon)){
            $_SESSION['notice'] = "Erfolgreich eingeloggt!";
        }else{
            $_SESSION['error'] = "Benutzername und/oder Passwort falsch!";
            
        }
    }else{
        $_SESSION['error'] = "Bitte füllen Sie alle Felder aus!";
    }
}

header('Location: index.php');
