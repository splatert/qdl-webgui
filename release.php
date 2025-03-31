



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include('lib/simplehtmldom/simple_html_dom.php');
    require_once('topbar.php');
?>


<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>


    <body>
        <script src="actions.js"></script>
        <script>loadingDialog();</script>

        <div class="page">

            <?php
            
                if (isset($_GET['url'])) {

                    $url = 'https://www.qobuz.com' .$_GET['url'];

                    $ch = curl_init();
                    $reqURL = $url;

                    curl_setopt($ch, CURLOPT_URL, $reqURL);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    

                    $response = curl_exec($ch);
                    curl_close($ch);


                    $dom = new simple_html_dom();
                    $dom->load($response);


                    $err404 = $dom->find('.container-404');
                    if (isset($err404[0])) {
                        echo '<script>removeLoadingDialog();</script>';
                        die('<b class="error">Error 404</b> - Qobuz resource no longer exists.');
                    }


                    $rel = $dom->find('.album-item')[0];
                    $avgCol = '';

                    echo '<div class="release">';
                    
                        $art = $rel->find('.album-cover img');
                        if (isset($art[0])) {
                            echo '<table>';
                        
                                $mini_cover = str_replace('_600', '_100', $art[0]->src);

                                echo '<tr><td><img class="album-cover" src="'.$mini_cover.'"><td></tr>';
                                echo '<tr><td class="enlarge-cover size12"><a target="_blank" href="'.$art[0]->src.'" >Enlarge</a></td></tr>';
                            echo '</table>';
                        }

                        echo '<ul style="width: fit-content;">';
                            $artist = $rel->find('.album-meta span');
                            $title = $rel->find('.album-meta h1');

                            if ($artist[0] && $title[0]) {
                                echo '<li class="artist-name">'.trim($artist[0]->plaintext);
                                echo '<title>'.trim($artist[0]->plaintext).' - '.$title[0]->plaintext.' | Qobuz-DL</title>';

                                $explicit = $rel->find('.ExplicitIcon');
                                if (isset($explicit[0])) {
                                    echo '<img title="Explicit" id="explicit" src="img/explicit.png">';
                                }

                                echo '</li>';
                                echo '<li class="album-title">'.trim($title[0]->plaintext).'</li>';
                            }

                            $relData = $rel->find('.album-meta__items li');
                            if (isset($relData[2])) {
                                echo '<li class="size12">'.$relData[2]->plaintext.'</li>';
                            }

                            if (isset($relData[0])) {
                                echo '<li class="size12">'.$relData[0]->plaintext.'</li>';
                            }

                            $relQuality = $rel->find('.album-quality__infos span');
                            echo '<div>';
                                if (isset($relQuality[0])) {

                                    $quality = explode('/', $relQuality[0]->plaintext);
                                    if ($quality[0] == '24-Bit') {
                                        echo '<img class="hires" src="img/ico/hires.png">';
                                    }

                                    echo '<ul>';
                                        echo '<li class="rel-quality" style="margin-top: 8px">'.$relQuality[0]->plaintext.'</li>';
                                }
                                if (isset($relQuality[1])) {
                                        echo '<li class="rel-channel">'.$relQuality[1]->plaintext.'</li>';
                                }

                                    echo '</ul>';
                            echo '</div>';

                        echo '</ul>';

                        echo '<div class="rel-actions">';
                            echo '<form method="GET" action="download.php">';
                                if (isset($_GET['url'])) {

                                    if (isset($artist[0]) && isset($title[0])) {
                                        echo '<input type="hidden" name="artist" value="'.trim($artist[0]->plaintext).'">';
                                        echo '<input type="hidden" name="title" value="'.trim($title[0]->plaintext).'">';
                                    }

                                    if (isset($art[0])) {
                                        echo '<input type="hidden" name="img" value="'.trim($art[0]->src).'">';
                                    }

                                    if (isset($artist[0]) && $title[0]) {

                                        $both = trim($artist[0]->plaintext) .' - '. trim($title[0]->plaintext);
                                        $both = preg_replace("/&(amp;)+/", '', $both); $both = preg_replace("/[^A-Za-z0-9]/", '', $both);

                                        echo '<script> function dlstatus() { getDLStatus("' .$both.'") } </script>';
                                    }

                                    echo '<input type="hidden" name="url" value="https://www.qobuz.com'.$_GET['url'].'" ></input>';
                                    echo '<button class="btn1" type="submit" style="padding: 10px;" onclick="dlstatus(); loadingDialog(\'Downloading tracks... Please wait.\');">
                                    <div style="display: grid;text-align: left;">
                                        <span>Download Album</span>';

                                    $numTrax = $dom->find('.album-about__items .album-about__item');
                                    if (isset($numTrax[0])) {
                                        echo '<span class="size8">'.$numTrax[0]->plaintext.'</span>';
                                    }
                                    echo '</div></button>';

                                    echo '<button style="padding:14px"><a target="_blank" href="https://www.qobuz.com'.$_GET['url'].'">View on Qobuz</a></button>';

                                }
                            echo '</form>';

                        echo '</div>';

                    echo '</div>';


                    if (isset($_GET['url'])) {
                        echo '<div class="size12" style="padding-bottom: 5px;background: linear-gradient(#fff,#e6e6e6);padding-left: 10px;">';
                            echo '<label style="text-shadow: 1px 1px white;" for="qdl-cmd">Qobuz-dl command: </label>';
                            echo '<input class="size12" name="qdl-cmd" onfocus="this.select();" onmouseup="return false;" type="text" value="qobuz-dl dl https://www.qobuz.com'.$_GET['url'].'" ></input>';
                        echo '</div>';
                    }


                    echo '<table class="tracklist">';
                            $trackItems = $dom->find('.track');
                            $tracknum = 0;

                            echo '<tr><th>#</th><th>Track</th><th>Duration</th><th>Credits</th><th>Preview</th></tr>';

                            foreach ($trackItems as $trackItem) {
                                
                                $title = $trackItem->find('.track__item--name');
                                $dur = $trackItem->find('.track__item--duration');
                                $infos = $trackItem->find('.track__infos .track__info');

                                if ($title[0] && $dur[0]) {

                                    $tracknum += 1;

                                    echo '<tr>
                                        <td>'.$tracknum.'</td>
                                    
                                        <td>'.$title[0]->plaintext.'</td>
                                        <td style="width:10%">'.$dur[0]->plaintext.'</td>
                                    ';

                                    if (isset($title[0]) && isset($infos[0])) {
                                        echo '<td style="text-align:center;width:10%;"><a class="noline" target="_blank" href="credits.php?t='.urlencode($title[0]->innertext).'&c='.urlencode($infos[0]->innertext).'"><b>ⓘ</b></a></td>';
                                    }
                                    else {
                                        echo '<td><center>N/A</center></td>';
                                    }

                                    $prev_audio = $trackItem->getAttribute('data-source');
                                    echo '<td style="width:10%">';
                                        if (isset($prev_audio)) {
                                            echo '<div style="text-align: center;">';
                                                echo '<img class="preview" onclick="preview(this, this.parentNode.lastChild)" src="img/misc/preview.png">';
                                                echo '<audio class="preview-audio"><source src="'.$prev_audio.'" type="audio/mpeg"></audio>';
                                            echo '</div>';
                                        }
                                    echo '</td>';

                                    echo '</tr>';
                                }

                            }
                    echo '</table>';
                }



            ?>

            <script>removeLoadingDialog();</script>
        </div>

        <?php
            require_once('footer.php');
        ?>
    </body>



</html>