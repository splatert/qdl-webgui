



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
            
                <div class="topbar-search-box <?php echo $sitetheme ?>" style="display: inline-block;">
                    <input type="text" name="q">
                    <select name="type">
                        <option value="search">Albums</option>
                        <option value="artists">Artists</option>
                        <option value="labels">Labels</option>
                    </select>
                </div>

                <input type="submit" class="btn1" name="type" value="search" onclick="loadingDialog('')">
                <input type="submit" class="btn2" name="type" value="lucky">
            </form>
        </div>

    </div>
</header>