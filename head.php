
<!-- 
Formular zur Auswahl einer gesuchten Kategorie und eingeben eines Suchbegriffs
-->
<form style="float: left;" action="index.php" method="get">
    <span id="category_list">
        <select name="category">
            <option value="" disabled selected>Kategorie auswählen</option>
            <?php while($cat = $categories_search->fetch_assoc()){ ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
            <?php } ?>
        </select>
    </span>
    <span id="search">
        <label>Suche: </label>
        <input type="text" name="search">
    </span>
    <span class="button">
        <button type="submit" name="btn_search">Suchen</button>
    </span>
</form>
<!-- 
Formular zum Login eines Nutzers
-->
<?php if(!isset($_SESSION['logged'])){ ?>
    
<form action="login.php" method="post">   
    <span id="login_name">
        <label>Username: </label>
        <input name="username" required>
    </span>
    <span id="login_password">
        <label>Passwort: </label>
        <input type="password" name="password" required>
    </span>
    
    <span class="button">
        <button type="submit" name="btn_send" value="login">Login</button>
    </span> 
    <span style="float: right;margin-right: 150px;" id="remember_me">
        <label>Login speichern? </label>
        <input type="checkbox" name="rememberMe" value="1">
    </span>
</form>

<?php } ?>

