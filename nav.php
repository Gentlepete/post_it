<nav>
    <ul>
        <li><a href="index.php">Sartseite</a></li>
        <?php if(isset($_SESSION['logged'])){ ?>
            <li><a href="user.php?userId=<?php echo $_SESSION['userId']; ?>">Profil</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php }else{ ?>
        <li><a href="sign_up.php">Registrieren</a></li>
        <?php } ?>
    </ul>
</nav>