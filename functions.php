<?php
/**
 * Возвращает массив из БД
 * @param object $link Ссылка соединения с БД
 * @param string $sql SQL-запрос, который будет производить выборку
 * @param int $fetch_type тип получаемого массива(неассоциативный по умолчанию)
 * @return array Возвращает массив, сформированный из выборки из БД
 * Если возникла ошибка переводит на страницу с ошибкой БД
 */
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

/**
 * Возвращает отформатированное число
 *
 * @param int $number Число для форматирования
 *
 * @return int Возвращает число с пробелом между значением тысячи и сотен и
 *             знаком рубля(1 000 ₽)
 */
function formatNumber($number)
{
    $number = ceil($number);
    if ($number < 1000) {
        return $number . ' ₽';
    }
    $number = number_format($number, null, null, ' ');
    return $number . ' ₽';
}

function timeToEnd($end_time)
{
    $now = date_create();
    $end_time = date_create($end_time);
    $interval = date_diff($now, $end_time);
    if ($now > $end_time) {
        return 'Окончен';
    }
    elseif ($interval->d > 0 || $interval->m > 0) {
        $days = $interval->format('%a');
        $hours = $interval->h + ($days * 24) . ':'
          . $interval->format('%I');
        return $hours;
    }
    return $interval->format('%H:%I');
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
    $navigation = include_template('navigation.php',
      ['categories' => $categories]);
    $content = include_template('404.php',
      ['response_code' => http_response_code()]);
    $layout_content = include_template('layout.php', [
      'title'      => 'Страница не найдена',
      'is_auth'    => $is_auth,
      'user_name'  => $user_name,
      'navigation' => $navigation,
      'content'    => $content,
      'categories' => $categories,
    ]);
    print($layout_content);
}

function createSelectList($arrayData, $selectedValue = '') {
    $html = '';
    foreach ($arrayData as $row) {
        if ($row[0] == $selectedValue) {
            $html .= '<option value="'.$row[0].'" selected>'.$row[1].'</option>';
        }
        else {
            $html .= '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
    }
    return $html;
}