



<?php

    if (isset($_COOKIE['theme'])) {
        $sitetheme = ' ' . $_COOKIE['theme'];
    }

    echo '<header class="theme-sel '.$sitetheme.'" style="width: fit-content;position: absolute;top: 0;right: 0;padding: 15px;">';
        if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'darkmode') {
            echo '<a class="theme-btn" href="misc/apply_theme.php?theme=classic'.$ret.'">Classic Theme</a>';
        }
        else {
            echo '<a class="theme-btn" href="misc/apply_theme.php?theme=darkmode'.$ret.'">Dark theme</a>';
        }
    echo '</header>';


?>