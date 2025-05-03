 



<?php 
    include('misc/theme_ctrl.php');
    include('global.php');

    include_once('cfgfile.php');
    $cfgFile = new ConfigFile;



    if (!file_exists($cfgFile->getPath())) {
        echo '<div class="dialog-container">
            <div class="dialog '.$sitetheme.'">
                <span>It seems that you are new to qobuz-dl. Use one of the methods below to create a configuration file.</span>

                <br><h4>Using the terminal</h4>
                <span>Run <span class="code">qobuz-dl -r</span></span>

                <br><h4>Using the frontend</h4>
                <a href="setup.php?step=0">Please visit the setup wizard</a>

            </div>
        </div>
        ';
    }


    if (isset($_GET['no-premium'])) {
        echo '<div class="dialog-container">
            <div class="dialog '.$sitetheme.'">
                <span><b>Free accounts are not eligible to download tracks.</b></span>
                <br><span>Your account does not have a streaming plan or an existing plan may have expired.</span>
                <br><br>
                <a href="setup.php?step=3">Change account</a>

            </div>
        </div>';
    }


?>



<html class="<?php echo $sitetheme ?>">
    <head>
        <link rel="stylesheet" href="style.css">
    </head>


    <body class="bg <?php echo $sitetheme ?>">
        <title>QobuzDL</title>
        <script src="actions.js"></script>


        <?php
            include_once('misc/shd_exists.php');
            $shd_installed = shd_installed();
            if (!$shd_installed) {
                echo '<span class="warn '.$sitetheme.'">SimpleHTMLDom is not installed. <a href="../config.php?step=3">Click here</a> to install it.</span>';
            }
        ?>


        <div class="top">

            <script>
                fetch('misc/myaccount.php')
                    .then(status => status.text())
                    .then(status => {

                        document.querySelector('.account-status .spinner').style.display = 'none';
                        document.querySelector('.account-status').style.opacity = 'unset';

                        var premiumData = JSON.parse(status);
                        if (premiumData) {
                            if (premiumData != null) {
                                if (premiumData[0] == true) {
                                    if (premiumData[1]) {
                                        document.querySelector('.account-status div .bottom .top').innerText = premiumData[1];
                                        document.querySelector('.account-status div .bottom').className += ' premium';
                                    }
                                }
                                else {
                                    document.querySelector('.account-status div .bottom .top').innerText = 'free account';
                                    document.querySelector('.account-status div .bottom').className += ' not-premium';
                                }

                                if (premiumData[2]) {
                                    document.querySelector('.account-status div .top').innerText = premiumData[2];
                                }

                            }
                        }
                    })
            </script>

            <div class="account-status <?php echo $sitetheme ?>" style="opacity: 0.5;">
                <aside>
                    <img src="img/ico/user64.png">
                </aside>

                <div style="padding-left: 15px; display: grid;">
                    <span class="top size12">Connecting...</span>
                    <span class="mid size12">Account status</span>
                    <ul class="bottom">
                        <li class="top">PLEASE WAIT</li>
                    </ul>
                </div>
                <img class="spinner" src="img/ico/load2.gif">

                <div class="line"></div>

                <div class="options">
                    <ul>
                        <li>
                            <a href="setup.php?step=3">Switch account</a>
                        </li>
                        <li>
                            <a href="setup.php?step=0">Setup wizard</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>


        <div class="frontpage-search">
            <center>
                <img class="logo <?php echo $sitetheme ?>" src="img/logo.png" width="135" height="80">
            </center>

            <form method="GET" action="search.php">
                <input type="text" name="q">
                <button type="submit" class="btn1" onclick="loadingDialog('')">Search</button>
                <input type="submit" class="btn2" name="type" value="lucky">
                    <br>
                <input type="radio" checked="checked" name="cat" value="search">
                <label for="cat">Albums</label>

                <input type="radio" name="cat" value="artists">
                <label for="cat">Artists</label>

                <input type="radio" name="cat" value="labels">
                <label for="cat">Labels</label>

            </form>
        </div>

        <table class="frontpage-genres<?php echo $sitetheme ?>">
            <tbody>
                <tr>
                    <td>
                        <ul>
                            <li><a href="search.php?genre=all">All Genres</a></li>
                        </ul>
                        
                        
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=pop-rock">Pop/Rock</a>    
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=jazz">Jazz</a>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=classique">Classical</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=electro">Electronic</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=soul-funk-rap">Soul/Funk/R&B</a>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=folk">Folk/Americana</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=rap-hip-hop">Hip-Hop</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=country">Country</a>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=metal">Metal</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=blues">Blues</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=amerique-latine">Latin</a>
                            </li>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=musique-de-films">Soundtracks</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=musiques-du-monde">World</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="search.php?genre=diction-litterature">Comedy/Other</a>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

    </body>
</html>