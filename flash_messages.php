<?php if(isset($_SESSION['error'])){ ?>
    <div id="error" class="container_element flash">
        <?php
        echo $_SESSION['error']; 
        unset($_SESSION['error']);
        ?>
    </div>
<?php } ?>
<?php if(isset($_SESSION['notice'])){ ?>
    <div id="notice" class="container_element flash">
        <?php 
        echo $_SESSION['notice']; 
        unset($_SESSION['notice']);
        ?>
    </div>
<?php } 