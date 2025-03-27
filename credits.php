



<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include('lib/simplehtmldom/simple_html_dom.php');
    require_once('topbar.php');
?>



<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="page">
        
            <?php
            
                if (isset($_GET['t'])) {
                    echo '<h3>'.$_GET['t'].'</h3>';
                }
            
                if (isset($_GET['c'])) {
                    echo '<p>'.$_GET['c'].'</p>';
                }

            ?>

        </div>
    </body>

</html>