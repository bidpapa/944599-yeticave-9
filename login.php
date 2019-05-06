<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Validator.php';

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = [
      'email'    => 'Введите e-mail',
      'password' => 'Введите пароль'
    ];

    $email_fields = [
      'email' => 'Введите e-mail в корректном формате'
    ];

    $validator = new Validator($required_fields, null, $email_fields, null, null, $_POST, null);
    $error = $validator->getErrors();
    $login = $validator->getValues();

    if (!$error) {
        $sql = "SELECT password, id, name FROM user WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$login['email']]);
        $result = mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_assoc($row);
        if ($array && password_verify($_POST['password'], $array['password'])) {
            session_start();
            $_SESSION['name'] = $array['name'];
            $_SESSION['id'] = $array['id'];
            header("Location: index.php");
        } elseif (!$array) {
            $error['email'] = 'Пользователя с таким email не существует';
        } elseif (!password_verify($_POST['password'], $array['password'])) {
            $error['password'] = 'Введен неверный пароль';
        }
    }
}

$navigation = include_template('navigation.php',
  ['categories' => $categories]);
$content = include_template('login.php',
  ['error' => $error, 'login' => $login]);
$layout_content = include_template('layout.php', [
  'title'      => 'Вход',
  'navigation' => $navigation,
  'content'    => $content,
  'categories' => $categories,
]);

print($layout_content);