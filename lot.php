<?php
session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if ($_SESSION)
{
    $is_auth = true;
    $user_name = $_SESSION['name'];
} else {
    $is_auth = false;
}

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $sql
      = "SELECT l.name, l.description, l.image, l.bid_step, l.end_date AS time, c.name AS category_name, l.start_price AS price, b.max
    FROM lot AS l
    INNER JOIN category AS c ON l.id_category = c.id
    LEFT JOIN (SELECT id_lot, MAX(amount) AS max FROM bid GROUP BY id_lot) AS b ON b.id_lot = l.id
    WHERE l.id = ?";
    $lot_info = selectByIdFromDB($link, $sql, $id);
    $lot_info['max'] > $lot_info['price'] ? $lot_info['price'] = $lot_info['max'] : $lot_info['price'];
    if ($lot_info) {
        $navigation = include_template('navigation.php',
          ['categories' => $categories]);
        $content = include_template('lot.php',
          ['lot_info' => $lot_info, 'is_auth' => $is_auth]);
        $layout_content = include_template('layout.php', [
          'title'      => $lot_info['name'],
          'is_auth'    => $is_auth,
          'user_name'  => $user_name,
          'navigation' => $navigation,
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