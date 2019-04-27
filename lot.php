<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';
$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $sql
      = "SELECT l.name, l.description, l.image, l.bid_step, l.end_date AS time, c.name AS category_name, b.max
    FROM lot AS l
    INNER JOIN category AS c ON l.id_category = c.id
    INNER JOIN (SELECT id_lot, MAX(amount) AS max FROM bid GROUP BY id_lot) AS b ON b.id_lot = l.id
    WHERE l.id = ?";
    $lot_info = selectByIdFromDB($link, $sql, $id);
    if ($lot_info) {
        $content = include_template('lot.php',
          ['categories' => $categories, 'lot_info' => $lot_info]);
        $layout_content = include_template('layout.php', [
          'title'      => $lot_info['name'],
          'is_auth'    => $is_auth,
          'user_name'  => $user_name,
          'content'    => $content,
          'categories' => $categories,
        ]);
        print($layout_content);
    } else {
        Redirect404($categories, $is_auth, $user_name);
    }
} else {
    Redirect404($categories, $is_auth, $user_name);
}