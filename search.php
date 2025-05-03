

<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include('lib/simplehtmldom/simple_html_dom.php');
    require_once('topbar.php');
    include('misc/theme_ctrl.php');
    include('global.php');


    if (isset($_GET['q']) && isset($_GET['cat'])) {
        if ($_GET['cat'] == 'labels') {
            header('Location: labels.php?q='.$_GET['q']);
        }
        elseif ($_GET['cat'] == 'artists') {
            header('Location: artists.php?q='.$_GET['q']);
        }
    }



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




<html class="<?php echo $sitetheme ?>">
    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="<?php echo $sitetheme ?>">
        <script src="actions.js"></script>
        <div class="page <?php echo $sitetheme ?>">

            <?php

            if (isset($_GET['cat'])) {
                if ($_GET['cat'] == 'lucky') {
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
                                        else if (str_starts_with($url, 'https://www.qobuz.com/store-router/interpreter/')) {
                                            $url = str_replace('https://www.qobuz.com/store-router/interpreter/', 'catalog.php?view-as=artist&url=/us-en/interpreter/', $url);
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

                            echo '<div class="title1 '.$sitetheme.'">';
                                $rt2 = $rt[0]->getElementByTagName('h2');
                                if ($rt2) {
                                    echo '<span>'.$rt2->plaintext.'</span>';
                                } 
                            echo '</div>';
                        }

                        $rels = $rows[$row]->find('.album-container');
                        foreach ($rels as $rel) {

                            $artist = $rel->find('.artist-name');
                            $title = $rel->find('.album-title');
                            $releaseURL = '';

                            if (isset($title[0])) {
                                $releaseURL = 'release.php?url='.urlencode($title[0]->href);
                            }

                            echo '<div class="release2 '.$sitetheme.'">';

                                $art = $rel->find('.album-cover img');
                                
                                echo '<div style="width:75%;display: flex;">';
                                    if (isset($art[0])) {
                                        $mini_cover = str_replace('_600', '_100', $art[0]->src);
                                        echo '<img class="album-cover" src="'.$mini_cover.'">';
                                    }

                                    echo '<div style="padding-left: 10px;padding-top: 5px;">
                                    <ul>';

                                        if ($artist[0] && $title[0]) {   
                                            echo '<li class="artist-name '.$sitetheme.'"> <a href="catalog.php?view-as=artist&url='.$artist[0]->href.'">'.trim($artist[0]->plaintext).'</a> </li>';
                                            echo '<li class="album-title'.$sitetheme.'"><a href="'.$releaseURL.'">'.trim($title[0]->plaintext).'</a></li>';
                                        }
                                    echo '</ul></div>';
                                echo '</div>';

                                echo '<div style="width:25%">
                                    <form method="GET" action="download.php">';
                                    
                                        if (isset($artist[0]) && $title[0]) {
                                            $both = trim($artist[0]->plaintext) .' - '. trim($title[0]->plaintext);
                                            $both = preg_replace("/&(amp;)+/", '', $both); $both = preg_replace("/[^A-Za-z0-9]/", '', $both);
                                            
                                            $art_src = '';
                                            if (isset($art[0])) {
                                                $art_src = trim($art[0]->src);
                                            }
                                            echo '<button style="margin:unset !important;" onclick="loadingDialog(); getDLStatus(\''.$both.'\', \''.$art_src.'\');" type="submit" class="search-dl-btn '.$sitetheme.'"><img src="img/ico/dl.png"></button>';

                                        }

                                        if (isset($artist[0])) {
                                            echo '<input type="hidden" name="artist" value="'.trim($artist[0]->plaintext).'">';
                                        }
                                        if (isset($title[0])) {
                                            echo '<input type="hidden" name="url" value="https://www.qobuz.com'.$title[0]->href.'">';
                                            echo '<input type="hidden" name="title" value="'.trim($title[0]->plaintext).'">';
                                        }
                                        if (isset($art[0])) {
                                            echo '<input type="hidden" name="img" value="'.$art[0]->src.'">';
                                        }
                                echo '</form>
                                </div>';

                            echo '</div>';


                        }

                    }
                }

            }


            // query search mode
            elseif (!isset($_GET['genre']) && isset($_GET['q']) && isset($_GET['cat']) && $_GET['cat'] == 'search') {
                

                echo '<title>'.trim($_GET['q']).' | Qobuz-DL</title>';
                echo '
                <div class="results-header '.$sitetheme.'" style="display: flex;">
                    <div class="lf" style="width: 100%;">
                        <span class="title-results">Results for <b>"'.$_GET['q'].'"</b></span>
                    </div>
                    <div class="rt" style="width: 100%; float: right;">
                        <form method="GET" action="search.php" style="margin: 10px;width: fit-content;float: right;">
                            <input type="hidden" name="type" value="search">
                            
                            <select name="type" style="padding: unset;margin-right: 15px;">
                                <option value="search">Albums</option>
                                <option value="artists">Artists</option>
                                <option value="labels">Labels</option>
                            </select>

                            <input type="hidden" name="q" value="'.$_GET['q'].'">
                            <select name="sort-by">
                                <option value="default">Sort by</option>
                                <option value="acclaimed">Highly Acclaimed</option>
                                <option value="lowhi">Price - Low to high</option>
                                <option value="newold">Newest to oldest</option>
                            </select>
                            <button class="arrow-rt-submit '.$sitetheme.'" type="submit"></button>
                        </form>
                    </div>
                </div>
                ';

                $ch = curl_init();
                $reqURL = 'https://www.qobuz.com/us-en/search/albums/'. $_GET['q'];

                // page number
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $reqURL = $reqURL . '/' . 'page/' . $_GET['page'];
                }

                // sort albums by x,y,z,etc.
                if (isset($_GET['sort-by']) && $_GET['sort-by'] != 'default') {
                    if ($_GET['sort-by'] == 'acclaimed') {
                        $reqURL .= '?ssf[s]=main_catalog_awards';
                    }
                    elseif ($_GET['sort-by'] == 'lowhi') {
                        $reqURL .= '?ssf[s]=main_catalog_price_asc';
                    }
                    elseif ($_GET['sort-by'] == 'newold') {
                        $reqURL .= '?ssf[s]=main_catalog_date_desc';
                    }
                }

                curl_setopt($ch, CURLOPT_URL, $reqURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                

                $response = curl_exec($ch);
                curl_close($ch);


                $dom = new simple_html_dom();
                $dom->load($response);

                $rels = $dom->find('.ReleaseCard');

                foreach ($rels as $rel) {
                    echo '<div class="release '.$sitetheme.'">';

                        $art = $rel->find('.CoverModel');
                        $title = $rel->find('.ReleaseCardInfosTitle');
                        $releaseURL = '';

                        if (isset($title[0])) {
                            $releaseURL = 'release.php?url='.urlencode($title[0]->href);
                        }

                        if (isset($art[0]) && isset($title[0])) {
                            $mini_cover = str_replace('_230', '_100', $art[0]->src);
                            echo '<a href="'.$releaseURL.'"><img class="album-cover" src="'.$mini_cover.'"></a>';
                        }

                        echo '<ul style="width: 75%;">';
                            $artist = $rel->find('.ReleaseCardInfosSubtitle');
                            $artistURL = $rel->find('.ReleaseCardInfosSubtitle a');

                            if ($artist[1] && $artistURL[0] && $title[0]) {
                                echo '<li class="artist-name"><a href="catalog.php?view-as=artist&url='.$artistURL[0]->href.'">'.$artist[1]->plaintext.'</a></li>';
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

                        echo '<div style="width:25%">
                            <form method="GET" action="download.php" style="height: 100%;">';
                        
                                if (isset($artist[1]) && isset($title[0])) {
                                    $both = trim($artist[1]->plaintext) .' - '. trim($title[0]->plaintext);
                                    $both = preg_replace("/&(amp;)+/", '', $both); $both = preg_replace("/[^A-Za-z0-9]/", '', $both);

                                    $art_src = '';
                                    if (isset($art[0])) {
                                        $art_src = trim($art[0]->src);
                                    }
                                    echo '<button style="margin:unset !important;" onclick="loadingDialog(); getDLStatus(\''.$both.'\', \''.$art_src.'\');" type="submit" class="search-dl-btn '.$sitetheme.'"><img src="img/ico/dl.png"></button>';
                                }

                                if (isset($artist[1])) {
                                    echo '<input type="hidden" name="artist" value="'.trim($artist[1]->plaintext).'">';
                                }
                                if (isset($title[0])) {
                                    echo '<input type="hidden" name="url" value="https://www.qobuz.com'.$title[0]->href.'">';
                                    echo '<input type="hidden" name="title" value="'.trim($title[0]->plaintext).'">';
                                }
                                if (isset($art[0])) {
                                    echo '<input type="hidden" name="img" value="'.$art[0]->src.'">';
                                }

                        echo '</form>
                        </div>';


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

    </body>

</html>