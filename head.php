
<!-- 
Formular zur Auswahl einer gesuchten Kategorie und eingeben eines Suchbegriffs
-->
<div id="search_form">
    <form action="index.php" method="get">
        <span id="category_list">
            <select name="category">
                <option value="" disabled selected>Kategorie auswählen</option>
                <option value="all" >-Alles-</option>
                <?php $_GET['category'] ? $chosenCat = $_GET['category'] : $chosenCat = NULL; ?> 
                <?php while($cat = $categories_search->fetch_assoc()){ ?>
                    <?php if($cat['id'] == $chosenCat){ ?>
                        <option value="<?php echo $cat['id']; ?>" selected><?php echo $cat['name']; ?></option>
                    <?php }else{?>
                      <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </span>
        <span id="search">
            <label>Suche: </label>
            <input type="text" name="search" autocomplete="off">
        </span>
        <span class="button">
            <button type="submit" name="btn_search">Suchen</button>
        </span>
    </form>
</div>
<!-- 
Formular zum Login eines Nutzers
-->
<?php if(!isset($_SESSION['logged'])){ ?>
<div id="login_form">    
    <form action="login.php" method="post">   
        <span id="login_name">
            <!--<label>Username: </label>-->
            <input name="username" placeholder="Username" required autofocus>
        </span>
        <span id="login_password">
            <!--<label>Passwort: </label>-->
            <input type="password" name="password" placeholder="Passwort" required>
        </span>
        <span style="" id="remember_me">
            <label>Login speichern? </label>
            <input type="checkbox" name="rememberMe" value="1">
        </span>
        <span class="button">
            <button type="submit" name="btn_send" value="login">Login</button>
        </span> 
    </form>
</div>
<?php }else{ ?>
<span style="float: right;">Eingeloggt als: <?php echo $_SESSION['username']; ?></span>
<?php } ?>


