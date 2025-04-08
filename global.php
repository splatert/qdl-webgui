



<?php
    include_once('misc/shd_exists.php');
    
    $shd_installed = shd_installed();
    if (!$shd_installed) {
        echo '<span class="warn">SimpleHTMLDom is not installed. <a href="../config.php?step=3">Click here</a> to install it.</span>';
    }
?>