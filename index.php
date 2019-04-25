<?php

require_once 'helpers.php';
require_once 'functions.php';
$link = require_once 'init.php';

$is_auth = rand(0, 1);

$user_name = 'Дмитрий'; // укажите здесь ваше имя

if (!$link) {
    showError(mysqli_connect_error());
} else {
    $sql = "SELECT * FROM category ORDER BY id";
    $categories = returnArrayFromDB($link, $sql);

    $sql
      = "SELECT l.name, c.name AS category, l.start_price AS price, l.end_date AS time, l.image AS url
FROM lot AS l INNER JOIN category AS c ON l.id_category = c.id WHERE l.end_date > NOW() ORDER BY l.creation_date DESC LIMIT 6";
    $adverts = returnArrayFromDB($link, $sql, MYSQLI_ASSOC);

    $content = include_template('index.php',
      ['categories' => $categories, 'adverts' => $adverts]);
    $layout_content = include_template('layout.php', [
      'title'      => 'Главная',
      'is_auth'    => $is_auth,
      'user_name'  => $user_name,
      'content'    => $content,
      'categories' => $categories,
    ]);

}
print($layout_content);
