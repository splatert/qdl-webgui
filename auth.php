



<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include_once('cfgfile.php');
    include_once('global.php');


    $qlty = 0;
    $dlFol = '';
    $email = null;
    $pwd = null;


    if (isset($_POST['audio-quality'])) {
        $qlty = $_POST['audio-quality'];
    }
    else {
        $qlty = 6;
    }

    $cfgFile = new ConfigFile();

    if (isset($_POST['dl-folder']) && $_POST['dl-folder'] != '') {
        if (!str_starts_with($_POST['dl-folder'], '/')) {
            $new = "/home/{$cfgFile->getUser()}/" . $_POST['dl-folder'];
            $_POST['dl-folder'] = $new;
            unset($new);
        }
        $dlFol = $_POST['dl-folder'];
    }
    else {
        $suggest = $cfgFile->suggestDlLocation();

        if (isset($suggest) && $suggest != '') {
            $dlFol = $suggest;
        }
    }


    if (!isset($_POST['auth-email']) || (isset($_POST['auth-email']) && $_POST['auth-email'] == '')) {
        die('email address is required.');
    }
    else {
        $email = $_POST['auth-email'];
    }


    if (!isset($_POST['auth-pwd']) || (isset($_POST['auth-pwd']) && $_POST['auth-pwd'] == '')) {
        die('password is required.');
    }
    else {
        $pwd = $_POST['auth-pwd'];
    }

    $app = new Qobuz_DL;
    $py = $app->getPythonPath();
    
    putenv("HOME=/home/{$app->getUser()}");
    exec("{$py} ./scripts/newcfg.py '{$email}' '{$pwd}' '{$dlFol}' {$qlty} 2>&1", $output);
    
    if (count($output) != 0) {
        print_r($output);
        die();
    }
    else {
        header('Location: setup.php?step=4&account-link-success');
    }


?>