<?php
session_start();
require_once 'functions.inc.php';

if(isset($_POST['btn_delete_post'])){
    $dbCon = getDbConnection('post_it');
    
    $post_id = $dbCon->real_escape_string(htmlspecialchars($_POST['btn_delete_post']));
    
    $imgQuery = "SELECT img_source AS name FROM posts "
            . "WHERE id=$post_id";
    $imageResult = sendSqlQuery($dbCon, $imgQuery);
    $deleteQuery = "DELETE FROM posts"
            . " WHERE id=$post_id";
    
    if(sendSqlQuery($dbCon, $deleteQuery)){
        $image = $imageResult->fetch_assoc();
        if($image){
            $path = "images/".$image['name'];
            unlink($path);
        }
        $_SESSION['notice'] = "Post erfolgreich gelöscht";
    }else{
        $_SESSION['error'] = "Post konnte nicht gelöscht werden!";
    }
}

if(isset($_SESSION['logged'])){
    $dbCon = getDbConnection('post_it');
    $userId = $_GET['userId'];
    $userQuery = "SELECT id, name, firstname, lastname, interests FROM users "
            ."WHERE id=$userId";
    
    $userResult = sendSqlQuery($dbCon, $userQuery);
    $user = $userResult->fetch_assoc();
    
//    $postQuery = "SELECT id, title, message, timestamp, category_id FROM posts "
//            . "WHERE user_id=.".$user['id'];
    $postQuery = "SELECT p.id, p.title, p.message, p.img_source, UNIX_TIMESTAMP(p.timestamp) AS timestamp, u.name, u.id AS user_id, c.name AS category FROM posts p "
        . "JOIN users u ON p.user_id = u.id "
        . "JOIN categories c ON p.category_id = c.id "
        . "WHERE user_id=".$user['id']." "
        . "ORDER BY timestamp DESC";
    
    $posts = sendSqlQuery($dbCon, $postQuery);
    
    $catQuery = "SELECT id, name FROM categories";
    
    $categories = sendSqlQuery($dbCon, $catQuery);
    
    $file_path = 'http://localhost/Post-it/images/';
    
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
        <!--Div zum Anzeigen oder Bearbeiten von User-Informationen-->
        <div id="user_profile" class="container_element">
            <h2 style="text-align: center;">Profil von <?php echo ucfirst($user['name']); ?></h2><br> 
            <!-- Wenn noch keine Einträge oder Bearbeiten geklickt wurde dann Formular zum Erstellen bzw. Bearbeiten der User-Informationen 
                ansonsten normale ausgabe der User-Informationen -->
            <?php if((!$user['firstname'] || !$user['lastname'] || !$user['interests'] || isset($_POST['btn_edit_info'])) && $_SESSION['userId'] == $user['id']){ ?>
                <form action="update_user.php" method="post">
                    <div style="float: left;margin-right: 20px;">
                        <label>Vorname: </label><br>
                        <input name="firstname" value="<?php echo $user['firstname']?>" >
                    </div>
                    <div style="">
                        <label>Nachname: </label><br>
                        <input name="lastname" value="<?php echo $user['lastname'];?>">
                    </div>
                    <div>
                        <label>Interessen: </label><br>
                        <textarea style="" name="interests" ><?php echo $user['interests'] ; ?></textarea>
                    </div>
                    <div>
                        <button type="submit" name="btn_update_user" value="update">Speichern</button>
                    </div>
                </form>
            <?php } else { ?>
                <p>Vorname: <?php echo $user['firstname']; ?></p><br>
                <p>Nachname: <?php echo $user['lastname']; ?></p><br>
                <p>Interessen: <?php echo $user['interests']; ?></p><br>
                <?php if($_SESSION['userId'] == $user['id']){ ?>
                <form action="user.php?userId=<?php echo $user['id']; ?>" method="post">
                    <button type="submit" name="btn_edit_info" value="<?php echo $user['id']; ?>">Bearbeiten</button>
                </form> 
                    
                <?php } ?>
            <?php } ?> 
        </div>
        <!--Div zum Anzeigen der Posts des Users. Einzelne Posts können bearbeitet oder gelöscht werden. Beim klick auf bearbeiten wird anstelle der 
            Postausgabe ein formular gerendert. -->
        <div class="container_element content">
            
            <?php while($post = $posts->fetch_assoc()){ ?>  
                
                <?php if(isset($_POST['btn_edit_post']) && $_POST['btn_edit_post'] == $post['id']){ ?>    
                    <form action="update_post.php" method="post">
                        <input class="title" name="title" value="<?php echo $post['title']; ?>" autocomplete="off"><br>
                        <textarea style="resize: none;width: 30%;" name="message"><?php echo $post['message']; ?></textarea><br>
                        <select name="category">
                            
                            <?php while($cat = $categories->fetch_assoc()){ ?>
                                <?php if($post['category'] == $cat['name']){ ?>
                                    <option value="<?php echo $cat['id']; ?>" selected><?php echo $cat['name']; ?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="btn_update_post" value="<?php echo $post['id']; ?>">Speichern</button>
                    </form> 
                    <?php if($post['img_source']){ ?>
                            <?php $src = $file_path.$post['img_source']; ?>
                            <a href="<?php echo $src; ?>"><img src="<?php echo $src; ?>" class="postImage"></a>
                    <?php } ?>
                <?php }else{ ?>
                <div class="post">
                    <p>
                        <span style="color: #596c25;"><?php echo ucfirst($post['name']); ?></span>
                        <span style="color: grey;">- <?php echo timeDiff($post['timestamp']);?> - <?php echo $post['category'];?></span>
                    </p>
                    <h2><?php echo $post['title'];?></h2>
                    <p class="post_message">
                        <?php echo $post['message'];?><br>
                        <?php if($post['img_source']){ ?>
                            <?php $src = $file_path.$post['img_source']; ?>
                            <a href="<?php echo $src; ?>"><img src="<?php echo $src; ?>" class="postImage"></a>
                        <?php } ?>
                    </p>
                    
                    <?php if($_SESSION['userId'] == $user['id']){ ?>
                        <form action="user.php?userId=<?php echo $user['id']; ?>" method="post">
                            <button type="submit" name="btn_edit_post" value="<?php echo $post['id']; ?>">Bearbeiten</button>
                            <button type="submit" name="btn_delete_post" value="<?php echo $post['id']; ?>">Löschen</button>
                        </form>
                    <?php } ?>
                </div>
                <?php } ?>
                <hr>
                
            <?php } ?>
        </div>
        
    </body>
</html>
