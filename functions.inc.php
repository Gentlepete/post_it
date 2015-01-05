<?php


// Funktion zum Erstellen der Datenbankverbindung
function getDbConnection($dbName, $dbHost='localhost', $dbUser='root', $dbPw = NULL){
    $dbCon = @new mysqli($dbHost, $dbUser, $dbPw, $dbName) or die();
    if($dbCon->connect_errno){
        die("<p> style='color:red;'>Fehler bei der Datenbankverbindung:<br>"
                . "$dbCon->connect_error ($dbCon->connect_errno)</p></body></html>");
    }
    return $dbCon;
}

// Schließen der Datenbankverbindung
function closeDbConnection(&$ref_dbCon)
{
    $ref_dbCon->close();
    return;
}


// Abschicken eines SQl-Queries
function sendSqlQuery(&$ref_dbCon, &$ref_sqlQuery)
{
    $result = $ref_dbCon->query($ref_sqlQuery);
    if(!$result){
        die("<p style='color:red;'>Fehler beim Query:<br>"
                . "$ref_dbCon->error</p></body></html>");
    }
    return $result;
}


// Validierung des gesamten Registrierungsformulars
function validateForm(){
    if(isFormComplete()){
        if(isMailValid(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING))){
            if(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) == filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING)){
                return true;
            }else{
                $_SESSION['error'] = "Die Passwörter stimmen nicht überein!";
                return false;
            }
        }else{
            $_SESSION['error'] = "Bitte gib eine gültige E-Mail ein!";
            return false;
        }
    }else{
        $_SESSION['error'] = "Bitte fülle Das Formular komplett aus!";
        return false;
    }
}


// Überprüfen ob in alle Formularfelder etwas eingtragen wurde
function isFormComplete()
{
    foreach($_POST as $v)
    {
        // mit der funktion trim entfernen wir alle whitespaces am anfang und 
        // am ende eines strings 
        $v = trim($v);
        if(empty($v) || $v == 'default')
        {
            return false;
        }
        
    }
    return true;
}


// Überprüfen ob eine valide E-Mail Adresse bei der Registration eingegeben wurde
function isMailValid($mail){
                 
    if(preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $mail)){
        return true;
    }else {
        return false;
    }
}


// Überprüfen ob ein username oder email schon in der Datenbank vorhanden ist
//, wenn ja, dann keine registrierung möglich
function isUserNotInDb(&$ref_dbCon)
{
    $sqlQuery = "SELECT id FROM users WHERE name = '".$_POST['username']."'"
            . "OR email = '".$_POST['email']."'";
    
    $result = sendSqlQuery($ref_dbCon, $sqlQuery);
    
    if($result->num_rows > 0){
        return false;
    }
    
    return true;
}


// Funktion zum Absenden der "Willkommen"-EMail an den gerade registrierten Nutzer
function sendWelcomeMail($mail){
            $betreff = "Willkommen bei Post-it";
            $empfaenger = $mail;
            $absender = 'FROM: info@post-it.de';
            $nachricht = "Herzlich Willkommen bei Post-it.\n\n"
                    . "Post-it ist eine Online Posting-Plattform, auf der die Nutzer Beiträge zu verschiedenen\n"
                    . "Themen erstellen können. Diese Beiträge können von allen anderen gesehen werden.\n"
                    . "Viel Spaß beim Posten.\n\n"
                    . "Dein Post-it Team";
            
            
            if(mail($empfaenger, $betreff, $nachricht, $absender)){
                return true;
            }else{
                return false;
            }
}


// Funktion für den Usr-Login
function doUserLogin(&$ref_dbCon)
{
    
    $name = $ref_dbCon->real_escape_string(htmlspecialchars($_POST['username']));
    
    $sqlQuery = "SELECT id FROM users WHERE name = '$name'";
    
    $result = sendSqlQuery($ref_dbCon, $sqlQuery);
    
    if($result->num_rows == 0){
        closeDbConnection($ref_dbCon);
        return false;
    }
    
    $dsatz = $result->fetch_assoc();
    $usrId = $dsatz['id'];
    
    $userData = getUserData($ref_dbCon, $usrId);
    
    closeDbConnection($ref_dbCon);
    
    if(!password_verify($_POST['password'], $userData['password'])){
        return false;
    }
    
    if(isset($_POST['rememberMe'])){
        createUserLoginCookies($userData);
    }
  
    if(loginUser($usrId, $userData)){
        return true;   
    }
    
}

// Funktion zum abrufen der Nutzerdaten
function getUserData(&$ref_dbCon, &$ref_userId)
{
    $sqlQuery = "SELECT id, name, email, password FROM users WHERE id = $ref_userId";
    $result = sendSqlQuery($ref_dbCon, $sqlQuery);
    $userData = $result->fetch_assoc();
    return $userData;
}


// Erzeugen der Login-Cookies
function createUserLoginCookies(&$ref_userData){
    // cookie mit namen rememberme speichern
    $userId = base64_encode($ref_userData['id']);
    $time = time() + 3600*12; // 12 stunden
    setcookie('rememberMe', $userId, $time);
    
    $hash = getUsrHash($ref_userData);
    setcookie('rememberMeToken', password_hash($hash, PASSWORD_DEFAULT), $time);
    
    return;
}

// Erzeugen eines Hashes aus username, email, password und salt_hash
function getUsrHash(&$ref_userData)
{
    $salt = '8k!_~Y';
    
    $hash = substr($ref_userData['name'], 0, 3)."|".substr($ref_userData['email'], 0, 3)."!"
            . substr($ref_userData['password'], 0, 3)."&$salt";
    
    return $hash;
}

// automatischer Login mit Hilfe der Login-Cookies
function doAutoLogin(){
    $dbCon = getDbConnection('post_it');
    //umwandlung der verschlüsselten id in klartext
    $usrId = base64_decode($_COOKIE['rememberMe']);
    $userData = getUserData($dbCon, $usrId);
    closeDbConnection($dbCon);
    
    $hash = getUsrHash($userData);
    
    
    if(password_verify($hash, $_COOKIE['rememberMeToken'])){
        if(loginUser($usrId, $userData)){
        return true;
        }
    }
    return false;
}

// Speichern der User-attribute in der Session
function loginUser(&$ref_userId, &$ref_userData)
{
    $_SESSION['logged'] = 1;
    $_SESSION['username'] = $ref_userData['name'];
    $_SESSION['userId'] = $ref_userId;
    
    return true;
}

function timeDiff($timestamp){
    
    $now = time();
    $diff = $now - $timestamp;
    $string = NULL;
    
    switch (true){
    
//      Wenn der Post weniger als eine Minute her ist.
        case $diff < 60:
            $string = "Vor weniger als einer Minute";
            break;
//      Wenn der Post weniger als eine Stunde her ist.
        case $diff < 3600:
            $string = "Vor ".(floor($diff / 60))." Minute(n)";        
            break;
//      Wenn der Post zwischen 1 Stunde und 24 Stunden her ist
        case $diff < 86400:
            $string = "Vor ".(floor($diff / 3600))." Stunde(n)";   
            break;
//      Wenn der Post zwischen 1 Tag und 7 Tagen her ist
        case $diff < 604800:
            $string = "Vor ".(floor($diff / 86400) + 1)." Tag(en)";
            break;
//      Wenn der Post länger als 7 tage her ist
        case $diff >= 604800:
            $string = " ".date('d.m.Y H:i:s', $timestamp);
            break;
    }
    
    return $string;
}

function fileSizeOkay($size){
    if($size < 500000){
        return true;
    }else{
        return false;
    }
        
}