<?php
$config = parse_ini_file('config.ini');
$link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname']);
mysqli_set_charset($link, $config['charset']);
return $link;