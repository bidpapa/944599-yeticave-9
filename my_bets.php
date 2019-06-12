<?php

session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Timer.php';

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);
$error = [];

if (isset($_SESSION['name'])) {
    $is_auth = true;
    $user_name = $_SESSION['name'];
    $user_id = $_SESSION['id'];

    $sql = "SELECT l.id, l.name AS lot_name, l.image, l.end_date, c.name AS category_name, b.amount, b.creation_date, b1.max, u.contact
    FROM bid AS b 
    LEFT JOIN lot AS l ON b.id_lot = l.id
    LEFT JOIN category AS c ON l.id_category = c.id
    LEFT JOIN user AS u ON l.id_creator = u.id
    LEFT JOIN (SELECT id_lot, MAX(amount) AS max FROM bid GROUP BY id_lot) AS b1 ON b1.id_lot = l.id
    WHERE b.id_user = $user_id
    ORDER BY b.creation_date DESC";

    $bids = returnArrayFromDB($link, $sql, MYSQLI_ASSOC);

    $timer = new Timer;
    $navigation = include_template('navigation.php',
      ['categories' => $categories]);
    $content = include_template('my_bets.php',
      ['error' => $error, 'bids' => $bids, 'timer' => $timer, 'is_auth' => $is_auth]);
    $layout_content = include_template('layout.php', [
      'container'  => '',
      'title'      => 'Мои ставки',
      'is_auth'    => $is_auth,
      'user_name'  => $user_name,
      'navigation' => $navigation,
      'content'    => $content,
      'categories' => $categories,
    ]);
    print($layout_content);

} else {
    Redirect403($categories);
}