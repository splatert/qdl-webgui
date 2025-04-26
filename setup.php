



<?php
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);
    include('lib/simplehtmldom/simple_html_dom.php');
    include_once('cfgfile.php');
    include_once('global.php');
    include('misc/theme_ctrl.php');
?>

<?php
    // Download quality (5, 6, 7, 27) [320, LOSSLESS, 24B <96KHZ, 24B >96KHZ]

    $step = 0;

    $steps = array(
        0 => 'Welcome',
        1 => 'Downloads',
        2 => 'Audio Quality',
        3 => 'Link Account',
        4 => 'SimpleHTMLDOM',
        5 => 'Title Formats',
        6 => 'Thank You'
    );

    if (isset($_GET['step'])) {
        $step = $_GET['step'];
    }

    $next_page = $step + 1;
    $prev_page = $step - 1;


    $missing_opt_values = array();

    if (isset($_GET['step'])) {

        $user = exec('whoami');
        $cfgFile = new ConfigFile();
        $cfg_path = $cfgFile->getPath();


        switch($_GET['step']) {
            case 1: 
                if (isset($_GET['dl-path'])) {
                    editCookie('opt-dl-path', $_GET['dl-path']);
                    $cfgFile->setProperty('default_folder', $_GET['dl-path']);
                    header('Location: setup.php?step='.$next_page);
                }
            case 2:
                if (isset($_GET['audio_qlty'])) {
                    editCookie('opt-audio-qlty', $_GET['audio_qlty']);
                    $cfgFile->setProperty('default_quality', $_GET['audio_qlty']);
                    header('Location: setup.php?step='.$next_page);
                }
            case 3:

                if (!isset($_COOKIE['opt-dl-path'])) {
                    array_push($missing_opt_values, 'Downloads path');
                    editCookie('opt-dl-path', $cfgFile->suggestDlLocation());
                }
                if (!isset($_COOKIE['opt-audio-qlty'])) {
                    array_push($missing_opt_values, 'Audio quality');
                    editCookie('opt-audio-qlty', 6);

                    header('Location: setup.php?step='.$step);

                }
            case 5:
                if (isset($_GET['format-query']) && isset($_GET['opt-apply'])) {
                    
                    if (isset($_GET['preview'])) {
                        unset($_GET['preview']);
                    }

                    $qry = $_GET['format-query'];
                    if (isset($_GET['opt-title-format-category'])) {
                        $cat = $_GET['opt-title-format-category'];
                        if ($cat == 'track') {
                            $cfgFile->setProperty('track_format', $qry);
                        }
                        else {
                            $cfgFile->setProperty('folder_format', $qry);
                        }

                        $_GET['save-success'] = '';
                        unset($_GET['opt-apply']);
                    }

                }
        }
        
    }


    require_once('topbar.php');

?>


