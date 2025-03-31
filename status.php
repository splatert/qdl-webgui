



<?php
    function getStatusMessage($relTitle) {

        $user = exec('whoami');
        $cfg_path = "/home/".$user.'/.config/qobuz-dl/config.ini';
        $config = file($cfg_path, FILE_IGNORE_NEW_LINES);
        
        if (isset($config[3])) {
            $dlPath = explode('default_folder = ', $config[3]);

            if (isset($dlPath[1])) {

                // fix directory, filter out illegal characters, replace spaces with dashes
                $path = trim($dlPath[1]);
                $relTitle = preg_replace("/&(amp;)+/", '', $relTitle);
                $relTitle = preg_replace("/[^A-Za-z0-9]/", '', $relTitle);

                $path = trim($dlPath[1]) . '/' . trim($relTitle);

                if (file_exists($path . '.log')) {

                    $file = file_get_contents($path . '.log');
                    $file = trim(preg_replace('/[\r\n]+/', '\n', $file));

                    $file = explode('\n', $file);

                    $lastline = $file[count($file)-1];
                    if (isset($lastline)) {
                        echo strip_tags($lastline);
                    }
                    else {
                        echo '';
                    }
                }
                else {
                    echo '[Log file does not exist.]';
                    echo '<br> <b>NULL: </b>'.$path . '.log';
                }



            }
        }
        

    }


    if (isset($_GET['rel'])) {
        getStatusMessage($_GET['rel']);
    }

?>