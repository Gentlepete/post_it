<?php
session_start();
require_once 'functions.inc.php';

if(isset($_COOKIE['rememberMeToken']) && isset($_COOKIE['rememberMe']) && !isset($_SESSION['logged'])){
    if(doAutoLogin()){
        $_SESSION['notice'] = "eingeloggt";
    }
}

$postQuery = "SELECT p.id, p.title, p.message, p.img_source, UNIX_TIMESTAMP(p.timestamp) AS timestamp, u.name, u.id AS user_id, u.avatar_src AS user_avatar, c.name AS category FROM posts p "
            . "JOIN users u ON p.user_id = u.id "
            . "JOIN categories c ON p.category_id = c.id ";

if(isset($_GET['btn_search'])){ 
    $search = $_GET['search'];
    if(isset($_GET['category']) && $_GET['category'] != "all"){
        $cat_id = $_GET['category'];
        $postQuery .= "WHERE p.category_id=$cat_id AND (p.title LIKE '%$search%' OR p.message LIKE '%$search%') ";
    }else{
        $postQuery .= "WHERE p.title LIKE '%$search%' OR p.message LIKE '%$search%' ";    
    }
}

$postQuery .= "ORDER BY timestamp DESC";
$dbCon = getDbConnection('post_it');

$catQuery = "SELECT id, name FROM categories";

$categories_search = sendSqlQuery($dbCon, $catQuery);
$categories = sendSqlQuery($dbCon, $catQuery);

$postResult = sendSqlQuery($dbCon, $postQuery);
$image_path = 'http://localhost/Post-it/images/';
$avatars_path = 'http://localhost/Post-it/avatars/';

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
        <link rel="stylesheet" href="style.css" type="text/css" media="screen">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/JavaScript" src="application.js"></script> 
        <title>Post-it</title>
    </head>
    <body>
        
        <?php include_once 'flash_messages.php'; ?>
        <?php include_once 'nav.php'; ?>
        <div id="head_menu" class="container_element">
            <?php include_once 'head.php'; ?>
            
        </div>
        <div class="container_element content"> 
            
            <!----------- Wenn ein User eingeloggt ist wird ihm hier das Post-Formular angezeigt ------->
            
                        <?php if(isset($_SESSION['logged'])){ ?>
                            <div class="container_element" id="post_form_div">
                                <form action="create_post.php" method="post" enctype="multipart/form-data">
                                    <input class="title" name="title" placeholder="Titel" autocomplete="off" required><br>
                                    <textarea class="textarea_post" name="message" placeholder="Gib hier Deine Nachricht ein..." required></textarea><br>
                                    <label>Bild Hochladen: </label>
                                    <!--<input type="hidden" name="MAX_FILE_SIZE" value="500000" />-->
                                    <input type="file" name="postImg" id="img" accept="image/*" ><br>
                                    <select name="category" required>
                                        <option value="no_category" disabled selected style="display: none;">Kategorie auswählen</option>
                                        <?php while($cat = $categories->fetch_assoc()){ ?>
                                           <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" name="btn_post" value="pressed">Posten</button>
                                </form>
                            </div>

                        <?php } ?>
            <!----------- Wenn Posts vorhanden sind werden Sie hier mit Hilfe einer Whileschleife alle untereinander angezeigt --------->
                    <?php if($postResult->num_rows == 0){ ?>
                        <hr>
                        <h3>Keine Einträge vorhanden</h3>  
                    <?php }else{ ?>
                    <?php while($post = $postResult->fetch_assoc()){ ?>

                    <div id="<?php echo $post['id']; ?>" class="post">
                        <hr>
                        <div style="position: relative;">
                            <a href="user.php?userId=<?php echo $post['user_id']; ?>">
                                <img class="avatar" src="<?php echo $avatars_path.$post['user_avatar'];?>" width="75px" >
                                <?php echo ucfirst($post['name']); ?>
                            </a>
                            <span class="timeDiff" title="<?php echo date('d.m.Y H:i:s', $post['timestamp']);?>">- <?php echo timeDiff($post['timestamp']);?> - <?php echo $post['category'];?> </span>
                        </div>
                        <div style="clear: both;"></div>
                        <div class="post_content">
                            <h2 class="post_title"><?php echo $post['title'];?></h2>
                            <p>
                                <?php echo $post['message'];?><br>
                                <?php if($post['img_source']){ ?>
                                    <?php $src = $image_path.$post['img_source']; ?>
                                <a href="<?php echo $src; ?>"><img src="<?php echo $src; ?>" class="postImage"></a>
                                <?php } ?>
                            </p>
                            <?php $commentsQuery = "SELECT c.id, c.message, c.user_id, UNIX_TIMESTAMP(c.timestamp) AS timestamp, u.name AS username, u.avatar_src AS user_avatar FROM comments c "
                                    . "JOIN posts p ON c.post_id = p.id "
                                    . "JOIN users u ON c.user_id = u.id "
                                    . "WHERE c.post_id = ".$post['id'] ; ?>
                            <?php $commentsResult = sendSqlQuery($dbCon, $commentsQuery); ?>
                            <ul style="margin-top: 10px;">

                                <?php if($commentsResult->num_rows > 0){ ?>
                                    <hr style="color: #e2ecc5;">
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
                                <?php if(isset($_SESSION['logged'])){ ?>

                                    <form action="comment.php" method="post">
                                        <input name="post_id" value="<?php echo $post['id']; ?>" hidden>
                                        <input class="comment" name="comment" placeholder="Kommentieren">
                                        <input type="submit" name="btn_comment" hidden>
                                    </form> 
                                <?php } ?>  

                            </ul>
                        </div>
                    </div>


                    <?php } ?>

            <?php } ?>
        </div>
    </body>
</html>
