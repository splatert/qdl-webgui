


<?php
    include('global.php');
?>

<header>

    <div class="topbar <?php echo $sitetheme ?>">
        
        <div>
            <a href="/">
                <img class="logo-mini <?php echo $sitetheme ?>" src="img/logo.png" width="67.5" height="40">
            </a>

            <form method="GET" action="search.php">
                <input type="text" name="q">
                <input type="submit" class="btn1" name="type" value="search" onclick="loadingDialog('')">
                <input type="submit" class="btn2" name="type" value="lucky">
            </form>
        </div>

    </div>
</header>