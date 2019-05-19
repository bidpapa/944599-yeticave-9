<?php

session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Validator.php';

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

if(isset($_GET['search'])) {

    $search = trim($_GET['search']) ?? '';

    if ($search) {
        $cur_page = $_GET['page'] ?? 1;
        $page_items = 9;

        $sql = "SELECT COUNT(*) as cnt FROM lot WHERE MATCH(name, description) AGAINST(?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items_count = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $pages_count = ceil($items_count[0]['cnt'] / $page_items);
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = "SELECT l.id, l.name, l.start_price, l.creation_date, l.end_date, l.image, c.name AS category FROM lot AS l 
        LEFT JOIN category AS c ON c.id = l.id_category 
        WHERE MATCH(l.name, description) AGAINST(?) 
        ORDER BY l.creation_date DESC LIMIT $page_items  OFFSET $offset";
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $navigation = include_template('navigation.php',
      ['categories' => $categories]);
    $content = include_template('search.php', ['lots' => $lots, 'pages' => $pages,
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

