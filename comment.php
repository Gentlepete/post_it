<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_comment'])){
    if(isFormComplete()){
        $dbCon = getDbConnection('post_it');
        $comment = $dbCon->real_escape_string(htmlspecialchars($_POST['comment'])); 
        $post_id = $dbCon->real_escape_string(htmlspecialchars($_POST['post_id'])); 
        $user_id = $_SESSION['userId'];
        $insertQuery = "INSERT comments VALUES (NULL, '$comment', '$post_id', '$user_id', NULL)";
        
        $insertResult = sendSqlQuery($dbCon, $insertQuery);
        
        if($insertResult){
            $_SESSION['notice'] = "Kommentar erfolgreich angelegt";
        }else{
            $_SESSION['error'] = "Kommentar konnte angelegt werden!";
        }
    }else{
        $_SESSION['error'] = "Der Kommentar darf nicht leer sein.";
    }
}

header('Location: '.$_SERVER['HTTP_REFERER']."#$post_id");

