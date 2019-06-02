<?php
session_start();

require_once 'helpers.php';
require_once 'functions.php';
$link = require_once 'init.php';
require_once 'getwinner.php';

if (isset($_SESSION['name']))
{
$is_auth = true;
$user_name = $_SESSION['name'];
} else {
    $is_auth = false;
}

if (!$link) {
    showError(mysqli_connect_error());
} else {
    $sql = "SELECT * FROM category ORDER BY id";
    $categories = returnArrayFromDB($link, $sql);

    $sql
      = "SELECT l.id, l.name, c.name AS category, l.start_price AS price, l.end_date AS time, l.image AS url
FROM lot AS l INNER JOIN category AS c ON l.id_category = c.id WHERE l.end_date > NOW() ORDER BY l.creation_date DESC LIMIT 6";
    $adverts = returnArrayFromDB($link, $sql, MYSQLI_ASSOC);

    $content = include_template('index.php',
      ['categories' => $categories, 'adverts' => $adverts]);
    $layout_content = include_template('layout.php', [
      'title'      => 'Главная',
      'container'  => 'container',
      'is_auth'    => $is_auth,
      'user_name'  => $user_name,
      'content'    => $content,
      'categories' => $categories,
    ]);

}
print($layout_content);
