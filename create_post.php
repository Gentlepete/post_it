<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_post'])){
    if(isFormComplete()){
        if(fileSizeOkay($_FILES['postImg']['size'])){
            $dbCon = getDbConnection('post_it');
            $title = $dbCon->real_escape_string(htmlspecialchars($_POST['title']));
            $message = $dbCon->real_escape_string(htmlspecialchars($_POST['message']));        
            $cat_id = $dbCon->real_escape_string(htmlspecialchars($_POST['category']));        
            $user_id = $_SESSION['userId'];
            $image = $_FILES['postImg']; 
    //        var_dump($image);

            $insertQuery = "INSERT posts VALUES (NULL, '$title', '$message', ";
            var_dump($image['tmp_name']);
            if(!empty($image['tmp_name'])){
                $folder = "images/";
                move_uploaded_file($_FILES['postImg']['tmp_name'], "$folder".$_FILES['postImg']['name']);
                $insertQuery .= "'".$image['name']."',";
            }else{
               $insertQuery .= "NULL,";
            }
            
            $insertQuery .= "'$cat_id', '$user_id', NULL)";
            var_dump($insertQuery);
            $insertResult = sendSqlQuery($dbCon, $insertQuery);

            if($insertResult){
                $_SESSION['notice'] = "Erfolgreich gepostet";
            }else {
                $_SESSION['error'] = "Fehler beim Posten";
            }
        }else{
            $_SESSION['error'] = "Bild zu groß!(Maximal 500 Kb)";
        }

            header('Location: index.php');
    }else {
        $_SESSION['error'] = "Bitte fülle alle Felder aus!";
        header("Location: index.php");
    }
    
    
    
}

