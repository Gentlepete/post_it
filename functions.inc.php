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

function closeDbConnection(&$ref_dbCon)
{
    $ref_dbCon->close();
    return;
}

function sendSqlQuery(&$ref_dbCon, &$ref_sqlQuery)
{
    $result = $ref_dbCon->query($ref_sqlQuery);
    if(!$result){
        die("<p style='color:red;'>Fehler beim Query:<br>"
                . "$ref_dbCon->error</p></body></html>");
    }
    return $result;
}

function isFormComplete()
{
    foreach($_POST as $v)
    {
        // mit der funktion trim entfernen wir alle whitespaces am anfang und 
        // am ende eines strings 
        $v = trim($v);
        if(empty($v))
        {
            return false;
        }
    }
    return true;
}

function isMailValid($mail){
    
    if(preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $mail)){
        return true;
    }else {
        return false;
    }
}

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