<html class="<?php echo $sitetheme ?>">

    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="<?php echo $sitetheme ?>">

        <script>
            function page(page) {
                var pages = document.getElementsByClassName('screen');
                for (let i=0; i<pages.length; i++) {
                    if (i == page) {
                        pages[i].style.display = 'block';
                    }
                    else {
                        pages[i].style.display = 'none';
                    }
                }
            }
        </script>

        <script src="actions.js"></script>

        <div class="page setup-page <?php echo $sitetheme ?>">

            <div class="setup-prog-container">
                <div class="setup-progress <?php echo $sitetheme ?>">
                    <?php
                    
                    foreach ($steps as $s => $i) {
                        echo '<div class="step size14">';
                            if (isset($_GET['step']) && $s == $step) {
                                echo '<a href="setup.php?step='.$s.'"><b>'.$i.'</b></a>';
                            }
                            else {
                                echo '<a href="setup.php?step='.$s.'">'.$i.'</a>';
                            }
                        echo '</div>';
                        if ($s != count($steps)-1) {
                            echo '<div class="arrow">></div>';
                        }
                    }

                    ?>
                </div>
            </div>


            <div class="setup-screens <?php echo $sitetheme ?>">

                <div class="screen" id="screen0"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>
                                <h3>Welcome</h3>
                                <p>This wizard will help you set up qobuz-dl.
                                    Your changes will be written to your qobuz-dl configuration file.
                                    You can skip to a certain sections of this setup by clicking on one of the tabs above or by
                                    using the navigation buttons on the bottom of this dialog. To confirm changes, click the <b>Save</b>
                                    button found on each settings category.
                                    To begin, click 'Next'.
                                </p>
                            </main>
                            <aside>
                                <img style="height:350px; border-radius: 15px;" src="img/misc/setup.png">
                            </aside>
                    </div>
                </div></div>




                <div class="screen" id="screen1"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main style="width: 100%;">
                                <h3>Downloads Folder</h3>
                                    <p>Please enter a path to where your downloads will be saved to.
                                    <br>Example: <code>/home/tux/qobuzdl</code> or <code>Qobuz Downloads</code>
                                </p>

                                <div style="display:flex;">
                                    <form method="GET" action="setup.php">

                                        <?php

                                            $p = '';

                                            if (isset($_GET['step']) && $_GET['step'] == 1) {
                                                if (isset($_GET['opt-suggest-path'])) {
                                                    $suggested_path = $cfgFile->suggestDlLocation();
                                                    if (isset($suggested_path)) {
                                                        $p = $suggested_path;
                                                    }
                                                }
                                            }

                                            echo '<input type="text" name="dl-path" value="'.$p.'">';
                                        ?>

                                        <input type="hidden" name="step" value="1">
                                        <button type="submit">Save</button>
                                    </form>

                                    <form method="GET" action="setup.php">
                                        <input type="hidden" name="step" value="1">    
                                        <button type="submit" name="opt-suggest-path" value="1" style="margin-left:5px;">Suggest path</button>
                                    </form>
                                </div>


                                <form method="GET" action="download.php">
                                    <input type="hidden" name="mode" value="purge">
                                    <button class="btn4" type="submit" style="float:right;">Purge Database</button>
                                </form>

                            </main>
                    </div>
                </div></div>


                <div class="screen" id="screen2"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>
                                <h3>Audio Quality</h3>
                                    <p>Please select your desired audio quality.
                                    <br>By default, it is set to <b>FLAC</b>.
                                </p>

                                <form method="GET" action="setup.php">

                                    <div>
                                        <table>
                                            <tr>
                                                <th>
                                                    <img src="img/misc/audioformat/320k.png">
                                                </th>
                                                <th>
                                                    <img src="img/misc/audioformat/flac.png">
                                                </th>
                                                <th>
                                                    <img src="img/misc/audioformat/l96k.png"> <red style="position:fixed;"> *</red>
                                                </th>
                                                <th>
                                                    <img src="img/misc/audioformat/g96k.png"> <red style="position:fixed;"> *</red>
                                                </th>
                                            </tr>

                                            <tr class="opt-qlty-labels">

                                                <?php
                                                    $qltyList = array(
                                                        [5, '320kbps MP3'],
                                                        [6, 'Lossless'],
                                                        [7, '24B <96KHZ'],
                                                        [27, '24B >96KHZ']
                                                    );

                                                    $default_selected_qlty = 6;
                                                    $sel = '';

                                                    $get = $cfgFile->getProperty('default_quality');
                                                    if ($get) {
                                                        $default_selected_qlty = $get;
                                                    }
                                                    else {
                                                        if (isset($_COOKIE['opt-audio-qlty'])) {
                                                            $default_selected_qlty = $_COOKIE['opt-audio-qlty']; 
                                                        }
                                                    }

                                                    foreach ($qltyList as $q => $qlty) {
                                                        if ($qlty[0] == $default_selected_qlty) {
                                                            $sel = 'checked="checked"';
                                                        }
                                                        else {
                                                            $sel = '';
                                                        }

                                                        echo '<td>
                                                            <input class="opt-qlty test" id="qlty'.$q.'" type="radio" name="audio_qlty" '.$sel.' value="'.$qlty[0].'">
                                                        </td>';

                                                        unset($sel);
                                                    }
                                                    

                                                ?>
                                            </tr>

                                        </table>

                                        <red>* </red><span class="size12">It is impossible for humans to perceive the difference between sampling rates higher than 44.1 kHz.</span><br>

                                    <input type="hidden" name="step" value="2">
                                    <button type="submit" style="margin-top:15px;">Save</button>
                                </form>

                            </main>
                    </div>
                </div></div>


                <div class="screen" id="screen3"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main style="width: 100%;">
                                <h3>Link Account</h3>  
                                <p>To rip audio tracks from Qobuz, you'll need an account with a valid <b>streaming subscription</b>.
                                Please enter your Qobuz account credentials below then click <b>connect</b> to link or change account.</p>        
                                
                                <?php
                                    if ($step == 3) {
                                        $e = $cfgFile->getProperty('email');
                                        if (isset($e)) {
                                            $first_four_letters = mb_substr($e, 0, 4);
                                            $domain = explode('@', $e);
                                    
                                            if (isset($domain[1])) {
                                                $e = $first_four_letters . '****@' . $domain[1];
                                                echo '<div style="width: fit-content;display: flex;">
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgBAMAAACBVGfHAAAAJ1BMVEVHcEyZmf/MzP8AAABmZsxCQkJ3d3f///8zmWYzZmb/zJn//8zMmWZtcb6kAAAAAXRSTlMAQObYZgAAALlJREFUKM9d0UsKwjAQBuBATjAtPYAp4jbpECm6KtW9RbrvqnsR79JDeALP4KGcJDh5zG4+mH+GRAiJIi+5u+e9hVzkY85FqiCoGYJUjWbwAlVzY3ACUOkI6kUA1wT2E0GdwKGZaEpHCNJGaJGSTR3h/OmUeqbwJhjHCKdvAS4jA18JtIiYgel2pvvD4AEYhB0KcJIDSQHC0hJ0sPBT95u/dF1KYOk3E2BlsFCAni/pCNLH2TQ06JH6H24fPxkWEQ99AAAAAElFTkSuQmCC"><span style="display:block;margin-bottom:10px;">
                                                Currently logged in as <b>'.$e.'</b></span>
                                                </div>';
                                            }
                                        }
                                    }
                                ?>

                                <form method="POST" action="auth.php" class="auth-form">
                                    <?php

                                        if ($step == 3) {

                                            if (isset($_COOKIE['opt-audio-qlty'])) { 
                                                echo '<input type="hidden" name="audio-quality" value="'.$_COOKIE['opt-audio-qlty'].'">'; 
                                            }
                                            if (isset($_COOKIE['opt-dl-path'])) {
                                                echo '<input type="hidden" name="dl-folder" value="'.$_COOKIE['opt-dl-path'].'">';
                                            }

                                            if (count($missing_opt_values) == 0) {
                                                echo '
                                                    <label for="auth-email">Email Address: </label>
                                                    <input type="email" name="auth-email" value="">

                                                    <label for="auth-pwd">Password: </label>
                                                    <input type="password" name="auth-pwd" value="">

                                                    <button class="btn5 '.$sitetheme.'" type="submit">Connect</button>
                                                ';
                                            }
                                            else {
                                                echo '<red>The following options need to be set before you can connect your account.</red>';
                                                foreach ($missing_opt_values as $m) {
                                                    echo '<ul>';
                                                        echo '<li>'.$m.'</li>';
                                                    echo '</ul>';
                                                }
                                            }

                                        }

                                    ?>

                                </form>
                                
                            </main>
                    </div>
                </div></div>




                <div class="screen" id="screen4"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>

                                <?php

                                    if (isset($_GET['step'])) {
                                        if ($_GET['step'] == 4) {
                                            if (isset($_GET['account-link-success'])) {
                                            echo '<span class="status-success">You have successfully linked your Qobuz account.
                                            </span>';
                                            }
                                            
                                            if (isset($_GET['shd-install-success'])) {
                                                echo '<span class="status-success">
                                                SimpleHTMLDOM has been successfully installed.</span>';
                                            }
                                        }
                                    }

                                ?>

                                <h3>Simple HTML DOM Parser</h3>
                                <p>In order to search for albums, artists, or labels, this program will require
                                    the SimpleHTMLDom library in order to scrape and return data from the Qobuz website.
                                    To have this frontend install the library for you, click the button below.
                                </p>

                                <?php
                                    require_once('misc/shd_exists.php');
                                    $installed = shd_installed();
                                    
                                    if (!$installed) {
                                        echo '<span>Status: <b>Not installed</b></span>';
                                    }
                                    else {
                                        echo '<span>Status: <b>Installed</b></span>';
                                    }

                                ?>

                                <form action="misc/shd_exists.php">
                                    <input type="hidden" name="action" value="install-shdp">
                                    <button type="submit">Install</button>
                                </form>
                            </main>
                    </div>
                </div></div>


                <div class="screen" id="screen5"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>

                                <?php
                                if (isset($_GET['save-success'])) {
                                    echo '<span class="status-success">Saved.</span>';
                                }
                                ?>

                                <h3>Title Formats</h3>
                                <p>On this page, you can change the title format for tracks and album folder to your liking.
                                    Please select the type of title you would like to edit then make your changes below.
                                </p>

                                <form>
                                    <input type="hidden" name="step" value="5">
                                    <button type="submit" name="opt-title-format-category" value="track">Track titles</button>
                                    <button type="submit" name="opt-title-format-category" value="album">Album folder</button>
                                </form>

                                <div>
                                    <div class="title-format-editor" style="margin-top: 15px;">
                                        <?php
                                            if (isset($_GET['opt-title-format-category'])) {
                                                $tfc = $_GET['opt-title-format-category'];
                                                if ($tfc == 'track' || $tfc == 'album') {

                                                    echo '
                                                        <form>
                                                            <input type="hidden" name="step" value="5">';
                                                            
                                                            if ($tfc == 'track') {
                                                                echo '<span>Track title format</span>
                                                                <input type="hidden" name="opt-title-format-category" value="track">';
                                                            }
                                                            else {
                                                                echo '<span>Album folder title format</span>
                                                                <input type="hidden" name="opt-title-format-category" value="album">';
                                                            }


                                                            echo '<div style="display:flex;">';
                                                                
                                                                $qry = '';
                                                                if (isset($_GET['format-query'])) {
                                                                    $qry = $_GET['format-query'];
                                                                }
                                                                else {
                                                                    if ($tfc == 'track') {
                                                                        $qry = $cfgFile->getProperty('track_format');
                                                                    }
                                                                    else {
                                                                        $qry = $cfgFile->getProperty('folder_format');
                                                                    }
                                                                }

                                                                echo '<input style="width: 100%;" type="text" name="format-query" value="'.$qry.'">';
                                                                
                                                                echo '<div style="width: 100%;" class="preview">';
                                                                    
                                                                    $metadata_tags = array(
                                                                        '{artist}' => 'Ferry C.',
                                                                        '{album}' => 'Junk EP',
                                                                        '{year}' => '2006',
                                                                        '{sampling_rate}' => '44.1',
                                                                        '{bit_depth}' => '16',
                                                                        '{tracktitle}' => 'Junk (Original Mix)',
                                                                        '{tracknumber}' => '01',
                                                                    );

                                                                    if ($qry) {
                                                                        $res = str_replace(array_keys($metadata_tags), $metadata_tags, $qry);
                                                                        echo $res;

                                                                    }

                                                                echo '</div>
                                                            </div>

                                                            <div style="margin-top: 5px;">
                                                                <button type="submit" name="preview" value="">Preview</button>
                                                                <button type="submit" name="opt-apply" value="track-title-format">Save</button>
                                                            </div>

                                                        </form>
                                                        ';

                                                }
                                            }
                                        ?>
                                    </div>
                                </div>

                            </main>
                    </div>
                </div></div>



                <div class="screen" id="screen6"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>
                                <h3>Setup Complete</h3>
                                <p>This concludes the qobuz-dl setup. You are now ready to use qobuz-dl.</p>
                            </main>
                    </div>
                </div></div>


            </div>


            <footer style="padding:5px;border-top: 1px solid #c6c6c6;">
                <form class="nav-buttons" method="GET" action="setup.php">
                    <?php
                        
                        if ($step != 0) {
                            echo '<button class="btn1" type="submit" name="step" value="'.$prev_page.'">Previous</button>';
                        }
                        if ($step != count($steps)-1) {
                            echo '<button class="btn2" type="submit" name="step" value="'.$next_page.'">Next</button>';
                        }
                        else {
                            echo '<button class="btn2"><a class="white-noline" href="/">Home</button>';
                        }
                    ?>
                </form>
            </footer>


            <script>
                page(<?php echo $step ?>);
            </script>


        </div>
    </body>
</html>