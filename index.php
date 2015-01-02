<?php
session_start();
require_once 'functions.inc.php';

if(isset($_COOKIE['rememberMeToken']) && isset($_COOKIE['rememberMe']) && !isset($_SESSION['logged'])){
    if(doAutoLogin()){
        $_SESSION['notice'] = "eingeloggt";
    }
}



if(isset($_GET['btn_search'])){ 
    $search = $_GET['search'];
    if(isset($_GET['category'])){
        $cat_id = $_GET['category'];
        
        $postQuery = "SELECT p.title, p.message, UNIX_TIMESTAMP(p.timestamp) AS timestamp, u.name, u.id AS user_id, c.name AS category FROM posts p "
            . "JOIN users u ON p.user_id = u.id "
            . "JOIN categories c ON p.category_id = c.id "
            . "WHERE p.category_id=$cat_id AND (p.title LIKE '%$search%' OR p.message LIKE '%$search%') "
            . "ORDER BY p.timestamp DESC";
    }else{
        $postQuery = "SELECT p.title, p.message, UNIX_TIMESTAMP(p.timestamp) AS timestamp, u.name, u.id AS user_id, c.name AS category FROM posts p "
            . "JOIN users u ON p.user_id = u.id "
            . "JOIN categories c ON p.category_id = c.id "
            . "WHERE p.title LIKE '%$search%' OR p.message LIKE '%$search%' "
            . "ORDER BY timestamp DESC";
    }
}else{

    $postQuery = "SELECT p.title, p.message, UNIX_TIMESTAMP(p.timestamp) AS timestamp, u.name, u.id AS user_id, c.name AS category FROM posts p "
            . "JOIN users u ON p.user_id = u.id "
            . "JOIN categories c ON p.category_id = c.id "
            . "ORDER BY timestamp DESC";
}
$dbCon = getDbConnection('post_it');

$catQuery = "SELECT id, name FROM categories";

$categories_search = sendSqlQuery($dbCon, $catQuery);
$categories = sendSqlQuery($dbCon, $catQuery);

$postResult = sendSqlQuery($dbCon, $postQuery);


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
        <title>Post-it</title>
    </head>
    <body>
        <?php include_once 'flash_messages.php'; ?>
        <div id="navigation" class="container_element">
            <?php include_once 'nav.php'; ?>
        </div>
        <div id="head_menu" class="container_element">
            <?php include_once 'head.php'; ?>
            
        </div>
        <div id="content" class="container_element">
            
            <div style="text-align: center;" class="container_element">
                <?php if(isset($_SESSION['logged'])){ ?>
                <form action="create_post.php" method="post">
                    <input name="title" placeholder="Titel"><br>
                    <textarea style="resize: none;width: 30%;" name="message" placeholder="Gib hier Deine Nachricht ein..."></textarea><br>
                    
                    <select name="category">
                        <option value="" disabled selected>Kategorie auswählen</option>
                        <?php while($cat = $categories->fetch_assoc()){ ?>
                           <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['name']; ?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" name="btn_post" value="pressed">Posten</button>
                </form>
                <?php } ?>
            </div>
            <hr>
            <?php while($post = $postResult->fetch_assoc()){ ?>
            
            <div>
                <p>
                    <a href="user.php?userId=<?php echo $post['user_id']; ?>"><?php echo $post['name']; ?></a>
                    <span class="timeDiff">- <?php echo timeDiff($post['timestamp']);?></span>
                </p>
                <p>
                    <?php echo $post['title'];?>
                    (<?php echo $post['category'];?>)
                </p>
                <p><?php echo $post['message'];?></p>
            </div>
            <hr>
            
            <?php } ?>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
