<?php
require_once 'settings.php';

$title = 'Регистрация';
$required_fields = ['email', 'password', 'name']; // обязательные при регистрации поля
$errors = [];

$registration_rules = [
    'email' => function () {
        return isCorrectEmail($_POST['email']);
    },
    'password' => function () {
        return isCorrectPassword($_POST['password']);
    },
    'name' => function () {
        return isCorrectLength($_POST['name'], 3, 25);
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

    if (empty($errors['email'])) {
        $errors['email'] = get_saved_email($con, $_POST['email']);
    }
    if (empty($errors['name'])) {
        $errors['name'] = get_saved_login($con, $_POST['name']);
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $result = add_user($con, $_POST['name'], $_POST['email'], $_POST['password']);
        if ($result > 0) {
            header('Location: success_registration_index.php');
            exit;
        }
    }
}

$main_content = include_template('form_register.php', ['errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
