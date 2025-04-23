



<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);



    class ConfigFile {

        public $cfg_path;


        function getUser() {
            $user = exec('whoami');
            return $user;
        }


        function getPath() {
            if (!isset($this->cfg_path)) {
                $this->setPath('default');
            }
            return $this->cfg_path;
        }


        function suggestDlLocation() {
            $user = $this->getUser();
            $p = "/home/{$user}/Downloads/qobuzdl";
            return $p;
        }


        function setPath($path) {
            if ($path == 'default') {
                $user = $this->getUser();
                $this->cfg_path = "/home/".$user.'/.config/qobuz-dl/config.ini';
            }
            else {
                $this->cfg_path = $path;
            }
        }



        function setProperty($property, $value) {

            if ($property && $value) {
                $found_property = false;

                $cfg_file_array = $this->read('php', false);
                if (file_exists($this->getPath())) {

                    $newline = $property . ' = ' . $value . PHP_EOL;

                    for ($i=0; $i<count($cfg_file_array); $i++) {
                        if (str_starts_with($cfg_file_array[$i], $property . ' = ')) {
                            $found_property = true;
                            $cfg_file_array[$i] = $newline;
                        }
                    }

                    if (!$found_property) {
                        $cfg_file_array[count($cfg_file_array)] = PHP_EOL . $newline;
                    }

                    // debug
                    // echo '<pre>';
                    //     print_r($cfg_file_array);
                    // echo '</pre>';
                    // die();

                    file_put_contents($this->getPath(), implode('', $cfg_file_array));

                }
            }

        }


        function getProperty($property) {

            $cfg_file_array = $this->read('php', false);

            if (file_exists($this->getPath())) {
                
                foreach ($cfg_file_array as $array_line) {
                
                    if (str_starts_with($array_line, $property)) {
                        
                        $line = $array_line;
                        $split = explode(' = ', $line);
                        
                        if (isset($split[1])) {
                            return trim($split[1]);
                        }
                    }
                }

            }


        }



        function read($format, $print) {

            $loadFormat = FILE_SKIP_EMPTY_LINES;
            
            if (file_exists($this->getPath())) {
                $config = file($this->cfg_path, $loadFormat);

                if ($print == null) {
                    $print = true;
                }

        
                if (isset($format)) {
        
                    if ($format == 'js') {
                        if ($print) {
                            echo json_encode($config);
                        }
                    }
                    elseif ($format == 'php') {
                        return $config;
                    }
        
                }
                elseif (!isset($format)) {
                    if ($print) {
                        echo '<pre>';
                            print_r($config);
                        echo '</pre>';
                    }
                }
            }

            
        }

    }



    if (isset($_GET['action'])) {

        $f = null;

        if ($_GET['action'] == 'read') {

            if (isset($_GET['format'])) {
                $f = $_GET['format'];
            }

            $file = new ConfigFile();
            $lines = $file->read($f, true);

        }
    }


?>