



<?php

    function getStatusMessage($relTitle) {

        $user = exec('whoami');
        include_once('cfgfile.php');
        
        $config = new ConfigFile();
        $dlpath = $config->getProperty('default_folder');


        if (isset($dlpath)) {

            // fix directory, filter out illegal characters, replace spaces with dashes
            $relTitle = preg_replace("/&(amp;)+/", '', $relTitle);
            $relTitle = preg_replace("/[^A-Za-z0-9]/", '', $relTitle);

            $path = $dlpath . '/' . trim($relTitle);

            if (file_exists($path . '.log')) {

                $file = file_get_contents($path . '.log');
                $file = trim(preg_replace('/[\r\n]+/', '\n', $file));
                $file = explode('\n', $file);

                $lastline = $file[count($file)-1];
                if (isset($lastline)) {

                    $str = strip_tags($lastline);

                    $reg = '/(\d*\.?\dM)|( \/\/\/ )|(\d*\.?\d*.tmp)/m';
                    preg_match_all($reg, $str, $out);

                    if (isset($out)) {
                        echo json_encode($out);
                    }
                    
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


    if (isset($_GET['rel'])) {
        getStatusMessage($_GET['rel']);
    }

?>