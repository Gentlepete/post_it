<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_send']))
{
    $_GET['userId'] = $_SESSION['userId'];
    $dbCon = getDbConnection('post_it');
    if($_POST['btn_send'] == 'updateInformations'){
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
    
}

if(isset($_SESSION['logged'])){
    $dbCon = getDbConnection('post_it');
    $userId = $_GET['userId'];
    $userQuery = "SELECT id, name, firstname, lastname, interests FROM users "
            ."WHERE id=$userId";
    
    $userResult = sendSqlQuery($dbCon, $userQuery);
    $user = $userResult->fetch_assoc();
    
    $postQuery = "SELECT id, title, message, timestamp, category_id FROM posts "
            . "WHERE user_id=.".$user['id'];
    
    $postResult = sendSqlQuery($dbCon, $userQuery);
    
    
}else{
    $_SESSION['error'] = "Zutritt verweigert";
    header('Location: index.php');
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
        
        <title><?php echo $user['name'] ; ?></title>
    </head>
    <body>
        <div id="navigation" class="container_element">
            <?php include_once 'nav.php'; ?>
        </div>
        <div id="user_profile" class="container_element">
            <h2 style="text-align: center;">Profil von <?php echo $user['name']; ?></h2>
            <?php 
            
            /*----------------------------------------------------------------------------------------------------------------------------
             * Die Bedingung ob eine Formularelement angezeig wird oder nicht sollte lieber nochmal überarbeitet werden.
             * Weil das mit den vielen bedingungen sehr unübersichtlich ist. Lieber eine Große Bedingung über dem Formular und
             * die normale ausgabe der Informationen dann in einem Else Block
             -------------------------------------------------------------------------------------------------------------------------------*/
            
            ?>
            <form action="user.php" method="post">
                
                <div style="float: left;margin-right: 20px;">
                    <label>Vorname: </label><br>
                    <?php if($user['firstname'] == '' || isset($_POST['btn_edit'])){ ?>
                      <input name="firstname" value="<?php echo $user['firstname']?>" >
                    <?php }else{ ?>
                      <p><?php echo $user['firstname']; ?></p>
                    <?php } ?>
                </div>
                
                <div style="">
                    <label>Nachname: </label><br>
                    <?php if($user['lastname'] == '' || isset($_POST['btn_edit'])){ ?>
                    <input name="lastname" value="<?php echo $user['lastname'];?>">
                    <?php }else{ ?>
                      <p><?php echo $user['lastname']; ?></p>
                    <?php } ?>
                </div>
                
                <div>
                    <label>Interessen: </label><br>
                    <?php if($user['interests'] == '' || isset($_POST['btn_edit'])){ ?>
                    <textarea style="width: 400px;" name="interests" ><?php echo $user['interests'] ; ?></textarea>
                    <?php }else{ ?>
                      <p><?php echo $user['interests']; ?></p>
                    <?php } ?>
                </div>
                <div>
                    <button type="submit" name="btn_send" value="updateInformations">Speichern</button>
                </div>
            </form>
            
        </div>
        <div id="content" class="container_element">
            
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
