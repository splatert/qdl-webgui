



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once('global.php');



    $dl_status = array(
        'release_info' => ['Various Artists', 'Unknown', 'imgurl'],
        'is_success' => true,
        'error_code' => '',
        'status_message' => '',
        'tip_message' => '',
        'content' => []
    );


    if (isset($_GET['img'])) {
        $dl_status['release_info'][2] = $_GET['img'];
    }
    if (isset($_GET['artist'])) {
        $dl_status['release_info'][0] = $_GET['artist'];
    }
    if (isset($_GET['title'])) {
        $dl_status['release_info'][1] = $_GET['title'];
    }


    $success = false;
    $lastline = '';
    $error_type = 'no_error';


    $symbol = '?';
    function addParamToURLContent($url, $name, $value) {
        global $symbol;

        $url .= $symbol .$name. '=' .$value;
        if ($symbol == '?') {
            $symbol = '&';
        }

        return $url;
    }



    function print_status($lang) {
        global $dl_status;
        if ($lang == 'js') {
            echo json_encode($dl_status);
        }
    }


    function removeStatusLogFile($path) {
        if ($path) {
            unlink($path);
        }
    }

    function createStatusLogFile($dir, $title) {
        if ($dir) {
            
            if (file_exists($dir)) {
                // fix directory
                $dir = trim($dir);
                // filter out illegal characters
                $title = preg_replace("/[^A-Za-z0-9]/", '', $title);

                chdir($dir);
                fopen($title . '.log', 'w');

                $fullpath = $dir .'/'. $title . '.log';
                return $fullpath;
            }

        }
    }


    $app = new Qobuz_DL;
    $appLink = $app->getAppLink();
    putenv("HOME=/home/{$app->getUser()}");

    $mode = 'dl';

    if (isset($_GET['mode'])) {
        
        if ($_GET['mode'] == 'dl' || $_GET['mode'] == 'lucky') {
            $mode = $_GET['mode'];
        }

        if ($_GET['mode'] == 'purge') {
            exec($appLink . ' -p 2>&1', $out);

            $dl_status['is_success'] = true;
            $dl_status['status_message'] = 'Database has been purged.';
            
            if (isset($_GET['return'])) {
                header('Location: '.$_GET['return'].'&db-purged');
            }
            else {
                print_status('js');
                die();
            }
            
        }
        elseif ($_GET['mode'] == 'abort-dl') {
            exec('killall qobuz-dl', $out);
            $st = ['ok'];
            if (isset($_GET['return'])) {
                header('Location: '.$_GET['return'].'&dl-aborted');
            }
            else {
                die(json_encode($st));
            }

        }

    }


    if (isset($_GET['url']) || isset($_GET['q'])) {

        
        if (isset($_GET['url']) && $_GET['url'] == '') {  
            $dl_status['is_success'] = false;
            $dl_status['status_message'] = 'URL required.';
            print_status('js');
            die();
        }
        if (isset($_GET['q']) && $_GET['q'] == '') {
            $dl_status['is_success'] = false;
            $dl_status['status_message'] = 'Please provide a search query.';
            print_status('js');
            die();
        }


        // download, lucky
        if ($mode == 'dl') {
            if (isset($_GET['url'])) {
                $cmd = 'dl ' . $_GET['url'];
            }
        }
        elseif ($mode == 'lucky') {
            if (isset($_GET['q'])) {
                $cmd = 'lucky ' . $_GET['q'];
            }
        }


        // no-db
        if (isset($_GET['nodb']) && $_GET['nodb'] == 1) {
            $cmd .= ' --no-db';
        }


        include_once('cfgfile.php');
        $config = new ConfigFile();
        $dlPath = $config->getProperty('default_folder');
        

        $logFile = null;
        if (isset($dlPath) && $dlPath != '') {
            if (isset($_GET['artist']) && isset($_GET['title'])) {
                $logFile = createStatusLogFile($dlPath, trim($_GET['artist'].' - '. $_GET['title']));
            }
            chdir(trim($dlPath));
        }

        
        $cmd .= ' 2>&1 | tee "'.$logFile.'" ';
        exec("{$appLink} {$cmd}", $output);

        $lastline = $output[count($output) - 1];

    }
    
    if ($lastline == 'Completed') {
        $dl_status['status_message'] = 'Download complete.';
        $dl_status['tip_message'] = 'To view your album, please visit your "downloads" folder.';
        $dl_status['is_success'] = true;

        $release = str_replace('https://www.qobuz.com', '', $_GET['url']);
        header("Location: release.php?url={$release}&dl-complete");
        print_status('js'); die();
    }
    else if ($lastline == "Use the '--no-db' flag to bypass this.") {
        $dl_status['status_message'] = 'This release was already downloaded according to the local database.';
        $dl_status['error_code'] = 'is_dloaded';
        $dl_status['is_success'] = false;
        
        $url = 'download.php';

        if (isset($_GET['artist'])) {
            $url = addParamToURLContent($url, 'artist', urlencode($_GET['artist']));
        }

        if (isset($_GET['title'])) {
            $url = addParamToURLContent($url, 'title', urlencode($_GET['title']));
        }

        if (isset($_GET['url'])) {
            $url = addParamToURLContent($url, 'url', $_GET['url']);
            $url = addParamToURLContent($url, 'no_db', 1);
            $dl_status['content'][0] = $url;

            $release = str_replace('https://www.qobuz.com', '', $_GET['url']);
            header("Location: release.php?url={$release}&is-dloaded");
        }
        else {
            print_status('js'); die();
        }

    }
    else if ($lastline == "Reset your credentials with 'qobuz-dl -r'") {
        $dl_status['status_message'] = 'Invalid credentials.';
        $dl_status['error_code'] = 'bad_login';
        $dl_status['tip_message'] = 'Is your email address and password correct?';
        $dl_status['is_success'] = false;

        print_status('js');
        die();
    }
    else if ($lastline == 'qobuz_dl.exceptions.IneligibleError: Free accounts are not eligible to download tracks.') {
        $dl_status['status_message'] = 'Free accounts are not eligible to download tracks.';
        $dl_status['tip_message'] = 'Your account does not have a streaming plan or an existing plan may have expired.';
        $dl_status['error_code'] = 'not_premium';
        $dl_status['is_success'] = false;

        header("Location: /?no-premium");
    }

    if (isset($logfile) && $logfile != '') {
        removeStatusLogFile($logfile);
    }


?>