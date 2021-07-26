<?php
require_once('settings.php');

$title = 'Регистрация';
$required_fields = ['email', 'password', 'name']; // обязательные при регистрации поля
$errors = [];


$registration_rules = [
    'email' => function () {
        if (isset($_POST['email'])) {
            return isCorrectEmail($_POST['email']);
        }
    },
    'password' => function () {
        if (isset($_POST['password'])) {
            return isCorrectPassword($_POST['password']);
        }
    },
    'name' => function () {
        if (isset($_POST['name'])) {
            return isCorrectLength($_POST['name'], 3, 50);
        }
    }
];


if (isset($_POST['submit'])) {

     //применяем функции валидации полей формы к каждому элементу формы внутри цикла
     foreach ($_POST as $key => $value) {
        if (isset($registration_rules[$key])) {
            $rule = $registration_rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    if (empty($errors)) {

        add_user($con, $_POST['email'], $_POST['password'], $_POST['name']);
        header('Location: index.php');
        exit;
    }
}

$main_content = include_template('form_register.php', ['errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
