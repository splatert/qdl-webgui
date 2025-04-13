



<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include('lib/simplehtmldom/simple_html_dom.php');
    include('global.php');
    require_once('topbar.php');

?>






<html class="<?php echo $sitetheme ?>">
    <head>

    </head>

    <body class="">
        <link rel="stylesheet" href="style.css">

        <div class="page <?php echo $sitetheme ?>">
            <?php
                if (isset($_GET['q']) && $_GET['q'] != '') {
                    echo '<title>'.trim($_GET['q']).' | Qobuz-DL</title>';
                    echo '<span class="title-results '.$sitetheme.'">Artist results for <b>"'.$_GET['q'].'"</b></span><hr size=1 color="#c6c6c6">';

                    echo '<div>';

                        $ch = curl_init();
                        $reqURL = 'https://www.qobuz.com/us-en/search/artists/'. $_GET['q'];

                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $reqURL = $reqURL . '/' . 'page/' . $_GET['page'];
                        }

                        curl_setopt($ch, CURLOPT_URL, $reqURL);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        

                        $response = curl_exec($ch);
                        curl_close($ch);


                        $dom = new simple_html_dom();
                        $dom->load($response);

                        echo '<table class="artists" style="width: 100%;">';

                        $artistItem = $dom->find('.FollowingCard');
                        foreach ($artistItem as $artist) {

                            $url = $artist->find('.CoverModelOverlay');
                            $img = $artist->find('.CoverModelImage');

                            if ($img[0] && $img[0]->src == '/assets-static/img/common/default_artist.svg') {
                                $img[0]->src = 'img/misc/artist128.png';
                            }


                            if (isset($url[0]) && isset($img[0])) {
                                echo '<tr class="artist-result '.$sitetheme.'">
                                    <td style="width:0"><img class="pic '.$sitetheme.'" src="'.$img[0]->src.'"></td>
                                    <td style="padding-left: 15px"';
                                    echo '<span>
                                        <a href="catalog.php?view-as=artist&url='.urlencode($url[0]->href).'">'.$artist->getAttribute('title').'</a>
                                    </span>';
                                echo '</td></tr>';
                            }
                            
                        }

                        echo '</table>';



                    echo '</div>';
                }

                echo '<form class="nav-buttons" method="GET" action="artists.php">';
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

            ?>
        </div>
    </body>

</html>