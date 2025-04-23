



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include_once('../global.php');


    if (isset($_GET['theme'])) {

        if ($_GET['theme'] != 'classic') {
            editCookie('theme', $_GET['theme']);         
        }
        else {
            if (isset($_COOKIE['theme'])) {
                editCookie('theme', null);   
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