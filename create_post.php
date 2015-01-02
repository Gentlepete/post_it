<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_post'])){
    
    if(isFormComplete()){
        $dbCon = getDbConnection('post_it');
        
        $title = $dbCon->real_escape_string(htmlspecialchars($_POST['title']));
        $message = $dbCon->real_escape_string(htmlspecialchars($_POST['message']));        
        $cat_id = $dbCon->real_escape_string(htmlspecialchars($_POST['category']));        
        $user_id = $_SESSION['userId'];
        
        $insertQuery = "INSERT posts VALUES (NULL, '$title', '$message', '$cat_id', '$user_id', NULL)";
        
        $insertResult = sendSqlQuery($dbCon, $insertQuery);
        
        if($insertResult){
            $_SESSION['notice'] = "Erfolgreich gepostet";
        }else {
            $_SESSION['error'] = "Fehler beim Posten";
        }
        
        header('Location: index.php');
    }else {
        $_SESSION['error'] = "Bitte fülle alle Felder aus!";
        header("Location: index.php");
    }
    
    
    
}

