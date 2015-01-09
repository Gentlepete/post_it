<?php
session_start();
require_once 'functions.inc.php';

// Dieser Block wird ausgeführt sobald das SignUp Formular abgeschickt wird
if(isset($_POST['btn_send'])){
    // Wenn Funktion zum Validieren ein True zurückgibt, wird der if-Block ausgeführt
    if(validateForm()){
        // Datenbankverbindung öffnen
        $dbCon = getDbConnection('post_it');
        
        // Wenn die angegeben Nutzerdaten (name und email) noch nicht in der Db vorhanden sind,
        // wird der If-Block ausgeführt
        if(isUserNotInDb($dbCon)){
               
                // angegebens Passwort wird mit der Funktion password_hash verschlüsselt
               $password = password_hash($dbCon->real_escape_string(htmlspecialchars($_POST['password'])), PASSWORD_DEFAULT);
               
               $name = $dbCon->real_escape_string(htmlspecialchars($_POST['username']));
               $email = $dbCon->real_escape_string(htmlspecialchars($_POST['email']));
               
               $sqlQuery = "INSERT users (name, email,avatar_src , password)"
                       . " VALUES ('$name', '$email', 'default_app_avatar.png' ,'$password')";
               
               if(sendSqlQuery($dbCon, $sqlQuery)){
                   sendWelcomeMail($email);
                   $_SESSION['notice'] = "<p style='color:green'>User erfolgreich angelegt!</p>";
               }
        }else{
            $_SESSION['error'] = "<p style='color:red'>Benutzername und/oder E-Mail bereits vergeben!</p>";
        }
    }
    
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/JavaScript" src="application.js"></script>
        <title>Registrieren</title>
    </head>
    <body>
        <?php include_once 'flash_messages.php'; ?>
        <?php include_once 'nav.php'; ?> 
        <div id="head_menu" class="container_element">
            <h2>Registrierung</h2>
        </div>
        <div class="container_element content">
            <form action="sign_up.php" method="post">
                <fieldset>
                    <legend >Bitte füllen Sie alle Formularfelder aus:</legend>
                    <p>
                        <label>Username:</label><br>
                        <input name="username" required>
                    </p>
                    <p>
                        <label>E-Mail:</label><br>
                        <input name="email" required>
                    </p>
                    <p>
                        <label>Passwort:</label><br>
                        <input type="password" name="password" required>
                    </p>
                    <p>
                        <label>Passwort wiederholen:</label><br>
                        <input type="password" name="password2" required>
                    </p>
                    <button type="submit" name="btn_send" value="sign_up">Registrieren</button>
                </fieldset>
            </form>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
