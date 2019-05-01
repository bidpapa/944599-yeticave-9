<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Validator.php';

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';
$id_creator = 1;
$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

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

    foreach ($_POST as $key => $value) {
        $lot[$key] = htmlspecialchars($value);
    }

    $validator = new Validator($required_fields, $integer_fields, $date_fields, $img_field, $lot, $_FILES);
    $error = $validator->getErrors();
    $img_name = $validator->loadImage();

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
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $id = mysqli_insert_id($link);
            header("Location: lot.php?id=$id");
        }
        elseif (!$res) {
            showError(mysqli_error($link));
        }
    }
}

$content = include_template('add.php',
  ['categories' => $categories, 'error' => $error, 'lot' => $lot]);
$layout_content = include_template('layout.php', [
  'title'      => 'Добавление лота',
  'is_auth'    => $is_auth,
  'user_name'  => $user_name,
  'content'    => $content,
  'categories' => $categories,
]);

print($layout_content);