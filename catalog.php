



<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include('lib/simplehtmldom/simple_html_dom.php');
    require_once('topbar.php');
    include('global.php');
?>


<html class="<?php echo $sitetheme ?>">
    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <script src="actions.js"></script>
        <div class="page <?php echo $sitetheme ?>">
            <?php

                if (isset($_GET['url']) && $_GET['url'] != '') {

                    $reqURL = 'https://www.qobuz.com'.$_GET['url'];
                    
                    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                        $reqURL = $reqURL . '/' . 'page/' . $_GET['page'];
                    }

                    // sort albums by x,y,z,etc.
                    if (isset($_GET['sort-by']) && $_GET['sort-by'] != 'default') {
                        if ($_GET['sort-by'] == 'acclaimed') {
                            $reqURL .= '?ssf[sortBy]=main_catalog_awards';
                        }
                        elseif ($_GET['sort-by'] == 'lowhi') {
                            $reqURL .= '?ssf[sortBy]=main_catalog_price_asc';
                        }
                        elseif ($_GET['sort-by'] == 'newold') {
                            $reqURL .= '?ssf[sortBy]=main_catalog_date_desc';
                        }
                    }

                    $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $reqURL);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                    $response = curl_exec($ch);
                    curl_close($ch);

                    $dom = new simple_html_dom();
                    $dom->load($response);


                    echo '<div class="label-details '.$sitetheme.'">
                        <aside>';
                        
                            if (isset($_GET['view-as']) && $_GET['view-as'] == 'artist') {
                                $pfp = $dom->find('.catalog-heading__picture');
                                if (isset($pfp[0])) {
                                    $attr = $pfp[0]->getAttribute('style');
                                    $imgurl = preg_match_all('/(?:\([\'"]?)(.*?)(?:[\'"]?\))/', $attr, $matches);

                                    if (isset($matches[1])) {

                                        if ($matches[1][0] == '/assets-static/img/common/default_artist.svg') {
                                            $matches[1][0] = 'img/misc/artist128.png';
                                        }

                                        echo '<img class="artist-pic" src="'.$matches[1][0].'">';
                                    }
                                }
                            }
                            else {
                                echo '<img src="img/misc/label64.png">';
                            }

                        echo '</aside>
                    <main><ul>';
                        $label_title = $dom->find('.catalog-heading__title');
                        if (isset($label_title[0])) {
                            echo "<li><b>{$label_title[0]->plaintext}</b></li>";
                        }

                        $labelinfo = $dom->find('.product__info');
                        if (isset($labelinfo[0])) {
                            $albumct = explode('album(s)', $labelinfo[0]->plaintext);
                            if (isset($albumct[0])) {
                                echo "<li class=size12>{$albumct[0]} albums</li>";
                            }
                        }

                        echo '
                        <li>
                        <form method="GET" action="catalog.php" style="width: fit-content;float: right;">
                            <input type="hidden" name="url" value="'.$_GET['url'].'">
                            <select name="sort-by">
                                <option value="default">Sort by</option>
                                <option value="acclaimed">Highly Acclaimed</option>
                                <option value="lowhi">Price - Low to high</option>
                                <option value="newold">Newest to oldest</option>
                            </select>
                            <button class="arrow-rt-submit" type="submit"></button>
                        </form>
                        </li>';

                    echo '</ul></main>';
                    echo '</div>';

                    if (isset($_GET['view-as']) && $_GET['view-as'] == 'artist') {
                        
                        $desc = '';
                        $profile_info = $dom->find('#catalog-heading__text');
                        if (isset($profile_info[0])) {
                            $desc = trim(htmlentities($profile_info[0]->plaintext));
                            if ($desc != 'Read more') {
                                echo '<p class="artist-desc">'.$desc.'</p>';
                            }
                        }
                    }


                    echo '<hr size="1" color="#c6c6c6">';


                    $items = $dom->find('.product__item');
                    foreach ($items as $item) {

                        $title = $item->find('.product__name');
                        $artist = $item->find('.product__artist');
                        $prodinfo = $item->find('.product__infos');
                        $link = $item->find('.product__container');

                        echo '<div class="release '.$sitetheme.'">';
                            
                            echo '<aside>';
                                $cover = $item->find('.product__cover');
                                if (isset($cover[0]) && isset($link[0])) {
                                    echo '
                                    <a href="release.php?url='.$link[0]->firstChild()->href.'">
                                        <img class="album-cover" src="'.$cover[0]->getAttribute('data-src').'">
                                    </a>';
                                }
                            echo '</aside>';
                            
                            echo '<main style="padding-left: 10px;"><ul>';
                                if (isset($artist[0])) {
                                    echo '<li class="artist-name '.$sitetheme.'">'.$artist[0]->firstChild()->plaintext.'</li>';
                                }

                                if (isset($title[0]) && isset($link[0])) {
                                    echo '<li class="album-title '.$sitetheme.'"><a href="release.php?url='.$link[0]->firstChild()->href.'"><b>'.$title[0]->plaintext.'</b></a></li>';
                                }

                                if (isset($prodinfo[0])) {
                                    $genre = explode(' - Released by ', $prodinfo[0]->plaintext);
                                    if (isset($genre[0])) {
                                        echo '<li class="size12">'.$genre[0].'</li>';
                                    }
                                    if (isset($genre[1])) {
                                        $reldate = explode(' on ', $genre[1]);
                                        if (isset($reldate[1])) {
                                            echo '<li class="size12">'.$reldate[1].'</li>';
                                        } 
                                    }
                                }

                            echo '</ul></main>';

                        echo '</div>';
                    }

                    echo '<form class="nav-buttons" method="GET" action="catalog.php">';
                        $query = '';
                        $type = 'search';
                        $page = 1;

                        if (isset($_GET['q'])) {
                            $query = $_GET['q'];
                        }

                        if (isset($_GET['url'])) {
                            echo '<input name="url" type="hidden" value="'.$_GET['url'].'"></input>';
                        }
                        
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

                }

            ?>
        </div>
    </body>
</html>