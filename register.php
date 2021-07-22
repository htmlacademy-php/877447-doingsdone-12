<?php
require_once('settings.php');

$title = 'Регистрация';
$required_fields = ['email', 'password', 'name']; // обязательные при регистрации поля
$errors = [];




$main_content = include_template('form_register.php', ['errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
