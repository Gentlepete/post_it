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

    $insertQuery = "UPDATE users SET firstname= '$firstname', lastname= '$lastname', interests= '$interests' "
            . "WHERE id=".$_SESSION['userId'];

    if($insertResult = sendSqlQuery($dbCon, $insertQuery)){
        $_SESSION['notice'] = "Informationen erfolgreich eingetragen";
    }else{
        $_SESSION['error'] = "Informationen konnten nicht eingetragen werden";
    }
   
    
}

header('Location: user.php?userId='.$_SESSION['userId']);

