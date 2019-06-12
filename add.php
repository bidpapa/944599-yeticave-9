<?php
session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Validator.php';

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

if (isset($_SESSION['name'])) {

$is_auth = true;
$user_name = $_SESSION['name'];
$id_creator = $_SESSION['id'];
$error = [];
$lot = ['message' => '', 'category' => '', 'lot-name' => '', 'lot-rate' => '', 'lot-step' => '', 'lot-date' => ''];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = [
      'lot-name' => 'Введите наименование лота',
      'message'  => 'Напишите описание лота',
      'category' => 'Выберите категорию',
      'lot-rate' => 'Введите начальную цену',
      'lot-step' => 'Введите шаг ставки',
      'lot-date' => 'Введите дату завершения торгов'
    ];
    $integer_fields = [
      'lot-rate' => 'Введите целое число больше нуля',
      'lot-step' => 'Введите целое число больше нуля',
    ];
    $date_fields = [
      'lot-date' => 'Введите дату в формате ГГГГ-ММ-ДД',
    ];
    $img_field = [
      'lot-img' => 'Загрузите картинку в формате JPG, JPEG или PNG'
    ];

    $validator = new Validator($required_fields, $integer_fields, null, $date_fields, $img_field, $_POST, $_FILES);
    $validator->check_category = true;
    $error = $validator->getErrors();
    $img_name = $validator->loadImage();
    $lot = $validator->getValues();

    if (!$error) {
        $sql = "INSERT INTO lot (name, description, id_category, image, start_price, bid_step, end_date, id_creator)
    VALUES(?,?,?,?,?,?,?,?)";
        $data = [
          $lot['lot-name'],
          $lot['message'],
          $lot['category'],
          $img_name,
          $lot['lot-rate'],
          $lot['lot-step'],
          $lot['lot-date'],
          $id_creator
        ];
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            $id = mysqli_insert_id($link);
            header("Location: lot.php?id=$id");
        }
        else {
            showError(mysqli_error($link));
        }
    }
}

$navigation = include_template('navigation.php',
  ['categories' => $categories]);
$content = include_template('add.php',
  ['categories' => $categories, 'error' => $error, 'lot' => $lot]);
$layout_content = include_template('layout.php', [
  'container'  => '',
  'title'      => 'Добавление лота',
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