



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    include('lib/simplehtmldom/simple_html_dom.php');
    require_once('topbar.php');
?>

<?php
    // Download quality (5, 6, 7, 27) [320, LOSSLESS, 24B <96KHZ, 24B >96KHZ]

    $step = 0;

    $steps = array(
        0 => 'Welcome',
        1 => 'Downloads',
        2 => 'Audio Quality',
        3 => 'WIP',
        4 => 'Complete'
    );

    if (isset($_GET['step'])) {
        $step = $_GET['step'];
    }

    $next_page = $step + 1;
    $prev_page = $step - 1;
?>


<html>

    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <?php
        

            if (isset($_GET['step'])) {

                $user = exec('whoami');
                $cfg_path = "/home/".$user.'/.config/qobuz-dl/config.ini';
                $config = file($cfg_path, FILE_SKIP_EMPTY_LINES);
        
        
                if ($_GET['step'] == 1) {

                    if (isset($_GET['dl-path'])) {
                        $config[3] = 'default_folder = ' . $_GET['dl-path'] . PHP_EOL;
                        file_put_contents($cfg_path, implode('', $config));
                        
                        echo '<script>window.location.href = "config.php?step='.$next_page.'" </script>';
                    }

                }
                else if ($_GET['step'] == 2) {
                    if (isset($_GET['audio_qlty'])) {
                        $config[4] = 'default_quality = ' . $_GET['audio_qlty'] . PHP_EOL;
                        file_put_contents($cfg_path, implode('', $config));
                        
                        echo '<script>window.location.href = "config.php?step='.$next_page.'" </script>';
                    }
                }
                
            }


        
        ?>

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

        <div class="page setup-page">

            <div class="setup-prog-container">
                <div class="setup-progress">
                    <?php
                    
                    foreach ($steps as $s => $i) {
                        echo '<div class="step">';
                            if (isset($_GET['step']) && $s == $step) {
                                echo '<b>'.$i.'</b>';
                            }
                            else {
                                echo $i;
                            }
                        echo '</div>';
                        if ($s != count($steps)-1) {
                            echo '<div class="arrow">></div>';
                        }
                    }

                    ?>
                </div>
            </div>


            <div class="setup-screens">

                <div class="screen" id="screen0"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>
                                <h3>Welcome</h3>
                                <p>On this page, you can adjust the settings of qobuz-dl to your liking.
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
                                    <p>Please enter a path to where you want to keep your downloads.
                                    <br><br>It must be an absolute path.
                                    <br><i>For example: /home/tux/Downloads/qobuzdl</i>
                                </p>

                                <form method="GET" action="config.php">
                                    <input type="text" name="dl-path">
                                    <input type="hidden" name="step" value="1">
                                    <button type="submit">Save</button>
                                </form>


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
                                    <br>By default, it is set to <b>lossless</b>.
                                </p>

                                <form method="GET" action="config.php">

                                    <div>
                                        <input id="qlty0" type="radio" name="audio_qlty" value="5">
                                        <label for="qlty0">320kbps</label>
                                    </div>

                                    <div>
                                        <input id="qlty1" type="radio" name="audio_qlty" value="6" checked="checked">
                                        <label for="qlty1">Lossless</label>
                                    </div>

                                    <div>
                                        <input id="qlty2" type="radio" name="audio_qlty" value="7">
                                        <label for="qlty2">24B &lt96KHZ</label>
                                    </div>

                                    <div>
                                        <input id="qlty3" type="radio" name="audio_qlty" value="27">
                                        <label for="qlty3">24B &gt96KHZ </label>
                                    </div>

                                    <input type="hidden" name="step" value="2">
                                    <button type="submit" style="margin-top:15px;">Save</button>
                                </form>

                            </main>
                    </div>
                </div></div>

                <div class="screen" id="screen3"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>
                                <h3>WIP</h3>
                            </main>
                    </div>
                </div></div>


                <div class="screen" id="screen4"> <div class="container">
                    <div class="top" style="display:flex;">
                            <main>
                                <h3>Setup Complete</h3>
                                <p>This concludes the qobuz-dl setup. You are ready to use qobuz-dl.</p>
                            </main>
                    </div>
                </div></div>


            </div>


            <footer style="padding:5px;border-top: 1px solid #c6c6c6;">
                <form class="nav-buttons" method="GET" action="config.php">
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