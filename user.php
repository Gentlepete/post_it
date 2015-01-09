<?php
session_start();
require_once 'functions.inc.php';

if(isset($_SESSION['logged'])){
    $dbCon = getDbConnection('post_it');
    $userId = $_GET['userId'];
    $userQuery = "SELECT id, name, firstname, lastname, interests, avatar_src FROM users "
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
    
    $postResult = sendSqlQuery($dbCon, $postQuery);
    
    $catQuery = "SELECT id, name FROM categories";
    
    $categories = sendSqlQuery($dbCon, $catQuery);
    
    $images_path = 'http://localhost/Post-it/images/';
    $avatars_path = "http://localhost/Post-it/avatars/";
    
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/JavaScript" src="application.js"></script>
        <title>Profil von <?php echo $user['name'] ; ?></title>
    </head>
    <body>
        <?php include_once 'flash_messages.php'; ?>
        <?php include_once 'nav.php'; ?>
        <!--Div zum Anzeigen oder Bearbeiten von User-Informationen-->
        <div id="user_profile" class="container_element">
            <div style="float:left;position: relative;">
                <h2 ><?php echo ucfirst($user['name']); ?></h2>
                <a href="<?php echo $avatars_path.$user['avatar_src']; ?>">
                    <img src="<?php echo $avatars_path.$user['avatar_src']; ?>" width="110px">
                </a>
            </div>
            
            <!-- Wenn noch keine Einträge oder Bearbeiten geklickt wurde dann Formular zum Erstellen bzw. Bearbeiten der User-Informationen 
                ansonsten normale ausgabe der User-Informationen -->
            <div style="margin-left: 30%;">
                <?php if((!$user['firstname'] || !$user['lastname'] || !$user['interests'] || isset($_POST['btn_edit_info'])) && $_SESSION['userId'] == $user['id']){ ?>
                    <form  action="update_user.php" method="post" enctype="multipart/form-data">
                        <div>
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
                            <label>Avatar ändern: </label>
                            <!--<input type="hidden" name="MAX_FILE_SIZE" value="500000" />-->
                            <input type="file" name="avatarImg" id="img" accept="image/*" ><br>
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
        </div>
        <!--Div zum Anzeigen der Posts des Users. Einzelne Posts können bearbeitet oder gelöscht werden. Beim klick auf bearbeiten wird anstelle der 
            Postausgabe ein formular gerendert. -->
        <div class="container_element content" style="margin-top: 40px;">
            <?php if($postResult->num_rows == 0){ ?>
                <h3>Keine Einträge vorhanden</h3>  
            <?php }else{ ?>
                <?php while($post = $postResult->fetch_assoc()){ ?>  
                    <div id="<?php echo $post['id']; ?>" class="post">
                        <?php if(isset($_POST['btn_edit_post']) && $_POST['btn_edit_post'] == $post['id']){ ?>    
                            <form action="update_post.php" method="post">
                                <input class="title" name="title" value="<?php echo $post['title']; ?>" autocomplete="off"><br>
                                <textarea class="textarea_post" style="resize: none;width: 30%;" name="message"><?php echo $post['message']; ?></textarea><br>
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
                                    <?php $src = $images_path.$post['img_source']; ?>
                                    <a href="<?php echo $src; ?>"><img src="<?php echo $src; ?>" class="postImage"></a>
                            <?php } ?>
                        <?php }else{ ?>
                                
                            <p>
                                <span style="color: #596c25;"><?php echo ucfirst($post['name']); ?></span>
                                <span style="color: grey;">- <?php echo timeDiff($post['timestamp']);?> - <?php echo $post['category'];?></span>
                            </p>
                            <div style="clear: both;"></div>
                            <div class="post_content">
                                <h2><?php echo $post['title'];?></h2>
                                <p class="post_message">
                                    <?php echo $post['message'];?><br>
                                    <?php if($post['img_source']){ ?>
                                        <?php $src = $images_path.$post['img_source']; ?>
                                        <a href="<?php echo $src; ?>"><img src="<?php echo $src; ?>" class="postImage"></a>
                                    <?php } ?>
                                </p>
                            </div>
                            <?php if($_SESSION['userId'] == $user['id']){ ?>
                            <form style="float: left;" action="user.php?userId=<?php echo $_SESSION['userId']."#".$post['id']; ?>" method="post">
                                    <button type="submit" name="btn_edit_post" value="<?php echo $post['id']; ?>">Bearbeiten</button>
                                </form>
                                <form action="delete_post.php" method="post">
                                    <button type="submit" name="btn_delete_post" value="<?php echo $post['id']; ?>">Löschen</button>
                                </form>
                            <?php } ?>

                        <?php } ?>
                        <?php $commentsQuery = "SELECT c.id, c.message, c.user_id, UNIX_TIMESTAMP(c.timestamp) AS timestamp, u.name AS username, u.avatar_src AS user_avatar FROM comments c "
                                    . "JOIN posts p ON c.post_id = p.id "
                                    . "JOIN users u ON c.user_id = u.id "
                                    . "WHERE c.post_id = ".$post['id'] ; ?>
                        <?php $commentsResult = sendSqlQuery($dbCon, $commentsQuery); ?>
                        <ul style="margin-top: 10px;">
                            <hr style="color: #e2ecc5;">
                            <?php if($commentsResult->num_rows > 0){ ?>
                                <input class="comments_link" type="button" value="<?php echo $commentsResult->num_rows; ?> Kommentar(e)">
                                <div class="comments">  
                                    <?php while($comment = $commentsResult->fetch_assoc()){ ?>
                                       <li>
                                           <div  style="position: relative;">
                                                <a href="user.php?userId=<?php echo $comment['user_id']; ?>">
                                                    <img class="avatar" src="<?php echo $avatars_path.$comment['user_avatar'];?>" width="60px" >
                                                    <?php echo ucfirst($comment['username']); ?>
                                                </a>
                                                <span class="timeDiff" title="<?php echo date('d.m.Y H:i:s', $comment['timestamp']);?>">
                                                     - <?php echo timeDiff($comment['timestamp']);?>
                                                </span> 
                                            </div>
                                            <div>
                                                <?php echo $comment['message'];?>
                                            </div> 
                                            <div style="clear: both;"></div>
                                       </li> 
                                    <?php } ?>   
                                </div>
                            <?php } ?>
                            <form action="comment.php" method="post">
                                <input name="post_id" value="<?php echo $post['id']; ?>" hidden>
                                <input class="comment" name="comment" placeholder="Kommentieren">
                                <input type="submit" name="btn_comment" hidden>
                            </form> 
                        </ul>
                    </div>
                    <hr>

                <?php } ?>
            <?php } ?>
        </div>
        
    </body>
</html>
