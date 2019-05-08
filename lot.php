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

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $sql
      = "SELECT l.id, l.id_creator, l.name, l.description, l.image, l.bid_step, l.end_date AS time, 
    c.name AS category_name, l.start_price AS price, b.max, b.id_user
    FROM lot AS l
    INNER JOIN category AS c ON l.id_category = c.id
    LEFT JOIN (SELECT id_lot, id_user, MAX(amount) AS max FROM bid WHERE id_lot = ? GROUP BY id_user, id_lot ORDER BY max DESC LIMIT 1) AS b ON b.id_lot = l.id    
    WHERE l.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$id, $id]);
    mysqli_stmt_execute($stmt);
    $row = mysqli_stmt_get_result($stmt);
    $lot_info = mysqli_fetch_assoc($row);

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['name'])) {
        $required_fields = [
          'cost' => 'Введите сумму'
        ];
        $integer_fields = [
          'cost' => 'Сумма должна быть целым числом больше нуля'
        ];
        $validator = new Validator($required_fields, $integer_fields, null,
          null, null, $_POST, null);
        $error = $validator->getErrors();
        $bet = $validator->getValues();
        if(!$error && ($bet['cost'] < ($lot_info['price']+$lot_info['bid_step']))) {
            $error['cost'] = 'Сумма не может быть меньше текущей суммы + шаг ставки';
        }
        if (!$error) {
            $sql = "INSERT INTO bid (amount, id_user, id_lot)
            VALUES(?,?,?)";
            $data = [
              $bet['cost'],
              $user_id,
              $id
            ];
            $stmt = db_get_prepare_stmt($link, $sql, $data);
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                header("Location: lot.php?id=$id");
            }
            else {
                showError(mysqli_error($link));
            }
        }
    }

    if ($lot_info) {
        $lot_info['max'] > $lot_info['price'] ?
          $lot_info['price'] = $lot_info['max'] : $lot_info['price'];
        if ($lot_info['id_creator'] === $user_id
          || $lot_info['id_user'] === $user_id
          || timeToEnd($lot_info['time']) === false
          || $is_auth === false
        ) {
            $show_bet_block = false;
        }

        if (!timeToEnd($lot_info['time'])) {
            $lot_info['status'] = 'Окончен';
        } else {
            $lot_info['status'] = timeToEnd($lot_info['time']);
        }

        $navigation = include_template('navigation.php',
          ['categories' => $categories]);
        $content = include_template('lot.php',
          ['error' => $error, 'show_bet_block' => $show_bet_block, 'lot_info' => $lot_info, 'is_auth' => $is_auth]);
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