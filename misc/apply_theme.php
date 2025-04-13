



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);


    if (isset($_GET['theme'])) {

        if ($_GET['theme'] != 'classic') {
            setcookie("theme", $_GET['theme'], time() + 31556926, "/");            
        }
        else {
            if (isset($_COOKIE['theme'])) {
                setcookie("theme", $_GET['theme'], time() - 31556926, "/");
            }
        }

        if (isset($_GET['ret'])) {
            header('Location: ' .$_GET['ret']);
        }
        else {
            header('Location: /');
        }
    }

?>