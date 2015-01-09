<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_update_user']))
{
    $_GET['userId'] = $_SESSION['userId'];
    $dbCon = getDbConnection('post_it');
    
    $firstname = $dbCon->real_escape_string(htmlspecialchars($_POST['firstname']));
    $lastname = $dbCon->real_escape_string(htmlspecialchars($_POST['lastname']));
    $interests = $dbCon->real_escape_string(htmlspecialchars($_POST['interests']));
    $image = $_FILES['avatarImg']; 
    $random = rand(1,100000);

    $allowed = array("image/jpeg", "image/gif", "image/png", "image/jpg");
    
    
    
    $insertQuery = "UPDATE users SET firstname= '$firstname', lastname= '$lastname', interests= '$interests' ";
    
    if(!empty($image['tmp_name']) && in_array($image['type'], $allowed)){
         
        // wenn schon ein avatar vorhanden, dann löschen
        $getAvatar = "SELECT avatar_src AS src FROM users "
            . "WHERE id=".$_SESSION['userId'];
        $oldAvatarResult = sendSqlQuery($dbCon, $getAvatar);
        
        $oldAvatar = $oldAvatarResult->fetch_assoc();
        
        if($oldAvatar['src'] && $oldAvatar['src'] != "default_app_avatar.png"){
            $path = "avatars/".$oldAvatar['src'];
            unlink($path);
        }
        // Bild komprimieren in /avatars und in dbquery einfügen
//        $image['tmp_name'] = compress($image['tmp_name'], $image['tmp_name'], 75);
        $folder = "avatars/";
        move_uploaded_file($image['tmp_name'], "$folder".$_SESSION['userId'].$random.$image['name']);
        $insertQuery .= ", avatar_src= '".$_SESSION['userId'].$random.$image['name']."' ";
    }elseif(!empty($image['tmp_name']) && !in_array($image['type'], $allowed)){
        $_SESSION['error'] = "Nur Bilder (jpeg, gif oder png) erlaubt";
        header('Location: index.php');
    }

    $insertQuery .= "WHERE id=".$_SESSION['userId'];
    
    

    if($insertResult = sendSqlQuery($dbCon, $insertQuery)){
        $_SESSION['notice'] = "Informationen erfolgreich eingetragen";
    }else{
        $_SESSION['error'] = "Informationen konnten nicht eingetragen werden";
    }
   
    
}

header('Location: user.php?userId='.$_SESSION['userId']);

