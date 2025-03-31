



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
?>


<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>


    <body>
        <script src="actions.js?v=2"></script>
        <div class="center-dialog">

            <div class="dl-details">
                <div class="aside">
                    <?php
                        if (isset($_GET['img'])) {
                            echo '<img src="'.$_GET['img'].'">';
                        }
                    ?>
                </div>
                <div class="text">
                    <ul>
                        <?php
                            if (isset($_GET['artist'])) {
                                echo '<li>'.$_GET['artist'].'</li>';
                            }
                            if (isset($_GET['title'])) {
                                echo '<li><b>'.$_GET['title'].'</b></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="download-status">
                <span class="status">
                    <?php
                        
                        $success = false;
                        $lastline = '';
                        $error_type = 'no_error';



                        function removeStatusLogFile($path) {
                            unlink($path);
                        }


                        function createStatusLogFile($dir, $title) {

                            // fix directory
                            $dir = trim($dir);
                            // filter out illegal characters
                            $title = preg_replace("/[^A-Za-z0-9]/", '', $title);

                            chdir($dir);
                            fopen($title . '.log', 'w');

                            $fullpath = $dir .'/'. $title . '.log';
                            return $fullpath;

                        }


                        $user = exec('whoami');
                        $app = "/home" ."/". $user . "/.local/pipx/venvs/qobuz-dl/bin/python /home/".$user."/.local/pipx/venvs/qobuz-dl/bin/qobuz-dl";
                        $mode = 'dl';

                        if (isset($_GET['mode'])) {
                            
                            if ($_GET['mode'] == 'lucky') {
                                $mode = 'lucky';
                            }

                            if ($_GET['mode'] == 'purge') {

                                putenv('HOME=/home/'.$user);

                                exec($app . ' -p 2>&1', $out);
                                echo 'Database has been purged.';

                                echo '<script> setTimeout(() => {history.back()}, 2000);</script>';
                                die();

                            }

                        }


                        if (isset($_GET['url']) || isset($_GET['q'])) {

                            if (isset($_GET['url']) && $_GET['url'] == '') {
                                die('URL required.');
                            }
                            if (isset($_GET['q']) && $_GET['q'] == '') {
                                die('Please provide a query.');
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

                            $user = exec('whoami');
                            $cfg_path = "/home/".$user.'/.config/qobuz-dl/config.ini';
                            $config = file($cfg_path, FILE_SKIP_EMPTY_LINES);
                            $dlPath = explode('default_folder = ', $config[3]);
                            
                            $logfile = '';

                            putenv('HOME=/home/'.$user);

                            if (isset($dlPath[1]) && $dlPath[1] != '') {
                                if (isset($_GET['artist']) && isset($_GET['title'])) {
                                    $logfile = createStatusLogFile($dlPath[1], trim($_GET['artist'].' - '. $_GET['title']));
                                }
                                chdir(trim($dlPath[1]));
                            }

                            $cmd .= ' 2>&1 | tee ' .$logfile;
                            exec($app. ' ' .$cmd, $output);

                            $lastline = $output[count($output) - 1];
                        }
                        
                        if ($lastline == 'Completed') {
                            $lastline = 'Download complete.';
                            $success = true;
                        }
                        else if ($lastline == "Use the '--no-db' flag to bypass this.") {
                            $lastline = 'This release was already downloaded according to the local database.';
                            $error_type = 'already_dloaded';
                        }
                        else if ($lastline == "Reset your credentials with 'qobuz-dl -r'") {
                            $lastline = 'Invalid credentials.';
                            $error_type = 'bad_credentials';
                        }
                        else if ($lastline == 'qobuz_dl.exceptions.IneligibleError: Free accounts are not eligible to download tracks.') {
                            $lastline = 'Free accounts are not eligible to download tracks.';
                            $error_type = 'not_premium';
                        }

                        //removeStatusLogFile($logfile);
                        
                        echo '<title>'.$lastline.' | QobuzDL</title>';
                        echo $lastline;
                    ?>
                </span>
                <span class="instr">
                    <?php
                        if ($success) { echo 'To view your album, please visit your "downloads" folder.'; }
                        else {
                            if ($error_type == 'bad_credentials') {
                                echo 'Is your email address and password correct?';
                            }
                            else if ($error_type == 'not_premium') {
                                echo 'Your account does not have a streaming plan.';
                            }
                        }
                    ?>
                </span>
            </div>

            <div style="display:flex;">
                <button style="padding: 5px;" class="btn1">
                    <?php
                        if (isset($_GET['mode']) && $_GET['mode'] == 'lucky') {
                            echo '<a style="text-decoration:none;color:white;" href="index.php">Go back</a>';
                        }
                        else {
                            echo '<a style="text-decoration:none;color:white;" href="javascript:history.back()">Go back</a>';
                        }
                    ?>
                </button>

                <?php
                    if ($error_type == 'already_dloaded') {
                        echo '<form method="GET" action="download.php">';
                            if (isset($_GET['url'])) {
                                echo '<input type="hidden" name="url" value="'.$_GET['url'].'">';
                            }

                            if (isset($_GET['img'])) {
                                echo '<input type="hidden" name="img" value="'.$_GET['img'].'">';
                            }

                            if (isset($_GET['artist']) && isset($_GET['title'])) {

                                $both = trim($_GET['artist']) .' - '. trim($_GET['title']);
                                $both = preg_replace("/&(amp;)+/", '', $both); $both = preg_replace("/[^A-Za-z0-9]/", '', $both);
                                echo '<script> function dlstatus() { getDLStatus("' .$both.'") } </script>';

                                echo '<input type="hidden" name="nodb" value="1">';
                                echo '<input type="hidden" name="artist" value="'.$_GET['artist'].'">';
                                echo '<input type="hidden" name="title" value="'.$_GET['title'].'">';
                                echo '<button class="btn2" style="margin-left:5px;" type="submit" onclick="loadingDialog(\'Downloading tracks... Please wait.\'); dlstatus();">Download anyway</button>';
                            }
                        echo '</form>';
                    }

                    elseif ($error_type == 'bad_credentials') {
                        echo '<button class="btn2" style="margin-left:5px;">
                            <a class="white-noline" href="config.php">Reset config</a>
                        </button>';
                    }

                ?>
            </div>



        </div>
    </body>
</html>
