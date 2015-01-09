
<div id="nav_toggle" class="container_element">
    <h3>NAV</h3>
</div>
<div id="navigation" class="container_element">
    <nav>

            <a href="index.php">Sartseite</a><br>
            <?php if(isset($_SESSION['logged'])){ ?>
                <a href="user.php?userId=<?php echo $_SESSION['userId']; ?>">Mein Profil</a><br>
                <a href="logout.php">Logout</a><br>
            <?php }else{ ?>
                <a href="sign_up.php">Registrieren</a><br>
            <?php } ?>

    </nav>
</div>
<!-- 
Formular zum Login eines Nutzers
-->
<?php if(!isset($_SESSION['logged'])){ ?>
<div id="login_toggle" class="container_element">
    <h3>LOGIN</h3> 
</div>
<div id="login_form" class="container_element">  
    <h3>Login</h3><br>
    <form action="login.php" method="post">   
        <span id="login_name">
            <!--<label>Username: </label>-->
            <input name="username" placeholder="Username" required autofocus>
        </span><br><br>
        <span id="login_password">
            <!--<label>Passwort: </label>-->
            <input type="password" name="password" placeholder="Passwort" required>
        </span><br>
        <span style="" id="remember_me">
            <label>Login speichern? </label>
            <input type="checkbox" name="rememberMe" value="1">
        </span><br>
        <span class="button">
            <button type="submit" name="btn_send" value="login">Login</button>
        </span> 
    </form>
</div>

<?php }?>
