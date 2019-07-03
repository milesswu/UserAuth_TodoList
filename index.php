<?php
require_once 'core/init.php';

//this function will load according to specified host without needing to directly acces the globals array
var_dump(Config::get('mysql/host')); 
?>