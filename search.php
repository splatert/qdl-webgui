

<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include('lib/simplehtmldom/simple_html_dom.php');
    require_once('topbar.php');



    $genres = array(
        'all' => 'All Genres',
        'pop-rock' => 'Pop/Rock',
        'jazz' => 'Jazz',
        'classique' => 'Classical',
        'electro' => 'Electronic',
        'soul-funk-rap' => 'Soul/Funk/R&B',
        'folk' => 'Folk/Americana',
        'rap-hip-hop' => 'Hip-Hop/Rap',
        'country' => 'Country',
        'metal' => 'Metal',
        'blues' => 'Blues',
        'amerique-latine' => 'Latin',
        'musique-de-films' => 'Soundtracks',
        'musiques-du-monde' => 'World',
        'diction-litterature' => 'Comedy/Other',
    );


?>




<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <script src="actions.js"></script>
        <div class="page">

            <?php


            if (isset($_GET['type'])) {
                if ($_GET['type'] == 'lucky') {
                    if (isset($_GET['q']) && $_GET['q'] != '') {
                        echo "Feelin' lucky";
                        echo '<script>loadingDialog("Downloading mystery album...")</script>';
                        echo '<script>window.location.href = "download.php?mode=lucky&q='.$_GET['q'].'";</script>';
                    }
                }
            }


            
            if (isset($_GET['genre'])) {
                if (!isset($genres[$_GET['genre']])) {
                    echo '<span class="error">Invalid genre.</span>';
                }
            }


            // genre results
            if (isset($_GET['genre']) && isset($genres[$_GET['genre']])) {
                if (isset($_GET['q'])) {
                    unset($_GET['q']);
                }

                echo '<title>'.$genres[$_GET['genre']].' | QobuzDL</title>';

                $reqURL = '';

                if ($_GET['genre'] == 'all') {
                    $reqURL = 'https://www.qobuz.com/us-en/shop';
                }
                else {
                    $reqURL = 'https://www.qobuz.com/us-en/shop/'.$_GET['genre'].'/download-streaming-albums';
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $reqURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $response = curl_exec($ch);
                curl_close($ch);

                $dom = new simple_html_dom();
                $dom->load($response);

                $banners = $dom->find('.slides_container img');
                $banners_a = $dom->find('.slides_container a');

                if ($banners && $banners_a) {
                    echo '<div>';
                        echo '<table class="genre-banners">';

                                foreach ( (array_rand($banners, 3)) as $banner) {
                                    echo '<tr><td>';

                                        $url = $banners_a[$banner]->href;

                                        if (str_starts_with($url, 'https://www.qobuz.com/store-router/search/query/')) {
                                            $url = str_replace('https://www.qobuz.com/store-router/search/query/', 'search.php?q=', $url);
                                        }
                                        else if (str_starts_with($url, 'https://www.qobuz.com/store-router/search?q=')) {
                                            $url = str_replace('https://www.qobuz.com/store-router/search', 'search.php', $url);
                                        }
                                        else {
                                            $url = 'release.php?url=' . $banners_a[$banner]->href;
                                        }


                                        echo '<a href="'.$url.'">';
                                            echo '<img src="'.$banners[$banner]->src.'">';
                                        echo '</a>';
                                    echo '</td></tr>';
                                }

                        echo '</table>';
                    echo '</div>';
                }

                if (isset($genres[$_GET['genre']])) {
                    echo '<span class="genre-title">'.$genres[$_GET['genre']] .' releases</span>';
                }


                $rows = $dom->find('.row-fluid');

                if (isset($rows)) {
                    foreach ($rows as $row => $i) {

                        $rt = $rows[$row]->find('.typo-padding');
                        if ($rt[0]) {

                            echo '<div class="title1">';
                                $rt2 = $rt[0]->getElementByTagName('h2');
                                if ($rt2) {
                                    echo '<span>'.$rt2->plaintext.'</span>';
                                } 
                            echo '</div>';
                        }

                        $rels = $rows[$row]->find('.album-container');
                        foreach ($rels as $rel) {
                            echo '<div class="release">';

                                $art = $rel->find('.album-cover img');
                                if (isset($art[0])) {
                                    $mini_cover = str_replace('_600', '_100', $art[0]->src);
                                    echo '<img class="album-cover" src="'.$mini_cover.'">';
                                }

                                echo '<ul>';
                                    $artist = $rel->find('.artist-name');
                                    $title = $rel->find('.album-title');

                                    if ($artist[0] && $title[0]) {

                                        $releaseURL = 'release.php?url='.urlencode($title[0]->href);

                                        echo '<li class="artist-name">'.trim($artist[0]->plaintext).'</li>';
                                        echo '<li class="album-title"><a href="'.$releaseURL.'">'.trim($title[0]->plaintext).'</a></li>';
                                    }
                                echo '</ul>';

                            echo '</div>';


                        }

                    }
                }

            }


            // query search mode
            elseif (!isset($_GET['genre']) && isset($_GET['q']) && isset($_GET['type']) && $_GET['type'] == 'search') {
                

                echo '<title>'.trim($_GET['q']).' | Qobuz-DL</title>';
                echo '<span class="title-results">Results for <b>"'.$_GET['q'].'"</b></span>';

                $ch = curl_init();
                $reqURL = 'https://www.qobuz.com/us-en/search/albums/'. $_GET['q'];

                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $reqURL = $reqURL . '/' . 'page/' . $_GET['page'];
                }

                curl_setopt($ch, CURLOPT_URL, $reqURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                

                $response = curl_exec($ch);
                curl_close($ch);


                $dom = new simple_html_dom();
                $dom->load($response);

                $rels = $dom->find('.ReleaseCard');

                foreach ($rels as $rel) {
                    echo '<div class="release">';

                        $art = $rel->find('.CoverModel');
                        if (isset($art[0])) {
                            $mini_cover = str_replace('_230', '_100', $art[0]->src);
                            echo '<img class="album-cover" src="'.$mini_cover.'">';
                        }

                        echo '<ul style="width: 100%;">';
                            $artist = $rel->find('.ReleaseCardInfosSubtitle');
                            $title = $rel->find('.ReleaseCardInfosTitle');

                            if ($artist[1] && $title[0]) {

                                $releaseURL = 'release.php?url='.urlencode($title[0]->href);

                                echo '<li class="artist-name">'.$artist[1]->plaintext.'</li>';
                                echo '<li class="album-title"><a href="'.$releaseURL.'">'.$title[0]->plaintext.'</a></li>';
                            }

                            $relData = $rel->find('.ReleaseCardInfosData');
                            if ($relData[0]) {
                                echo '<li class="size12">'.$relData[0]->plaintext.'</li>';
                            }

                            $relQuality = $rel->find('.ReleaseCardQualityText span');
                            
                            if (isset($relQuality[0]) || isset($relQuality[1])) {
                                echo '<div>';
                            }
                                if (isset($relQuality[0])) {

                                    $quality = explode('/', $relQuality[0]->plaintext);
                                    if ($quality[0] == '24-Bit') {
                                        echo '<img class="hires" src="img/ico/hires.png">';
                                    }

                                    echo '<ul>';
                                        echo '<li class="rel-quality" style="margin-top:10px;">'.$relQuality[0]->plaintext.'</li>';
                                }
                                if (isset($relQuality[1])) {
                                        echo '<li class="rel-channel">'.$relQuality[1]->plaintext.'</li>';
                                }

                                $dsd = $rel->find('.ReleaseCardQualityContainer span[title="DSD Audio"]');
                                if (isset($dsd[0])) {
                                    echo '<li class="size12" style="margin-top:10px;"><b>[DSD</b> Audio]</li>';
                                }

                                    echo '</ul>';
                            if (isset($relQuality[0]) || isset($relQuality[1])) {
                                echo '</div>';
                            }

                        echo '</ul>';

                    echo '</div>';
                }



                echo '<form class="nav-buttons" method="GET" action="search.php">';
                    $query = '';
                    $type = 'search';
                    $page = 1;

                    if (isset($_GET['q'])) {
                        $query = $_GET['q'];
                    }

                    echo '<input name="type" type="hidden" value="'.$type.'"></input>';
                    echo '<input name="q" type="hidden" value="'.$query.'"></input>';
                    
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    }

                    $page_nxt = $page + 1;
                    $page_prev = $page - 1;

                    if ($page > 1) {
                        echo '<button onclick="loadingDialog(\'\')" class="btn1" name="page" type="submit" value="'.$page_prev.'">← Prev page</button>';
                    }
                    echo '<button onclick="loadingDialog(\'\')" class="btn1" name="page" type="submit" value="'.$page_nxt.'">Next page →</button>';

                echo '</form>';


                if (isset($_GET['page'])) {

                }


            }


            ?>


        </div>

        <?php
            require_once('footer.php');
        ?>

    </body>

</html>