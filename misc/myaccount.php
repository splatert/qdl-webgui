



<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);


    include_once('../cfgfile.php');
    include_once('../global.php');

    $cfgfile = new ConfigFile();
    $email = $cfgfile->getProperty('email');

    if (isset($email)) {
        $first_four_letters = mb_substr($email, 0, 4);
        $domain = explode('@', $email);

        if (isset($domain[1])) {
            $email = $first_four_letters . '****@' . $domain[1];
        }

    }


    $user = exec('whoami');
    $app = "/home" ."/". $user . "/.local/pipx/venvs/qobuz-dl/bin/python /home/".$user."/.local/bin/qobuz-dl";


    $app = new Qobuz_DL;
    $app->detectApp();
    $appLink = $app->getAppLink();
    putenv("HOME=/home/{$app->getUser()}");

    $cmd = 'dl https://www.qobuz.com/en-us/album/0 2>&1';
    exec($appLink. ' ' .$cmd, $output);

    $isPremium = array(false, 'none', null);
    if (isset($email)) {
        $isPremium[2] = $email;
    }
    
    if (isset($output[2]) && str_starts_with($output[2], 'Membership: ')) {
        $membership = explode('Membership: ', $output[2]);
        if (isset($membership[1])) {
            if ($membership[1] == 'Studio' || $membership[1] == 'Sublime') {
                $isPremium[0] = true;
                $isPremium[1] = $membership[1];
            }
        }
    }
    echo json_encode($isPremium);




?>