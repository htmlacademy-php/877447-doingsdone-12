<?php
require_once('settings.php');

$title = 'Авторизация';
$required_fields = ['email', 'password']; // обязательные при регистрации поля
$errors = [];


$authorization_rules = [
    'email' => function () {
        if (isset($_POST['email'])) {
            return isCorrectEmail($_POST['email']);
        }
    },
    'password' => function () {
        if (isset($_POST['password'])) {
            return isCorrectPassword($_POST['password']);
        }
    }
];

if (isset($_POST['submit'])) {

    //применяем функции валидации полей формы к каждому элементу формы внутри цикла
    foreach ($_POST as $key => $value) {
        if (isset($authorization_rules[$key])) {
            $rule = $authorization_rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $user = search_user($con, $_POST['email']);

        if ($user['user_email'] == $_POST['email']) {

            if (password_verify($_POST['password'], $user['user_password'])) {
                // верный пароль, открываем сессию
                session_start();
                $_SESSION['user'] = $user;
                if (empty($projects)) header('Location: add_project.php');
                else header('Location: index.php');
            } else {
                // неверный пароль
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Пользователь с указанным email не найден';
        }
    }
}

$main_content = include_template('form_auth.php', ['errors' => $errors]);
$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
