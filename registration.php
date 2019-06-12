<?php

session_start();

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'classes/Validator.php';

$sql = "SELECT * FROM category ORDER BY id";
$categories = returnArrayFromDB($link, $sql);
$error = [];
$registration = [];
$login = [];

if (!isset($_SESSION['name'])) {

    $is_auth = false;
    $user_name = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required_fields = [
          'email'    => 'Введите e-mail',
          'password' => 'Введите пароль',
          'name'     => 'Введите имя',
          'message'  => 'Напишите как с вами связаться'
        ];

        $email_fields = [
          'email' => 'Введите e-mail в корректном формате'
        ];

        $validator = new Validator($required_fields, null, $email_fields, null,
          null, $_POST, null);
        $error = $validator->getErrors();
        $registration = $validator->getValues();

        $sql = "SELECT COUNT(email) FROM user WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$registration['email']]);
        $result = mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_row($row);

        if ($array[0] === 0 && !$error) {
            $sql
              = "INSERT INTO user (email, password, name, contact)
        VALUES(?,?,?,?)";
            $stmt = db_get_prepare_stmt($link, $sql, $registration);
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                header("Location: login.php");
            } else {
                showError(mysqli_error($link));
            }
        } elseif ($array[0] === 1) {
            $error['email'] = 'Данный email уже используется';
        }
    }
    $content = include_template('registration.php',
      ['error' => $error, 'registration' => $registration]);
} else {
    $is_auth = true;
    $user_name = $_SESSION['name'];
    $content = include_template('login_message.php',
      ['error' => $error, 'login' => $login, 'login_page' => false]);
}
$navigation = include_template('navigation.php',
  ['categories' => $categories]);

$layout_content = include_template('layout.php', [
  'title'      => 'Регистрация',
  'navigation' => $navigation,
  'content'    => $content,
  'categories' => $categories,
  'container'  => '',
  'is_auth'    => $is_auth,
  'user_name'  => $user_name,
]);

print($layout_content);
