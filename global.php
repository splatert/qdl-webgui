



<?php

    if (!class_exists('Qobuz_DL')) {

        class Qobuz_DL {
            public $python_binary;
            public $qobuz_dl_binary;


            function __construct() {
                $this->detectApp();
            }


            function getUser() {
                $user = exec('whoami');
                return $user;
            }


            function getPythonPath() {
                return $this->python_binary;
            }

            function getAppPath() {
                return $this->qobuz_dl_binary;
            }


            function getAppLink() {
                if ($this->python_binary && $this->python_binary != '' && $this->qobuz_dl_binary && $this->qobuz_dl_binary != '') {
                    $link = $this->python_binary . ' ' . $this->qobuz_dl_binary;
                    return $link;
                }
                else {
                    return false;
                }
            }

    
            function detectApp() {
    
                // Find links to python binaries based on install location.
                // Haven't tested this on regular python.

                $user = $this->getUser();
                $find = "/home/{$user}/.local/pipx/venvs/qobuz-dl/bin/python";

                if (file_exists($find)) {
                    $this->python_binary = $find;
                    $this->qobuz_dl_binary = "/home/{$user}/.local/bin/qobuz-dl";
                }
                else {
                    $this->python_binary = '/usr/bin/python3';
                    $this->qobuz_dl_binary = '/usr/bin/qobuz-dl';
                }
            }

    
        }
    }



    global $sitetheme;
    if (isset($_COOKIE['theme'])) {
        $sitetheme = ' ' . $_COOKIE['theme'];
    }

    $ret = '&ret='.urlencode($_SERVER['REQUEST_URI']);



    if (!function_exists('editCookie')) {
        function editCookie($name, $value) {
            $expires = time() + 31556926;
            if ($value == null) {
                $expires = time() - 31556926;  
            }
            setcookie($name, $value, $expires, "/"); 
        }
    }


?>