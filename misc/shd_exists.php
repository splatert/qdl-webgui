



<html>
    <head>
        <link rel="stylesheet" href="../style.css">
    </head>

    <body>
        <header>
        <?php

            function shd_installed() {
                if (!file_exists(__DIR__.'/../lib/simplehtmldom')) {
                    return false;
                }
                else {
                    return true;
                }
            }
            
            if (isset($_GET['action'])) {
                if ($_GET['action'] == 'status') {
                    $installed = shd_installed();
                    if (!$installed) {
                        echo '<span>Status: <b>Not Installed</b></span>';
                    }
                }
                elseif ($_GET['action'] == 'install-shdp') {
                    chdir(__DIR__. '/..');
                    
                    if (!file_exists('lib/simplehtmldom')) {
                        mkdir('lib/simplehtmldom', 0777, true);
                    }

                    chdir('lib/simplehtmldom');
                    exec('wget https://sourceforge.net/projects/simplehtmldom/files/latest/download -O shtmldom.zip && unzip shtmldom.zip && rm shtmldom.zip');


                    echo 'SimpleHTMLDOM has been successfully installed.  <a href="../config.php?step=3">Go back</a>';

                }
            }

        ?>
        </header>
    </body>

</html>