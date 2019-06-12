<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$config = parse_ini_file('config.ini');
$link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname']);
mysqli_set_charset($link, $config['charset']);
return $link;