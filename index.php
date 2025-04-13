 



<?php 
    include('global.php');
?>



<html class="<?php echo $sitetheme ?>">
    <head>
        <link rel="stylesheet" href="style.css">
    </head>


    <body class="bg <?php echo $sitetheme ?>">
        <title>QobuzDL</title>
        <script src="actions.js"></script>

        <div class="top">

            <script>
                fetch('misc/is_premium.php')
                    .then(status => status.text())
                    .then(status => {

                        document.querySelector('.account-status .spinner').style.display = 'none';
                        document.querySelector('.account-status').style.opacity = 'unset';

                        var premiumData = JSON.parse(status);
                        if (premiumData) {
                            if (premiumData[0] && premiumData[0] == true) {
                                if (premiumData[1]) {
                                    document.querySelector('.account-status div .bottom .top').innerText = premiumData[1];
                                    document.querySelector('.account-status div .bottom').className += ' premium';
                                }
                            }
                            else {
                                document.querySelector('.account-status div .bottom .top').innerText = 'free account';
                                document.querySelector('.account-status div .bottom').className += 'not-premium';
                            }
                        }
                    })
            </script>

            <div class="account-status <?php echo $sitetheme ?>" style="opacity: 0.5;">
                <aside>
                    <img src="img/ico/user64.png">
                </aside>

                <div style="padding-left: 15px;">
                    <span class="top size12">Membership status</span>
                    <ul class="bottom">
                        <li class="top">PLEASE WAIT</li>
                    </ul>
                </div>
                <img class="spinner" src="img/ico/load2.gif">

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
                <input type="radio" checked="checked" name="type" value="search">
                <label for="type">Albums</label>

                <input type="radio" name="type" value="artists">
                <label for="type">Artists</label>

                <input type="radio" name="type" value="labels">
                <label for="type">Labels</label>

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

        <?php
            require_once('footer.php');
        ?>

    </body>
</html>