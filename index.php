 



<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>


    <body>
        <title>QobuzDL</title>
        <script src="actions.js"></script>
        <div class="frontpage-search">
            <center>
                <img class="logo" src="img/logo.png" width="135" height="80">
            </center>

            <form method="GET" action="search.php">
                <input type="text" name="q">
                <input type="submit" class="btn1" name="type" value="search" onclick="loadingDialog('')">
                <input type="submit" class="btn2" name="type" value="lucky">
            </form>
        </div>

        <table class="frontpage-genres">
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