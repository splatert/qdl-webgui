



<?php
    include_once('misc/shd_exists.php');

    $sitetheme = '';
    if (isset($_COOKIE['theme'])) {
        $sitetheme = ' ' . $_COOKIE['theme'];
    }

    $shd_installed = shd_installed();
    if (!$shd_installed) {
        echo '<span class="warn '.$sitetheme.'">SimpleHTMLDom is not installed. <a href="../config.php?step=3">Click here</a> to install it.</span>';
    }

    $ret = '&ret='.urlencode($_SERVER['REQUEST_URI']);


    echo '<header class="theme-sel '.$sitetheme.'" style="width: fit-content;position: absolute;top: 0;right: 0;padding: 15px;">';
        if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'darkmode') {
            echo '<a class="theme-btn" href="misc/apply_theme.php?theme=classic'.$ret.'">Classic Theme</a>';
        }
        else {
            echo '<a class="theme-btn" href="misc/apply_theme.php?theme=darkmode'.$ret.'">Dark theme</a>';
        }
    echo '</header>';


?>