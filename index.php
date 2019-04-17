<?php

require_once 'helpers.php';

$is_auth = rand(0, 1);

$user_name = 'Дмитрий'; // укажите здесь ваше имя

$categories = [
  'Доски и лыжи',
  'Крепления',
  'Ботинки',
  'Одежда',
  'Инструменты',
  'Разное',
];

$adverts
  = [
  [
    'name'     => '2014 Rossignol District Snowboard',
    'category' => 'Доски и лыжи',
    'price'    => '10999',
    'url'      => 'img/lot-1.jpg',
    'time'     => 'tomorrow',
  ],
  [
    'name'     => 'DC Ply Mens 2016/2017 Snowboard',
    'category' => 'Доски и лыжи',
    'price'    => '159999',
    'url'      => 'img/lot-2.jpg',
    'time'     => 'tomorrow',
  ],
  [
    'name'     => 'Крепления Union Contact Pro 2015 года размер L/XL',
    'category' => 'Крепления',
    'price'    => '8000',
    'url'      => 'img/lot-3.jpg',
    'time'     => 'tomorrow',
  ],
  [
    'name'     => 'Ботинки для сноуборда DC Mutiny Charocal',
    'category' => 'Ботинки',
    'price'    => '10999',
    'url'      => 'img/lot-4.jpg',
    'time'     => 'tomorrow',
  ],
  [
    'name'     => 'Куртка для сноуборда DC Mutiny Charocal',
    'category' => 'Одежда',
    'price'    => '7500',
    'url'      => 'img/lot-5.jpg',
    'time'     => 'tomorrow',
  ],
  [
    'name'     => 'Маска Oakley Canopy',
    'category' => 'Разное',
    'price'    => '5400',
    'url'      => 'img/lot-6.jpg',
    'time'     => 'tomorrow',
  ],
];

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

$content = include_template('index.php',
  ['categories' => $categories, 'adverts' => $adverts]);
$layout_content = include_template('layout.php', [
  'title'      => 'Главная',
  'is_auth'    => $is_auth,
  'user_name'  => $user_name,
  'content'    => $content,
  'categories' => $categories,
]);
print($layout_content);

