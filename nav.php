<nav>
    
        <a href="index.php">Sartseite</a><br>
        <?php if(isset($_SESSION['logged'])){ ?>
            <a href="user.php?userId=<?php echo $_SESSION['userId']; ?>">Mein Profil</a><br>
            <a href="logout.php">Logout</a><br>
        <?php }else{ ?>
            <a href="sign_up.php">Registrieren</a><br>
        <?php } ?>
   
</nav>