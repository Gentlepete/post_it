<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_delete_post'])){
    $dbCon = getDbConnection('post_it');
    
    $post_id = $dbCon->real_escape_string(htmlspecialchars($_POST['btn_delete_post']));
    
    $imgQuery = "SELECT img_source AS name FROM posts "
            . "WHERE id=$post_id";
    $deleteCommentsQuery = "DELETE FROM comments "
            . "WHERE post_id=$post_id";
    $imageResult = sendSqlQuery($dbCon, $imgQuery);
    $deleteQuery = "DELETE FROM posts "
            . "WHERE id=$post_id";
    
    
    if(sendSqlQuery($dbCon, $deleteQuery)){
        
        //Wenn Image zum Post vorhanden, dann unlinken (also löschen)
        $image = $imageResult->fetch_assoc();
        if($image['name']){
            $path = "images/".$image['name'];
            unlink($path);
        }
        
        //Zum Post gehörige Kommentare Löschen
        sendSqlQuery($dbCon, $deleteCommentsQuery);
        
        
        $_SESSION['notice'] = "Post erfolgreich gelöscht";
    }else{
        $_SESSION['error'] = "Postnicht gelöscht werden!";
    }
}

header('Location: user.php?userId='.$_SESSION['userId']);