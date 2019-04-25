<?php

require_once 'helpers.php';

$is_auth = rand(0, 1);

$user_name = 'Дмитрий'; // укажите здесь ваше имя

$link = mysqli_connect('127.0.0.1', 'root', '', 'yeticave');
mysqli_set_charset($link, 'utf8');

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

function returnArrayFromDB($link, $sql, $fetch_type = MYSQLI_NUM)
{
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_stmt_get_result($stmt);
    if($rows) {
        $array = mysqli_fetch_all($rows, $fetch_type);
        return $array;
    }
    else {
        showError(mysqli_error($link));
    }
}

function showError($error)
{
    $content = include_template('error.php', ['error' => $error]);
    $layout_content = include_template('layout.php', ['content' => $content]);
    print($layout_content);
    die;
}

function formatNumber($number)
{
    $number = ceil($number);
    if ($number < 1000) {
        return $number . ' ₽';
    } else {
        $number = number_format($number, null, null, ' ');
        return $number . ' ₽';
    }
}

function timeToEnd($end_time)
{
    $now = date_create();
    $end_time = date_create($end_time);
    $interval = date_diff($now, $end_time);
    if ($interval->d > 0) {
        $hours = $interval->h + ($interval->d * 24) . ':'
          . $interval->format('%I');
        return $hours;
    }
    else {
        return $interval->format('%H:%I');
    }
}

function timeToEndLessOneHour($end_time)
{
    $now = time();
    $end_time = strtotime($end_time);
    if (($end_time - $now) <= 3600) {
        return true;
    }
    return false;
}