<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_update_post']))
{
    
    if(isFormComplete() && isset($_POST['category'])){
        $_GET['userId'] = $_SESSION['userId'];
        $dbCon = getDbConnection('post_it');

        $title = $dbCon->real_escape_string(htmlspecialchars($_POST['title']));
        $message = $dbCon->real_escape_string(htmlspecialchars($_POST['message']));        
        $cat_id = $dbCon->real_escape_string(htmlspecialchars($_POST['category']));        
        $post_id = $dbCon->real_escape_string(htmlspecialchars($_POST['btn_update_post']));       
        $updateQuery = "UPDATE posts SET title='$title', message='$message', category_id='$cat_id' "
                . "WHERE id=$post_id";

        if($insertResult = sendSqlQuery($dbCon, $updateQuery)){
            $_SESSION['notice'] = "Post erfolgreich bearbeitet";
        }else{
            $_SESSION['error'] = "Post konnte nicht bearbeitet werden";
        }
    }else{
        $_SESSION['error'] = "Bitte fülle das Formular vollständig aus!";
    }
}

header('Location: user.php?userId='.$_SESSION['userId']);
