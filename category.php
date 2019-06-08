<?php
session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if (isset($_SESSION['name']))
{
    $is_auth = true;
    $user_name = $_SESSION['name'];
    $user_id = $_SESSION['id'];
} else {
    $is_auth = false;
}

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);

    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;

    $sql = "SELECT COUNT(*) as cnt FROM lot WHERE id_category = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $pages_count = ceil($items_count[0]['cnt'] / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = "SELECT l.id AS id_lot, l.name as name, l.image, l.start_price, l.end_date, c.id, c.name as category FROM lot AS l
    LEFT JOIN category AS c ON l.id_category = c.id
    WHERE l.id_category = ? AND l.end_date > NOW() ORDER BY l.creation_date DESC LIMIT $page_items  OFFSET $offset";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $navigation = include_template('navigation.php',
      ['categories' => $categories]);
    $content = include_template('category.php',
      ['lots' => $lots, 'is_auth' => $is_auth, 'pages' => $pages,
       'pages_count' => $pages_count,
       'cur_page' => $cur_page]);
    $layout_content = include_template('layout.php', [

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