
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
        <div style="display: inline-block;">
            <span id="search">
                <input type="text" name="search" placeholder="Suchwort eingeben" autocomplete="off">
            </span>
            <span class="button">
                <button type="submit" name="btn_search">Suchen</button>
            </span>
        </div>
    </form>
</div>


