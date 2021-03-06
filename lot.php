<?php
session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Validator.php';
require_once 'classes/Timer.php';

if (isset($_SESSION['name']))
{
    $is_auth = true;
    $user_name = $_SESSION['name'];
    $user_id = $_SESSION['id'];
} else {
    $is_auth = false;
    $user_name = '';
    $user_id = null;
}
$error = [];

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $sql
      = "SELECT l.id, l.id_creator, l.name, l.description, l.image, l.bid_step, l.end_date AS time, 
    c.name AS category_name, l.start_price AS price, b.max, b1.id_user
    FROM lot AS l
    INNER JOIN category AS c ON l.id_category = c.id
    LEFT JOIN (SELECT id_lot, MAX(amount) AS max FROM bid GROUP BY id_lot) AS b ON b.id_lot = l.id
    LEFT JOIN bid AS b1 ON b1.amount = b.max
    WHERE l.id = ?";
    $lot_info = selectByIdFromDB($link, $sql, $id);

    $sql = "SELECT u.name, b.creation_date, b.amount FROM bid AS b 
    LEFT JOIN user AS u ON b.id_user = u.id 
    WHERE b.id_lot = ? ORDER BY b.creation_date DESC";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
        $show_bet_block = true;
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

        $timer = new Timer;

        $navigation = include_template('navigation.php',
          ['categories' => $categories]);
        $content = include_template('lot.php', [
          'error' => $error,
          'show_bet_block' => $show_bet_block,
          'lot_info' => $lot_info,
          'bets' => $bets,
          'timer' => $timer,
          'is_auth' => $is_auth
        ]);
        $layout_content = include_template('layout.php', [
          'container'  => '',
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