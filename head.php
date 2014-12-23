<!-- 
Formular zur Auswahl einer gesuchten Kategorie und eingeben eines Suchbegriffs
-->
<form style="float: left;" action="index.php" method="get">
    
        
        <span id="category_list">
            <select name="category">
                <option value="" disabled selected>Kategorie auswählen</option>
                <option value="politik">Politik</option>
                <option value="wissenschaft">Wissenschaft</option>
                <option value="fun">Fun</option>
                <option value="unnuetzes wissen">Unnützes Wissen</option>
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
<form action="login.php" method="post">
    
        
        <span id="login_name">
            <label>Username: </label>
            <input name="username">
        </span>
        <span id="login_password">
            <label>Passwort: </label>
            <input type="password" name="password">
        </span>
        <span class="button">
            <button type="submit" name="btn_login">Login</button>
        </span>
   
</form>

