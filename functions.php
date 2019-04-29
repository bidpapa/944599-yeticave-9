<?php
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

function selectByIdFromDB($link, $sql, $id)
{
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_stmt_get_result($stmt);
    if($row) {
        $array = mysqli_fetch_assoc($row);
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

function Redirect404($categories, $is_auth, $user_name)
{
    header("HTTP/1.1 404 Not Found");
    $content = include_template('404.php',
      ['categories' => $categories, 'response_code' => http_response_code()]);
    $layout_content = include_template('layout.php', [
      'title'      => 'Страница не найдена',
      'is_auth'    => $is_auth,
      'user_name'  => $user_name,
      'content'    => $content,
      'categories' => $categories,
    ]);
    print($layout_content);
}