<?php
class Config {
    public static function get($path = null) {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path); //returns array of elements separated by '/'
            //print_r($path); prints array

            foreach($path as $bit) {
                if(isset($config[$bit])) {
                    //following the next portion of path if path exists
                    $config = $config[$bit]; 
                }
            }

            return $config;
        }

        return false;
    }
}