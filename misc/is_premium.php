



<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    $user = exec('whoami');
    $app = "/home" ."/". $user . "/.local/pipx/venvs/qobuz-dl/bin/python /home/".$user."/.local/pipx/venvs/qobuz-dl/bin/qobuz-dl";


    putenv('HOME=/home/'.$user);
    $cmd = 'dl https://www.qobuz.com/en-us/album/0 2>&1';
    exec($app. ' ' .$cmd, $output);



    $isPremium = array(false, 'none');
    
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